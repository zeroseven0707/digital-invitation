<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Template Undangan - NIKAHIN</title>

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
            background: linear-gradient(135deg, #faf8f3 0%, #f5f1e8 100%);
        }

        .navbar {
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 15px 0;
        }

        .navbar-brand img {
            height: 40px;
        }

        .btn-gold {
            background: linear-gradient(135deg, #d4af37, #b8941e);
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-gold:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(212, 175, 55, 0.4);
            color: white;
        }

        .hero-section {
            padding: 80px 0 60px;
            text-align: center;
        }

        .hero-title {
            font-family: 'Playfair Display', serif;
            font-size: 48px;
            font-weight: 700;
            color: #1a1410;
            margin-bottom: 20px;
        }

        .hero-subtitle {
            font-size: 18px;
            color: #666;
            margin-bottom: 40px;
        }

        .template-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            transition: all 0.3s;
            margin-bottom: 30px;
            height: 100%;
        }

        .template-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }

        .template-thumbnail {
            width: 100%;
            height: 250px;
            object-fit: cover;
            background: #f5f5f5;
        }

        .template-body {
            padding: 20px;
        }

        .template-name {
            font-family: 'Playfair Display', serif;
            font-size: 22px;
            font-weight: 600;
            color: #1a1410;
            margin-bottom: 10px;
        }

        .template-description {
            font-size: 14px;
            color: #666;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .btn-preview {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #d4af37, #b8941e);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-preview:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(212, 175, 55, 0.4);
            color: white;
        }

        .footer {
            background: #1a1410;
            color: white;
            padding: 40px 0;
            margin-top: 60px;
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="/">
                <img src="{{ asset('images/logo.png') }}" alt="NIKAHIN">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="/">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('public.templates.index') }}">Template</a>
                    </li>
                    @auth
                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}" class="btn btn-gold ms-3">
                                <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                            </a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a href="{{ route('login') }}" class="nav-link">Masuk</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('register') }}" class="btn btn-gold ms-3">
                                <i class="fas fa-user-plus me-2"></i> Daftar
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero-section">
        <div class="container">
            <h1 class="hero-title">Pilih Template Undangan Anda</h1>
            <p class="hero-subtitle">
                Temukan template undangan digital yang sempurna untuk momen spesial Anda.<br>
                Semua template dapat disesuaikan dengan kebutuhan Anda.
            </p>
        </div>
    </div>

    <!-- Templates Grid -->
    <div class="container pb-5">
        <div class="row">
            @forelse($templates as $template)
                <div class="col-md-4 col-sm-6">
                    <div class="template-card">
                        @if($template->thumbnail_path && Storage::disk('public')->exists($template->thumbnail_path))
                            <img src="{{ Storage::disk('public')->url($template->thumbnail_path) }}"
                                 alt="{{ $template->name }}"
                                 class="template-thumbnail">
                        @else
                            <div class="template-thumbnail d-flex align-items-center justify-content-center">
                                <i class="fas fa-image fa-4x text-muted"></i>
                            </div>
                        @endif

                        <div class="template-body">
                            <h3 class="template-name">{{ $template->name }}</h3>
                            <p class="template-description">{{ $template->description }}</p>
                            <a href="{{ route('public.templates.preview', $template->id) }}"
                               class="btn-preview">
                                <i class="fas fa-eye me-2"></i> Lihat Preview
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fas fa-palette fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">Tidak ada template yang tersedia saat ini.</h5>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <div class="container">
            <p class="mb-0">&copy; {{ date('Y') }} NIKAHIN. All rights reserved.</p>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
