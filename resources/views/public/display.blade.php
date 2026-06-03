<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Layar Gerbang — {{ $invitation->bride_name }} & {{ $invitation->groom_name }}</title>
<meta name="theme-color" content="#05030a">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500&family=Inter:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
/* ═══════════════════════════════════════════════════════════════
   RESET & TOKENS
═══════════════════════════════════════════════════════════════ */
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html,body{width:100%;height:100%;overflow:hidden;font-family:'Inter',sans-serif;background:#05030a;color:#fff}

:root{
  --gold:#e8c547;--gold-l:#f5e6a3;--gold-d:#b8941f;--gold-glow:rgba(232,197,71,0.12);
  --rose:#d4a5a5;--rose-d:#a87a7a;
  --deep:#05030a;--deep2:#0a0712;--deep3:#120e1e;--deep4:#1a1528;
  --surface:rgba(255,255,255,0.03);--surface-hover:rgba(255,255,255,0.06);
  --border:rgba(255,255,255,0.06);--border-gold:rgba(232,197,71,0.12);
  --text-primary:#fff;--text-secondary:rgba(255,255,255,0.5);--text-muted:rgba(255,255,255,0.25);
  --glass:rgba(10,7,18,0.7);--glass-border:rgba(255,255,255,0.08);
  --radius-sm:12px;--radius-md:20px;--radius-lg:28px;--radius-xl:40px;
  --shadow-sm:0 4px 12px rgba(0,0,0,0.3);--shadow-md:0 8px 32px rgba(0,0,0,0.4);
  --shadow-lg:0 24px 80px rgba(0,0,0,0.5);--shadow-gold:0 0 60px rgba(232,197,71,0.06);
}

/* ═══════════════════════════════════════════════════════════════
   CANVAS & BACKGROUND
═══════════════════════════════════════════════════════════════ */
#canvas{position:fixed;inset:0;z-index:0;pointer-events:none}

.bg-orb{
  position:fixed;border-radius:50%;filter:blur(120px);pointer-events:none;z-index:0;
  animation:orbFloat 12s ease-in-out infinite;
}
.bg-orb-1{
  width:700px;height:700px;background:radial-gradient(circle,var(--gold-glow),transparent 70%);
  opacity:.35;top:-250px;right:-200px;animation-delay:0s;
}
.bg-orb-2{
  width:600px;height:600px;background:radial-gradient(circle,rgba(212,165,165,0.1),transparent 70%);
  opacity:.4;bottom:-200px;left:-200px;animation-delay:-5s;
}
.bg-orb-3{
  width:400px;height:400px;background:radial-gradient(circle,rgba(139,92,246,0.06),transparent 70%);
  opacity:.5;top:40%;left:10%;animation-delay:-8s;
}
.bg-orb-4{
  width:300px;height:300px;background:radial-gradient(circle,rgba(59,130,246,0.05),transparent 70%);
  opacity:.4;bottom:25%;right:15%;animation-delay:-3s;
}
@keyframes orbFloat{
  0%,100%{transform:translateY(0) scale(1)}
  33%{transform:translateY(-30px) scale(1.05)}
  66%{transform:translateY(15px) scale(0.98)}
}

.noise-overlay{
  position:fixed;inset:0;z-index:1;pointer-events:none;opacity:.02;
  background-image:url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
  background-size:256px;
}

/* ═══════════════════════════════════════════════════════════════
   IDLE SCREEN
═══════════════════════════════════════════════════════════════ */
#idle{
  position:fixed;inset:0;z-index:10;
  display:flex;flex-direction:column;align-items:center;justify-content:center;
  gap:0;
}

/* ── Status bar ── */
.status-bar{
  position:fixed;top:0;left:0;right:0;z-index:20;
  display:flex;align-items:center;justify-content:space-between;
  padding:20px 32px;
  background:linear-gradient(to bottom,rgba(5,3,10,0.8),transparent);
  pointer-events:none;
}
.status-left,.status-right{display:flex;align-items:center;gap:12px}
.status-dot{
  width:6px;height:6px;border-radius:50%;background:#22c55e;
  box-shadow:0 0 8px #22c55e;animation:statusPulse 2s ease infinite;
}
@keyframes statusPulse{0%,100%{opacity:1}50%{opacity:.5}}
.status-text{font-size:11px;font-weight:500;letter-spacing:2px;text-transform:uppercase;color:var(--text-muted)}
.status-time{font-family:'Space Grotesk',monospace;font-size:13px;font-weight:500;color:var(--text-secondary);letter-spacing:1px}

