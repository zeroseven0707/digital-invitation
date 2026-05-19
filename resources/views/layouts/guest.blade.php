<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'NIKAHIN') }}</title>

    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@400;500;600;700&family=Great+Vibes&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f8f9fd;
            min-height: 100vh;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        .split-layout {
            display: flex;
            min-height: 100vh;
            width: 100vw;
        }

        /* Left Visual Column */
        .visual-column {
            flex: 1;
            background: linear-gradient(135deg, #1a1a2e 0%, #2d2d44 100%);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 50px;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .visual-column::before {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(107, 76, 230, 0.25) 0%, transparent 70%);
            top: -100px;
            left: -100px;
            border-radius: 50%;
        }

        .visual-column::after {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(212, 175, 55, 0.1) 0%, transparent 70%);
            bottom: -150px;
            right: -150px;
            border-radius: 50%;
        }

        .visual-header {
            position: relative;
            z-index: 10;
        }

        .visual-logo {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            font-weight: 700;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .visual-logo img {
            height: 50px;
            width: auto;
        }

        .visual-logo-text {
            font-family: 'Great Vibes', cursive;
            font-size: 2rem;
            color: #f0d060;
        }

        .visual-body {
            position: relative;
            z-index: 10;
            margin: auto 0;
            max-width: 500px;
        }

        .visual-title {
            font-family: 'Playfair Display', serif;
            font-size: 3rem;
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 20px;
        }

        .visual-title span {
            color: #f0d060;
            font-family: 'Great Vibes', cursive;
            font-size: 3.5rem;
            display: block;
            margin-top: 5px;
        }

        .visual-desc {
            font-size: 1.1rem;
            line-height: 1.8;
            opacity: 0.85;
            margin-bottom: 40px;
        }

        .visual-mockup-wrapper {
            position: relative;
            height: 380px;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
        }

        .visual-phone-frame {
            background: #0f172a;
            border-radius: 40px;
            padding: 12px;
            border: 4px solid #334155;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.6), 0 0 0 2px rgba(255, 255, 255, 0.05);
            width: 190px;
            aspect-ratio: 9/19;
            transform: rotate(-6deg) translateY(-10px);
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .visual-phone-frame:hover {
            transform: rotate(-3deg) scale(1.03) translateY(-15px);
        }

        .visual-phone-screen {
            border-radius: 28px;
            overflow: hidden;
            width: 100%;
            height: 100%;
            box-shadow: inset 0 0 10px rgba(0,0,0,0.8);
        }

        .visual-phone-screen img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .visual-floating-card {
            position: absolute;
            background: rgba(15, 10, 36, 0.9) !important;
            backdrop-filter: blur(12px);
            border: 2px solid #f0d060 !important;
            padding: 14px 22px;
            border-radius: 14px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4), 0 0 0 1px rgba(240, 208, 96, 0.2);
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 800;
            font-size: 0.95rem;
            color: #f0d060 !important;
            right: 40px;
            top: 60px;
            z-index: 15;
            letter-spacing: 0.5px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
            animation: float-y 3.5s ease-in-out infinite alternate;
        }
        
        .visual-floating-card i {
            font-size: 1.2rem;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
        }

        @keyframes float-y {
            from { transform: translateY(0) rotate(1deg); }
            to { transform: translateY(-12px) rotate(-1deg); }
        }

        .visual-footer {
            position: relative;
            z-index: 10;
            font-size: 0.85rem;
            opacity: 0.7;
        }

        /* Right Form Column */
        .form-column {
            flex: 1;
            background: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 60px 40px;
            position: relative;
        }

        .form-wrapper {
            width: 100%;
            max-width: 400px;
        }

        .mobile-logo-box {
            display: none;
            text-align: center;
            margin-bottom: 30px;
        }

        .mobile-logo-box img {
            width: 100px;
            height: auto;
            filter: drop-shadow(0 4px 12px rgba(107, 76, 230, 0.2));
        }

        .auth-card {
            background: white;
            width: 100%;
        }

        .auth-title {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 10px;
        }

        .auth-subtitle {
            font-size: 0.95rem;
            color: #666;
            margin-bottom: 35px;
        }

        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            font-size: 0.9rem;
        }

        .form-label i {
            color: #6b4ce6;
            margin-right: 6px;
        }

        .input-group {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
            background: #f8fafc;
            transition: all 0.3s;
        }

        .input-group:focus-within {
            border-color: #6b4ce6;
            box-shadow: 0 0 0 4px rgba(107, 76, 230, 0.15);
            background: white;
        }

        .input-group-text {
            background: transparent;
            border: none;
            color: #94a3b8;
            padding-left: 16px;
        }

        .form-control {
            border: none;
            background: transparent;
            padding: 14px 16px;
            font-size: 0.95rem;
            color: #1e293b;
        }

        .form-control:focus {
            background: transparent;
            box-shadow: none;
            border-color: transparent;
        }

        .form-check-input:checked {
            background-color: #6b4ce6;
            border-color: #6b4ce6;
        }

        .btn-primary {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #6b4ce6 0%, #5538d4 100%);
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s;
            margin-top: 15px;
            box-shadow: 0 4px 12px rgba(107, 76, 230, 0.25);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(107, 76, 230, 0.4);
            background: linear-gradient(135deg, #6b4ce6 0%, #5538d4 100%);
        }

        .link-gold {
            color: #6b4ce6;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
        }

        .link-gold:hover {
            color: #5538d4;
        }

        .divider {
            text-align: center;
            margin: 30px 0;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #f1f5f9;
        }

        .divider span {
            background: white;
            padding: 0 15px;
            position: relative;
            color: #94a3b8;
            font-size: 0.85rem;
        }

        /* Responsive styling */
        @media (max-width: 968px) {
            .visual-column {
                display: none;
            }
            .form-column {
                padding: 40px 20px;
                background: #f8f9fd;
            }
            .form-wrapper {
                background: white;
                padding: 40px 30px;
                border-radius: 24px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            }
            .mobile-logo-box {
                display: block;
            }
            .auth-title {
                font-size: 1.8rem;
                text-align: center;
            }
            .auth-subtitle {
                text-align: center;
                margin-bottom: 30px;
            }
        }
    </style>
</head>
<body>

    <div class="split-layout">
        <!-- Left Visual Column -->
        <div class="visual-column">
            <div class="visual-header">
                <a href="/" class="visual-logo">
                    <img src="{{ asset('images/logo.png') }}" alt="Nikahin Logo">
                    <span class="visual-logo-text">Nikahin</span>
                </a>
            </div>

            <div class="visual-body">
                <h1 class="visual-title">
                    Kelola Undangan
                    <span>Lebih Mudah & Praktis</span>
                </h1>
                <p class="visual-desc">
                    Masuk atau buat akun baru untuk mengakses seluruh fitur premium Nikahin. Buat dan bagikan kebahagiaan Anda dalam hitungan menit.
                </p>

                <div class="visual-mockup-wrapper">
                    <div class="visual-phone-frame">
                        <div class="visual-phone-screen">
                            <img src="{{ asset('images/gambar-app.jpeg') }}" alt="Nikahin App Screen">
                        </div>
                    </div>
                    <div class="visual-floating-card">
                        <i class="fas fa-check-circle" style="color: #f0d060;"></i>
                        <span>#1 Undangan Mobile App</span>
                    </div>
                </div>
            </div>

            <div class="visual-footer">
                &copy; {{ date('Y') }} Nikahin. All rights reserved.
            </div>
        </div>

        <!-- Right Form Column -->
        <div class="form-column">
            <div class="form-wrapper">
                <div class="mobile-logo-box">
                    <a href="/">
                        <img src="{{ asset('images/logo.png') }}" alt="Nikahin Logo">
                    </a>
                </div>

                <div class="auth-card">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
