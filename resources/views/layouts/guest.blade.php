<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'NIKAHIN') }}</title>

    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #1a1410 0%, #2d2520 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .auth-container {
            width: 100%;
            max-width: 450px;
        }

        .logo-box {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo-box img {
            width: 120px;
            height: auto;
            filter: drop-shadow(0 4px 12px rgba(212, 175, 55, 0.3));
        }

        .auth-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
        }

        .auth-title {
            font-family: 'Playfair Display', serif;
            font-size: 28px;
            font-weight: 700;
            color: #1a1410;
            margin-bottom: 8px;
            text-align: center;
        }

        .auth-subtitle {
            font-size: 14px;
            color: #666;
            text-align: center;
            margin-bottom: 30px;
        }

        .form-label {
            font-weight: 500;
            color: #333;
            margin-bottom: 8px;
        }

        .form-label i {
            color: #d4af37;
            margin-right: 6px;
        }

        .input-group-text {
            background: white;
            border-right: 0;
            color: #999;
        }

        .form-control {
            border-left: 0;
            padding: 12px 16px;
        }

        .form-control:focus {
            border-color: #d4af37;
            box-shadow: 0 0 0 0.2rem rgba(212, 175, 55, 0.25);
        }

        .form-control:focus + .input-group-text {
            border-color: #d4af37;
        }

        .btn-primary {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #d4af37, #b8941e);
            border: none;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(212, 175, 55, 0.4);
            background: linear-gradient(135deg, #d4af37, #b8941e);
        }

        .link-gold {
            color: #d4af37;
            text-decoration: none;
            font-weight: 500;
        }

        .link-gold:hover {
            color: #b8941e;
        }

        .divider {
            text-align: center;
            margin: 25px 0;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #e5e5e5;
        }

        .divider span {
            background: white;
            padding: 0 15px;
            position: relative;
            color: #999;
            font-size: 13px;
        }

        .alert {
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .footer-text {
            text-align: center;
            margin-top: 20px;
            color: rgba(255, 255, 255, 0.7);
            font-size: 13px;
        }

        .input-group {
            border: 2px solid #e5e5e5;
            border-radius: 10px;
            overflow: hidden;
        }

        .input-group:focus-within {
            border-color: #d4af37;
        }

        .input-group .form-control,
        .input-group .input-group-text {
            border: none;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="logo-box">
            <a href="/">
                <img src="{{ asset('images/logo.png') }}" alt="NIKAHIN">
            </a>
        </div>

        <div class="auth-card">
            {{ $slot }}
        </div>

        <div class="footer-text">
            &copy; {{ date('Y') }} NIKAHIN. All rights reserved.
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
