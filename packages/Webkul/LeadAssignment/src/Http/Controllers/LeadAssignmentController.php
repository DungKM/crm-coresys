<?php


namespace Webkul\LeadAssignment\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Webkul\User\Models\User;
use Webkul\User\Models\Role;

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
        $data = request()->validate([
            'enabled' => 'required|boolean',
            'method' => 'required|in:round_robin,weighted',
            'active_users' => 'required|array',
            'weights' => 'nullable|array',
        ]);

        // Lưu cấu hình vào core_config, luôn cập nhật updated_at
        DB::table('core_config')->updateOrInsert(
            ['code' => self::CONFIG_CODES['enabled']],
            ['value' => $data['enabled'], 'updated_at' => now()]
        );
        DB::table('core_config')->updateOrInsert(
            ['code' => self::CONFIG_CODES['method']],
            ['value' => $data['method'], 'updated_at' => now()]
        );
        DB::table('core_config')->updateOrInsert(
            ['code' => self::CONFIG_CODES['active_users']],
            ['value' => json_encode($data['active_users']), 'updated_at' => now()]
        );
        DB::table('core_config')->updateOrInsert(
            ['code' => self::CONFIG_CODES['weights']],
            ['value' => json_encode($data['weights'] ?? []), 'updated_at' => now()]
        );

        return redirect()
            ->route('admin.settings.lead_assignment.index')
            ->with('success', 'Cấu hình Lead Assignment đã được lưu!');
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
}
