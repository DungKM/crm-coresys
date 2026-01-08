@extends('appointment::emails.layout')

@section('title', 'Lịch hẹn đã bị hủy')

@section('header-title', 'Lịch Hẹn Đã Hủy')

@section('content')
    <p style="font-size: 16px; margin-bottom: 8px;">
        Xin chào <strong style="color: #0891B2;">{{ $appointment->assignedUser->name ?? 'Nhân viên' }}</strong>,
    </p>

    <p style="font-size: 14px; color: #64748B; margin-bottom: 24px;">
        Chúng tôi xin thông báo rằng một lịch hẹn bạn phụ trách đã bị hủy.
    </p>

    <span class="status-badge status-cancelled">Đã hủy</span>

    <!-- Appointment Details Card -->
    <div class="appointment-card">
        <!-- Thời gian đã hủy -->
        <div class="appointment-row">
            <div class="appointment-info">
                <h4>Thời gian đã hủy</h4>
                <p style="text-decoration: line-through; color: #94A3B8;">
                    {{ $appointment->start_at->format('d/m/Y') }} lúc {{ $appointment->start_at->format('H:i') }}
                </p>
                <p style="font-size: 13px; color: #64748B; font-weight: normal; margin-top: 4px;">
                    Thời lượng: {{ $appointment->duration_minutes }} phút
                </p>
            </div>
        </div>

        <!-- Hình thức -->
        <div class="appointment-row">
            <div class="appointment-info">
                <h4>Hình thức</h4>
                <p>
                    @if($appointment->meeting_type === 'call')
                        Gọi điện thoại
                    @elseif($appointment->meeting_type === 'online')
                        Online Meeting
                    @else
                        Gặp trực tiếp
                    @endif
                </p>
            </div>
        </div>

        <!-- Khách hàng -->
        <div class="appointment-row">
            <div class="appointment-info">
                <h4>Khách hàng</h4>
                <p>{{ $appointment->customer_name }}</p>
                <p style="font-size: 13px; color: #64748B; margin-top: 2px;">
                    Email: {{ $appointment->customer_email }} | SĐT: {{ $appointment->customer_phone }}
                </p>
            </div>
        </div>

        <!-- Dịch vụ -->
        @if($appointment->service_name)
        <div class="appointment-row">
            <div class="appointment-info">
                <h4>Dịch vụ</h4>
                <p>{{ $appointment->service_name }}</p>
            </div>
        </div>
        @endif

        <!-- Lý do hủy -->
        @if(isset($reason) && $reason)
        <div class="appointment-row">
            <div class="appointment-info">
                <h4>Lý do hủy</h4>
                <p style="font-size: 14px; color: #64748B;">{{ $reason }}</p>
            </div>
        </div>
        @endif

        <!-- Thời gian hủy -->
        <div class="appointment-row">
            <div class="appointment-info">
                <h4>Thời gian hủy</h4>
                <p style="font-size: 14px;">
                    {{ isset($appointment->cancelled_at) && $appointment->cancelled_at ? $appointment->cancelled_at->format('d/m/Y H:i') : now()->format('d/m/Y H:i') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Warning Message -->
    <div class="alert-box alert-danger">
        <p style="font-size: 14px; margin: 0;">
            <strong>Lưu ý:</strong> Lịch hẹn này đã bị hủy và không còn hiệu lực.
        </p>
    </div>

    <div class="divider"></div>

    <p style="font-size: 12px; color: #94A3B8; text-align: center; margin-top: 24px;">
        Email này được gửi tự động, vui lòng không trả lời.
    </p>
@endsection
