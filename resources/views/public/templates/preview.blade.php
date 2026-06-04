<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview: {{ $template->name }} - NIKAHIN</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Inter:wght@300;400;500;600;700&family=Great+Vibes&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --dark:   #0f0e17;
            --dark2:  #1a1825;
            --accent: #c9a96e;
            --accent2:#e8c98a;
            --muted:  rgba(255,255,255,0.55);
            --white:  #ffffff;
        }

        html, body { height: 100%; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--dark);
            color: var(--white);
            overflow: hidden;
        }

        /* ══════════════════════
           HEADER
        ══════════════════════ */
        .preview-header {
            position: fixed;
            top: 0; left: 0; right: 0;
            height: 60px;
            background: rgba(15, 14, 23, 0.92);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(201,169,110,0.15);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .header-logo {
            font-family: 'Great Vibes', cursive;
            font-size: 1.6rem;
            color: var(--accent);
            text-decoration: none;
        }

        .header-sep {
            width: 1px;
            height: 20px;
            background: rgba(255,255,255,0.15);
        }

        .header-template-name {
            font-size: 0.85rem;
            color: var(--muted);
            font-weight: 400;
        }

        .header-template-name strong {
            color: var(--white);
            font-weight: 600;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 18px;
            border-radius: 8px;
            font-size: 0.82rem;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: all 0.25s;
        }

        .btn-ghost {
            background: rgba(255,255,255,0.07);
            color: rgba(255,255,255,0.75);
            border: 1px solid rgba(255,255,255,0.1);
        }
        .btn-ghost:hover {
            background: rgba(255,255,255,0.13);
            color: var(--white);
        }

        .btn-gold {
            background: linear-gradient(135deg, var(--accent), #b8883c);
            color: #0f0e17;
        }
        .btn-gold:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(201,169,110,0.4);
        }

        /* ══════════════════════
           LAYOUT UTAMA
        ══════════════════════ */
        .preview-layout {
            display: flex;
            height: calc(100vh - 60px);
            margin-top: 60px;
        }

        /* ── PANEL KIRI ── */
        .panel-left {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 48px 56px;
            position: relative;
            overflow: hidden;
        }

        /* Background decoration */
        .panel-left::before {
            content: '';
            position: absolute;
            width: 600px; height: 600px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(201,169,110,0.08) 0%, transparent 70%);
            top: -200px; left: -200px;
            pointer-events: none;
        }
        .panel-left::after {
            content: '';
            position: absolute;
            width: 400px; height: 400px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(107,76,230,0.07) 0%, transparent 70%);
            bottom: -100px; right: 60px;
            pointer-events: none;
        }

        .left-content { position: relative; z-index: 1; max-width: 520px; }

        .tag-label {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: rgba(201,169,110,0.12);
            border: 1px solid rgba(201,169,110,0.3);
            color: var(--accent2);
            padding: 5px 14px;
            border-radius: 50px;
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 24px;
        }

        .tag-dot {
            width: 6px; height: 6px;
            background: var(--accent);
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.4; transform: scale(0.7); }
        }

        .main-title {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2rem, 3.5vw, 3rem);
            line-height: 1.18;
            font-weight: 700;
            margin-bottom: 18px;
            color: var(--white);
        }

        .main-title em {
            font-style: italic;
            color: var(--accent);
        }

        .main-subtitle {
            font-size: 0.95rem;
            color: var(--muted);
            line-height: 1.75;
            margin-bottom: 36px;
            max-width: 420px;
        }

        /* Feature pills */
        .features {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 40px;
        }

        .feature-pill {
            display: flex;
            align-items: center;
            gap: 7px;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.08);
            padding: 7px 14px;
            border-radius: 50px;
            font-size: 0.78rem;
            color: rgba(255,255,255,0.7);
            font-weight: 500;
        }

        .feature-pill svg {
            color: var(--accent);
            flex-shrink: 0;
        }

        /* CTA */
        .cta-group {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            align-items: center;
        }

        .btn-cta-primary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 13px 28px;
            border-radius: 12px;
            font-size: 0.88rem;
            font-weight: 700;
            text-decoration: none;
            background: linear-gradient(135deg, var(--accent), #b8883c);
            color: #0f0e17;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 20px rgba(201,169,110,0.3);
        }

        .btn-cta-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 28px rgba(201,169,110,0.45);
        }

        .btn-cta-secondary {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 13px 24px;
            border-radius: 12px;
            font-size: 0.88rem;
            font-weight: 600;
            text-decoration: none;
            background: transparent;
            color: rgba(255,255,255,0.75);
            border: 1px solid rgba(255,255,255,0.15);
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-cta-secondary:hover {
            background: rgba(255,255,255,0.07);
            color: var(--white);
            border-color: rgba(255,255,255,0.25);
        }

        /* Divider */
        .panel-divider {
            width: 1px;
            height: calc(100vh - 60px);
            background: linear-gradient(to bottom,
                transparent 0%,
                rgba(201,169,110,0.2) 20%,
                rgba(201,169,110,0.2) 80%,
                transparent 100%
            );
            flex-shrink: 0;
        }

        /* ── PANEL KANAN ── */
        .panel-right {
            width: 420px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255,255,255,0.02);
            padding: 28px 32px;
            position: relative;
        }

        /* Subtle glow behind phone */
        .panel-right::before {
            content: '';
            position: absolute;
            width: 340px; height: 340px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(201,169,110,0.1) 0%, transparent 70%);
            pointer-events: none;
        }

        /* Phone mockup */
        .phone-mockup {
            position: relative;
            z-index: 1;
            width: 260px;
            height: calc(260px * 19 / 9 + 28px);
            background: #0f0e17;
            border-radius: 38px;
            padding: 14px 10px;
            box-shadow:
                0 0 0 1.5px rgba(255,255,255,0.08),
                0 0 0 3px rgba(201,169,110,0.15),
                0 30px 80px rgba(0,0,0,0.7),
                0 8px 24px rgba(0,0,0,0.4);
        }

        /* Dynamic island / notch */
        .phone-notch {
            position: absolute;
            top: 14px;
            left: 50%;
            transform: translateX(-50%);
            width: 70px; height: 8px;
            background: #1a1825;
            border-radius: 4px;
            z-index: 3;
        }

        /* Side buttons */
        .phone-btn-vol {
            position: absolute;
            left: -3px; top: 80px;
            width: 3px; height: 22px;
            background: rgba(255,255,255,0.12);
            border-radius: 2px 0 0 2px;
        }
        .phone-btn-vol + .phone-btn-vol { top: 112px; }

        .phone-btn-power {
            position: absolute;
            right: -3px; top: 100px;
            width: 3px; height: 36px;
            background: rgba(255,255,255,0.12);
            border-radius: 0 2px 2px 0;
        }

        /* Screen */
        .phone-screen {
            width: 100%;
            height: 100%;
            border-radius: 26px;
            overflow: hidden;
            position: relative;
            background: #1a1825;
        }

        .phone-screen iframe {
            width: 390px;
            height: calc(390px * 19 / 9);
            border: none;
            transform-origin: top left;
            display: block;
            pointer-events: none;
        }

        /* Status bar overlay */
        .phone-statusbar {
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 28px;
            background: linear-gradient(to bottom, rgba(0,0,0,0.4) 0%, transparent 100%);
            z-index: 2;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 6px 14px 0;
            pointer-events: none;
        }

        .statusbar-time {
            font-size: 0.6rem;
            font-weight: 700;
            color: rgba(255,255,255,0.9);
            font-family: 'Inter', sans-serif;
        }

        .statusbar-icons {
            display: flex;
            gap: 4px;
            align-items: center;
        }

        .statusbar-icons svg { opacity: 0.85; }

        /* Home indicator */
        .phone-home {
            position: absolute;
            bottom: 6px; left: 50%;
            transform: translateX(-50%);
            width: 80px; height: 4px;
            background: rgba(255,255,255,0.3);
            border-radius: 2px;
            z-index: 3;
        }

        /* Interact hint */
        .phone-hint {
            position: absolute;
            bottom: -32px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.72rem;
            color: rgba(255,255,255,0.3);
            white-space: nowrap;
        }

        /* ══════════════════════
           MOBILE — tampilkan iframe full-screen
        ══════════════════════ */
        @media (max-width: 900px) {
            body { overflow: auto; }

            .panel-left  { display: none; }
            .panel-divider { display: none; }

            .panel-right {
                width: 100%;
                padding: 0;
                background: white;
                align-items: stretch;
            }

            .panel-right::before { display: none; }

            .phone-mockup {
                width: 100%;
                height: calc(100vh - 60px);
                background: transparent;
                border-radius: 0;
                padding: 0;
                box-shadow: none;
            }

            .phone-notch,
            .phone-btn-vol,
            .phone-btn-power,
            .phone-statusbar,
            .phone-home,
            .phone-hint { display: none; }

            .phone-screen {
                border-radius: 0;
                height: 100%;
            }

            .phone-screen iframe {
                width: 100%;
                height: 100%;
                transform: none;
                pointer-events: auto;
            }
        }
    </style>
