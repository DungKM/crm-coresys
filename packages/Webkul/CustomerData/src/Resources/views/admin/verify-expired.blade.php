
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Link đã hết hạn</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #f59e0b 0%, #ef4444 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .container {
            background: white;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 600px;
            width: 100%;
            overflow: hidden;
            animation: shake 0.5s ease-out;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-10px); }
            20%, 40%, 60%, 80% { transform: translateX(10px); }
        }
        
        .header {
            background: linear-gradient(135deg, #f59e0b 0%, #ef4444 100%);
            padding: 50px 30px;
            text-align: center;
            color: white;
        }
        
        .error-icon {
            width: 100px;
            height: 100px;
            background: white;
            border-radius: 50%;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 24px;
            animation: pulse 2s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        .header h1 {
            font-size: 32px;
            margin-bottom: 12px;
        }
        
        .content {
            padding: 40px 30px;
        }
        
        .warning-box {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 30px;
        }
        
        .warning-box h3 {
            color: #92400e;
            font-size: 18px;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .warning-box p {
            color: #b45309;
            line-height: 1.6;
            margin-bottom: 8px;
        }
        
        .info-card {
            background: #f8fafc;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
        }
        
        .info-row {
            display: flex;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .info-row:last-child { border-bottom: none; }
        
        .info-icon {
            width: 20px;
            height: 20px;
            margin-right: 12px;
        }
        
        .info-label {
            color: #64748b;
            font-weight: 600;
            margin-right: 12px;
            min-width: 100px;
        }
        
        .info-value {
            color: #1e293b;
            font-weight: 500;
        }
        
        .actions {
            display: flex;
            gap: 12px;
        }
        
        .btn {
            flex: 1;
            padding: 16px 24px;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }
        
        .footer {
            text-align: center;
            padding: 24px;
            background: #f8fafc;
            color: #64748b;
            font-size: 13px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="error-icon">
                <svg width="56" height="56" fill="none" stroke="#ef4444" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h1>Link đã hết hạn</h1>
            <p>Link xác thực của bạn đã không còn hiệu lực</p>
        </div>

        <div class="content">
            <div class="warning-box">
                <h3>
                    <svg width="24" height="24" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    Tại sao link hết hạn?
                </h3>
                <p><strong>• Link xác thực chỉ có hiệu lực trong 7 ngày</strong></p>
                <p>• Đã quá thời hạn sử dụng</p>
                <p>• Hoặc link đã được sử dụng trước đó</p>
            </div>

            <div class="info-card">
                <div class="info-row">
                    <svg class="info-icon" fill="none" stroke="#667eea" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span class="info-label">Họ tên:</span>
                    <span class="info-value">{{ $customerData->name }}</span>
                </div>
                
                <div class="info-row">
                    <svg class="info-icon" fill="none" stroke="#667eea" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <span class="info-label">Email:</span>
                    <span class="info-value">{{ $customerData->email }}</span>
                </div>
            </div>

            <div class="actions">
                <a href="{{ route('admin.customer-data.index') }}" class="btn btn-primary">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Liên hệ để được hỗ trợ
                </a>
            </div>
        </div>

        <div class="footer">
            © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </div>
    </div>
</body>
</html>
