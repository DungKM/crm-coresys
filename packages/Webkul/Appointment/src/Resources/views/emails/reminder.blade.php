@extends('appointment::emails.layout')

@section('title', 'Nhắc nhở lịch hẹn')

@section('header-title', 'Nhắc Nhở Lịch Hẹn')

@section('content')
    <p style="font-size: 16px; margin-bottom: 8px;">
        Xin chào <strong style="color: #0891B2;">{{ $appointment->customer_name }}</strong>,
    </p>

    <p style="font-size: 14px; color: #64748B; margin-bottom: 24px;">
        Đây là email nhắc nhở về lịch hẹn của bạn sẽ diễn ra trong <strong style="color: #F59E0B;">{{ $timeText }}</strong> nữa.
    </p>

    <span class="status-badge status-upcoming">Sắp diễn ra</span>

    <!-- Countdown Alert -->
    <div class="countdown-box">
        <h2>{{ $timeText }}</h2>
        <p>đến giờ hẹn của bạn!</p>
    </div>

    <!-- Appointment Details Card -->
    <div class="appointment-card">
        <!-- Thời gian -->
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
                <p style="font-size: 16px; font-weight: 700;">{{ $appointment->call_phone }}</p>
            </div>
        </div>
        @elseif($appointment->meeting_type === 'online' && $appointment->meeting_link)
        <div class="appointment-row">
            <div class="appointment-info">
                <h4>Link meeting</h4>
                <p style="margin-top: 8px;">
                    <a href="{{ $appointment->meeting_link }}" class="btn btn-primary" style="display: inline-block; padding: 12px 24px;">
                        Tham gia ngay
                    </a>
                </p>
            </div>
        </div>
        @elseif($appointment->meeting_type === 'onsite' && $appointment->full_address)
        <div class="appointment-row">
            <div class="appointment-info">
                <h4>Địa điểm</h4>
                <p style="font-size: 14px;">{{ $appointment->full_address }}</p>
                <p style="margin-top: 8px;">
                    <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($appointment->full_address) }}"
                       style="color: #F59E0B; text-decoration: none; font-weight: 600;">
                        Xem bản đồ →
                    </a>
                </p>
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

    <!-- Reminder Tips -->
    <div class="reminder-tips">
        <h4>Lưu ý quan trọng:</h4>
        <ul>
            <li>Vui lòng có mặt đúng giờ</li>
            @if($appointment->meeting_type === 'online')
            <li>Kiểm tra kết nối internet và thiết bị trước khi tham gia</li>
            @elseif($appointment->meeting_type === 'onsite')
            <li>Tính toán thời gian di chuyển để đến đúng giờ</li>
            @endif
            <li>Chuẩn bị các tài liệu cần thiết (nếu có)</li>
        </ul>
    </div>

    <!-- Action Buttons -->
    @if($appointment->status !== 'confirmed')
    <div class="button-group">
        <a href="{{ $cancelUrl }}" class="btn btn-outline" style="border-color: #EF4444; color: #EF4444 !important;">
            Hủy lịch hẹn
        </a>
    </div>
    @endif

    <div class="divider"></div>

    <p style="font-size: 13px; color: #64748B; text-align: center; line-height: 1.8;">
        Nếu bạn có bất kỳ thắc mắc nào, vui lòng liên hệ với chúng tôi qua:<br>
        <strong>Hotline:</strong> {{ $company['phone'] }} | <strong>Email:</strong> {{ $company['email'] }}
    </p>

    <p style="font-size: 12px; color: #94A3B8; text-align: center; margin-top: 24px;">
        Chúng tôi rất mong được gặp bạn!
    </p>
@endsection
