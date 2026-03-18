<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #f8fafc;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }
        .wrapper {
            width: 100%;
            table-layout: fixed;
            background-color: #f8fafc;
            padding-bottom: 40px;
        }
        .main {
            background-color: #ffffff;
            margin: 0 auto;
            width: 100%;
            max-width: 600px;
            border-spacing: 0;
            color: #334155;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            margin-top: 40px;
        }
        .header {
            background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
            padding: 40px 20px;
            text-align: center;
        }
        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 28px;
            font-weight: 800;
            letter-spacing: -0.025em;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 16px;
        }
        .message {
            font-size: 16px;
            line-height: 24px;
            color: #64748b;
            margin-bottom: 32px;
        }
        .otp-container {
            background-color: #f1f5f9;
            border-radius: 8px;
            padding: 24px;
            text-align: center;
            margin-bottom: 32px;
        }
        .otp-code {
            font-size: 42px;
            font-weight: 800;
            letter-spacing: 12px;
            color: #4f46e5;
            margin: 0;
        }
        .expiry {
            font-size: 14px;
            color: #94a3b8;
            margin-top: 8px;
        }
        .footer {
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #94a3b8;
        }
        .divider {
            height: 1px;
            background-color: #e2e8f0;
            margin: 0 30px;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <table class="main">
            <tr>
                <td class="header">
                    <h1>{{ config('app.name') }}</h1>
                </td>
            </tr>
            <tr>
                <td class="content">
                    <div class="greeting">Verify Your Email</div>
                    <div class="message">
                        Thank you for joining us! To complete your registration and secure your account, please use the following one-time password (OTP) code:
                    </div>
                    <div class="otp-container">
                        <div class="otp-code">{{ $otp }}</div>
                        <div class="expiry">Valid for 15 minutes only</div>
                    </div>
                    <div class="message" style="margin-bottom: 0;">
                        If you didn't request this verification code, you can safely ignore this email.
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="divider"></div>
                </td>
            </tr>
            <tr>
                <td class="footer">
                    &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
