<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lỗi - {{ config('appointment.company.name') }}</title>
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
            max-width: 500px;
            width: 100%;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            overflow: hidden;
            text-align: center;
        }
        .header {
            background: #ffffff; /* header trắng */
            padding: 50px 30px;
            color: #1F2937; /* chữ đen */
        }
        .error-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            border-radius: 50%;
            background: #FEE2E2; /* nền đỏ nhạt cho icon */
            color: #DC2626;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            font-weight: bold;
            animation: shake 0.5s ease-in-out;
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }
        .header h1 {
            font-size: 28px;
            margin-bottom: 10px;
            font-weight: 700;
        }
        .content {
            padding: 30px 25px;
        }
        .error-message {
            background: #FEE2E2;
            border-left: 4px solid #EF4444;
            padding: 20px;
            border-radius: 8px;
            margin: 24px 0;
            text-align: left;
        }
        .error-message p {
            color: #991B1B;
            font-size: 15px;
            line-height: 1.6;
            font-weight: 600;
        }
        .help-text {
            margin-top: 24px;
            padding: 16px;
            background: #F9FAFB;
            border-radius: 8px;
            font-size: 14px;
            color: #6B7280;
            text-align: left;
        }
        .help-text p {
            margin-bottom: 12px;
            font-weight: 600;
        }
        .help-text ul {
            padding-left: 20px;
            line-height: 1.8;
        }
        .actions {
            margin-top: 32px;
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
            border: none;
            cursor: pointer;
        }
        .btn-primary {
            background: #EF4444;
            color: #ffffff;
        }
        .btn-primary:hover {
            background: #DC2626;
            transform: translateY(-2px);
        }
        .btn-secondary {
            background: #ffffff;
            color: #EF4444;
            border: 2px solid #EF4444;
        }
        .btn-secondary:hover {
            background: #EF4444;
            color: #ffffff;
        }
        .footer {
            background: #ffffff; /* footer trắng */
            padding: 24px 30px;
            color: #6B7280;
            font-size: 14px;
            border-top: 1px solid #E5E7EB;
        }
        .footer p {
            margin: 8px 0;
        }
        .footer strong {
            color: #1F2937;
        }
        @media (max-width: 600px) {
            .header {
                padding: 40px 20px;
            }
            .content {
                padding: 30px 20px;
            }
            .btn {
                display: block;
                margin: 8px 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="error-icon">!</div>
            <h1>Có lỗi xảy ra</h1>
        </div>

        <div class="content">
            <div class="error-message">
                <p>{{ $message }}</p>
            </div>

            <div class="help-text">
                <p>Có thể do các nguyên nhân sau:</p>
                <ul>
                    <li>Link đã hết hạn (quá 72 giờ)</li>
                    <li>Link không hợp lệ hoặc đã được sử dụng</li>
                    <li>Lịch hẹn đã bị hủy hoặc thay đổi</li>
                    <li>Lỗi hệ thống tạm thời</li>
                </ul>
            </div>

            <div class="actions">
                <a href="mailto:{{ config('appointment.company.email') }}" class="btn btn-secondary">
                    Liên hệ hỗ trợ
                </a>
            </div>
        </div>

        <div class="footer">
            <p><strong>Cần hỗ trợ?</strong></p>
            <p>
                Hotline: <strong>{{ config('appointment.company.phone') }}</strong><br>
                Email: <strong>{{ config('appointment.company.email') }}</strong>
            </p>
            <p style="margin-top: 16px; font-size: 12px; color: #9CA3AF;">
                © {{ date('Y') }} {{ config('appointment.company.name') }}
            </p>
        </div>
    </div>
</body>
</html>
