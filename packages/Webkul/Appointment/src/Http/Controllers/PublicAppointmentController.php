<?php

namespace Webkul\Appointment\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Appointment\Repositories\AppointmentRepository;
use Webkul\Appointment\Events\AppointmentConfirmed;
use Webkul\Appointment\Events\AppointmentCancelled;
use Webkul\Appointment\Events\AppointmentRescheduled;
use Webkul\Appointment\Models\AppointmentEmailLog;
use Carbon\Carbon;

class PublicAppointmentController extends Controller
{
    protected $appointmentRepository;

    public function __construct(AppointmentRepository $appointmentRepository)
    {
        $this->appointmentRepository = $appointmentRepository;
    }

    /**
     * Confirm appointment via email link
     */
    public function confirm($id, $token)
    {
        try {
            $appointment = $this->appointmentRepository->find($id);

            if (!$appointment) {
                return view('appointment::public.error', [
                    'message' => 'Không tìm thấy lịch hẹn.'
                ]);
            }

            // Validate token qua email_logs
            $emailLog = AppointmentEmailLog::where('appointment_id', $id)
                ->where('token', $token)
                ->first();

            if (!$emailLog || !$emailLog->validateToken($token)) {
                return view('appointment::public.error', [
                    'message' => 'Link xác nhận không hợp lệ.'
                ]);
            }

            if ($appointment->status === 'cancelled') {
                return view('appointment::public.error', [
                    'message' => 'Lịch hẹn này đã bị hủy.'
                ]);
            }

            // Mark email as clicked
            $emailLog->markAsClicked();

            // Confirm appointment
            $appointment->update([
                'status' => 'confirmed'
            ]);

            // Record action
            $emailLog->recordAction('confirmed');

            // Fire event
            event(new AppointmentConfirmed($appointment, 'email'));

            Log::info('Appointment confirmed via email', [
                'appointment_id' => $appointment->id,
                'email_log_id' => $emailLog->id
            ]);

            return view('appointment::public.success', [
                'type' => 'confirmed',
                'appointment' => $appointment,
                'message' => 'Lịch hẹn đã được xác nhận thành công!'
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to confirm appointment', [
                'appointment_id' => $id,
                'error' => $e->getMessage()
            ]);

            return view('appointment::public.error', [
                'message' => 'Có lỗi xảy ra. Vui lòng thử lại sau.'
            ]);
        }
    }

    /**
     * Show cancel form
     */
    public function showCancelForm($id, $token)
    {
        try {
            $appointment = $this->appointmentRepository->find($id);

            if (!$appointment) {
                return view('appointment::public.error', [
                    'message' => 'Không tìm thấy lịch hẹn.'
                ]);
            }

            $emailLog = AppointmentEmailLog::where('appointment_id', $id)
                ->where('token', $token)
                ->first();

            if (!$emailLog || !$emailLog->validateToken($token)) {
                return view('appointment::public.error', [
                    'message' => 'Link không hợp lệ hoặc đã hết hạn.'
                ]);
            }

            if ($appointment->status === 'cancelled') {
                return view('appointment::public.error', [
                    'message' => 'Lịch hẹn này đã bị hủy trước đó.'
                ]);
            }

            $emailLog->markAsClicked();

            return view('appointment::public.cancel', [
                'appointment' => $appointment,
                'token' => $token
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to show cancel form', [
                'appointment_id' => $id,
                'error' => $e->getMessage()
            ]);

            return view('appointment::public.error', [
                'message' => 'Có lỗi xảy ra. Vui lòng thử lại sau.'
            ]);
        }
    }

