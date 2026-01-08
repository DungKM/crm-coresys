@extends('appointment::emails.layout')

@section('title', 'Lịch hẹn đã được đổi giờ')

@section('header-title', 'Đổi Lịch Hẹn')

@section('content')
    <p style="font-size: 16px; margin-bottom: 8px;">
        Xin chào <strong style="color: #0891B2;">{{ $appointment->customer_name }}</strong>,
    </p>

    <p style="font-size: 14px; color: #64748B; margin-bottom: 24px;">
        Lịch hẹn của bạn đã được thay đổi. Vui lòng xem thông tin mới bên dưới và xác nhận lại lịch hẹn.
    </p>

    <span class="status-badge status-rescheduled">Đã đổi lịch</span>

    <!-- Old Appointment -->
    <div class="alert-box alert-warning">
        <h3 style="font-size: 14px; margin: 0 0 8px 0; font-weight: 600;">
            Lịch cũ (đã hủy)
        </h3>
        <p style="font-size: 14px; margin: 0; text-decoration: line-through; color: #92400E;">
            <strong>Thời gian:</strong> {{ $oldStartAt->format('d/m/Y') }} lúc {{ $oldStartAt->format('H:i') }}
        </p>
    </div>

    <!-- New Appointment Details Card -->
    <div class="appointment-card">
        <h3 style="font-size: 15px; color: #F59E0B; margin: 0 0 20px 0; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
            Thời gian mới
        </h3>

        <!-- Thời gian mới -->
        <div class="appointment-row">
            <div class="appointment-info">
                <h4>Thời gian</h4>
                <p style="color: #F59E0B; font-size: 16px;">
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
                <p><a href="{{ $appointment->meeting_link }}" style="color: #F59E0B; text-decoration: none; font-weight: 600;">{{ $appointment->meeting_link }}</a></p>
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
        @if($appointment->service_name)
        <div class="appointment-row">
            <div class="appointment-info">
                <h4>Dịch vụ</h4>
                <p>{{ $appointment->service_name }}</p>
            </div>
        </div>
        @endif

        <!-- Người phụ trách -->
        @if($appointment->assignedUser)
        <div class="appointment-row">
            <div class="appointment-info">
                <h4>Người phụ trách</h4>
                <p>{{ $appointment->assignedUser->name }}</p>
            </div>
        </div>
        @endif
    </div>

    <!-- Warning Message -->
    <div class="alert-box alert-warning">
        <p style="font-size: 14px; margin: 0;">
            <strong>Lưu ý:</strong> Vui lòng xác nhận lại lịch hẹn mới để chúng tôi có thể phục vụ bạn tốt nhất.
        </p>
    </div>

    <!-- Action Button -->
    <div class="button-group">
        <a href="{{ $confirmUrl }}" class="btn btn-primary">
            Xác nhận lịch hẹn
        </a>
        <a href="{{ $cancelUrl }}" class="btn btn-outline" style="border-color: #EF4444; color: #EF4444 !important;">
            Hủy lịch hẹn
        </a>
    </div>

    <div class="divider"></div>

    <p style="font-size: 13px; color: #64748B; text-align: center; line-height: 1.8;">
        Nếu bạn có bất kỳ thắc mắc nào, vui lòng liên hệ với chúng tôi qua:<br>
        <strong>Hotline:</strong> {{ $company['phone'] }} | <strong>Email:</strong> {{ $company['email'] }}
    </p>

    <p style="font-size: 12px; color: #94A3B8; text-align: center; margin-top: 24px;">
        Email này được gửi tự động, vui lòng không trả lời.
    </p>
@endsection
