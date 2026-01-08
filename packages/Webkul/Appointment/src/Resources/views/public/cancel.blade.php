<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hủy lịch hẹn - {{ config('appointment.company.name') }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #ffffff; /* nền trắng */
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            width: 100%;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: #ffffff; /* header trắng */
            padding: 40px 30px;
            text-align: center;
            color: #1F2937; /* chữ đen */
        }
        .header h1 {
            font-size: 26px;
            margin-bottom: 8px;
            font-weight: 700;
        }
        .header p {
            font-size: 15px;
            opacity: 0.9;
        }
        .content {
            padding: 30px 25px;
        }
        .appointment-summary {
            background: #FEF3C7;
            border-left: 4px solid #F59E0B;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 24px;
        }
        .appointment-summary h3 {
            color: #92400E;
            font-size: 16px;
            margin-bottom: 12px;
            font-weight: 600;
        }
        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            color: #78350F;
            font-size: 14px;
        }
        .summary-value {
            font-weight: 600;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #1F2937;
            font-size: 14px;
        }
        .form-group textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #E5E7EB;
            border-radius: 8px;
            font-family: inherit;
            font-size: 14px;
            resize: vertical;
            min-height: 100px;
            transition: border-color 0.3s;
        }
        .form-group textarea:focus {
            outline: none;
            border-color: #F59E0B;
        }
        .warning-box {
            background: #FEE2E2;
            border-left: 4px solid #EF4444;
            padding: 16px;
            border-radius: 8px;
            margin: 24px 0;
        }
        .warning-box p {
            color: #991B1B;
            font-size: 14px;
            margin: 0;
        }
        .actions {
            display: flex;
            gap: 12px;
            margin-top: 32px;
        }
        .btn {
            flex: 1;
            padding: 14px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 15px;
            text-align: center;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
        }
        .btn-danger {
            background: #EF4444;
            color: #ffffff;
        }
        .btn-danger:hover {
            background: #DC2626;
            transform: translateY(-2px);
        }
        .btn-secondary {
            background: #ffffff;
            color: #6B7280;
            border: 2px solid #E5E7EB;
        }
        .btn-secondary:hover {
            background: #F9FAFB;
            border-color: #D1D5DB;
        }
        .footer {
            background: #ffffff; /* footer trắng */
            padding: 24px 30px;
            text-align: center;
            color: #1F2937; /* chữ đen */
            font-size: 14px;
            border-top: 1px solid #E5E7EB;
        }
        @media (max-width: 600px) {
            .actions {
                flex-direction: column;
            }
            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Hủy lịch hẹn</h1>
            <p>Vui lòng xác nhận hủy lịch hẹn của bạn</p>
        </div>

        <div class="content">
            <div class="appointment-summary">
                <h3>Thông tin lịch hẹn</h3>
                <div class="summary-item">
                    <span>Khách hàng:</span>
                    <span class="summary-value">{{ $appointment->customer_name }}</span>
                </div>
                <div class="summary-item">
                    <span>Thời gian:</span>
                    <span class="summary-value">{{ $appointment->start_at->format('d/m/Y H:i') }}</span>
                </div>
                <div class="summary-item">
                    <span>Hình thức:</span>
                    <span class="summary-value">
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
                <div class="summary-item">
                    <span>Dịch vụ:</span>
                    <span class="summary-value">{{ $appointment->service_name }}</span>
                </div>
                @endif
            </div>

            <form method="POST" action="{{ route('appointment.public.cancel.process', ['id' => $appointment->id, 'token' => $token]) }}">
                @csrf

                <div class="form-group">
                    <label for="reason">Lý do hủy (không bắt buộc)</label>
                    <textarea
                        id="reason"
                        name="reason"
                        placeholder="Vui lòng cho chúng tôi biết lý do bạn hủy lịch hẹn để chúng tôi có thể cải thiện dịch vụ..."
                    ></textarea>
                </div>

                <div class="warning-box">
                    <p><strong>Lưu ý:</strong> Sau khi hủy, lịch hẹn này sẽ không thể khôi phục. Bạn có thể đặt lịch mới nếu cần.</p>
                </div>

                <div class="actions">
                    <button type="submit" class="btn btn-danger">
                        Xác nhận hủy
                    </button>
                    <a href="{{ config('appointment.company.website') }}" class="btn btn-secondary">
                        Quay lại
                    </a>
                </div>
            </form>
        </div>

        <div class="footer">
            <p><strong>{{ config('appointment.company.name') }}</strong></p>
            <p>{{ config('appointment.company.phone') }} | {{ config('appointment.company.email') }}</p>
        </div>
    </div>
</body>
</html>
