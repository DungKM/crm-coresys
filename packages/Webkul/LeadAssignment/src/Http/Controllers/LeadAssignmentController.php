<?php


namespace Webkul\LeadAssignment\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Webkul\User\Models\User;
use Webkul\User\Models\Role;
use Webkul\Lead\Models\Lead;
use Webkul\LeadAssignment\Services\LeadAssignmentService;

class LeadAssignmentController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    // Các hằng số cho code config
    const CONFIG_CODES = [
        'enabled' => 'lead_assignment.enabled',
        'method' => 'lead_assignment.method',
        'active_users' => 'lead_assignment.active_users',
        'weights' => 'lead_assignment.weights',
        'last_assigned_index' => 'lead_assignment.last_assigned_index',
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $salesRole = Role::where('name', 'Sales')->first();
        $salesUsers = $salesRole
            ? User::where('role_id', $salesRole->id)->get()
            : collect();

        $leadAssignmentConfig = DB::table('core_config')
            ->whereIn('code', self::CONFIG_CODES)
            ->pluck('value', 'code');

        // Đảm bảo luôn có giá trị mặc định
        foreach (self::CONFIG_CODES as $key => $code) {
            if (!isset($leadAssignmentConfig[$code])) {
                $leadAssignmentConfig[$code] = $key === 'weights' || $key === 'active_users'
                    ? json_encode([])
                    : '';
            }
        }

        return view('leadassignment::index', [
            'salesUsers' => $salesUsers,
            'leadAssignmentConfig' => $leadAssignmentConfig,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $data = request()->all();

        // Đảm bảo enabled luôn là 0 hoặc 1
        $enabled = isset($data['enabled']) && $data['enabled'] ? 1 : 0;
        $method = in_array($data['method'] ?? '', ['round_robin', 'weighted']) ? $data['method'] : 'round_robin';
        $activeUsers = isset($data['active_users']) ? (array) $data['active_users'] : [];
        $weights = isset($data['weights']) ? (array) $data['weights'] : [];

        DB::table('core_config')->updateOrInsert(
            ['code' => self::CONFIG_CODES['enabled']],
            ['value' => $enabled, 'updated_at' => now()]
        );
        DB::table('core_config')->updateOrInsert(
            ['code' => self::CONFIG_CODES['method']],
            ['value' => $method, 'updated_at' => now()]
        );
        DB::table('core_config')->updateOrInsert(
            ['code' => self::CONFIG_CODES['active_users']],
            ['value' => json_encode($activeUsers), 'updated_at' => now()]
        );
        DB::table('core_config')->updateOrInsert(
            ['code' => self::CONFIG_CODES['weights']],
            ['value' => json_encode($weights), 'updated_at' => now()]
        );

        // Tự động phân bổ lead sau khi lưu cấu hình
        $assignedCount = 0;
        $skippedCount = 0;

        if ($enabled && !empty($activeUsers)) {
            try {
                $service = app(LeadAssignmentService::class);
                $unassignedLeads = Lead::whereNull('user_id')
                    ->orderBy('created_at', 'asc')
                    ->get();

                foreach ($unassignedLeads as $lead) {
                    $userId = $service->assignUserId();
                    if ($userId) {
                        $lead->user_id = $userId;
                        $lead->save();
                        $assignedCount++;
                    } else {
                        $skippedCount++;
                    }
                }
            } catch (\Exception $e) {
                // Log lỗi nhưng vẫn báo thành công việc lưu config
            }
        }

        $message = 'Đã lưu cấu hình';
        if ($assignedCount > 0) {
            $message .= " và phân bổ {$assignedCount} lead thành công";
            if ($skippedCount > 0) {
                $message .= " ({$skippedCount} lead bỏ qua)";
            }
        } elseif (!$enabled) {
            $message .= '. Tính năng đang tắt, không phân bổ lead.';
        } elseif (empty($activeUsers)) {
            $message .= '. Chưa chọn sales nào, không phân bổ lead.';
        } else {
            $message .= '. Không có lead nào cần phân bổ.';
        }

        return redirect()
            ->route('admin.settings.lead_assignment.index')
            ->with('success', $message);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    }

    /**
     * Assign users to unassigned leads based on configuration.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function assignLeads()
    {
        try {
            $service = app(LeadAssignmentService::class);

            // Lấy tất cả lead chưa có user_id
            $unassignedLeads = Lead::whereNull('user_id')
                ->orderBy('created_at', 'asc')
                ->get();

            if ($unassignedLeads->isEmpty()) {
                return redirect()
                    ->back()
                    ->with('warning', 'Không có lead nào cần phân bổ.');
            }

            $assignedCount = 0;
            $skippedCount = 0;

            foreach ($unassignedLeads as $lead) {
                $userId = $service->assignUserId();

                if ($userId) {
                    $lead->user_id = $userId;
                    $lead->save();
                    $assignedCount++;
                } else {
                    $skippedCount++;
                }
            }

            $message = "Đã phân bổ {$assignedCount} lead thành công.";
            if ($skippedCount > 0) {
                $message .= " ({$skippedCount} lead bị bỏ qua do cấu hình)";
            }

            return redirect()
                ->back()
                ->with('success', $message);

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
}