/* ── Header ornament ── */
.idle-header{
  display:flex;align-items:center;gap:20px;margin-bottom:40px;
  opacity:0;animation:fadeIn 1.2s ease .2s forwards;
}
.idle-header-line{
  width:60px;height:1px;
  background:linear-gradient(to right,transparent,var(--gold-d));
}
.idle-header-line.r{background:linear-gradient(to left,transparent,var(--gold-d))}
.idle-header-icon{
  width:32px;height:32px;border:1px solid var(--border-gold);border-radius:50%;
  display:flex;align-items:center;justify-content:center;
  font-size:14px;color:var(--gold);animation:gentleSpin 8s linear infinite;
}
@keyframes gentleSpin{from{transform:rotate(0deg)}to{transform:rotate(360deg)}}

/* ── Couple names ── */
.idle-couple{
  font-family:'Playfair Display',serif;
  font-size:clamp(48px,8vw,120px);
  font-weight:700;
  line-height:1.05;
  text-align:center;
  letter-spacing:-2px;
  opacity:0;animation:fadeUp 1.2s cubic-bezier(.22,1,.36,1) .4s forwards;
}
.idle-couple .name-1{
  display:block;
  background:linear-gradient(135deg,#fff 0%,var(--gold-l) 50%,var(--gold) 100%);
  -webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;
}
.idle-couple .amp{
  display:block;font-style:italic;font-weight:400;
  font-size:clamp(28px,4vw,56px);color:var(--gold);
  margin:.15em 0;opacity:.8;
  animation:ampGlow 3s ease-in-out infinite;
}
@keyframes ampGlow{0%,100%{text-shadow:0 0 20px rgba(232,197,71,0.1)}50%{text-shadow:0 0 40px rgba(232,197,71,0.3)}}
.idle-couple .name-2{display:block;color:var(--text-secondary);font-weight:500}

/* ── Subtitle ── */
.idle-subtitle-wrap{
  display:flex;align-items:center;gap:16px;margin-top:24px;
  opacity:0;animation:fadeIn 1s ease .8s forwards;
}
.idle-subtitle{
  font-size:clamp(11px,1.2vw,14px);
  font-weight:600;letter-spacing:6px;text-transform:uppercase;
  color:var(--text-muted);
}
.idle-subtitle-badge{
  padding:4px 12px;border-radius:100px;
  background:var(--surface);border:1px solid var(--border);
  font-size:10px;font-weight:600;letter-spacing:2px;text-transform:uppercase;
  color:var(--gold);backdrop-filter:blur(10px);
}

/* ── Date card ── */
.idle-date-card{
  display:flex;align-items:center;gap:16px;
  margin-top:32px;
  padding:14px 28px;
  border-radius:var(--radius-xl);
  background:var(--glass);border:1px solid var(--glass-border);
  backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);
  box-shadow:var(--shadow-sm),inset 0 1px 0 rgba(255,255,255,.05);
  opacity:0;animation:fadeUp 1s cubic-bezier(.22,1,.36,1) 1s forwards;
  transition:transform .3s ease,box-shadow .3s ease;
}
.idle-date-card:hover{transform:translateY(-2px);box-shadow:var(--shadow-md),var(--shadow-gold)}
.idle-date-icon{
  width:40px;height:40px;border-radius:var(--radius-sm);
  background:linear-gradient(135deg,var(--gold-glow),transparent);
  border:1px solid var(--border-gold);
  display:flex;align-items:center;justify-content:center;
  color:var(--gold);flex-shrink:0;
}
.idle-date-icon svg{width:18px;height:18px}
.idle-date-content{text-align:left}
.idle-date-day{font-size:clamp(14px,1.4vw,18px);font-weight:600;color:var(--text-primary);letter-spacing:.3px}
.idle-date-time{font-size:clamp(12px,1.1vw,14px);color:var(--text-secondary);margin-top:2px;font-weight:500}
.idle-date-sep{width:1px;height:32px;background:var(--border);margin:0 4px}