    /**
     * Process cancel appointment
     */
    public function cancel(Request $request, $id, $token)
    {
        try {
            $appointment = $this->appointmentRepository->find($id);

            $emailLog = AppointmentEmailLog::where('appointment_id', $id)
                ->where('token', $token)
                ->first();

            if (!$appointment || !$emailLog || !$emailLog->validateToken($token)) {
                return redirect()->back()->with('error', 'Link không hợp lệ.');
            }

            if ($appointment->status === 'cancelled') {
                return redirect()->back()->with('error', 'Lịch hẹn này đã bị hủy.');
            }

            $reason = $request->input('reason');

            $emailLog->markAsClicked();

            $appointment->update([
                'status' => 'cancelled',
                'cancellation_reason' => $reason,
                'cancelled_by' => null,
                'cancelled_at' => now(),
            ]);

            $emailLog->recordAction('cancelled');

            event(new AppointmentCancelled($appointment, null, $reason));

            return view('appointment::public.success', [
                'type' => 'cancelled',
                'appointment' => $appointment,
                'message' => 'Lịch hẹn đã được hủy thành công!'
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to cancel appointment', [
                'appointment_id' => $id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()->with('error', 'Có lỗi xảy ra.');
        }
    }
    /**
     * Show reschedule form
     */
    public function showRescheduleForm($id, $token)
    {
        try {
            $appointment = $this->appointmentRepository->find($id);

            $emailLog = AppointmentEmailLog::where('appointment_id', $id)
                ->where('token', $token)
                ->first();

            if (!$appointment || !$emailLog || !$emailLog->validateToken($token)) {
                return view('appointment::public.error', [
                    'message' => 'Link không hợp lệ hoặc đã hết hạn.'
                ]);
            }

            if ($appointment->status === 'cancelled') {
                return view('appointment::public.error', [
                    'message' => 'Không thể đổi lịch hẹn đã bị hủy.'
                ]);
            }

            $emailLog->markAsClicked();

            return view('appointment::public.reschedule', [
                'appointment' => $appointment,
                'token' => $token
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to show reschedule form', [
                'appointment_id' => $id,
                'error' => $e->getMessage()
            ]);

            return view('appointment::public.error', [
                'message' => 'Có lỗi xảy ra.'
            ]);
        }
    }


    /**
     * Process reschedule appointment
     */
    public function reschedule(Request $request, $id, $token)
{
    $request->validate([
        'start_at' => 'required|date|after:now',
        'reason' => 'nullable|string|max:500',
    ]);

    try {
        $appointment = $this->appointmentRepository->find($id);

        $emailLog = AppointmentEmailLog::where('appointment_id', $id)
            ->where('token', $token)
            ->first();

        if (!$appointment || !$emailLog || !$emailLog->validateToken($token)) {
            return redirect()->back()->with('error', 'Link không hợp lệ.');
        }

        $emailLog->markAsClicked();

        $oldStartAt = $appointment->start_at;
        $oldEndAt   = $appointment->end_at;

        $newStartAt = Carbon::parse($request->start_at, $appointment->timezone);
        $newEndAt   = $newStartAt->copy()->addMinutes($appointment->duration_minutes);

        // --- Kiểm tra conflict giống BaseAppointmentController ---
        $assignedUserId = $appointment->assigned_user_id;
        if ($assignedUserId && $this->checkScheduleConflict($assignedUserId, $newStartAt, $newEndAt, $appointment->id)) {
            return redirect()->back()->with('error', 'Nhân viên đã có lịch hẹn trùng giờ. Vui lòng chọn thời gian khác.');
        }

        $appointment->update([
            'start_at' => $newStartAt,
            'end_at' => $newEndAt,
            'status' => 'rescheduled',
            'original_start_at' => $oldStartAt,
            'reschedule_reason' => $request->reason,
            'rescheduled_by' => null,
            'rescheduled_at' => now(),
        ]);

        $emailLog->recordAction('rescheduled');

        event(new AppointmentRescheduled(
            $appointment,
            $oldStartAt,
            $oldEndAt,
            $request->reason
        ));

        return view('appointment::public.success', [
            'type' => 'rescheduled',
            'appointment' => $appointment,
            'message' => 'Lịch hẹn đã được đổi thành công!'
        ]);

    } catch (\Exception $e) {
        Log::error('Failed to reschedule appointment', [
            'appointment_id' => $id,
            'error' => $e->getMessage()
        ]);

        return redirect()->back()->with('error', 'Có lỗi xảy ra.');
    }
}

/**
 * Chuyển logic check conflict từ BaseAppointmentController
 */
protected function checkScheduleConflict($userId, $startAt, $endAt, ?int $excludeAppointmentId = null): bool
{
    return $this->appointmentRepository->getModel()->newQuery()
        ->where('assigned_user_id', $userId)
        ->whereIn('status', ['scheduled', 'confirmed', 'rescheduled'])
        ->when($excludeAppointmentId, function ($q) use ($excludeAppointmentId) {
            $q->where('id', '!=', $excludeAppointmentId);
        })
        ->where(function($q) use ($startAt, $endAt) {
            $q->where('start_at', '<', $endAt)
              ->where('end_at', '>', $startAt);
        })
        ->exists();
}
    /**
     * Download ICS calendar file
     */
    public function downloadICS($id, $token)
    {
        try {
            $appointment = $this->appointmentRepository->find($id);

            $emailLog = AppointmentEmailLog::where('appointment_id', $id)
                ->where('token', $token)
                ->first();

            if (!$appointment || !$emailLog || !$emailLog->validateToken($token)) {
                abort(404);
            }

            $emailLog->markAsClicked();

            $ics = $this->generateICS($appointment);

            return response($ics)
                ->header('Content-Type', 'text/calendar; charset=utf-8')
                ->header('Content-Disposition', 'attachment; filename="appointment-'.$appointment->id.'.ics"');

        } catch (\Exception $e) {
            Log::error('Failed to download ICS', [
                'appointment_id' => $id,
                'error' => $e->getMessage()
            ]);

            abort(404);
        }
    }

    /**
     * Track email opened (1x1 pixel)
     */
    public function trackEmailOpened($id, $token)
    {
        try {
            $emailLog = AppointmentEmailLog::where('appointment_id', $id)
                ->where('token', $token)
                ->first();

            if ($emailLog && $emailLog->validateToken($token)) {
                $emailLog->markAsOpened();
            }

        } catch (\Exception $e) {
            // silent
        }

        return response(base64_decode(
            'R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7'
        ))->header('Content-Type', 'image/gif');
    }
    /**
     * Generate ICS file content
     */
    private function generateICS($appointment)
    {
        $startAt = $appointment->start_at->format('Ymd\THis\Z');
        $endAt = $appointment->end_at->format('Ymd\THis\Z');
        $created = $appointment->created_at->format('Ymd\THis\Z');

        $description = "Loại: " . $appointment->meeting_type;
        if ($appointment->service_name) {
            $description .= "\\nDịch vụ: " . $appointment->service_name;
        }
        if ($appointment->note) {
            $description .= "\\nGhi chú: " . $appointment->note;
        }

        $location = '';
        if ($appointment->meeting_type === 'onsite' && $appointment->full_address) {
            $location = $appointment->full_address;
        } elseif ($appointment->meeting_type === 'online' && $appointment->meeting_link) {
            $location = $appointment->meeting_link;
        }

        $ics = "BEGIN:VCALENDAR\r\n";
        $ics .= "VERSION:2.0\r\n";
        $ics .= "PRODID:-//Krayin CRM//Appointment//EN\r\n";
        $ics .= "BEGIN:VEVENT\r\n";
        $ics .= "UID:appointment-{$appointment->id}@krayin.com\r\n";
        $ics .= "DTSTAMP:{$created}\r\n";
        $ics .= "DTSTART:{$startAt}\r\n";
        $ics .= "DTEND:{$endAt}\r\n";
        $ics .= "SUMMARY:Lịch hẹn - {$appointment->customer_name}\r\n";
        $ics .= "DESCRIPTION:{$description}\r\n";
        if ($location) {
            $ics .= "LOCATION:{$location}\r\n";
        }
        $ics .= "STATUS:CONFIRMED\r\n";
        $ics .= "END:VEVENT\r\n";
        $ics .= "END:VCALENDAR\r\n";

        return $ics;
    }
}
