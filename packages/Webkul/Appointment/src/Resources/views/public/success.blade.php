<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thành công - {{ config('appointment.company.name') }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #ffffff; /* background toàn trang màu trắng */
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            width: 100%;
            background: #ffffff; /* container màu trắng */
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1); /* giảm shadow nhẹ nhàng hơn */
            overflow: hidden;
        }
        .header {
            background: #ffffff; /* header trắng */
            padding: 40px 30px;
            text-align: center;
            color: #1F2937; /* chữ header màu đen */
        }
        .checkmark {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            border-radius: 50%;
            background: #DBF5E0; /* nền vòng tròn nhạt, vẫn màu xanh nhạt */
            display: flex;
            align-items: center;
            justify-content: center;
            animation: scaleIn 0.5s ease-out;
        }
        .checkmark::after {
            content: "✓";
            font-size: 48px;
            font-weight: bold;
            color: #10B981; /* dấu tích màu xanh */
        }
        @keyframes scaleIn {
            0% { transform: scale(0); opacity: 0; }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); opacity: 1; }
        }
        .header h1 {
            font-size: 28px;
            margin-bottom: 10px;
            font-weight: 700;
        }
        .header p {
            font-size: 16px;
            opacity: 0.9;
        }
        .content {
            padding: 30px 25px;
        }
        .content h2 {
            font-size: 20px;
            margin-bottom: 16px;
            color: #1F2937;
        }
        .appointment-info {
            background: #F9FAFB;
            border-radius: 12px;
            padding: 24px;
            margin: 24px 0;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #E5E7EB;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            color: #6B7280;
            font-size: 14px;
        }
        .info-value {
            color: #1F2937;
            font-weight: 600;
            text-align: right;
        }
        .status-confirmed { color: #10B981; }
        .status-cancelled { color: #EF4444; }
        .status-rescheduled { color: #F59E0B; }

        .reminder-box {
            background: #DBEAFE;
            border-left: 4px solid #3B82F6;
            padding: 16px;
            border-radius: 8px;
            margin: 24px 0;
        }
        .reminder-box p {
            color: #1E40AF;
            font-size: 14px;
            margin: 0;
        }
        .actions {
            margin-top: 32px;
            text-align: center;
        }
        .btn {
            display: inline-block;
            padding: 14px 28px;
            margin: 8px 4px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.3s;
        }
        .btn-primary {
            background: #10B981;
            color: #ffffff;
        }
        .btn-primary:hover {
            background: #059669;
            transform: translateY(-2px);
        }
        .btn-secondary {
            background: #ffffff;
            color: #10B981;
            border: 2px solid #10B981;
        }
        .btn-secondary:hover {
            background: #10B981;
            color: #ffffff;
        }
        .footer {
            background: #ffffff;
            padding: 24px 30px;
            text-align: center;
            color: #6B7280;
            font-size: 14px;
            border-top: 1px solid #E5E7EB;
        }
        @media (max-width: 600px) {
            .header { padding: 30px 20px; }
            .content { padding: 25px 20px; }
            .btn { display: block; margin: 8px 0; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="checkmark"></div>
            <h1>{{ $message }}</h1>
            <p>
                @if($type === 'confirmed')
                    Cảm ơn bạn đã xác nhận lịch hẹn
                @elseif($type === 'cancelled')
                    Lịch hẹn của bạn đã được hủy
                @elseif($type === 'rescheduled')
                    Lịch hẹn của bạn đã được đổi
                @endif
            </p>
        </div>

        <div class="content">
            <h2>Thông tin lịch hẹn</h2>

            <div class="appointment-info">
                <div class="info-row">
                    <span class="info-label">Khách hàng</span>
                    <span class="info-value">{{ $appointment->customer_name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Thời gian</span>
                    <span class="info-value">{{ $appointment->start_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Thời lượng</span>
                    <span class="info-value">{{ $appointment->duration_minutes }} phút</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Hình thức</span>
                    <span class="info-value">
                        @if($appointment->meeting_type === 'call')
                            Gọi điện
                        @elseif($appointment->meeting_type === 'online')
                            Online
                        @else
                            Trực tiếp
                        @endif
                    </span>
                </div>
                @if($appointment->service_name)
                <div class="info-row">
                    <span class="info-label">Dịch vụ</span>
                    <span class="info-value">{{ $appointment->service_name }}</span>
                </div>
                @endif
                <div class="info-row">
                    <span class="info-label">Trạng thái</span>
                    <span class="info-value
                        @if($type === 'confirmed') status-confirmed
                        @elseif($type === 'cancelled') status-cancelled
                        @elseif($type === 'rescheduled') status-rescheduled
                        @endif
                    ">
                        @if($type === 'confirmed')
                            Đã xác nhận
                        @elseif($type === 'cancelled')
                            Đã hủy
                        @elseif($type === 'rescheduled')
                            Đã đổi lịch
                        @endif
                    </span>
                </div>
            </div>

            @if($type === 'confirmed' || $type === 'rescheduled')
            <div class="reminder-box">
                <p><strong>Nhắc nhở:</strong> Chúng tôi sẽ gửi email nhắc nhở trước giờ hẹn. Vui lòng có mặt đúng giờ!</p>
            </div>
            @endif
        </div>

        <div class="footer">
            <p><strong>{{ config('appointment.company.name') }}</strong></p>
            <p>{{ config('appointment.company.phone') }} | {{ config('appointment.company.email') }}</p>
        </div>
    </div>
</body>
</html>
