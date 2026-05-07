<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Selamat Datang, {{ $guest->name }}</title>
    <meta name="theme-color" content="#1a0a2e">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --gold:       #d4af37;
            --gold-light: #f0d060;
            --gold-dark:  #a8892a;
            --deep:       #1a0a2e;
            --deep2:      #2d1b4e;
            --rose:       #c9748a;
            --white:      #ffffff;
            --cream:      #fdf8f0;
        }

        html, body {
            height: 100%;
            overflow: hidden;
            font-family: 'Inter', sans-serif;
            background: var(--deep);
        }

        /* ─── Particle canvas ─── */
        #particles {
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 0;
        }

        /* ─── Background gradient ─── */
        .bg {
            position: fixed;
            inset: 0;
            background: radial-gradient(ellipse at 20% 20%, #2d1b4e 0%, #1a0a2e 50%, #0d0618 100%);
            z-index: 0;
        }

        /* ─── Floating orbs ─── */
        .orb {
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.25;
            pointer-events: none;
            z-index: 0;
            animation: orbFloat 8s ease-in-out infinite;
        }
        .orb-1 { width: 400px; height: 400px; background: var(--gold); top: -100px; right: -100px; animation-delay: 0s; }
        .orb-2 { width: 300px; height: 300px; background: var(--rose); bottom: -80px; left: -80px; animation-delay: -3s; }
        .orb-3 { width: 200px; height: 200px; background: #7c3aed; top: 40%; left: 10%; animation-delay: -5s; }

        @keyframes orbFloat {
            0%, 100% { transform: translateY(0) scale(1); }
            50%       { transform: translateY(-30px) scale(1.05); }
        }

        /* ─── Main wrapper ─── */
        .wrapper {
            position: relative;
            z-index: 10;
            height: 100dvh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        /* ─── Card ─── */
        .card {
            background: rgba(255,255,255,0.06);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(212,175,55,0.25);
            border-radius: 28px;
            padding: 40px 32px 36px;
            max-width: 420px;
            width: 100%;
            text-align: center;
            box-shadow:
                0 0 0 1px rgba(212,175,55,0.1),
                0 32px 80px rgba(0,0,0,0.5),
                inset 0 1px 0 rgba(255,255,255,0.1);
            opacity: 0;
            transform: translateY(40px) scale(0.95);
            animation: cardIn 0.8s cubic-bezier(0.34,1.56,0.64,1) 0.3s forwards;
        }

        @keyframes cardIn {
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* ─── Status badge ─── */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 100px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            margin-bottom: 24px;
            opacity: 0;
            animation: fadeUp 0.5s ease 0.9s forwards;
        }
        .status-badge.success {
            background: rgba(34,197,94,0.15);
            border: 1px solid rgba(34,197,94,0.35);
            color: #4ade80;
        }
        .status-badge.warning {
            background: rgba(251,191,36,0.15);
            border: 1px solid rgba(251,191,36,0.35);
            color: #fbbf24;
        }
        .status-badge svg { width: 13px; height: 13px; }

        /* ─── Ring avatar ─── */
        .avatar-ring {
            position: relative;
            width: 100px;
            height: 100px;
            margin: 0 auto 20px;
            opacity: 0;
            animation: fadeUp 0.5s ease 1.0s forwards;
        }
        .avatar-ring::before {
            content: '';
            position: absolute;
            inset: -4px;
            border-radius: 50%;
            background: conic-gradient(var(--gold), var(--rose), var(--gold-light), var(--gold));
            animation: spin 4s linear infinite;
        }
        .avatar-ring::after {
            content: '';
            position: absolute;
            inset: -2px;
            border-radius: 50%;
            background: rgba(26,10,46,0.9);
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        .avatar-inner {
            position: relative;
            z-index: 1;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--gold-dark), var(--gold), var(--gold-light));
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Playfair Display', serif;
            font-size: 36px;
            font-weight: 700;
            color: var(--deep);
        }

        /* ─── Welcome text ─── */
        .welcome-label {
            font-size: 12px;
            font-weight: 500;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: var(--gold);
            margin-bottom: 8px;
            opacity: 0;
            animation: fadeUp 0.5s ease 1.1s forwards;
        }

        .guest-name {
            font-family: 'Playfair Display', serif;
            font-size: clamp(26px, 6vw, 34px);
            font-weight: 700;
            color: var(--white);
            line-height: 1.2;
            margin-bottom: 6px;
            opacity: 0;
            animation: fadeUp 0.5s ease 1.2s forwards;
        }

        .category-pill {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 100px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.5px;
            background: rgba(212,175,55,0.15);
            border: 1px solid rgba(212,175,55,0.3);
            color: var(--gold-light);
            margin-bottom: 28px;
            opacity: 0;
            animation: fadeUp 0.5s ease 1.3s forwards;
        }

        /* ─── Divider ─── */
        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 24px;
            opacity: 0;
            animation: fadeUp 0.5s ease 1.35s forwards;
        }
        .divider-line { flex: 1; height: 1px; background: linear-gradient(to right, transparent, rgba(212,175,55,0.4), transparent); }
        .divider-icon { color: var(--gold); font-size: 16px; }

        /* ─── Couple names ─── */
        .couple-names {
            font-family: 'Playfair Display', serif;
            font-size: clamp(18px, 4.5vw, 22px);
            font-weight: 600;
            color: var(--white);
            margin-bottom: 4px;
            opacity: 0;
            animation: fadeUp 0.5s ease 1.4s forwards;
        }
        .couple-names .amp {
            color: var(--gold);
            font-style: italic;
            margin: 0 6px;
        }

        .event-subtitle {
            font-size: 12px;
            color: rgba(255,255,255,0.5);
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 24px;
            opacity: 0;
            animation: fadeUp 0.5s ease 1.45s forwards;
        }

        /* ─── Info pills ─── */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 28px;
            opacity: 0;
            animation: fadeUp 0.5s ease 1.5s forwards;
        }
        .info-pill {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 14px;
            padding: 12px 10px;
            text-align: center;
        }
        .info-pill .pill-icon {
            font-size: 18px;
            margin-bottom: 4px;
            display: block;
        }
        .info-pill .pill-label {
            font-size: 9px;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--gold);
            display: block;
            margin-bottom: 3px;
        }
        .info-pill .pill-value {
            font-size: 12px;
            font-weight: 500;
            color: rgba(255,255,255,0.85);
            line-height: 1.3;
        }

        /* ─── CTA button ─── */
        .cta-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 15px 24px;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--gold-dark), var(--gold), var(--gold-light));
            color: var(--deep);
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 8px 24px rgba(212,175,55,0.35);
            opacity: 0;
            animation: fadeUp 0.5s ease 1.6s forwards;
        }
        .cta-btn:hover { transform: translateY(-2px); box-shadow: 0 12px 32px rgba(212,175,55,0.45); }
        .cta-btn:active { transform: translateY(0); }
        .cta-btn svg { width: 18px; height: 18px; }

        /* ─── Footer note ─── */
        .footer-note {
            margin-top: 20px;
            font-size: 11px;
            color: rgba(255,255,255,0.3);
            opacity: 0;
            animation: fadeUp 0.5s ease 1.7s forwards;
        }

        /* ─── Confetti burst (success only) ─── */
        .confetti-wrap {
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 5;
            overflow: hidden;
        }
        .confetti-piece {
            position: absolute;
            top: -20px;
            width: 8px;
            height: 8px;
            border-radius: 2px;
            opacity: 0;
            animation: confettiFall var(--dur) ease-in var(--delay) forwards;
        }
        @keyframes confettiFall {
            0%   { opacity: 1; transform: translateY(0) rotate(0deg) scale(1); }
            80%  { opacity: 1; }
            100% { opacity: 0; transform: translateY(100vh) rotate(720deg) scale(0.5); }
        }

        /* ─── Pulse ring (success) ─── */
        .pulse-ring {
            position: absolute;
            inset: -12px;
            border-radius: 50%;
            border: 2px solid rgba(212,175,55,0.5);
            animation: pulseRing 2s ease-out 1.5s infinite;
        }
        @keyframes pulseRing {
            0%   { transform: scale(1); opacity: 0.6; }
            100% { transform: scale(1.5); opacity: 0; }
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ─── Already checked-in overlay ─── */
        .already-badge {
            position: absolute;
            top: -10px;
            right: -10px;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: #fbbf24;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2;
            box-shadow: 0 2px 8px rgba(0,0,0,0.3);
        }
        .already-badge svg { width: 14px; height: 14px; color: #1a0a2e; }

        /* ─── Responsive ─── */
        @media (max-height: 700px) {
            .card { padding: 28px 24px 24px; }
            .avatar-ring { width: 80px; height: 80px; margin-bottom: 14px; }
            .avatar-inner { font-size: 28px; }
            .info-grid { margin-bottom: 20px; }
        }
    </style>
</head>
<body>

<div class="bg"></div>
<canvas id="particles"></canvas>

<!-- Floating orbs -->
<div class="orb orb-1"></div>
<div class="orb orb-2"></div>
<div class="orb orb-3"></div>

@if(!$alreadyCheckedIn)
<!-- Confetti burst on first check-in -->
<div class="confetti-wrap" id="confetti"></div>
@endif

<div class="wrapper">
    <div class="card">

        {{-- Status badge --}}
        @if(!$alreadyCheckedIn)
        <div class="status-badge success">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <polyline points="20 6 9 17 4 12"/>
            </svg>
            Check-in Berhasil
        </div>
        @else
        <div class="status-badge warning">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
            </svg>
            Sudah Check-in
        </div>
        @endif

        {{-- Avatar --}}
        <div class="avatar-ring">
            @if(!$alreadyCheckedIn)
            <div class="pulse-ring"></div>
            @else
            <div class="already-badge">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                </svg>
            </div>
            @endif
            <div class="avatar-inner">
                {{ strtoupper(mb_substr(trim($guest->name), 0, 1)) }}{{ strtoupper(mb_substr(trim(strstr($guest->name, ' ') ?: $guest->name), 1, 1)) }}
            </div>
        </div>

        {{-- Welcome text --}}
        <p class="welcome-label">✦ Selamat Datang ✦</p>
        <h1 class="guest-name">{{ $guest->name }}</h1>
        <span class="category-pill">{{ $categoryLabel }}</span>

        {{-- Divider --}}
        <div class="divider">
            <div class="divider-line"></div>
            <span class="divider-icon">💍</span>
            <div class="divider-line"></div>
        </div>

        {{-- Couple names --}}
        <p class="couple-names">
            {{ $invitation->bride_name }}
            <span class="amp">&</span>
            {{ $invitation->groom_name }}
        </p>
        <p class="event-subtitle">Undangan Pernikahan</p>

        {{-- Info grid --}}
        <div class="info-grid">
            <div class="info-pill">
                <span class="pill-icon">📅</span>
                <span class="pill-label">Resepsi</span>
                <span class="pill-value">
                    {{ \Carbon\Carbon::parse($invitation->reception_date)->locale('id')->isoFormat('D MMM YYYY') }}
                </span>
            </div>
            <div class="info-pill">
                <span class="pill-icon">🕐</span>
                <span class="pill-label">Waktu</span>
                <span class="pill-value">
                    {{ \Carbon\Carbon::parse($invitation->reception_time_start)->format('H:i') }}
                    @if($invitation->reception_time_end)
                    – {{ \Carbon\Carbon::parse($invitation->reception_time_end)->format('H:i') }}
                    @endif
                </span>
            </div>
            <div class="info-pill" style="grid-column: span 2;">
                <span class="pill-icon">📍</span>
                <span class="pill-label">Lokasi</span>
                <span class="pill-value">{{ $invitation->reception_location }}</span>
            </div>
        </div>

        {{-- CTA --}}
        <a href="{{ url('/i/' . $invitation->unique_url) }}" class="cta-btn">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
            Lihat Undangan Lengkap
        </a>

        {{-- Footer --}}
        <p class="footer-note">
            @if(!$alreadyCheckedIn)
            Check-in tercatat pada {{ $checkedInAt->format('H:i') }} WIB
            @else
            Anda sudah check-in pada {{ $checkedInAt->format('H:i') }} WIB
            @endif
        </p>

    </div>
</div>

<script>
// ─── Particle system ───────────────────────────────────────────────────────
(function () {
    const canvas = document.getElementById('particles');
    const ctx = canvas.getContext('2d');
    let W, H, particles = [];

    function resize() {
        W = canvas.width  = window.innerWidth;
        H = canvas.height = window.innerHeight;
    }
    window.addEventListener('resize', resize);
    resize();

    function rand(a, b) { return a + Math.random() * (b - a); }

    class Particle {
        constructor() { this.reset(); }
        reset() {
            this.x  = rand(0, W);
            this.y  = rand(0, H);
            this.r  = rand(0.5, 2);
            this.vx = rand(-0.2, 0.2);
            this.vy = rand(-0.4, -0.1);
            this.a  = rand(0.1, 0.6);
            this.da = rand(-0.002, 0.002);
        }
        update() {
            this.x += this.vx;
            this.y += this.vy;
            this.a += this.da;
            if (this.a <= 0 || this.a >= 0.7) this.da *= -1;
            if (this.y < -5) this.reset();
        }
        draw() {
            ctx.beginPath();
            ctx.arc(this.x, this.y, this.r, 0, Math.PI * 2);
            ctx.fillStyle = `rgba(212,175,55,${this.a})`;
            ctx.fill();
        }
    }

    for (let i = 0; i < 80; i++) particles.push(new Particle());

    function loop() {
        ctx.clearRect(0, 0, W, H);
        particles.forEach(p => { p.update(); p.draw(); });
        requestAnimationFrame(loop);
    }
    loop();
})();

@if(!$alreadyCheckedIn)
// ─── Confetti burst ────────────────────────────────────────────────────────
(function () {
    const wrap = document.getElementById('confetti');
    const colors = ['#d4af37','#f0d060','#c9748a','#a78bfa','#ffffff','#fbbf24'];
    const count = 80;

    for (let i = 0; i < count; i++) {
        const el = document.createElement('div');
        el.className = 'confetti-piece';
        el.style.cssText = `
            left: ${Math.random() * 100}%;
            background: ${colors[Math.floor(Math.random() * colors.length)]};
            width: ${4 + Math.random() * 8}px;
            height: ${4 + Math.random() * 8}px;
            border-radius: ${Math.random() > 0.5 ? '50%' : '2px'};
            --dur: ${2 + Math.random() * 2}s;
            --delay: ${0.8 + Math.random() * 1.5}s;
        `;
        wrap.appendChild(el);
    }

    // Remove confetti after animation
    setTimeout(() => wrap.remove(), 5000);
})();
@endif
</script>

</body>
</html>
