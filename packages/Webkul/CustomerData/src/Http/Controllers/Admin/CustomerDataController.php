<?php 

namespace Webkul\CustomerData\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Mail, Validator, DB, Cache, Log};
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\CustomerData\Models\CustomerData;
use Webkul\CustomerData\Mail\VerifyCustomerDataMail;
use Webkul\Lead\Repositories\LeadRepository;
use Webkul\Lead\Repositories\PipelineRepository;
use Webkul\Contact\Models\Person;
use Webkul\Lead\Models\{Lead, Stage};

class CustomerDataController extends Controller
{
    protected $leadRepository;
    protected $pipelineRepository;

    public function __construct(
        LeadRepository $leadRepository,
        PipelineRepository $pipelineRepository
    ) {
        $this->leadRepository = $leadRepository;
        $this->pipelineRepository = $pipelineRepository;
    }

    // Danh sách dữ liệu khách hàng 
    public function index(Request $request)
    {
        $query = CustomerData::with(['lead.person']);

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($customerType = $request->get('customer_type')) {
            $query->where('customer_type', $customerType);
        }

        $customerData = $query->latest()->paginate(20);

        return view('customer-data::admin.index', compact('customerData'));
    }

    // Tạo mới khách hàng thủ công
    public function create()
    {
        $sources = [
            'facebook' => 'Facebook',
            'google'   => 'Google',
            'tiktok'   => 'TikTok',
            'zalo'     => 'Zalo',
            'other'    => 'Nguồn khác',
        ];

        return view('customer-data::admin.create', compact('sources'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customer_data,email',
            'phone' => 'required|string|max:20',
            'source' => 'required|string|max:100',
            'title' => 'nullable|string',
            'customer_type' => 'required|in:retail,business',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $data = $request->only([
                'name', 'email', 'phone',
                'source', 'title', 'customer_type'
            ]);

            $customerData = CustomerData::create($data);
            $customerData->generateVerifyToken();

            return redirect()
                ->route('admin.customer-data.index')
                ->with('success', 'Dữ liệu đã được tạo!');
        } catch (\Exception $e) {
            Log::error('Lỗi tạo CustomerData: ' . $e->getMessage());
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Có lỗi: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $customerData = CustomerData::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customer_data,email,' . $id,
            'phone' => 'required|string|max:20',
            'source' => 'required|string|max:100',
            'title' => 'nullable|string',
            'customer_type' => 'required|in:retail,business',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $customerData->update($request->all());
            
            return redirect()
                ->route('admin.customer-data.index')
                ->with('success', 'Đã cập nhật!');
        } catch (\Exception $e) {
            Log::error('Lỗi update CustomerData: ' . $e->getMessage());
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Có lỗi: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $customerData = CustomerData::withTrashed()->findOrFail($id);

            // Nếu chưa bị xóa -> xóa mềm
            if (is_null($customerData->deleted_at)) {
                $customerData->delete();

                return redirect()
                    ->route('admin.customer-data.index')
                    ->with('success', 'Đã chuyển vào thùng rác!');
            }

            // Nếu đã bị xóa mềm -> xóa vĩnh viễn
            $customerData->forceDelete();

            return redirect()
                ->route('admin.customer-data.index')
                ->with('success', 'Đã xóa vĩnh viễn!');
            
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Có lỗi khi xóa: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $customerData = CustomerData::findOrFail($id);
        return view('customer-data::admin.show', compact('customerData'));
    }

    public function edit($id)
    {
        $customerData = CustomerData::findOrFail($id);
        $sources = [
            'facebook' => 'Facebook',
            'google'   => 'Google',
            'tiktok'   => 'TikTok',
            'zalo'     => 'Zalo',
            'other'    => 'Nguồn khác',
        ];

        return view('customer-data::admin.edit', compact('customerData', 'sources'));
    }

    public function sendVerificationEmail($id)
    {
        try {
            $customerData = CustomerData::select(['id', 'name', 'email', 'status', 'verify_token', 'verify_token_expires_at'])
                ->findOrFail($id);
            
            if ($customerData->status !== 'pending') {
                return redirect()->back()->with('warning', 'Dữ liệu này đã được xử lý!');
            }

            if (!$customerData->isTokenValid()) {
                $customerData->generateVerifyToken();
            }

            Mail::to($customerData->email)->send(new VerifyCustomerDataMail($customerData));

            return redirect()->back()->with('success', 'Đã gửi email xác thực!');
        } catch (\Exception $e) {
            Log::error('Lỗi gửi email: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Không thể gửi email: ' . $e->getMessage());
        }
    }

    public function verify($token)
    {
        try {
            $customerData = CustomerData::where('verify_token', $token)
                ->with('lead.person')
                ->firstOrFail();

            // Token hết hạn
            if (!$customerData->isTokenValid()) {
                $customerData->markAsSpam('Token đã hết hạn');
                return view('customer-data::admin.verify-expired', compact('customerData'));
            }

            // Đã convert rồi
            if ($customerData->status === 'converted' && $customerData->converted_to_lead_id) {
                $leadExists = Lead::find($customerData->converted_to_lead_id);
                
                if ($leadExists) {
                    return view('customer-data::admin.verify-success', compact('customerData'));
                }
                
                // Lead bị xóa -> reset
                Log::warning("Lead #{$customerData->converted_to_lead_id} không tồn tại");
                $customerData->update(['converted_to_lead_id' => null, 'status' => 'verified']);
            }

            DB::beginTransaction();

            try {
                $customerData->markAsVerified();
                $customerData->refresh();

                $lead = $this->createLeadFromCustomerData($customerData, 1);

                $customerData->update([
                    'status' => 'converted',
                    'converted_to_lead_id' => $lead->id,
                ]);

                DB::commit();

                Log::info("Verify thành công: CustomerData #{$customerData->id} -> Lead #{$lead->id}");

                return view('customer-data::admin.verify-success', compact('customerData'));

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Lỗi verify: ' . $e->getMessage());
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error('Lỗi verify token: ' . $e->getMessage());
            return redirect()
                ->route('admin.customer-data.index')
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    public function convertToLead($id)
    {
        try {
            $customerData = CustomerData::findOrFail($id);
            
            if ($customerData->converted_to_lead_id) {
                $leadExists = Lead::find($customerData->converted_to_lead_id);
                
                if ($leadExists) {
                    return redirect()
                        ->route('admin.leads.index')
                        ->with('info', 'Lead #' . $customerData->converted_to_lead_id . ' đã được tạo.');
                }
                
                // Reset nếu Lead bị xóa
                $customerData->update(['converted_to_lead_id' => null]);
            }
            
            if ($customerData->status !== 'verified') {
                return redirect()->back()->with('error', 'Chỉ chuyển được dữ liệu đã xác thực!');
            }

            DB::beginTransaction();
            
            try {
                $lead = $this->createLeadFromCustomerData($customerData, auth()->id());

                $customerData->update([
                    'status' => 'converted',
                    'converted_to_lead_id' => $lead->id,
                ]);

                DB::commit();

                Log::info("Convert thành công: CustomerData #{$customerData->id} -> Lead #{$lead->id}");

                return redirect()
                    ->route('admin.leads.index')
                    ->with('success', 'Đã chuyển thành Lead #' . $lead->id . '!');
                
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Lỗi convert: ' . $e->getMessage());
                throw $e;
            }
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Không thể chuyển: ' . $e->getMessage());
        }
    }

    public function massAction(Request $request)
    {
        $action = $request->input('action');
        $ids = $request->input('ids', []);

        if (empty($ids)) {
            return redirect()->back()->with('warning', 'Vui lòng chọn ít nhất 1 mục!');
        }

        try {
            switch ($action) {
                case 'send_verification':
                    return $this->massSendVerification($ids);
                case 'delete':
                    return $this->massDelete($ids);
                case 'mark_spam':
                    return $this->massMarkSpam($ids);
                default:
                    return redirect()->back()->with('warning', 'Hành động không hợp lệ!');
            }
        } catch (\Exception $e) {
            Log::error('Lỗi mass action: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi: ' . $e->getMessage());
        }
    }

    private function createLeadFromCustomerData(CustomerData $customerData, int $userId): Lead
    {
        try {
            $cacheKey = 'default_pipeline_stage';
            $pipelineData = Cache::remember($cacheKey, 300, function () {
                $pipeline = $this->pipelineRepository->getDefaultPipeline();
                
                if (!$pipeline) {
                    throw new \Exception('Không tìm thấy Pipeline mặc định');
                }
                
                $stage = Stage::where('lead_pipeline_id', $pipeline->id)
                    ->orderBy('sort_order', 'asc')
                    ->first();
                
                if (!$stage) {
                    throw new \Exception('Pipeline không có Stage');
                }
                
                return [
                    'pipeline_id' => $pipeline->id,
                    'pipeline_name' => $pipeline->name,
                    'stage_id' => $stage->id,
                    'stage_name' => $stage->name,
                ];
            });
            
            // Check Person bằng index
            $existingPerson = Person::where('unique_id', $customerData->email . '|' . ($customerData->phone ?: ''))
                ->select(['id', 'name'])
                ->first();

            if ($existingPerson) {
                // Tạo Lead với Person có sẵn
                $lead = Lead::create([
                    'title' => $customerData->title ?: 'Lead từ ' . $customerData->source,
                    'person_id' => $existingPerson->id,
                    'lead_value' => 0,
                    'status' => 1,
                    'lead_source_id' => $this->getLeadSourceId($customerData->source),
                    'lead_type_id' => $customerData->customer_type === 'business' ? 2 : 1,
                    'user_id' => $userId,
                    'lead_pipeline_id' => $pipelineData['pipeline_id'],
                    'lead_pipeline_stage_id' => $pipelineData['stage_id'],
                ]);
                
                Log::info("Lead #{$lead->id} created with existing Person #{$existingPerson->id}");
                return $lead;
            }

            // Tạo Person + Lead mới
            $lead = $this->leadRepository->create([
                'title' => $customerData->title ?: 'Lead từ ' . $customerData->source,
                'person' => [
                    'name' => $customerData->name,
                    'emails' => [['value' => $customerData->email, 'label' => 'work']],
                    'contact_numbers' => $customerData->phone ? [['value' => $customerData->phone, 'label' => 'work']] : [],
                ],
                'lead_source_id' => $this->getLeadSourceId($customerData->source),
                'lead_type_id' => $customerData->customer_type === 'business' ? 2 : 1,
                'user_id' => $userId,
                'lead_value' => 0,
                'status' => 1,
                'lead_pipeline_id' => $pipelineData['pipeline_id'],
                'lead_pipeline_stage_id' => $pipelineData['stage_id'],
            ]);
            
            Log::info("Lead #{$lead->id} created with new Person");
            return $lead;
            
        } catch (\Exception $e) {
            Log::error('Lỗi createLeadFromCustomerData: ' . $e->getMessage());
            throw new \Exception('Không thể tạo Lead: ' . $e->getMessage());
        }
    }

    private function getLeadSourceId($source): int
    {
        static $sourceMap = null;
        
        if ($sourceMap === null) {
            $sourceMap = [
                'facebook' => 1,
                'google' => 2,
                'tiktok' => 3,
                'zalo' => 4,
                'other' => 5,
            ];
        }

        return $sourceMap[strtolower($source)] ?? 1;
    }

    private function massSendVerification(array $ids)
    {
        $items = CustomerData::select(['id', 'name', 'email', 'verify_token', 'verify_token_expires_at'])
            ->whereIn('id', $ids)
            ->where('status', 'pending')
            ->get();
        
        if ($items->isEmpty()) {
            return redirect()->back()->with('warning', 'Không có dữ liệu pending!');
        }

        $successCount = 0;
        foreach ($items as $item) {
            try {
                if (!$item->isTokenValid()) {
                    $item->generateVerifyToken();
                }
                Mail::to($item->email)->send(new VerifyCustomerDataMail($item));
                $successCount++;
            } catch (\Exception $e) {
                Log::warning("Không gửi được email cho #{$item->id}: " . $e->getMessage());
                continue;
            }
        }
        
        return redirect()->back()->with(
            $successCount > 0 ? 'success' : 'error',
            $successCount > 0 ? "Đã gửi email cho {$successCount} khách hàng!" : 'Không gửi được email!'
        );
    }

    private function massDelete(array $ids)
    {
        try {
            $items = CustomerData::withTrashed()->whereIn('id', $ids)->get();

            $softDeletedCount = 0;
            $forceDeletedCount = 0;

            foreach ($items as $item) {
                if (is_null($item->deleted_at)) {
                    $item->delete();
                    $softDeletedCount++;
                } else {
                    $item->forceDelete();
                    $forceDeletedCount++;
                }
            }

            return redirect()->back()->with(
                'success',
                "Đã xóa mềm {$softDeletedCount} mục, và xóa vĩnh viễn {$forceDeletedCount} mục!"
            );

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi: ' . $e->getMessage());
        }
    }

    private function massMarkSpam(array $ids)
    {
        $updated = CustomerData::whereIn('id', $ids)->update([
            'status' => 'spam',
            'spam_reason' => 'Đánh dấu spam hàng loạt'
        ]);
        
        return redirect()->back()->with(
            $updated > 0 ? 'success' : 'warning',
            $updated > 0 ? "Đã đánh dấu spam {$updated} mục!" : 'Không có mục nào!'
        );
    }
}