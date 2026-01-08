<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #263238;
            background-color: #F5F7FA;
        }
        .email-wrapper {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .email-header {
            background-color: #ffffff; /* nền trắng */
            padding: 40px 30px;
            text-align: center;
        }

        .email-header .logo {
            font-size: 28px;
            font-weight: 700;
            color: #263238; /* chữ đen */
            letter-spacing: -0.5px;
        }

        .email-header h1 {
            color: #263238; /* chữ đen */
            font-size: 22px;
            margin-top: 16px;
            font-weight: 600;
            letter-spacing: -0.3px;
        }
        .email-body {
            padding: 40px 30px;
            background-color: #ffffff;
        }

        /* Status Badges */
        .status-badge {
            display: inline-block;
            padding: 8px 20px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 24px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .status-scheduled {
            background-color: #E0F2FE;
            color: #0369A1;
        }
        .status-confirmed {
            background-color: #D1FAE5;
            color: #065F46;
        }
        .status-rescheduled {
            background-color: #FEF3C7;
            color: #92400E;
        }
        .status-cancelled {
            background-color: #FEE2E2;
            color: #991B1B;
        }
        .status-upcoming {
            background-color: #FFEDD5;
            color: #9A3412;
        }

        /* Appointment Card */
        .appointment-card {
            background-color: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 8px;
            padding: 24px;
            margin: 24px 0;
        }
        .appointment-row {
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #E2E8F0;
        }
        .appointment-row:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }
        .appointment-info h4 {
            font-size: 12px;
            color: #64748B;
            margin-bottom: 6px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }
        .appointment-info p {
            font-size: 15px;
            color: #263238;
            font-weight: 500;
            margin: 0;
            line-height: 1.5;
        }

        /* Button Styles */
        .button-group {
            margin: 32px 0;
            text-align: center;
        }
        .btn {
            display: inline-block;
            padding: 14px 32px;
            margin: 6px 4px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.2s ease;
        }
        .btn-primary {
            background-color: #0891B2;
            color: #ffffff !important;
        }
        .btn-secondary {
            background-color: #10B981;
            color: #ffffff !important;
        }
        .btn-danger {
            background-color: #EF4444;
            color: #ffffff !important;
        }
        .btn-outline {
            background-color: transparent;
            border: 2px solid #CBD5E1;
            color: #64748B !important;
        }

        .divider {
            height: 1px;
            background-color: #E2E8F0;
            margin: 32px 0;
        }

        .email-footer {
            background-color: #F8FAFC;
            padding: 32px 30px;
            text-align: center;
            border-top: 1px solid #E2E8F0;
        }
        .company-info p {
            font-size: 13px;
            color: #64748B;
            margin: 6px 0;
            line-height: 1.6;
        }

        /* Alert Boxes */
        .alert-box {
            padding: 16px 20px;
            border-radius: 6px;
            margin: 24px 0;
            border-left: 4px solid;
        }
        .alert-success {
            background-color: #ECFDF5;
            border-color: #10B981;
            color: #065F46;
        }
        .alert-warning {
            background-color: #FFFBEB;
            border-color: #F59E0B;
            color: #92400E;
        }
        .alert-danger {
            background-color: #FEF2F2;
            border-color: #EF4444;
            color: #991B1B;
        }
        .alert-info {
            background-color: #F0F9FF;
            border-color: #0891B2;
            color: #0C4A6E;
        }

        /* Countdown Box */
        .countdown-box {
            background: linear-gradient(135deg, #FEF3C7 0%, #FDE68A 100%);
            border: 2px solid #F59E0B;
            padding: 24px;
            border-radius: 8px;
            margin: 24px 0;
            text-align: center;
        }
        .countdown-box h2 {
            font-size: 28px;
            color: #B45309;
            margin: 0 0 8px 0;
            font-weight: 700;
        }
        .countdown-box p {
            font-size: 16px;
            color: #92400E;
            margin: 0;
            font-weight: 600;
        }

        /* Reminder Tips */
        .reminder-tips {
            background-color: #F0F9FF;
            border-left: 4px solid #0891B2;
            padding: 20px;
            border-radius: 6px;
            margin: 24px 0;
        }
        .reminder-tips h4 {
            font-size: 14px;
            color: #0C4A6E;
            margin: 0 0 12px 0;
            font-weight: 600;
        }
        .reminder-tips ul {
            margin: 0;
            padding-left: 20px;
            color: #164E63;
        }
        .reminder-tips li {
            margin-bottom: 6px;
            font-size: 14px;
            line-height: 1.6;
        }

        /* Highlight Text */
        .highlight {
            color: #0891B2;
            font-weight: 600;
        }

        /* Mobile Responsive */
        @media only screen and (max-width: 600px) {
            .email-wrapper {
                margin: 0;
                box-shadow: none;
            }
            .email-header, .email-body, .email-footer {
                padding: 24px 20px;
            }
            .btn {
                display: block;
                margin: 8px 0;
                width: 100%;
            }
            .appointment-card {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <!-- Header -->
        <div class="email-header">
            @if(!empty($company['logo_url']))
                <img src="{{ $company['logo_url'] }}" alt="{{ $company['name'] }}" style="max-width: 160px; height: auto;">
            @else
                <div class="logo">{{ $company['name'] ?? 'Krayin' }}</div>
            @endif
            <h1>@yield('header-title')</h1>
        </div>

        <!-- Body -->
        <div class="email-body">
            @yield('content')
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <div class="company-info">
                <p style="font-weight: 600; color: #263238; margin-bottom: 8px;">{{ $company['name'] ?? '' }}</p>
                <p>{{ $company['address'] ?? '' }}</p>
                <p>
                    <strong>Email:</strong> {{ $company['email'] ?? '' }} |
                    <strong>Hotline:</strong> {{ $company['phone'] ?? '' }}
                </p>
                @if(!empty($company['website']))
                <p><a href="{{ $company['website'] }}" style="color: #0891B2; text-decoration: none; font-weight: 600;">{{ $company['website'] }}</a></p>
                @endif
            </div>

            <p style="font-size: 11px; color: #94A3B8; margin-top: 20px;">
                © {{ date('Y') }} {{ $company['name'] ?? '' }}. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