</head>
<body>

    <!-- ── HEADER ── -->
    <div class="preview-header">
        <div class="header-left">
            <a href="/" class="header-logo">Nikahin</a>
            <div class="header-sep"></div>
            <span class="header-template-name">Preview &mdash; <strong>{{ $template->name }}</strong></span>
        </div>
        <div class="header-actions">
            <a href="{{ route('public.templates.index') }}" class="btn btn-ghost">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                Kembali
            </a>
        </div>
    </div>

    <!-- ── MAIN LAYOUT ── -->
    <div class="preview-layout">

        <!-- Panel Kiri: Marketing copy -->
        <div class="panel-left">
            <div class="left-content">

                <div class="tag-label">
                    <span class="tag-dot"></span>
                    Template {{ $template->name }}
                </div>

                <h1 class="main-title">
                    Undangan Digital<br>
                    yang <em>Berkesan</em><br>
                    untuk Momen Sakral
                </h1>

                <p class="main-subtitle">
                    Bagikan momen bahagia pernikahan Anda dengan cara yang modern dan elegan.
                    Template ini dirancang dengan animasi halus, layout yang cantik di semua perangkat,
                    dan fitur lengkap yang memudahkan tamu Anda.
                </p>

                <div class="features">
                    <div class="feature-pill">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        Animasi Halus
                    </div>
                    <div class="feature-pill">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="5" y="2" width="14" height="20" rx="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>
                        Mobile Friendly
                    </div>
                    <div class="feature-pill">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        RSVP Online
                    </div>
                    <div class="feature-pill">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        Peta Lokasi
                    </div>
                    <div class="feature-pill">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                        Galeri Foto
                    </div>
                    <div class="feature-pill">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/></svg>
                        Musik Latar
                    </div>
                </div>

                <div class="cta-group">
                    @auth
                        <a href="{{ route('invitations.create') }}?template={{ $template->id }}" class="btn-cta-primary">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 5v14M5 12h14"/></svg>
                            Buat Undangan Sekarang
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="btn-cta-primary">
                            Mulai Gratis Sekarang
                        </a>
                        <a href="{{ route('login') }}" class="btn-cta-secondary">
                            Sudah punya akun? Masuk
                        </a>
                    @endauth
                </div>

            </div>
        </div>

        <!-- Divider -->
        <div class="panel-divider"></div>

        <!-- Panel Kanan: Phone mockup -->
        <div class="panel-right">
            <div class="phone-mockup">
                <div class="phone-notch"></div>
                <span class="phone-btn-vol"></span>
                <span class="phone-btn-vol"></span>
                <span class="phone-btn-power"></span>

                <div class="phone-screen" id="phoneScreen">
                    <div class="phone-statusbar">
                        <span class="statusbar-time" id="statusTime">9:41</span>
                        <div class="statusbar-icons">
                            <!-- Signal -->
                            <svg width="14" height="10" viewBox="0 0 24 16" fill="white"><rect x="0" y="11" width="4" height="5" rx="1"/><rect x="5" y="7" width="4" height="9" rx="1"/><rect x="10" y="4" width="4" height="12" rx="1"/><rect x="15" y="1" width="4" height="15" rx="1"/></svg>
                            <!-- Battery -->
                            <svg width="18" height="10" viewBox="0 0 28 14" fill="none" stroke="white" stroke-width="1.5"><rect x="1" y="2" width="22" height="10" rx="2"/><path d="M24 5v4"/><rect x="3" y="4" width="14" height="6" rx="1" fill="white" stroke="none"/></svg>
                        </div>
                    </div>

                    <iframe
                        id="previewIframe"
                        src="{{ route('public.templates.preview', $template->id) }}?iframe=1"
                        title="Preview {{ $template->name }}"
                        loading="lazy"
                        sandbox="allow-scripts allow-same-origin"
                    ></iframe>

                    <div class="phone-home"></div>
                </div>
            </div>

            <div class="phone-hint">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20zm0 6v4l3 3"/></svg>
                Scroll di dalam layar untuk melihat selengkapnya
            </div>
        </div>

    </div>

    <script>
    // ── Scale iframe to fit phone screen ──────────────────────────────────────
    function scaleIframe() {
        const screen = document.getElementById('phoneScreen');
        const iframe = document.getElementById('previewIframe');
        if (!screen || !iframe) return;

        const sw = screen.offsetWidth;
        const iw = 390;
        const scale = sw / iw;

        iframe.style.width  = iw + 'px';
        iframe.style.height = (iw * 19 / 9) + 'px';
        iframe.style.transform = 'scale(' + scale + ')';
        iframe.style.transformOrigin = 'top left';
    }

    scaleIframe();
    window.addEventListener('resize', scaleIframe);

    // ── Live clock in status bar ──────────────────────────────────────────────
    function updateClock() {
        const el = document.getElementById('statusTime');
        if (!el) return;
        const now = new Date();
        el.textContent = now.getHours() + ':' + String(now.getMinutes()).padStart(2, '0');
    }
    updateClock();
    setInterval(updateClock, 10000);

    // ── Allow scroll inside iframe on desktop ────────────────────────────────
    const phoneScreen = document.getElementById('phoneScreen');
    if (phoneScreen && window.innerWidth > 900) {
        const iframe = document.getElementById('previewIframe');
        // Enable pointer events on hover so user can scroll
        phoneScreen.addEventListener('mouseenter', () => {
            if (iframe) iframe.style.pointerEvents = 'auto';
        });
        phoneScreen.addEventListener('mouseleave', () => {
            if (iframe) iframe.style.pointerEvents = 'none';
        });
    }
    </script>

</body>
</html>
