<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $invitation->bride_name }} & {{ $invitation->groom_name }} - Undangan Pernikahan</title>
    <meta name="description" content="Undangan pernikahan {{ $invitation->bride_name }} dan {{ $invitation->groom_name }}. Kami mengundang Anda untuk berbagi kebahagiaan di hari istimewa kami.">

    <meta property="og:url" content="{{ url()->current() }}">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="nikahin - Undangan Digital">
    <meta property="og:title" content="Undangan Pernikahan {{ $invitation->bride_name }} & {{ $invitation->groom_name }}">
    <meta property="og:description" content="Kami mengundang Anda untuk berbagi kebahagiaan di hari istimewa kami. {{ $invitation->bride_name }} & {{ $invitation->groom_name }}">
    @if($invitation->galleries->isNotEmpty())
    <meta property="og:image" content="{{ asset('storage/' . $invitation->galleries->first()->photo_path) }}">
    <meta property="og:image:secure_url" content="{{ asset('storage/' . $invitation->galleries->first()->photo_path) }}">
    <meta property="og:image:type" content="image/jpeg">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="Foto {{ $invitation->bride_name }} & {{ $invitation->groom_name }}">
    @else
    <meta property="og:image" content="{{ asset('images/logo.png') }}">
    <meta property="og:image:secure_url" content="{{ asset('images/logo.png') }}">
    <meta property="og:image:alt" content="nikahin - Undangan Digital">
    @endif
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@nikahin">
    <meta name="twitter:title" content="Undangan Pernikahan {{ $invitation->bride_name }} & {{ $invitation->groom_name }}">
    <meta name="twitter:description" content="Kami mengundang Anda untuk berbagi kebahagiaan di hari istimewa kami. {{ $invitation->bride_name }} & {{ $invitation->groom_name }}">
    @if($invitation->galleries->isNotEmpty())
    <meta name="twitter:image" content="{{ asset('storage/' . $invitation->galleries->first()->photo_path) }}">
    @else
    <meta name="twitter:image" content="{{ asset('images/logo.png') }}">
    @endif
    <meta property="og:locale" content="id_ID">
    <meta name="robots" content="index, follow">
    <meta name="author" content="nikahin">
    <meta name="theme-color" content="#d4af37">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Inter:wght@300;400;500;600;700&family=Great+Vibes&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --dark:    #0f0e17;
            --dark2:   #1a1825;
            --accent:  #c9a96e;
            --accent2: #e8c98a;
            --muted:   rgba(255,255,255,0.55);
            --white:   #ffffff;
        }

        html, body { height: 100%; overflow: hidden; }

        body { font-family: 'Inter', sans-serif; background: var(--dark); color: var(--white); }

        /* ══════════════════════
           LAYOUT UTAMA — desktop: 2 panel, mobile: phone only
        ══════════════════════ */
        .inv-layout {
            display: flex;
            height: 100vh;
        }

        /* ── PANEL KIRI ── */
        .inv-panel-left {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 56px 64px;
            position: relative;
            overflow: hidden;
        }

        /* Decorative background */
        .inv-panel-left::before {
            content: '';
            position: absolute;
            width: 500px; height: 500px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(201,169,110,0.1) 0%, transparent 65%);
            top: -160px; left: -160px;
            pointer-events: none;
        }
        .inv-panel-left::after {
            content: '';
            position: absolute;
            width: 360px; height: 360px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(107,76,230,0.07) 0%, transparent 70%);
            bottom: -80px; right: 40px;
            pointer-events: none;
        }

        .inv-left-content { position: relative; z-index: 1; width: 100%; max-width: 560px; }

        /* Brand */
        .inv-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 40px;
            text-decoration: none;
        }

        .inv-brand-logo {
            font-family: 'Great Vibes', cursive;
            font-size: 2rem;
            color: var(--accent);
            line-height: 1;
        }

        .inv-brand-sep {
            width: 1px; height: 18px;
            background: rgba(255,255,255,0.15);
        }

        .inv-brand-label {
            font-size: 0.8rem;
            color: rgba(255,255,255,0.4);
            letter-spacing: 0.06em;
            font-weight: 500;
        }

        /* Kepada label */
        .inv-kepada {
            font-size: 0.8rem;
            color: rgba(255,255,255,0.4);
            letter-spacing: 0.12em;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .inv-guest-name {
            font-family: 'Playfair Display', serif;
            font-size: clamp(1.4rem, 2.5vw, 1.9rem);
            color: var(--accent2);
            font-style: italic;
            margin-bottom: 32px;
            line-height: 1.4;
        }

        /* Names */
        .inv-join-label {
            font-size: 0.75rem;
            letter-spacing: 0.18em;
            color: rgba(255,255,255,0.35);
            text-transform: uppercase;
            margin-bottom: 12px;
        }

        .inv-names {
            font-family: 'Playfair Display', serif;
            font-size: clamp(2.8rem, 4.5vw, 4rem);
            font-weight: 700;
            line-height: 1.1;
            color: var(--white);
            margin-bottom: 6px;
        }

        .inv-names em {
            color: var(--accent);
            font-style: italic;
        }

        .inv-names-amp {
            display: inline-block;
            color: rgba(255,255,255,0.2);
            font-size: 0.65em;
            padding: 0 8px;
        }

        /* Divider */
        .inv-divider {
            width: 56px; height: 1px;
            background: linear-gradient(to right, var(--accent), transparent);
            margin: 24px 0;
        }

        /* Info cards */
        .inv-info-cards {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-bottom: 32px;
        }

        .inv-info-card {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.07);
            border-radius: 12px;
            padding: 14px 18px;
        }

        .inv-info-icon {
            width: 36px; height: 36px;
            border-radius: 9px;
            background: rgba(201,169,110,0.12);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .inv-info-icon svg { color: var(--accent); }

        .inv-info-text {}
        .inv-info-label {
            font-size: 0.72rem;
            color: rgba(255,255,255,0.35);
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 3px;
        }

        .inv-info-value {
            font-size: 0.95rem;
            color: rgba(255,255,255,0.85);
            font-weight: 500;
            line-height: 1.5;
        }

        /* Countdown */
        .inv-countdown {
            display: flex;
            gap: 10px;
            margin-bottom: 32px;
        }

        .inv-count-box {
            flex: 1;
            background: rgba(201,169,110,0.1);
            border: 1px solid rgba(201,169,110,0.2);
            border-radius: 10px;
            padding: 12px 8px;
            text-align: center;
        }

        .inv-count-num {
            display: block;
            font-size: clamp(1.6rem, 3vw, 2.2rem);
            font-family: 'Playfair Display', serif;
            color: var(--accent);
            line-height: 1;
            margin-bottom: 5px;
        }

        .inv-count-lbl {
            font-size: 0.65rem;
            color: rgba(255,255,255,0.4);
            text-transform: uppercase;
            letter-spacing: 0.1em;
        }

        /* Action buttons */
        .inv-actions { display: flex; gap: 10px; flex-wrap: wrap; }

        .inv-btn {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 12px 22px;
            border-radius: 10px;
            font-size: 0.88rem;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: all 0.25s;
        }

        .inv-btn-primary {
            background: linear-gradient(135deg, var(--accent), #b8883c);
            color: #0f0e17;
            box-shadow: 0 4px 16px rgba(201,169,110,0.3);
        }
        .inv-btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 22px rgba(201,169,110,0.45);
        }

        .inv-btn-ghost {
            background: rgba(255,255,255,0.06);
            color: rgba(255,255,255,0.75);
            border: 1px solid rgba(255,255,255,0.12);
        }
        .inv-btn-ghost:hover {
            background: rgba(255,255,255,0.11);
            color: var(--white);
        }

        /* Footer brand */
        .inv-footer-brand {
            margin-top: 40px;
            font-size: 0.75rem;
            color: rgba(255,255,255,0.2);
        }

        .inv-footer-brand a {
            color: rgba(201,169,110,0.5);
            text-decoration: none;
        }

        /* Divider line */
        .inv-panel-divider {
            width: 1px;
            height: 100vh;
            background: linear-gradient(to bottom,
                transparent 0%,
                rgba(201,169,110,0.15) 15%,
                rgba(201,169,110,0.15) 85%,
                transparent 100%
            );
            flex-shrink: 0;
        }

        /* ── PANEL KANAN ── */
        .inv-panel-right {
            width: 44vw;
            max-width: 520px;
            min-width: 340px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255,255,255,0.015);
            padding: 24px 28px;
            position: relative;
        }

        .inv-panel-right::before {
            content: '';
            position: absolute;
            width: 300px; height: 300px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(201,169,110,0.08) 0%, transparent 70%);
            pointer-events: none;
        }

        /* Phone mockup — tinggi mengikuti viewport */
        .inv-phone {
            position: relative;
            z-index: 1;
            /* Tinggi phone = 90vh, lebar dihitung dari aspect ratio 9:19 */
            height: 90vh;
            width: calc(90vh * 9 / 19);
            max-width: 340px;
            background: #0f0e17;
            border-radius: 38px;
            padding: 14px 10px;
            box-shadow:
                0 0 0 1.5px rgba(255,255,255,0.07),
                0 0 0 3px rgba(201,169,110,0.12),
                0 30px 80px rgba(0,0,0,0.7),
                0 8px 24px rgba(0,0,0,0.4);
        }

        .inv-phone-notch {
            position: absolute;
            top: 14px; left: 50%;
            transform: translateX(-50%);
            width: 70px; height: 8px;
            background: #1a1825;
            border-radius: 4px;
            z-index: 3;
        }

        .inv-phone-btnL {
            position: absolute;
            left: -3px; top: 80px;
            width: 3px; height: 22px;
            background: rgba(255,255,255,0.1);
            border-radius: 2px 0 0 2px;
        }
        .inv-phone-btnL2 {
            position: absolute;
            left: -3px; top: 112px;
            width: 3px; height: 22px;
            background: rgba(255,255,255,0.1);
            border-radius: 2px 0 0 2px;
        }
        .inv-phone-btnR {
            position: absolute;
            right: -3px; top: 100px;
            width: 3px; height: 36px;
            background: rgba(255,255,255,0.1);
            border-radius: 0 2px 2px 0;
        }

        .inv-phone-screen {
            width: 100%;
            height: 100%;
            border-radius: 26px;
            overflow: hidden;
            position: relative;
            background: #1a1825;
        }

        .inv-phone-screen iframe {
            width: 390px;
            height: calc(390px * 19 / 9);
            border: none;
            transform-origin: top left;
            display: block;
            pointer-events: none;
        }
        .inv-statusbar {
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 28px;
            background: linear-gradient(to bottom, rgba(0,0,0,0.45) 0%, transparent 100%);
            z-index: 2;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 6px 14px 0;
            pointer-events: none;
        }

        .inv-status-time {
            font-size: 0.6rem;
            font-weight: 700;
            color: rgba(255,255,255,0.9);
        }

        .inv-status-icons { display: flex; gap: 4px; align-items: center; }

        .inv-phone-home {
            position: absolute;
            bottom: 6px; left: 50%;
            transform: translateX(-50%);
            width: 80px; height: 4px;
            background: rgba(255,255,255,0.28);
            border-radius: 2px;
            z-index: 3;
        }

        /* ══════════════════════
           MOBILE — full screen
        ══════════════════════ */
        @media (max-width: 900px) {
            html, body { overflow: auto; height: auto; }

            .inv-layout { height: auto; min-height: 100vh; }

            .inv-panel-left    { display: none; }
            .inv-panel-divider { display: none; }

            .inv-panel-right {
                width: 100vw;
                min-width: 0;
                max-width: none;
                padding: 0;
                margin: 0;
                background: transparent;
                align-items: stretch;
            }
            .inv-panel-right::before { display: none; }

            .inv-phone {
                width: 100vw;
                height: 100vh;
                background: transparent;
                border-radius: 0;
                padding: 0;
                box-shadow: none;
                max-width: none;
            }

            .inv-phone-notch,
            .inv-phone-btnL, .inv-phone-btnL2, .inv-phone-btnR,
            .inv-statusbar,
            .inv-phone-home { display: none; }

            .inv-phone-screen {
                border-radius: 0;
                width: 100vw;
                height: 100vh;
                background: transparent;
            }

            .inv-phone-screen iframe {
                width: 100vw !important;
                height: 100vh !important;
                transform: none !important;
                pointer-events: auto;
                display: block;
            }
        }
    </style>
