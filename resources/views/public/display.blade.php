<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Layar Gerbang — {{ $invitation->bride_name }} & {{ $invitation->groom_name }}</title>
<meta name="theme-color" content="#0d0618">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
/* ── Reset ── */
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html,body{width:100%;height:100%;overflow:hidden;font-family:'Inter',sans-serif;background:#0d0618;color:#fff}

/* ── CSS vars ── */
:root{
  --gold:#d4af37;--gold-l:#f0d060;--gold-d:#a8892a;
  --rose:#c9748a;--deep:#0d0618;--deep2:#1a0a2e;--deep3:#2d1b4e;
  --green:#22c55e;--green-d:#16a34a;
}

/* ── Canvas ── */
#canvas{position:fixed;inset:0;z-index:0;pointer-events:none}

/* ── Orbs ── */
.orb{position:fixed;border-radius:50%;filter:blur(100px);pointer-events:none;z-index:0;animation:orbFloat 10s ease-in-out infinite}
.o1{width:600px;height:600px;background:var(--gold);opacity:.12;top:-200px;right:-150px;animation-delay:0s}
.o2{width:500px;height:500px;background:var(--rose);opacity:.1;bottom:-150px;left:-150px;animation-delay:-4s}
.o3{width:350px;height:350px;background:#7c3aed;opacity:.12;top:35%;left:5%;animation-delay:-7s}
.o4{width:250px;height:250px;background:#2563eb;opacity:.08;bottom:20%;right:10%;animation-delay:-2s}
@keyframes orbFloat{0%,100%{transform:translateY(0) scale(1)}50%{transform:translateY(-40px) scale(1.06)}}

/* ══════════════════════════════════════════
   IDLE SCREEN
══════════════════════════════════════════ */
#idle{
  position:fixed;inset:0;z-index:10;
  display:flex;flex-direction:column;align-items:center;justify-content:center;
  gap:0;
  transition:opacity .6s ease;
}

/* Decorative top line */
.idle-topline{
  display:flex;align-items:center;gap:16px;margin-bottom:32px;
  opacity:0;animation:fadeIn 1s ease .3s forwards;
}
.idle-topline-bar{width:80px;height:1px;background:linear-gradient(to right,transparent,var(--gold))}
.idle-topline-bar.r{background:linear-gradient(to left,transparent,var(--gold))}
.idle-topline-dot{width:6px;height:6px;border-radius:50%;background:var(--gold)}

/* Couple names */
.idle-couple{
  font-family:'Playfair Display',serif;
  font-size:clamp(52px,7vw,96px);
  font-weight:700;
  line-height:1.1;
  text-align:center;
  letter-spacing:-1px;
  opacity:0;animation:fadeUp 1s cubic-bezier(.22,1,.36,1) .5s forwards;
}
.idle-couple .amp{
  display:block;
  font-style:italic;font-weight:400;
  font-size:clamp(32px,4.5vw,60px);
  color:var(--gold);
  margin:.1em 0;
}

/* Subtitle */
.idle-subtitle{
  font-size:clamp(13px,1.4vw,18px);
  font-weight:500;
  letter-spacing:5px;
  text-transform:uppercase;
  color:rgba(255,255,255,.45);
  margin-top:20px;
  opacity:0;animation:fadeIn 1s ease 1s forwards;
}

/* Date badge */
.idle-date{
  display:flex;align-items:center;gap:12px;
  margin-top:28px;
  padding:10px 24px;
  border:1px solid rgba(212,175,55,.3);
  border-radius:100px;
  background:rgba(212,175,55,.07);
  font-size:clamp(13px,1.3vw,16px);
  font-weight:500;
  color:var(--gold-l);
  opacity:0;animation:fadeIn 1s ease 1.2s forwards;
}
.idle-date svg{width:16px;height:16px;opacity:.7}

/* Divider ornament */
.idle-ornament{
  margin-top:36px;
  font-size:clamp(18px,2vw,26px);
  letter-spacing:12px;
  color:rgba(212,175,55,.4);
  opacity:0;animation:fadeIn 1s ease 1.4s forwards;
}

/* Counter bar */
.idle-counter{
  position:fixed;bottom:36px;left:50%;transform:translateX(-50%);
  display:flex;align-items:center;gap:20px;
  padding:14px 32px;
  background:rgba(255,255,255,.05);
  border:1px solid rgba(255,255,255,.1);
  border-radius:100px;
  backdrop-filter:blur(12px);
  opacity:0;animation:fadeIn 1s ease 1.6s forwards;
  white-space:nowrap;
}
.counter-item{display:flex;align-items:center;gap:8px;font-size:clamp(12px,1.2vw,15px)}
.counter-num{
  font-family:'Playfair Display',serif;
  font-size:clamp(20px,2.2vw,28px);
  font-weight:700;
  color:var(--gold);
  min-width:2ch;text-align:center;
  transition:all .4s cubic-bezier(.34,1.56,.64,1);
}
.counter-label{color:rgba(255,255,255,.5);font-weight:500}
.counter-sep{width:1px;height:28px;background:rgba(255,255,255,.15)}

/* Scan prompt */
.idle-scan-prompt{
  position:fixed;bottom:100px;left:50%;transform:translateX(-50%);
  display:flex;align-items:center;gap:10px;
  font-size:clamp(11px,1.1vw,14px);
  color:rgba(255,255,255,.3);
  letter-spacing:1px;
  opacity:0;animation:pulse 3s ease 2s infinite;
}
.idle-scan-prompt svg{width:16px;height:16px}
@keyframes pulse{0%,100%{opacity:.3}50%{opacity:.7}}

/* ══════════════════════════════════════════
   POPUP OVERLAY
══════════════════════════════════════════ */
#popup{
  position:fixed;inset:0;z-index:100;
  display:flex;align-items:center;justify-content:center;
  background:rgba(0,0,0,0);
  pointer-events:none;
  transition:background .6s ease;
}
#popup.active{
  background:rgba(5,2,15,.82);
  backdrop-filter:blur(20px);
  -webkit-backdrop-filter:blur(20px);
  pointer-events:auto;
}

