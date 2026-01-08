@extends('appointment::emails.layout')

@section('title', 'Thông báo lịch hẹn mới')

@section('header-title', 'Lịch Hẹn Mới')

@section('content')
    <p style="font-size: 16px; margin-bottom: 8px;">
        Xin chào <strong style="color: #0891B2;">{{ $appointment->assignedUser->name ?? 'Nhân viên' }}</strong>,
    </p>

    <p style="font-size: 14px; color: #64748B; margin-bottom: 24px;">
        Bạn có một lịch hẹn mới được tạo. Dưới đây là thông tin chi tiết:
    </p>

    <span class="status-badge status-scheduled">Chờ xác nhận</span>

    <!-- Appointment Details Card -->
    <div class="appointment-card">
        <!-- Thời gian -->
        <div class="appointment-row">
            <div class="appointment-info">
                <h4>Thời gian</h4>
                <p>{{ $appointment->start_at->format('d/m/Y') }} lúc {{ $appointment->start_at->format('H:i') }}</p>
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

        <!-- Chi tiết theo loại -->
        @if($appointment->meeting_type === 'call' && $appointment->call_phone)
        <div class="appointment-row">
            <div class="appointment-info">
                <h4>Số điện thoại khách</h4>
                <p>{{ $appointment->call_phone }}</p>
            </div>
        </div>
        @elseif($appointment->meeting_type === 'online' && $appointment->meeting_link)
        <div class="appointment-row">
            <div class="appointment-info">
                <h4>Link meeting</h4>
                <p><a href="{{ $appointment->meeting_link }}" style="color: #0891B2; text-decoration: none; font-weight: 600;">{{ $appointment->meeting_link }}</a></p>
            </div>
        </div>
        @elseif($appointment->meeting_type === 'onsite' && $appointment->full_address)
        <div class="appointment-row">
            <div class="appointment-info">
                <h4>Địa điểm</h4>
                <p style="font-size: 14px;">{{ $appointment->full_address }}</p>
            </div>
        </div>
        @endif

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
    </div>

    <!-- Action Button: chỉ có nút hủy -->
    <div class="button-group" style="margin-top: 20px; text-align: center;">
        <a href="{{ $cancelUrl }}" class="btn btn-outline">
            Hủy lịch hẹn
        </a>
    </div>

    <div class="divider"></div>

    <p style="font-size: 12px; color: #94A3B8; text-align: center; margin-top: 24px;">
        Email này được gửi tự động, vui lòng không trả lời.
    </p>
@endsection
