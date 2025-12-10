<?php

namespace Webkul\DataCollection\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Webkul\Lead\Models\Lead;
use Webkul\Contact\Models\Person;
use Webkul\Contact\Models\Organization;

class DataCollectionController extends Controller
{
    public function submit(Request $request)
    {
        if (!$this->isValidEmail($request->email)) {
            return response()->json([
                'success' => false,
                'message' => 'Email không hợp lệ hoặc domain không tồn tại'
            ], 400);
        }

        if (!$this->isValidPhone($request->phone)) {
            return response()->json([
                'success' => false,
                'message' => 'Số điện thoại không hợp lệ'
            ], 400);
        }

        $data = [
            'name'                => $request->name,
            'email'               => $request->email,
            'phone'               => $request->phone,
            'title'               => $request->title,
            'description'         => $request->description,
            'lead_value'          => $request->lead_value,
            'expected_close_date' => $request->expected_close_date,
            'customer_type'       => $request->customer_type ?? 'Lẻ',
            'organization_name'   => $request->organization_name,
            'job_title'           => $request->job_title,
            'lead_source_id'      => $request->lead_source_id ?? 3,
            'lead_type_id'        => $request->lead_type_id ?? 2,
        ];

        $existingPerson = Person::where(function ($query) use ($data) {
            $query->whereRaw("JSON_CONTAINS(emails, ?)", [json_encode(['value' => $data['email']])])
                  ->orWhereRaw("JSON_CONTAINS(contact_numbers, ?)", [json_encode(['value' => $data['phone']])]);
        })->first();

        if ($existingPerson) {
            $updatedPerson = $this->updatePerson($existingPerson, $data);
            $userId = $updatedPerson->user_id ?? 1;
            $lead = $this->createLead($data, $updatedPerson->id, $userId);

            return response()->json([
                'success'   => true,
                'message'   => 'Person đã được cập nhật và Lead mới đã tạo',
                'person_id' => $updatedPerson->id,
                'lead_id'   => $lead->id,
                'user_id'   => $userId,
                'action'    => 'updated'
            ], 201);
        }

        $newPerson = $this->createPerson($data);

        return response()->json([
            'success'   => true,
            'message'   => 'Person mới đã được tạo',
            'person_id' => $newPerson->id,
            'user_id'   => 1,
            'action'    => 'created'
        ], 201);
    }

    private function updatePerson(Person $person, array $data): Person
    {
        $currentEmails = is_string($person->emails)
            ? json_decode($person->emails, true)
            : $person->emails;
        $currentEmails = $currentEmails ?? [];

        $currentPhones = is_string($person->contact_numbers)
            ? json_decode($person->contact_numbers, true)
            : $person->contact_numbers;
        $currentPhones = $currentPhones ?? [];

        $emailExists = false;
        foreach ($currentEmails as $item) {
            if (isset($item['value']) && $item['value'] === $data['email']) {
                $emailExists = true;
                break;
            }
        }

        if (!$emailExists) {
            $currentEmails[] = ['label' => 'work', 'value' => $data['email']];
        }

        $phoneExists = false;
        foreach ($currentPhones as $item) {
            if (isset($item['value']) && $item['value'] === $data['phone']) {
                $phoneExists = true;
                break;
            }
        }

        if (!$phoneExists) {
            $currentPhones[] = ['label' => 'work', 'value' => $data['phone']];
        }

        $organizationId = null;
        if (!empty($data['organization_name'])) {
            $organizationId = $this->getOrCreateOrganization($data['organization_name']);
        }

        $person->name = $data['name'];
        $person->emails = $currentEmails;
        $person->contact_numbers = $currentPhones;
        $person->job_title = $data['job_title'] ?? $person->job_title;
        $person->organization_id = $organizationId ?? $person->organization_id;
        $person->save();

        return $person->fresh();
    }

    private function createPerson(array $data, int $userId = 1): Person
    {
        $organizationId = null;
        if (!empty($data['organization_name'])) {
            $organizationId = $this->getOrCreateOrganization($data['organization_name']);
        }

        $person = new Person();
        $person->name = $data['name'];
        $person->emails = [['label' => 'work', 'value' => $data['email']]];
        $person->contact_numbers = [['label' => 'work', 'value' => $data['phone']]];
        $person->job_title = $data['job_title'] ?? null;
        $person->user_id = $userId;
        $person->organization_id = $organizationId;
        $person->save();

        return $person;
    }

    private function getOrCreateOrganization(string $organizationName): int
    {
        $organization = Organization::where('name', $organizationName)->first();

        if (!$organization) {
            $organization = Organization::create([
                'name'    => $organizationName,
                'user_id' => 1,
            ]);
        }

        return $organization->id;
    }

    private function createLead(array $data, int $personId, int $userId): Lead
    {
        $leadTitle = $data['title']
            ? $data['title']
            : 'Lead từ Website - ' . ($data['customer_type'] ?? 'Lẻ') . ' - ' . $data['name'];

        $leadDescription = $data['description'] ?? 'Khách hàng đăng ký từ API';

        $leadValue = isset($data['lead_value']) && is_numeric($data['lead_value'])
            ? (float) $data['lead_value']
            : 0.00;

        return Lead::create([
            'title'                  => $leadTitle,
            'description'            => $leadDescription,
            'lead_value'             => $leadValue,
            'status'                 => 1,
            'person_id'              => $personId,
            'user_id'                => $userId,
            'lead_source_id'         => $data['lead_source_id'] ?? 3,
            'lead_type_id'           => $data['lead_type_id'] ?? 2,
            'lead_pipeline_id'       => 1,
            'lead_pipeline_stage_id' => 1,
            'expected_close_date'    => $data['expected_close_date'] ?? null,
        ]);
    }

    private function isValidEmail(?string $email): bool
    {
        if (empty($email)) {
            return false;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        $domain = substr(strrchr($email, "@"), 1);
        $mx = dns_get_record($domain, DNS_MX);

        return !empty($mx);
    }

    private function isValidPhone(?string $phone): bool
    {
        if (empty($phone)) {
            return false;
        }

        $clean = preg_replace('/[\s\-\(\)\.]/', '', $phone);

        // Số quốc tế: +XX... (5-15 số, chuẩn E.164)
        if (preg_match('/^\+(\d{1,3})(\d{4,14})$/', $clean, $matches)) {
            return in_array($matches[1], array_merge(
                range(1, 7), range(20, 27), range(30, 49), range(51, 58), range(60, 66),
                range(81, 95), range(98, 98), range(212, 269), range(290, 299),
                range(350, 389), range(420, 423), range(500, 509), range(590, 599),
                range(670, 692), range(850, 856), range(880, 886), range(960, 977),
                range(992, 998)
            ));
        }

        // Số VN: 10 số, đầu số hợp lệ
        return preg_match('/^0(3[2-9]|5[2689]|7[06-9]|8[1-9]|9[0-4]|6[2-9])\d{7}$/', $clean);
    }

}