/* ── Card ── */
.popup-card{
  position:relative;
  width:min(560px,90vw);
  background:rgba(14,8,28,.95);
  border-radius:28px;
  padding:clamp(36px,4.5vw,56px) clamp(32px,4.5vw,56px) clamp(28px,3.5vw,44px);
  text-align:center;
  overflow:hidden;
  /* Subtle gold border */
  box-shadow:
    inset 0 0 0 1px rgba(212,175,55,.18),
    0 48px 100px rgba(0,0,0,.7),
    0 0 60px rgba(212,175,55,.04);
  /* hidden state */
  opacity:0;
  transform:translateY(32px) scale(.97);
  transition:opacity .55s cubic-bezier(.22,1,.36,1), transform .55s cubic-bezier(.22,1,.36,1);
}
#popup.active .popup-card{
  opacity:1;
  transform:translateY(0) scale(1);
}

/* Ambient glow behind card */
.popup-card::after{
  content:'';
  position:absolute;
  top:-60%;left:50%;
  transform:translateX(-50%);
  width:70%;height:120%;
  background:radial-gradient(ellipse at center,rgba(212,175,55,.07) 0%,transparent 70%);
  pointer-events:none;
  z-index:0;
}

/* ── Top ornament line ── */
.popup-ornament{
  display:flex;align-items:center;gap:12px;
  justify-content:center;
  margin-bottom:clamp(20px,2.5vw,32px);
  position:relative;z-index:1;
}
.popup-ornament-line{
  width:clamp(32px,5vw,60px);height:1px;
  background:linear-gradient(to right,transparent,rgba(212,175,55,.5));
}
.popup-ornament-line.r{
  background:linear-gradient(to left,transparent,rgba(212,175,55,.5));
}
.popup-ornament-diamond{
  width:6px;height:6px;
  background:var(--gold);
  transform:rotate(45deg);
  opacity:.8;
}

/* ── Initials monogram — removed ── */