</head>
<body>

    <div class="inv-layout">

        <!-- ── PANEL KIRI: Info Undangan ── -->
        <div class="inv-panel-left">
            <div class="inv-left-content">

                <!-- Brand -->
                <a href="/" class="inv-brand">
                    <span class="inv-brand-logo">Nikahin</span>
                    <span class="inv-brand-sep"></span>
                    <span class="inv-brand-label">Undangan Digital</span>
                </a>

                <!-- Kepada tamu -->
                @php $guestName = request('to', null); @endphp
                @if($guestName)
                <div class="inv-kepada">Kepada Yth.</div>
                <div class="inv-guest-name">{{ $guestName }}</div>
                @else
                <div class="inv-kepada">Anda diundang untuk hadir di</div>
                @endif

                <!-- Nama mempelai -->
                <div class="inv-join-label">The Wedding of</div>
                <div class="inv-names">
                    <em>{{ $invitation->groom_name }}</em>
                    <span class="inv-names-amp">&</span>
                    <em>{{ $invitation->bride_name }}</em>
                </div>

                <div class="inv-divider"></div>

                <!-- Info kartu -->
                <div class="inv-info-cards">
                    <!-- Tanggal resepsi -->
                    <div class="inv-info-card">
                        <div class="inv-info-icon">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        </div>
                        <div class="inv-info-text">
                            <div class="inv-info-label">Resepsi</div>
                            <div class="inv-info-value">
                                {{ \Carbon\Carbon::parse($invitation->reception_date)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                                &bull; {{ $invitation->reception_time_start }}
                                @if($invitation->reception_time_end) – {{ $invitation->reception_time_end }} @else WIB @endif
                            </div>
                        </div>
                    </div>

                    <!-- Lokasi -->
                    <div class="inv-info-card">
                        <div class="inv-info-icon">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s-8-4.5-8-11.8A8 8 0 0 1 12 2a8 8 0 0 1 8 8.2c0 7.3-8 11.8-8 11.8z"/><circle cx="12" cy="10" r="3"/></svg>
                        </div>
                        <div class="inv-info-text">
                            <div class="inv-info-label">Lokasi</div>
                            <div class="inv-info-value">
                                {{ $invitation->reception_location }}
                                @if($invitation->full_address)
                                <br><span style="font-size:0.78rem;opacity:.6;">{{ Str::limit($invitation->full_address, 55) }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Countdown -->
                <div class="inv-countdown" id="invCountdown">
                    <div class="inv-count-box"><span class="inv-count-num" id="iDays">--</span><span class="inv-count-lbl">Hari</span></div>
                    <div class="inv-count-box"><span class="inv-count-num" id="iHours">--</span><span class="inv-count-lbl">Jam</span></div>
                    <div class="inv-count-box"><span class="inv-count-num" id="iMins">--</span><span class="inv-count-lbl">Menit</span></div>
                    <div class="inv-count-box"><span class="inv-count-num" id="iSecs">--</span><span class="inv-count-lbl">Detik</span></div>
                </div>

                <!-- Aksi -->
                <div class="inv-actions">
                    @if($invitation->google_maps_url)
                    <a href="{{ $invitation->google_maps_url }}" target="_blank" rel="noopener" class="inv-btn inv-btn-primary">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 22s-8-4.5-8-11.8A8 8 0 0 1 12 2a8 8 0 0 1 8 8.2c0 7.3-8 11.8-8 11.8z"/><circle cx="12" cy="10" r="3"/></svg>
                        Buka Peta
                    </a>
                    @endif
                    <button class="inv-btn inv-btn-ghost" onclick="shareInvitation()" title="Bagikan undangan">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
                        Bagikan
                    </button>
                    <button class="inv-btn inv-btn-ghost" onclick="addToCalendar()" title="Simpan ke kalender">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><line x1="12" y1="14" x2="12" y2="18"/><line x1="10" y1="16" x2="14" y2="16"/></svg>
                        Kalender
                    </button>
                </div>

                <!-- Footer brand -->
                <div class="inv-footer-brand">
                    Dibuat dengan <a href="/">Nikahin</a> &mdash; Platform Undangan Digital
                </div>

            </div>
        </div>

        <!-- Divider -->
        <div class="inv-panel-divider"></div>

        <!-- ── PANEL KANAN: Phone Mockup ── -->
        <div class="inv-panel-right">
            <div class="inv-phone">
                <div class="inv-phone-notch"></div>
                <span class="inv-phone-btnL"></span>
                <span class="inv-phone-btnL2"></span>
                <span class="inv-phone-btnR"></span>

                <div class="inv-phone-screen" id="invPhoneScreen">
                    <div class="inv-statusbar">
                        <span class="inv-status-time" id="invStatusTime">9:41</span>
                        <div class="inv-status-icons">
                            <svg width="14" height="10" viewBox="0 0 24 16" fill="white"><rect x="0" y="11" width="4" height="5" rx="1"/><rect x="5" y="7" width="4" height="9" rx="1"/><rect x="10" y="4" width="4" height="12" rx="1"/><rect x="15" y="1" width="4" height="15" rx="1"/></svg>
                            <svg width="18" height="10" viewBox="0 0 28 14" fill="none" stroke="white" stroke-width="1.5"><rect x="1" y="2" width="22" height="10" rx="2"/><path d="M24 5v4"/><rect x="3" y="4" width="14" height="6" rx="1" fill="white" stroke="none"/></svg>
                        </div>
                    </div>

                    {{-- iframe: load undangan mentah via ?raw=1, preserves semua CSS/JS template --}}
                    <iframe
                        id="invIframe"
                        src="{{ url()->current() }}?raw=1{{ request('to') ? '&to='.urlencode(request('to')) : '' }}"
                        title="Undangan {{ $invitation->groom_name }} & {{ $invitation->bride_name }}"
                        loading="lazy"
                        sandbox="allow-scripts allow-same-origin allow-forms"
                    ></iframe>

                    <div class="inv-phone-home"></div>
                </div>
            </div>
        </div>

    </div>

    <script>
    // ── Scale iframe to fit phone screen ─────────────────────────────────────
    function scaleInvitation() {
        const screen = document.getElementById('invPhoneScreen');
        const iframe = document.getElementById('invIframe');
        if (!screen || !iframe) return;

        // Di mobile: biarkan CSS yang handle, jangan override
        if (window.innerWidth <= 900) {
            iframe.style.width     = '';
            iframe.style.height    = '';
            iframe.style.transform = '';
            return;
        }

        // screen width = actual rendered width of .inv-phone-screen
        const sw    = screen.offsetWidth;
        const iw    = 390; // design width of template
        const scale = sw / iw;

        iframe.style.width           = iw + 'px';
        iframe.style.height          = Math.ceil(iw * 19 / 9) + 'px';
        iframe.style.transform       = 'scale(' + scale + ')';
        iframe.style.transformOrigin = 'top left';
        iframe.style.pointerEvents   = 'none';
        iframe.style.display         = 'block';
    }

    // Run after layout is complete
    requestAnimationFrame(() => { scaleInvitation(); });
    window.addEventListener('resize', scaleInvitation);

    // ── Enable scroll inside phone on hover ──────────────────────────────────
    const phoneScreen = document.getElementById('invPhoneScreen');
    if (phoneScreen && window.innerWidth > 900) {
        const iframe = document.getElementById('invIframe');
        phoneScreen.addEventListener('mouseenter', () => {
            if (iframe) iframe.style.pointerEvents = 'auto';
        });
        phoneScreen.addEventListener('mouseleave', () => {
            if (iframe) iframe.style.pointerEvents = 'none';
        });
    }

    // ── Live clock ────────────────────────────────────────────────────────────
    function updateClock() {
        const el = document.getElementById('invStatusTime');
        if (!el) return;
        const now = new Date();
        el.textContent = now.getHours() + ':' + String(now.getMinutes()).padStart(2, '0');
    }
    updateClock();
    setInterval(updateClock, 10000);

    // ── Countdown ─────────────────────────────────────────────────────────────
    (function startCountdown() {
        const target = new Date("{{ \Carbon\Carbon::parse($invitation->reception_date)->format('Y-m-d') }} {{ $invitation->reception_time_start }}:00").getTime();
        const ids = ['iDays','iHours','iMins','iSecs'];
        function tick() {
            const diff = target - Date.now();
            const set = (id, val) => {
                const el = document.getElementById(id);
                if (el) el.textContent = String(Math.max(0, val)).padStart(2, '0');
            };
            if (diff <= 0) {
                ids.forEach(id => { const el = document.getElementById(id); if (el) el.textContent = '00'; });
            } else {
                set('iDays',  Math.floor(diff / 86400000));
                set('iHours', Math.floor((diff % 86400000) / 3600000));
                set('iMins',  Math.floor((diff % 3600000) / 60000));
                set('iSecs',  Math.floor((diff % 60000) / 1000));
            }
        }
        tick();
        setInterval(tick, 1000);
    })();

    // ── Share ─────────────────────────────────────────────────────────────────
    function shareInvitation() {
        const url  = window.location.href;
        const text = 'Undangan Pernikahan {{ $invitation->groom_name }} & {{ $invitation->bride_name }}';
        if (navigator.share) {
            navigator.share({ title: text, url: url }).catch(() => {});
        } else {
            navigator.clipboard.writeText(url).then(() => {
                alert('Link undangan berhasil disalin!');
            }).catch(() => {
                prompt('Salin link undangan ini:', url);
            });
        }
    }

    // ── Add to Calendar ───────────────────────────────────────────────────────
    function addToCalendar() {
        @php
            $start = \Carbon\Carbon::parse($invitation->reception_date->format('Y-m-d') . ' ' . $invitation->reception_time_start)->format('Ymd\THis');
            $end   = $invitation->reception_time_end
                ? \Carbon\Carbon::parse($invitation->reception_date->format('Y-m-d') . ' ' . $invitation->reception_time_end)->format('Ymd\THis')
                : \Carbon\Carbon::parse($invitation->reception_date->format('Y-m-d') . ' ' . $invitation->reception_time_start)->addHours(3)->format('Ymd\THis');
        @endphp
        const gcal = 'https://calendar.google.com/calendar/render?action=TEMPLATE'
            + '&text=' + encodeURIComponent('Pernikahan {{ $invitation->groom_name }} & {{ $invitation->bride_name }}')
            + '&dates={{ $start }}/{{ $end }}'
            + '&details=' + encodeURIComponent('Undangan pernikahan {{ $invitation->groom_name }} & {{ $invitation->bride_name }}')
            + '&location=' + encodeURIComponent('{{ addslashes($invitation->reception_location) }}');
        window.open(gcal, '_blank');
    }
    </script>

</body>
</html>
