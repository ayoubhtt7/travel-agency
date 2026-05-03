<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TravelApp</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f1f5f9; color: #1e293b; }
        .wrapper { max-width: 600px; margin: 40px auto; background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08); }
        .header { background: linear-gradient(135deg, #0a2342, #0d6efd); padding: 32px 40px; text-align: center; }
        .header .logo { color: #38bdf8; font-size: 22px; font-weight: 800; letter-spacing: 1px; }
        .header .tagline { color: rgba(255,255,255,0.7); font-size: 13px; margin-top: 4px; }
        .body { padding: 36px 40px; }
        .greeting { font-size: 18px; font-weight: 700; margin-bottom: 8px; }
        .intro { color: #64748b; font-size: 15px; margin-bottom: 28px; line-height: 1.6; }
        .card { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 24px; margin-bottom: 24px; }
        .card-title { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.2px; color: #94a3b8; margin-bottom: 14px; }
        .detail-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #e2e8f0; font-size: 14px; }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { color: #64748b; }
        .detail-value { font-weight: 600; color: #1e293b; text-align: right; }
        .total-row { display: flex; justify-content: space-between; padding: 14px 0 0; font-size: 16px; }
        .total-label { font-weight: 700; }
        .total-value { font-weight: 800; color: #0d6efd; font-size: 18px; }
        .badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; }
        .badge-success { background: #dcfce7; color: #166534; }
        .badge-danger  { background: #fee2e2; color: #991b1b; }
        .badge-warning { background: #fef9c3; color: #854d0e; }
        .btn { display: inline-block; background: #0d6efd; color: white; padding: 13px 32px; border-radius: 8px; text-decoration: none; font-weight: 700; font-size: 15px; margin-top: 8px; }
        .divider { border: none; border-top: 1px solid #e2e8f0; margin: 24px 0; }
        .footer { background: #f8fafc; padding: 24px 40px; text-align: center; border-top: 1px solid #e2e8f0; }
        .footer p { font-size: 12px; color: #94a3b8; line-height: 1.8; }
        .footer a { color: #0d6efd; text-decoration: none; }
        .alert-box { border-radius: 10px; padding: 16px 20px; margin-bottom: 24px; font-size: 14px; line-height: 1.6; }
        .alert-success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; }
        .alert-danger  { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }
        .alert-warning { background: #fffbeb; border: 1px solid #fde68a; color: #92400e; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        <div class="logo">✈ TravelApp</div>
        <div class="tagline">Your journey starts here</div>
    </div>
    <div class="body">
        @yield('content')
    </div>
    <div class="footer">
        <p>
            This email was sent to <strong>{{ $user->email ?? '' }}</strong><br>
            © {{ date('Y') }} TravelApp. All rights reserved.<br>
            <a href="#">Unsubscribe</a> · <a href="#">Privacy Policy</a>
        </p>
    </div>
</div>
</body>
</html>