/* ── Text ── */
.popup-welcome{
  position:relative;z-index:1;
  font-size:clamp(10px,1vw,12px);
  font-weight:500;
  letter-spacing:4px;
  text-transform:uppercase;
  color:rgba(212,175,55,.6);
  margin-bottom:clamp(6px,.8vw,10px);
}
.popup-name{
  position:relative;z-index:1;
  font-family:'Playfair Display',serif;
  font-size:clamp(32px,5vw,64px);
  font-weight:700;
  line-height:1.05;
  color:#fff;
  margin-bottom:clamp(6px,.8vw,10px);
  word-break:break-word;
  letter-spacing:-.5px;
}
.popup-category{
  position:relative;z-index:1;
  display:inline-block;
  padding:4px 16px;
  border-radius:100px;
  background:transparent;
  border:1px solid rgba(212,175,55,.2);
  font-size:clamp(10px,.9vw,12px);
  font-weight:500;
  letter-spacing:2px;
  text-transform:uppercase;
  color:rgba(212,175,55,.55);
  margin-bottom:clamp(20px,2.5vw,32px);
}

/* ── Divider ── */
.popup-divider{
  position:relative;z-index:1;
  display:flex;align-items:center;gap:14px;
  margin-bottom:clamp(14px,1.8vw,22px);
}
.popup-divider-line{
  flex:1;height:1px;
  background:rgba(255,255,255,.07);
}
.popup-divider-icon{
  font-size:clamp(14px,1.5vw,18px);
  opacity:.6;
}

/* ── Couple ── */
.popup-couple{
  position:relative;z-index:1;
  font-family:'Playfair Display',serif;
  font-size:clamp(16px,2.2vw,26px);
  font-weight:400;
  font-style:italic;
  color:rgba(255,255,255,.5);
  margin-bottom:clamp(16px,2vw,24px);
  letter-spacing:.3px;
}
.popup-couple .amp{color:rgba(212,175,55,.5);margin:0 6px;font-style:normal}

/* ── Time chip ── */
.popup-time{
  position:relative;z-index:1;
  display:inline-flex;align-items:center;gap:7px;
  padding:7px 18px;
  border-radius:100px;
  background:rgba(255,255,255,.04);
  border:1px solid rgba(255,255,255,.08);
  font-size:clamp(11px,1.1vw,13px);
  font-weight:500;
  color:rgba(255,255,255,.45);
  letter-spacing:.5px;
}
.popup-time svg{width:13px;height:13px;opacity:.5}

/* ── Progress bar ── */
.popup-progress{
  position:absolute;bottom:0;left:0;right:0;height:2px;
  background:rgba(255,255,255,.05);
  overflow:hidden;
}
.popup-progress-bar{
  height:100%;
  background:linear-gradient(to right,rgba(212,175,55,.3),rgba(212,175,55,.7),rgba(212,175,55,.3));
  width:100%;
  transform-origin:left;
  transform:scaleX(1);
  transition:none;
}

/* ── Confetti ── */
#confetti{position:fixed;inset:0;pointer-events:none;z-index:200;overflow:hidden}
.cp{
  position:absolute;top:-20px;
  animation:cpFall var(--d) ease-in var(--dl) forwards;
  opacity:0;
}
@keyframes cpFall{
  0%{opacity:1;transform:translateY(0) rotate(0deg) scale(1)}
  85%{opacity:1}
  100%{opacity:0;transform:translateY(105vh) rotate(720deg) scale(.4)}
}

/* ── Keyframes ── */
@keyframes fadeIn{from{opacity:0}to{opacity:1}}
@keyframes fadeUp{from{opacity:0;transform:translateY(24px)}to{opacity:1;transform:translateY(0)}}
</style>
</head>
<body>

<canvas id="canvas"></canvas>
<div class="orb o1"></div>
<div class="orb o2"></div>
<div class="orb o3"></div>
<div class="orb o4"></div>
<div id="confetti"></div>

