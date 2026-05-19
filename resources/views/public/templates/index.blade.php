<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Template Undangan - NIKAHIN</title>

    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@300;400;500;600;700&family=Great+Vibes&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --primary:      #1a1a2e;
            --secondary:    #2d2d44;
            --accent:       #6b4ce6;
            --accent-dark:  #5538d4;
            --accent-light: #8b6ff0;
            --gold:         #d4af37;
            --gold-light:   #f0d060;
            --light:        #f8f9fd;
            --text:         #1a1a2e;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--light);
            color: var(--text);
            min-height: 100vh;
        }

        /* ── Navbar ── */
        nav {
            background: var(--primary);
            padding: 14px 5%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 20px rgba(107,76,230,0.2);
        }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .nav-brand img { height: 48px; width: auto; }

        .nav-brand-text {
            font-family: 'Great Vibes', cursive;
            font-size: 1.8rem;
            color: white;
            line-height: 1;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 28px;
            list-style: none;
        }

        .nav-links a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: color 0.2s;
        }

        .nav-links a:hover, .nav-links a.active { color: var(--gold); }

        .btn-nav {
            background: linear-gradient(135deg, var(--accent), var(--accent-dark));
            color: white !important;
            padding: 9px 22px;
            border-radius: 50px;
            font-weight: 600;
            box-shadow: 0 4px 14px rgba(107,76,230,0.4);
            transition: all 0.3s;
        }

        .btn-nav:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(107,76,230,0.5);
        }

        /* ── Hero ── */
        .hero {
            background: linear-gradient(135deg, var(--primary) 0%, #2d1b69 100%);
            padding: 70px 5% 60px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(107,76,230,0.25) 0%, transparent 70%);
            top: -150px; right: -100px;
            border-radius: 50%;
            pointer-events: none;
        }

        .hero-tag {
            display: inline-block;
            background: rgba(107,76,230,0.2);
            border: 1px solid rgba(107,76,230,0.4);
            color: var(--accent-light);
            padding: 6px 18px;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 20px;
        }

        .hero-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2rem, 5vw, 3rem);
            color: white;
            margin-bottom: 14px;
            position: relative;
        }

        .hero-subtitle {
            font-size: 1rem;
            color: rgba(255,255,255,0.65);
            max-width: 520px;
            margin: 0 auto;
            line-height: 1.7;
        }

        /* ── Grid ── */
        .templates-section {
            padding: 70px 5% 100px;
            max-width: 1300px;
            margin: 0 auto;
        }

        .templates-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 36px;
            justify-items: center;
        }

        /* ── Phone card ── */
        .template-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 14px;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .template-card:hover { transform: translateY(-8px); }

        .template-phone {
            position: relative;
            width: 160px;
            background: #1a1a2e;
            border-radius: 28px;
            padding: 10px 8px;
            box-shadow:
                0 0 0 2px #2d2d44,
                0 20px 50px rgba(107,76,230,0.2),
                0 8px 20px rgba(0,0,0,0.3);
            transition: box-shadow 0.3s ease;
        }

        .template-card:hover .template-phone {
            box-shadow:
                0 0 0 2px var(--accent),
                0 28px 60px rgba(107,76,230,0.4),
                0 12px 30px rgba(0,0,0,0.4);
        }

        /* Notch */
        .template-phone::before {
            content: '';
            position: absolute;
            top: 10px; left: 50%;
            transform: translateX(-50%);
            width: 40px; height: 6px;
            background: #2d2d44;
            border-radius: 3px;
            z-index: 2;
        }

        /* Side button */
        .template-phone::after {
            content: '';
            position: absolute;
            right: -3px; top: 60px;
            width: 3px; height: 30px;
            background: #2d2d44;
            border-radius: 0 2px 2px 0;
        }

        .template-screen {
            border-radius: 20px;
            overflow: hidden;
            aspect-ratio: 9 / 19;
            position: relative;
            background: linear-gradient(135deg, var(--accent), var(--accent-dark));
        }

        .template-screen img {
            width: 100%; height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.4s ease;
        }

        .template-card:hover .template-screen img { transform: scale(1.04); }

        .template-screen-placeholder {
            width: 100%; height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .template-overlay {
            position: absolute;
            inset: 0;
            background: rgba(26,26,46,0.6);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s;
            border-radius: 20px;
        }

        .template-card:hover .template-overlay { opacity: 1; }

        .btn-preview {
            background: white;
            color: var(--accent);
            padding: 8px 18px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.78rem;
            border: 2px solid white;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            transform: translateY(6px);
            transition: all 0.3s;
            white-space: nowrap;
        }

        .template-card:hover .btn-preview { transform: translateY(0); }

        .btn-preview:hover {
            background: var(--accent);
            color: white;
            border-color: var(--accent);
        }

        .template-footer { text-align: center; }

        .template-name {
            font-family: 'Inter', sans-serif;
            font-size: 0.88rem;
            font-weight: 600;
            color: var(--primary);
            margin: 0 0 3px;
        }

        .template-style {
            font-size: 0.75rem;
            color: rgba(107,76,230,0.65);
            font-weight: 500;
        }

        /* ── Empty state ── */
        .empty-state {
            grid-column: 1 / -1;
            text-align: center;
            padding: 80px 0;
            color: #aaa;
        }

        .empty-state i {
            font-size: 3.5rem;
            margin-bottom: 16px;
            display: block;
            opacity: 0.25;
        }

        /* ── Footer ── */
        footer {
            background: #0f0f1e;
            color: rgba(255,255,255,0.5);
            text-align: center;
            padding: 28px;
            font-size: 0.85rem;
        }

        footer a { color: var(--gold); text-decoration: none; }

        /* ── Responsive ── */
        @media (max-width: 600px) {
            .nav-links { display: none; }
            .templates-grid { grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 24px; }
            .template-phone { width: 130px; }
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav>
        <a href="/" class="nav-brand">
            <img src="{{ asset('images/logo.png') }}" alt="Nikahin">
            <span class="nav-brand-text">Nikahin</span>
        </a>
        <ul class="nav-links">
            <li><a href="/">Beranda</a></li>
            <li><a href="{{ route('public.templates.index') }}" class="active">Template</a></li>
            @auth
                <li><a href="{{ route('dashboard') }}" class="btn-nav"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            @else
                <li><a href="{{ route('login') }}">Masuk</a></li>
                <li><a href="{{ route('register') }}" class="btn-nav"><i class="fas fa-user-plus"></i> Daftar</a></li>
            @endauth
        </ul>
    </nav>

    <!-- Hero -->
    <div class="hero">
        <div class="hero-tag"><i class="fas fa-layer-group"></i> Koleksi Template</div>
        <h1 class="hero-title">Pilih Desain Undangan Anda</h1>
        <p class="hero-subtitle">
            Berbagai tema dari klasik hingga modern, dirancang dengan animasi yang memukau
            dan tampilan yang sempurna di semua perangkat.
        </p>
    </div>

    <!-- Templates -->
    <div class="templates-section">
        <div class="templates-grid">
            @forelse($templates as $template)
            <div class="template-card">
                <div class="template-phone">
                    <div class="template-screen">
                        @if($template->thumbnail_path && Storage::disk('public')->exists($template->thumbnail_path))
                            <img src="{{ Storage::disk('public')->url($template->thumbnail_path) }}"
                                 alt="{{ $template->name }}">
                        @else
                            <div class="template-screen-placeholder">
                                <i class="fas fa-image" style="font-size: 2rem; color: rgba(255,255,255,0.2);"></i>
                            </div>
                        @endif
                        <div class="template-overlay">
                            <a href="{{ route('public.templates.preview', $template->id) }}" class="btn-preview">
                                <i class="fas fa-eye"></i> Preview
                            </a>
                        </div>
                    </div>
                </div>
                <div class="template-footer">
                    <h4 class="template-name">{{ $template->name }}</h4>
                    <span class="template-style">{{ Str::limit($template->description, 22) }}</span>
                </div>
            </div>
            @empty
            <div class="empty-state">
                <i class="fas fa-images"></i>
                <p>Belum ada template tersedia.</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Footer -->
    <footer>
        &copy; {{ date('Y') }} <a href="/">Nikahin</a>. All rights reserved.
    </footer>

</body>
</html>
