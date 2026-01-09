@extends('appointment::emails.layout')

@section('title', 'Lịch hẹn đã được xác nhận')

@section('header-title', 'Đã Xác Nhận')

@section('content')
    <p style="font-size: 16px; margin-bottom: 8px;">
        Xin chào <strong style="color: #0891B2;">{{ $appointment->customer_name }}</strong>,
    </p>

    <p style="font-size: 14px; color: #64748B; margin-bottom: 24px;">
        Cảm ơn bạn đã xác nhận! Lịch hẹn của bạn đã được ghi nhận và chúng tôi rất mong được gặp bạn.
    </p>

    <span class="status-badge status-confirmed">Đã xác nhận</span>

    <!-- Appointment Details Card -->
    <div class="appointment-card">
        <!-- Thời gian -->
        <div class="appointment-row">
            <div class="appointment-info">
                <h4>Thời gian</h4>
                <p style="color: #10B981;">{{ $appointment->start_at->format('d/m/Y') }} lúc {{ $appointment->start_at->format('H:i') }}</p>
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
                <p><a href="{{ $appointment->meeting_link }}" style="color: #10B981; text-decoration: none; font-weight: 600;">{{ $appointment->meeting_link }}</a></p>
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

    <!-- Success Message -->
    <div class="alert-box alert-success">
        <p style="font-size: 14px; margin: 0;">
            <strong>Xác nhận thành công!</strong> Chúng tôi sẽ gửi nhắc nhở trước giờ hẹn. Vui lòng có mặt đúng giờ.
        </p>
    </div>

    <div class="divider"></div>

    <p style="font-size: 13px; color: #64748B; text-align: center; line-height: 1.8;">
        Nếu bạn có bất kỳ thắc mắc nào, vui lòng liên hệ với chúng tôi qua:<br>
        <strong>Hotline:</strong> {{ $company['phone'] }} | <strong>Email:</strong> {{ $company['email'] }}
    </p>

    <p style="font-size: 12px; color: #94A3B8; text-align: center; margin-top: 24px;">
        Cảm ơn bạn đã tin tưởng và sử dụng dịch vụ của chúng tôi!
    </p>
@endsection