{{-- ══ IDLE SCREEN ══ --}}
<div id="idle">
  <div class="idle-topline">
    <div class="idle-topline-bar"></div>
    <div class="idle-topline-dot"></div>
    <div class="idle-topline-bar r"></div>
  </div>

  <h1 class="idle-couple">
    {{ $invitation->bride_name }}
    <span class="amp">&amp;</span>
    {{ $invitation->groom_name }}
  </h1>

  <p class="idle-subtitle">Undangan Pernikahan</p>

  <div class="idle-date">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
      <rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/>
      <line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
    </svg>
    {{ \Carbon\Carbon::parse($invitation->reception_date)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
    &nbsp;·&nbsp;
    {{ \Carbon\Carbon::parse($invitation->reception_time_start)->format('H:i') }}
    @if($invitation->reception_time_end)
      – {{ \Carbon\Carbon::parse($invitation->reception_time_end)->format('H:i') }} WIB
    @else
      WIB
    @endif
  </div>

  <div class="idle-ornament">✦ &nbsp; ✦ &nbsp; ✦</div>

  <div class="idle-scan-prompt">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
      <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
      <rect x="3" y="14" width="7" height="7"/><line x1="14" y1="14" x2="14" y2="14"/>
      <line x1="17" y1="14" x2="21" y2="14"/><line x1="14" y1="17" x2="14" y2="21"/>
      <line x1="17" y1="17" x2="21" y2="17"/><line x1="17" y1="21" x2="21" y2="21"/>
    </svg>
    Silakan scan QR code Anda di gerbang
  </div>

  <div class="idle-counter">
    <div class="counter-item">
      <span class="counter-num" id="cnt-checkin">{{ $checkedInCount }}</span>
      <span class="counter-label">Tamu Hadir</span>
    </div>
    <div class="counter-sep"></div>
    <div class="counter-item">
      <span class="counter-num" id="cnt-total">{{ $totalGuests }}</span>
      <span class="counter-label">Total Undangan</span>
    </div>
  </div>
</div>

{{-- ══ POPUP ══ --}}
<div id="popup">
  <div class="popup-card">

    {{-- Top ornament --}}
    <div class="popup-ornament">
      <div class="popup-ornament-line"></div>
      <div class="popup-ornament-diamond"></div>
      <div class="popup-ornament-line r"></div>
    </div>

    {{-- Welcome label --}}
    <p class="popup-welcome">Selamat Datang</p>

    {{-- Guest name --}}
    <h2 class="popup-name" id="popup-name">Nama Tamu</h2>

    {{-- Category --}}
    <span class="popup-category" id="popup-category">Keluarga</span>

    {{-- Divider --}}
    <div class="popup-divider">
      <div class="popup-divider-line"></div>
      <span class="popup-divider-icon">💍</span>
      <div class="popup-divider-line"></div>
    </div>

    {{-- Couple names --}}
    <p class="popup-couple">
      {{ $invitation->bride_name }}
      <span class="amp">&amp;</span>
      {{ $invitation->groom_name }}
    </p>

    {{-- Check-in time --}}
    <div class="popup-time">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
      </svg>
      <span id="popup-time">Check-in 00:00</span>
    </div>

    {{-- Progress bar --}}
    <div class="popup-progress"><div class="popup-progress-bar" id="popup-bar"></div></div>
  </div>
</div>

<script>
// ══════════════════════════════════════════════════════════════
//  CONFIG
// ══════════════════════════════════════════════════════════════
const UNIQUE_URL     = @json($invitation->unique_url);
const STREAM_URL     = `/display/${UNIQUE_URL}/stream`;
const FALLBACK_URL   = `/api/display/${UNIQUE_URL}/latest-checkin`;
const POPUP_DURATION = 8000;   // ms popup stays visible

// ══════════════════════════════════════════════════════════════
//  PARTICLE CANVAS
// ══════════════════════════════════════════════════════════════
(function () {
  const cv = document.getElementById('canvas');
  const cx = cv.getContext('2d');
  let W, H;
  const resize = () => { W = cv.width = innerWidth; H = cv.height = innerHeight; };
  addEventListener('resize', resize); resize();
  const rand = (a, b) => a + Math.random() * (b - a);
  class P {
    constructor() { this.reset(); }
    reset() {
      this.x = rand(0, W); this.y = rand(0, H);
      this.r = rand(.4, 1.8); this.vx = rand(-.15, .15); this.vy = rand(-.35, -.08);
      this.a = rand(.05, .5); this.da = rand(-.0015, .0015);
    }
    tick() {
      this.x += this.vx; this.y += this.vy; this.a += this.da;
      if (this.a <= 0 || this.a >= .55) this.da *= -1;
      if (this.y < -4) this.reset();
    }
    draw() {
      cx.beginPath(); cx.arc(this.x, this.y, this.r, 0, Math.PI * 2);
      cx.fillStyle = `rgba(212,175,55,${this.a})`; cx.fill();
    }
  }
  const ps = Array.from({ length: 120 }, () => new P());
  const loop = () => { cx.clearRect(0, 0, W, H); ps.forEach(p => { p.tick(); p.draw(); }); requestAnimationFrame(loop); };
  loop();
})();

// ══════════════════════════════════════════════════════════════
//  CONFETTI
// ══════════════════════════════════════════════════════════════
function spawnConfetti() {
  const wrap = document.getElementById('confetti');
  wrap.innerHTML = '';
  const colors = ['#d4af37','#f0d060','#c9748a','#a78bfa','#ffffff','#fbbf24','#34d399'];
  for (let i = 0; i < 60; i++) {
    const el = document.createElement('div');
    el.className = 'cp';
    const size = 4 + Math.random() * 6;
    el.style.cssText = `
      left:${Math.random()*100}%;
      width:${size}px; height:${size}px;
      background:${colors[Math.floor(Math.random()*colors.length)]};
      border-radius:${Math.random()>.6?'50%':'2px'};
      --d:${2.5+Math.random()*2}s;
      --dl:${Math.random()*1}s;
    `;
    wrap.appendChild(el);
  }
  setTimeout(() => { wrap.innerHTML = ''; }, 4500);
}

// ══════════════════════════════════════════════════════════════
//  POPUP LOGIC
// ══════════════════════════════════════════════════════════════
const popup    = document.getElementById('popup');
const popupBar = document.getElementById('popup-bar');
let dismissTimer = null;

function formatTime(iso) {
  const d = new Date(iso);
  return d.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
}

function showPopup(guest) {
  document.getElementById('popup-name').textContent     = guest.name;
  document.getElementById('popup-category').textContent = guest.category_label;
  document.getElementById('popup-time').textContent     = 'Check-in ' + formatTime(guest.timestamp || guest.checked_in_at);

  clearTimeout(dismissTimer);

  popup.classList.add('active');
  spawnConfetti();

  // Progress bar
  popupBar.style.transition = 'none';
  popupBar.style.transform  = 'scaleX(1)';
  requestAnimationFrame(() => requestAnimationFrame(() => {
    popupBar.style.transition = `transform ${POPUP_DURATION}ms linear`;
    popupBar.style.transform  = 'scaleX(0)';
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
const cntEl      = document.getElementById('cnt-checkin');

function bumpCounter() {
  checkinCount++;
  cntEl.textContent = checkinCount;
  cntEl.style.transform = 'scale(1.4)';
  setTimeout(() => { cntEl.style.transform = 'scale(1)'; }, 400);
}

// ══════════════════════════════════════════════════════════════
//  SSE — real-time push from server
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

  // Reconnect on error (browser does this automatically, but we log it)
  es.onerror = () => {
    console.warn('[SSE] Connection error — browser will auto-reconnect');
  };

  return es;
}

// ══════════════════════════════════════════════════════════════
//  FALLBACK POLLING — used only if SSE is not supported
// ══════════════════════════════════════════════════════════════
function startFallbackPolling() {
  console.warn('[Display] SSE not supported, falling back to polling');
  let lastCheckinAt = null;

  async function poll() {
    try {
      const url = lastCheckinAt
        ? `${FALLBACK_URL}?after=${encodeURIComponent(lastCheckinAt)}`
        : FALLBACK_URL;
      const res  = await fetch(url, { headers: { 'Accept': 'application/json' } });
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
//  INIT — use polling (SSE requires multi-threaded server)
// ══════════════════════════════════════════════════════════════
startFallbackPolling();
</script>
</body>
</html>
