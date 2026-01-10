@extends('appointment::emails.layout')

@section('title', 'Lịch hẹn đã được xác nhận')

@section('header-title', 'Lịch Hẹn Đã Xác Nhận')

@section('content')
    <p style="font-size: 16px; margin-bottom: 8px;">
        Xin chào <strong style="color: #0891B2;">
            {{ $appointment->assignedUser->name ?? 'Nhân viên' }}
        </strong>,
    </p>

    <p style="font-size: 14px; color: #64748B; margin-bottom: 24px;">
        Chúng tôi xin thông báo rằng khách hàng
        <strong>{{ $appointment->customer_name }}</strong>
        đã xác nhận lịch hẹn.
    </p>

    <span class="status-badge status-confirmed">Đã xác nhận</span>

    @php
        // ✅ Lấy tên dịch vụ an toàn
        $serviceName =
            $appointment->service->name
            ?? $appointment->service_name
            ?? null;
    @endphp

    <!-- Appointment Details Card -->
    <div class="appointment-card">

        <!-- Thời gian -->
        <div class="appointment-row">
            <div class="appointment-info">
                <h4>Thời gian</h4>
                <p style="color: #10B981;">
                    {{ $appointment->start_at->format('d/m/Y') }}
                    lúc {{ $appointment->start_at->format('H:i') }}
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
                    Email: {{ $appointment->customer_email }}
                    | SĐT: {{ $appointment->customer_phone }}
                </p>
            </div>
        </div>

        <!-- ✅ Dịch vụ -->
        @if($serviceName)
        <div class="appointment-row">
            <div class="appointment-info">
                <h4>Dịch vụ</h4>
                <p>{{ $serviceName }}</p>
            </div>
        </div>
        @endif
    </div>

    <!-- Success Message -->
    <div class="alert-box alert-success">
        <p style="font-size: 14px; margin: 0;">
            <strong>Lịch hẹn đã được xác nhận!</strong> Khách hàng sẽ có mặt đúng giờ.
        </p>
    </div>

    <div class="divider"></div>

    <p style="font-size: 12px; color: #94A3B8; text-align: center; margin-top: 24px;">
        Email này được gửi tự động, vui lòng không trả lời.
    </p>
@endsection
