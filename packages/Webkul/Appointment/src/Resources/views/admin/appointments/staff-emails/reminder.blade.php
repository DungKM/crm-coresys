@extends('appointment::emails.layout')

@section('title', 'Nhắc nhở lịch hẹn')

@section('header-title', 'Nhắc Nhở Lịch Hẹn')

@section('content')
    <p style="font-size: 16px; margin-bottom: 8px;">
        Xin chào <strong style="color: #0891B2;">{{ $appointment->assignedUser->name ?? 'Nhân viên' }}</strong>,
    </p>

    <p style="font-size: 14px; color: #64748B; margin-bottom: 24px;">
        Đây là email nhắc nhở về lịch hẹn được phân công cho bạn sẽ diễn ra sau <strong style="color: #0891B2;">{{ $timeText }}</strong> nữa.
    </p>

    <span class="status-badge status-scheduled">Sắp diễn ra</span>

    <!-- Appointment Details Card -->
    <div class="appointment-card">
        <!-- Khách hàng -->
        <div class="appointment-row">
            <div class="appointment-info">
                <h4>Khách hàng</h4>
                <p>{{ $appointment->customer_name }}</p>
                @if($appointment->customer_phone)
                <p style="font-size: 13px; color: #64748B; font-weight: normal; margin-top: 4px;">
                    SĐT: {{ $appointment->customer_phone }}
                </p>
                @endif
                @if($appointment->customer_email)
                <p style="font-size: 13px; color: #64748B; font-weight: normal; margin-top: 4px;">
                    Email: {{ $appointment->customer_email }}
                </p>
                @endif
            </div>
        </div>

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
                <h4>Số điện thoại</h4>
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

        <!-- Dịch vụ -->
        @if($appointment->service)
        <div class="appointment-row">
            <div class="appointment-info">
                <h4>Dịch vụ</h4>
                <p>{{ $appointment->service->name }}</p>
            </div>
        </div>
        @elseif($appointment->service_name)
        <div class="appointment-row">
            <div class="appointment-info">
                <h4>Dịch vụ</h4>
                <p>{{ $appointment->service_name }}</p>
            </div>
        </div>
        @endif

        <!-- Ghi chú -->
        @if($appointment->note)
        <div class="appointment-row">
            <div class="appointment-info">
                <h4>Ghi chú</h4>
                <p style="font-size: 14px; color: #64748B;">{{ $appointment->note }}</p>
            </div>
        </div>
        @endif
    </div>

    <p style="font-size: 12px; color: #94A3B8; text-align: center; margin-top: 24px;">
        Email này được gửi tự động, vui lòng không trả lời.
    </p>
@endsection
