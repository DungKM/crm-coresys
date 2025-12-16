<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận thông tin đăng ký</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .container {
            background: #f9f9f9;
            border-radius: 8px;
            padding: 30px;
            border: 1px solid #ddd;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #2c3e50;
            margin: 0;
        }
        .header img {
            width: 70px;
            margin-bottom: 10px;
        }
        .content {
            background: white;
            padding: 25px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: #3498db;
            color: white !important;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
        }
        .btn:hover {
            background: #2980b9;
        }
        .footer {
            text-align: center;
            color: #777;
            font-size: 12px;
            margin-top: 20px;
        }
        .info-box {
            background: #e8f4f8;
            border-left: 4px solid #3498db;
            padding: 15px;
            margin: 20px 0;
        }
        .warning {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
        }
        .icon {
            width: 20px;
            vertical-align: middle;
            margin-right: 6px;
        }
    </style>
</head>
<body>
    <div class="container">

        <!-- Header -->
        <div class="header">
            @if(isset($icons['logo']))
                <img src="{{ $icons['logo'] }}" alt="Logo">
            @endif
            <h1>Chào mừng bạn!</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <p>Xin chào <strong>{{ $name }}</strong>,</p>

            <p>Cảm ơn bạn đã quan tâm đến dịch vụ của chúng tôi. Chúng tôi đã nhận được thông tin đăng ký của bạn.</p>

            <div class="info-box">
                <p>
                    @if(isset($icons['mail']))
                        <img class="icon" src="{{ $icons['mail'] }}" alt="">
                    @endif
                    <strong>Vui lòng xác nhận địa chỉ email của bạn</strong>
                </p>
                <p>Nhấn vào nút bên dưới để xác nhận:</p>
            </div>

            <div style="text-align: center;">
                <a href="{{ $verifyUrl }}" class="btn">
                    @if(isset($icons['verify']))
                        <img class="icon" src="{{ $icons['verify'] }}" alt="">
                    @endif
                    Xác nhận Email
                </a>
            </div>

            <p>Hoặc copy link sau:</p>
            <p style="word-break: break-all; color: #3498db;">{{ $verifyUrl }}</p>

            <div class="warning">
                <p>
                    @if(isset($icons['warning']))
                        <img class="icon" src="{{ $icons['warning'] }}" alt="">
                    @endif
                    <strong>Lưu ý:</strong>
                </p>
                <ul style="margin: 5px 0; padding-left: 20px;">
                    <li>Link hết hạn vào: <strong>{{ $expiresAt }}</strong></li>
                    <li>Nếu không xác nhận, thông tin sẽ không hợp lệ.</li>
                </ul>
            </div>

            <p style="margin-top: 30px;">Sau khi xác nhận, đội ngũ của chúng tôi sẽ liên hệ với bạn.</p>

            <p>Nếu bạn không thực hiện đăng ký, vui lòng bỏ qua email này.</p>

            <p style="margin-top: 30px;">
                Trân trọng,<br>
                <strong>{{ config('app.name') }}</strong>
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Email này được gửi tự động, vui lòng không phản hồi.</p>
            <p>© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
