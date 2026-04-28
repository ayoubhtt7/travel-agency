<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'TravelApp') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #0a2342 0%, #1a4a8a 60%, #0d6efd 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .auth-card {
            background: white;
            border-radius: 16px;
            padding: 40px;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .auth-logo {
            text-align: center;
            margin-bottom: 28px;
            font-size: 1.8rem;
            font-weight: 700;
            color: #0a2342;
            text-decoration: none;
            display: block;
        }
        .auth-logo:hover { color: #0d6efd; }
        .form-label { font-weight: 600; color: #374151; }
        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13,110,253,0.15);
        }
        .btn-auth {
            background: linear-gradient(135deg, #0d6efd, #0a58ca);
            border: none;
            border-radius: 8px;
            padding: 10px 28px;
            font-weight: 600;
            color: white;
        }
        .btn-auth:hover {
            background: linear-gradient(135deg, #0a58ca, #084298);
            color: white;
        }
        .auth-link {
            color: #6b7280;
            font-size: 0.875rem;
            text-decoration: underline;
        }
        .auth-link:hover { color: #111827; }
    </style>
</head>
<body>
    <div class="auth-card">
        <a href="/" class="auth-logo">✈ TravelApp</a>
        {{ $slot }}
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
