@extends('appointment::emails.layout')

@section('title', 'Khách hàng đã đổi lịch hẹn')

@section('header-title', 'Lịch Hẹn Đã Đổi')

@php
    // ✅ Lấy tên dịch vụ an toàn (GIỐNG FILE LỊCH HẸN MỚI)
    $serviceName =
        $appointment->service->name
        ?? $appointment->service_name
        ?? null;
@endphp

@section('content')
    <p style="font-size: 16px; margin-bottom: 8px;">
        Xin chào
        <strong style="color: #0891B2;">
            {{ $appointment->assignedUser->name ?? 'Nhân viên' }}
        </strong>,
    </p>

    <p style="font-size: 14px; color: #64748B; margin-bottom: 24px;">
        Khách hàng <strong>{{ $appointment->customer_name }}</strong> đã đổi lịch hẹn.
    </p>

    <span class="status-badge status-rescheduled">Đã đổi lịch</span>

    <!-- Old Appointment -->
    <div class="alert-box alert-warning">
        <h3 style="font-size: 14px; margin: 0 0 8px 0; font-weight: 600;">
            Lịch cũ (đã hủy)
        </h3>
        <p style="font-size: 14px; margin: 0; text-decoration: line-through; color: #92400E;">
            <strong>Thời gian:</strong>
            {{ $oldStartAt->format('d/m/Y') }}
            lúc {{ $oldStartAt->format('H:i') }}
        </p>
    </div>

    <!-- New Appointment Details Card -->
    <div class="appointment-card">

        <!-- Thời gian mới -->
        <div class="appointment-row">
            <div class="appointment-info">
                <h4>Thời gian mới</h4>
                <p style="color: #F59E0B; font-size: 16px;">
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

        <!-- ✅ DỊCH VỤ (XỬ LÝ GIỐNG FILE LỊCH HẸN MỚI) -->
        @if($serviceName)
        <div class="appointment-row">
            <div class="appointment-info">
                <h4>Dịch vụ</h4>
                <p>{{ $serviceName }}</p>
            </div>
        </div>
        @endif

    </div>

    <!-- Warning Message -->
    <div class="alert-box alert-warning">
        <p style="font-size: 14px; margin: 0;">
            <strong>Lưu ý:</strong> Khách hàng đã thay đổi lịch hẹn. Hãy theo dõi để phục vụ đúng giờ.
        </p>
    </div>

    <div class="divider"></div>

    <p style="font-size: 12px; color: #94A3B8; text-align: center; margin-top: 24px;">
        Email này được gửi tự động, vui lòng không trả lời.
    </p>
@endsection