/* ── Ornament ── */
.idle-ornament{
  margin-top:40px;
  display:flex;align-items:center;gap:16px;
  opacity:0;animation:fadeIn 1s ease 1.2s forwards;
}
.idle-ornament-line{flex:1;height:1px;max-width:80px;background:linear-gradient(to right,transparent,var(--border-gold))}
.idle-ornament-line.r{background:linear-gradient(to left,transparent,var(--border-gold))}
.idle-ornament-diamond{
  width:8px;height:8px;background:var(--gold);transform:rotate(45deg);opacity:.6;
  animation:diamondPulse 2s ease-in-out infinite;
}
@keyframes diamondPulse{0%,100%{opacity:.4;transform:rotate(45deg) scale(1)}50%{opacity:.8;transform:rotate(45deg) scale(1.2)}}

@keyframes scanFloat{
  0%,100%{opacity:.6;transform:translateX(-50%) translateY(0)}
  50%{opacity:1;transform:translateX(-50%) translateY(-6px)}
}
.idle-scan svg{width:16px;height:16px;color:var(--gold)}

/* ── Counter ── */
.idle-counter{
  position:fixed;
  bottom:28px;
  left:0;
  right:0;
  display:flex;
  justify-content:center;
  pointer-events:none;
  opacity:0;
  animation:fadeUp 1s cubic-bezier(.22,1,.36,1) 1.4s forwards;
}
.idle-counter-card{
  display:flex;
  align-items:center;
  gap:8px;
  padding:16px 36px;
  border-radius:var(--radius-xl);
  background:var(--glass);
  border:1px solid var(--glass-border);
  backdrop-filter:blur(24px);
  -webkit-backdrop-filter:blur(24px);
  box-shadow:var(--shadow-md),0 0 40px rgba(0,0,0,.2);
  pointer-events:auto;
}
.counter-item{display:flex;align-items:center;gap:10px;padding:0 8px}
.counter-num{
  font-family:'Space Grotesk',monospace;
  font-size:clamp(22px,2.5vw,32px);font-weight:700;color:var(--gold);
  min-width:2.5ch;text-align:center;letter-spacing:-1px;
  transition:all .5s cubic-bezier(.34,1.56,.64,1);
  text-shadow:0 0 20px rgba(232,197,71,.2);
}
.counter-label{font-size:clamp(11px,1.1vw,13px);color:var(--text-muted);font-weight:500;letter-spacing:.5px}
.counter-sep{width:1px;height:36px;background:var(--border);border-radius:1px}
.counter-trend{
  display:flex;align-items:center;gap:4px;
  padding:3px 8px;border-radius:6px;
  background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.15);
  font-size:10px;font-weight:600;color:#22c55e;letter-spacing:.5px;
}

/* ═══════════════════════════════════════════════════════════════
   POPUP OVERLAY
═══════════════════════════════════════════════════════════════ */
#popup{
  position:fixed;inset:0;z-index:100;
  display:flex;align-items:center;justify-content:center;
  background:rgba(5,3,10,0);
  pointer-events:none;
  transition:background .7s cubic-bezier(.22,1,.36,1);
}
#popup.active{
  background:rgba(5,3,10,.88);
  backdrop-filter:blur(30px) saturate(1.2);
  -webkit-backdrop-filter:blur(30px) saturate(1.2);
  pointer-events:auto;
}

.popup-card{
  position:relative;
  width:min(520px,88vw);
  background:linear-gradient(180deg,rgba(14,10,24,0.95) 0%,rgba(10,7,18,0.98) 100%);
  border-radius:var(--radius-lg);
  padding:clamp(40px,5vw,60px) clamp(32px,4vw,48px) clamp(32px,4vw,48px);
  text-align:center;
  overflow:hidden;
  border:1px solid var(--glass-border);
  box-shadow:
    0 48px 100px rgba(0,0,0,.6),
    0 0 80px rgba(232,197,71,.04),
    inset 0 1px 0 rgba(255,255,255,.04);
  opacity:0;
  transform:translateY(40px) scale(.96) rotateX(5deg);
  transition:opacity .6s cubic-bezier(.22,1,.36,1), transform .6s cubic-bezier(.22,1,.36,1);
  perspective:1000px;
}
#popup.active .popup-card{
  opacity:1;
  transform:translateY(0) scale(1) rotateX(0deg);
}

.popup-glow{
  position:absolute;top:-40%;left:50%;transform:translateX(-50%);
  width:80%;height:100%;
  background:radial-gradient(ellipse at center,rgba(232,197,71,.06) 0%,transparent 60%);
  pointer-events:none;z-index:0;
}

.popup-ornament{
  display:flex;align-items:center;gap:14px;justify-content:center;
  margin-bottom:clamp(24px,3vw,36px);position:relative;z-index:1;
}
.popup-ornament-line{
  width:clamp(40px,6vw,80px);height:1px;
  background:linear-gradient(to right,transparent,rgba(232,197,71,.4));
}
.popup-ornament-line.r{background:linear-gradient(to left,transparent,rgba(232,197,71,.4))}
.popup-ornament-shape{
  width:10px;height:10px;position:relative;
}
.popup-ornament-shape::before,.popup-ornament-shape::after{
  content:'';position:absolute;inset:0;
  border:1px solid var(--gold);opacity:.5;
  transform:rotate(45deg);
}
.popup-ornament-shape::after{transform:rotate(45deg) scale(.6);opacity:.3}

.popup-welcome{
  position:relative;z-index:1;
  font-size:clamp(9px,.9vw,11px);font-weight:600;
  letter-spacing:5px;text-transform:uppercase;
  color:var(--gold);opacity:.7;
  margin-bottom:clamp(8px,1vw,12px);
}

.popup-name{
  position:relative;z-index:1;
  font-family:'Playfair Display',serif;
  font-size:clamp(36px,6vw,72px);font-weight:700;
  line-height:1.05;color:#fff;
  margin-bottom:clamp(8px,1vw,12px);
  word-break:break-word;letter-spacing:-1px;
  background:linear-gradient(180deg,#fff 0%,rgba(255,255,255,.85) 100%);
  -webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;
}

.popup-category{
  position:relative;z-index:1;
  display:inline-flex;align-items:center;gap:6px;
  padding:6px 18px;border-radius:100px;
  background:var(--surface);border:1px solid var(--border-gold);
  font-size:clamp(10px,.9vw,12px);font-weight:600;
  letter-spacing:2px;text-transform:uppercase;
  color:var(--gold);margin-bottom:clamp(24px,3vw,36px);
  backdrop-filter:blur(10px);
}
.popup-category::before{
  content:'';width:5px;height:5px;border-radius:50%;background:var(--gold);
  box-shadow:0 0 8px var(--gold);animation:categoryDot 2s ease infinite;
}
@keyframes categoryDot{0%,100%{opacity:1}50%{opacity:.4}}

.popup-divider{
  position:relative;z-index:1;
  display:flex;align-items:center;gap:16px;
  margin-bottom:clamp(20px,2.5vw,32px);
}
.popup-divider-line{flex:1;height:1px;background:linear-gradient(to right,transparent,rgba(255,255,255,.08),transparent)}
.popup-divider-icon{
  font-size:clamp(18px,2vw,24px);opacity:.5;
  animation:ringBob 2s ease-in-out infinite;
}
@keyframes ringBob{0%,100%{transform:translateY(0)}50%{transform:translateY(-4px)}}

.popup-couple{
  position:relative;z-index:1;
  font-family:'Playfair Display',serif;
  font-size:clamp(18px,2.5vw,28px);font-weight:400;font-style:italic;
  color:var(--text-secondary);margin-bottom:clamp(20px,2.5vw,32px);
  letter-spacing:.5px;
}
.popup-couple .amp{color:var(--gold);margin:0 8px;font-style:normal;opacity:.6}

.popup-time{
  position:relative;z-index:1;
  display:inline-flex;align-items:center;gap:8px;
  padding:10px 22px;border-radius:var(--radius-xl);
  background:var(--surface);border:1px solid var(--border);
  font-size:clamp(12px,1.2vw,14px);font-weight:500;
  color:var(--text-secondary);letter-spacing:.5px;
  font-family:'Space Grotesk',monospace;
}
.popup-time svg{width:14px;height:14px;opacity:.5}

.popup-progress{
  position:absolute;bottom:0;left:0;right:0;height:3px;
  background:rgba(255,255,255,.03);overflow:hidden;
}
.popup-progress-bar{
  height:100%;
  background:linear-gradient(90deg,transparent,var(--gold),var(--gold-l),var(--gold),transparent);
  background-size:200% 100%;
  width:100%;transform-origin:left;transform:scaleX(1);
  transition:none;animation:shimmer 2s linear infinite;
}
@keyframes shimmer{0%{background-position:200% 0}100%{background-position:-200% 0}}

.popup-hint{
  position:absolute;bottom:-36px;left:50%;transform:translateX(-50%);
  font-size:11px;color:var(--text-muted);letter-spacing:1px;
  opacity:0;transition:opacity .3s ease;white-space:nowrap;
}
#popup.active .popup-hint{opacity:1;transition-delay:.5s}

/* ═══════════════════════════════════════════════════════════════
   CONFETTI
═══════════════════════════════════════════════════════════════ */
#confetti{position:fixed;inset:0;pointer-events:none;z-index:200;overflow:hidden}
.cp{
  position:absolute;top:-20px;
  animation:cpFall var(--d) cubic-bezier(.25,.46,.45,.94) var(--dl) forwards;
  opacity:0;will-change:transform,opacity;
}
@keyframes cpFall{
  0%{opacity:1;transform:translateY(0) rotate(0deg) scale(1)}
  70%{opacity:1}
  100%{opacity:0;transform:translateY(110vh) rotate(var(--rot)) scale(.3)}
}

/* ═══════════════════════════════════════════════════════════════
   KEYFRAMES
═══════════════════════════════════════════════════════════════ */
@keyframes fadeIn{from{opacity:0}to{opacity:1}}
@keyframes fadeUp{from{opacity:0;transform:translateY(30px)}to{opacity:1;transform:translateY(0)}}

/* ═══════════════════════════════════════════════════════════════
   RESPONSIVE
═══════════════════════════════════════════════════════════════ */
@media(max-width:640px){
  .status-bar{padding:16px 20px}
  .idle-counter{padding:12px 24px;gap:4px}
  .counter-item{gap:6px}
  .idle-scan{bottom:100px}
  .popup-card{padding:32px 24px 28px}
}
</style>
<base target="_blank">
</head>
<body>

<canvas id="canvas"></canvas>
<div class="bg-orb bg-orb-1"></div>
<div class="bg-orb bg-orb-2"></div>
<div class="bg-orb bg-orb-3"></div>
<div class="bg-orb bg-orb-4"></div>
<div class="noise-overlay"></div>
<div id="confetti"></div>

<!-- Status Bar -->
<div class="status-bar">
  <div class="status-left">
    <div class="status-dot"></div>
    <span class="status-text">Live Display</span>
  </div>
  <div class="status-right">
    <span class="status-time" id="clock">00:00</span>
  </div>
</div>

{{-- ══ IDLE SCREEN ══ --}}
<div id="idle">
  <div class="idle-header">
    <div class="idle-header-line"></div>
    <div class="idle-header-icon">✦</div>
    <div class="idle-header-line r"></div>
  </div>

  <h1 class="idle-couple">
    <span class="name-1">{{ $invitation->bride_name }}</span>
    <span class="amp">&amp;</span>
    <span class="name-2">{{ $invitation->groom_name }}</span>
  </h1>

  <div class="idle-subtitle-wrap">
    <span class="idle-subtitle">Undangan Pernikahan</span>
    <span class="idle-subtitle-badge">Exclusive</span>
  </div>

  <div class="idle-date-card">
    <div class="idle-date-icon">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
        <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/>
        <line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
      </svg>
    </div>
    <div class="idle-date-content">
      <div class="idle-date-day">{{ \Carbon\Carbon::parse($invitation->reception_date)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</div>
      <div class="idle-date-time">{{ \Carbon\Carbon::parse($invitation->reception_time_start)->format('H:i') }}@if($invitation->reception_time_end) – {{ \Carbon\Carbon::parse($invitation->reception_time_end)->format('H:i') }} WIB @else WIB @endif</div>
    </div>
  </div>

  <div class="idle-ornament">
    <div class="idle-ornament-line"></div>
    <div class="idle-ornament-diamond"></div>
    <div class="idle-ornament-diamond"></div>
    <div class="idle-ornament-diamond"></div>
    <div class="idle-ornament-line r"></div>
  </div>

  <div class="idle-counter">
    <div class="idle-counter-card">
      <div class="counter-item">
        <span class="counter-num" id="cnt-checkin">{{ $checkedInCount }}</span>
        <span class="counter-label">Tamu Hadir</span>
      </div>
    </div>
  </div>
</div>

{{-- ══ POPUP ══ --}}
<div id="popup">
  <div class="popup-card">
    <div class="popup-glow"></div>

    <div class="popup-ornament">
      <div class="popup-ornament-line"></div>
      <div class="popup-ornament-shape"></div>
      <div class="popup-ornament-line r"></div>
    </div>

    <p class="popup-welcome">Selamat Datang</p>
    <h2 class="popup-name" id="popup-name">Nama Tamu</h2>
    <span class="popup-category" id="popup-category">Keluarga</span>

    <div class="popup-divider">
      <div class="popup-divider-line"></div>
      <span class="popup-divider-icon">💍</span>
      <div class="popup-divider-line"></div>
    </div>

    <p class="popup-couple">
      {{ $invitation->bride_name }}<span class="amp">&amp;</span>{{ $invitation->groom_name }}
    </p>

    <div class="popup-time">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
      </svg>
      <span id="popup-time">Check-in 00:00</span>
    </div>

    <div class="popup-progress"><div class="popup-progress-bar" id="popup-bar"></div></div>
    <div class="popup-hint">Klik dimana saja untuk menutup</div>
  </div>
</div>

<script>
// ══════════════════════════════════════════════════════════════
//  CONFIG
// ══════════════════════════════════════════════════════════════
const UNIQUE_URL     = @json($invitation->unique_url);
const STREAM_URL     = `/display/${UNIQUE_URL}/stream`;
const FALLBACK_URL   = `/api/display/${UNIQUE_URL}/latest-checkin`;
const POPUP_DURATION = 8000;

// ══════════════════════════════════════════════════════════════
//  PARTICLE CANVAS
// ══════════════════════════════════════════════════════════════
(function(){
  const cv = document.getElementById('canvas');
  const cx = cv.getContext('2d');
  let W, H;
  const resize = () => { W = cv.width = innerWidth; H = cv.height = innerHeight; };
  addEventListener('resize', resize); resize();
  const rand = (a,b) => a + Math.random() * (b - a);

  class Particle {
    constructor() { this.reset(); }
    reset() {
      this.x = rand(0, W); this.y = rand(0, H);
      this.r = rand(.3, 2.2);
      this.vx = rand(-.12, .12); this.vy = rand(-.3, -.06);
      this.a = rand(.02, .45); this.da = rand(-.002, .002);
      this.hue = rand(40, 55);
    }
    tick() {
      this.x += this.vx; this.y += this.vy; this.a += this.da;
      if (this.a <= .02 || this.a >= .5) this.da *= -1;
      if (this.y < -5) this.reset();
    }
    draw() {
      cx.beginPath(); cx.arc(this.x, this.y, this.r, 0, Math.PI * 2);
      cx.fillStyle = `hsla(${this.hue}, 70%, 60%, ${this.a})`; cx.fill();
      if (this.r > 1.5) {
        cx.beginPath(); cx.arc(this.x, this.y, this.r * 3, 0, Math.PI * 2);
        cx.fillStyle = `hsla(${this.hue}, 70%, 60%, ${this.a * .15})`; cx.fill();
      }
    }
  }

  const particles = Array.from({length: 150}, () => new Particle());
  const loop = () => {
    cx.clearRect(0, 0, W, H);
    particles.forEach(p => { p.tick(); p.draw(); });
    requestAnimationFrame(loop);
  };
  loop();
})();

// ══════════════════════════════════════════════════════════════
//  CONFETTI
// ══════════════════════════════════════════════════════════════
function spawnConfetti() {
  const wrap = document.getElementById('confetti');
  wrap.innerHTML = '';
  const colors = ['#e8c547','#f5e6a3','#d4a5a5','#a78bfa','#ffffff','#fbbf24','#34d399','#60a5fa'];
  const shapes = ['circle','square','diamond','line'];

  for (let i = 0; i < 80; i++) {
    const el = document.createElement('div');
    el.className = 'cp';
    const size = 3 + Math.random() * 7;
    const shape = shapes[Math.floor(Math.random() * shapes.length)];
    const color = colors[Math.floor(Math.random() * colors.length)];
    const rotation = Math.random() * 720;

    let borderRadius, width, height;
    switch(shape) {
      case 'circle': borderRadius = '50%'; width = size; height = size; break;
      case 'square': borderRadius = '2px'; width = size; height = size; break;
      case 'diamond': borderRadius = '2px'; width = size; height = size; break;
      case 'line': borderRadius = '2px'; width = size * .3; height = size * 2.5; break;
    }

    el.style.cssText = `
      left:${Math.random() * 100}%;
      width:${width}px; height:${height}px;
      background:${color};
      border-radius:${borderRadius};
      --d:${2.5 + Math.random() * 2.5}s;
      --dl:${Math.random() * 1.2}s;
      --rot:${rotation}deg;
      ${shape === 'diamond' ? 'transform:rotate(45deg);' : ''}
    `;
    wrap.appendChild(el);
  }
  setTimeout(() => { wrap.innerHTML = ''; }, 5000);
}

// ══════════════════════════════════════════════════════════════
//  POPUP LOGIC
// ══════════════════════════════════════════════════════════════
const popup = document.getElementById('popup');
const popupBar = document.getElementById('popup-bar');
let dismissTimer = null;

function formatTime(iso) {
  const d = new Date(iso);
  return d.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
}

function showPopup(guest) {
  document.getElementById('popup-name').textContent = guest.name;
  document.getElementById('popup-category').textContent = guest.category_label;
  document.getElementById('popup-time').textContent = 'Check-in ' + formatTime(guest.timestamp || guest.checked_in_at);

  clearTimeout(dismissTimer);
  popup.classList.add('active');
  spawnConfetti();

  popupBar.style.transition = 'none';
  popupBar.style.transform = 'scaleX(1)';
  requestAnimationFrame(() => requestAnimationFrame(() => {
    popupBar.style.transition = `transform ${POPUP_DURATION}ms linear`;
    popupBar.style.transform = 'scaleX(0)';
  }));

  dismissTimer = setTimeout(hidePopup, POPUP_DURATION);
}

function hidePopup() {
  popup.classList.remove('active');
  clearTimeout(dismissTimer);
}

popup.addEventListener('click', (e) => { if (e.target === popup) hidePopup(); });

// ══════════════════════════════════════════════════════════════
//  COUNTER
// ══════════════════════════════════════════════════════════════
let checkinCount = {{ $checkedInCount }};
const cntEl = document.getElementById('cnt-checkin');

function bumpCounter() {
  checkinCount++;
  cntEl.textContent = checkinCount;
  cntEl.style.transform = 'scale(1.5)';
  cntEl.style.textShadow = '0 0 30px rgba(232,197,71,.5)';
  setTimeout(() => {
    cntEl.style.transform = 'scale(1)';
    cntEl.style.textShadow = '0 0 20px rgba(232,197,71,.2)';
  }, 500);
}

// ══════════════════════════════════════════════════════════════
//  CLOCK
// ══════════════════════════════════════════════════════════════
function updateClock() {
  const now = new Date();
  document.getElementById('clock').textContent = now.toLocaleTimeString('id-ID', {
    hour: '2-digit', minute: '2-digit', second: '2-digit'
  });
}
setInterval(updateClock, 1000);
updateClock();

// ══════════════════════════════════════════════════════════════
//  SSE
// ══════════════════════════════════════════════════════════════
function connectSSE() {
  const es = new EventSource(STREAM_URL);

  es.addEventListener('checkin', (e) => {
    try {
      const guest = JSON.parse(e.data);
      bumpCounter();
      showPopup(guest);
    } catch (_) {}
  });

  es.addEventListener('connected', () => {
    console.log('[SSE] Connected to display stream');
  });

  es.onerror = () => {
    console.warn('[SSE] Connection error — browser will auto-reconnect');
  };

  return es;
}

// ══════════════════════════════════════════════════════════════
//  FALLBACK POLLING
// ══════════════════════════════════════════════════════════════
function startFallbackPolling() {
  console.warn('[Display] SSE not supported, falling back to polling');
  let lastCheckinAt = null;

  async function poll() {
    try {
      const url = lastCheckinAt
        ? `${FALLBACK_URL}?after=${encodeURIComponent(lastCheckinAt)}`
        : FALLBACK_URL;
      const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
      if (!res.ok) return;
      const data = await res.json();
      const event = data.event || data.guest || null;
      if (!event) return;
      const ts = event.timestamp || event.checked_in_at;
      if (!ts) return;
      lastCheckinAt = ts;
      bumpCounter();
      showPopup({ ...event, timestamp: ts });
    } catch (_) {}
  }

  setInterval(poll, 3000);
  setTimeout(poll, 1500);
}

// ══════════════════════════════════════════════════════════════
//  INIT
// ══════════════════════════════════════════════════════════════
startFallbackPolling();
</script>
</body>
</html>
