<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title') — Nikahin</title>
<meta name="description" content="@yield('meta_description')">
<meta name="robots" content="index, follow">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
/* ── Reset & Base ── */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html { font-size: 16px; scroll-behavior: smooth; scroll-padding-top: 76px; }
body {
  font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
  background: #f4f5f9;
  color: #111827;
  line-height: 1.7;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}

/* ── Variables ── */
:root {
  --primary: #6b4ce6;
  --primary-dark: #5538d4;
  --primary-light: #ede9fd;
  --primary-mid: #8b6ff0;
  --text: #111827;
  --text-secondary: #4b5563;
  --text-muted: #9ca3af;
  --border: #e5e7eb;
  --surface: #ffffff;
  --bg: #f4f5f9;
  --radius: 16px;
  --radius-sm: 10px;
  --shadow-sm: 0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
  --shadow-md: 0 4px 16px rgba(107,76,230,.08), 0 2px 8px rgba(0,0,0,.05);
  --shadow-lg: 0 12px 40px rgba(107,76,230,.12), 0 4px 16px rgba(0,0,0,.06);
}

/* ══════════════════════════════
   NAVBAR
══════════════════════════════ */
.nav {
  position: sticky; top: 0; z-index: 200;
  background: rgba(255,255,255,.9);
  backdrop-filter: blur(16px) saturate(1.5);
  -webkit-backdrop-filter: blur(16px) saturate(1.5);
  border-bottom: 1px solid rgba(229,231,235,.8);
  box-shadow: 0 1px 0 rgba(107,76,230,.04);
}
.nav-inner {
  max-width: 1140px; margin: 0 auto;
  padding: 0 28px;
  display: flex; align-items: center; justify-content: space-between;
  height: 76px;
}
.nav-brand {
  display: flex; align-items: center; gap: 10px;
  text-decoration: none;
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-weight: 800; font-size: 1.1rem;
  color: var(--primary);
}
.nav-brand-icon {
  width: 34px; height: 34px; border-radius: 10px;
  background: linear-gradient(135deg, var(--primary), var(--primary-mid));
  display: flex; align-items: center; justify-content: center;
  box-shadow: 0 4px 12px rgba(107,76,230,.3);
}
.nav-brand-icon svg { width: 18px; height: 18px; color: #fff; }
.nav-pills {
  display: flex; align-items: center; gap: 4px;
  background: var(--bg);
  border: 1px solid var(--border);
  border-radius: 100px;
  padding: 4px;
}
.nav-pill {
  padding: 7px 18px;
  border-radius: 100px;
  font-size: .8rem; font-weight: 600;
  text-decoration: none;
  color: var(--text-secondary);
  transition: all .2s;
  letter-spacing: .2px;
}
.nav-pill:hover { color: var(--primary); background: var(--primary-light); }
.nav-pill.active {
  background: var(--primary);
  color: #fff;
  box-shadow: 0 4px 12px rgba(107,76,230,.3);
}

/* ══════════════════════════════
   HERO
══════════════════════════════ */
.hero {
  position: relative;
  overflow: hidden;
  background: linear-gradient(135deg, #1e0a4e 0%, #3d1c8c 40%, var(--primary) 70%, var(--primary-mid) 100%);
  padding: 72px 28px 80px;
  text-align: center;
}
.hero::before {
  content: '';
  position: absolute; inset: 0;
  background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23fff' fill-opacity='0.03'%3E%3Ccircle cx='30' cy='30' r='2'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}
.hero::after {
  content: '';
  position: absolute; bottom: -1px; left: 0; right: 0; height: 60px;
  background: var(--bg);
  clip-path: ellipse(55% 100% at 50% 100%);
}
.hero-inner { position: relative; z-index: 1; }
.hero-badge {
  display: inline-flex; align-items: center; gap: 7px;
  background: rgba(255,255,255,.1);
  border: 1px solid rgba(255,255,255,.15);
  border-radius: 100px;
  padding: 6px 16px;
  font-size: .75rem; font-weight: 600;
  letter-spacing: 1.5px; text-transform: uppercase;
  color: rgba(255,255,255,.7);
  margin-bottom: 20px;
}
.hero-badge-dot {
  width: 6px; height: 6px; border-radius: 50%;
  background: #a78bfa;
}
.hero h1 {
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-size: clamp(2rem, 5vw, 3rem);
  font-weight: 800;
  color: #fff;
  letter-spacing: -1px;
  line-height: 1.15;
  margin-bottom: 14px;
}
.hero-sub {
  font-size: .9rem;
  color: rgba(255,255,255,.55);
  display: flex; align-items: center; justify-content: center; gap: 10px;
}
.hero-sub-sep {
  width: 3px; height: 3px; border-radius: 50%;
  background: rgba(255,255,255,.3);
}

/* ══════════════════════════════
   LAYOUT — 2 Column
══════════════════════════════ */
.page-body {
  max-width: 1140px; margin: 0 auto;
  padding: 48px 28px 80px;
  display: grid;
  grid-template-columns: 240px 1fr;
  gap: 32px;
  align-items: start;
}

/* ══════════════════════════════
   SIDEBAR
══════════════════════════════ */
.sidebar {
  position: sticky;
  top: 84px;
}
.sidebar-card {
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  padding: 22px;
  box-shadow: var(--shadow-sm);
}
.sidebar-title {
  font-size: .7rem; font-weight: 700;
  letter-spacing: 1.5px; text-transform: uppercase;
  color: var(--text-muted);
  padding-bottom: 12px;
  border-bottom: 1px solid var(--border);
  margin-bottom: 12px;
}
.sidebar-nav { display: flex; flex-direction: column; gap: 2px; }
.sidebar-link {
  display: flex; align-items: center; gap: 9px;
  padding: 8px 10px;
  border-radius: var(--radius-sm);
  text-decoration: none;
  font-size: .82rem; font-weight: 500;
  color: var(--text-secondary);
  transition: all .18s;
  line-height: 1.4;
}
.sidebar-link:hover {
  background: var(--primary-light);
  color: var(--primary);
}
.sidebar-link.active {
  background: var(--primary-light);
  color: var(--primary);
  font-weight: 600;
}
.sidebar-num {
  min-width: 20px; height: 20px;
  border-radius: 6px;
  background: var(--border);
  color: var(--text-muted);
  font-size: .65rem; font-weight: 700;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
  transition: all .18s;
}
.sidebar-link:hover .sidebar-num,
.sidebar-link.active .sidebar-num {
  background: var(--primary);
  color: #fff;
}

.sidebar-other {
  margin-top: 16px;
  padding-top: 16px;
  border-top: 1px solid var(--border);
}
.sidebar-other-title {
  font-size: .7rem; font-weight: 600;
  text-transform: uppercase; letter-spacing: 1.5px;
  color: var(--text-muted);
  margin-bottom: 10px;
}
.sidebar-other-link {
  display: flex; align-items: center; gap: 8px;
  padding: 8px 10px;
  border-radius: var(--radius-sm);
  text-decoration: none;
  font-size: .82rem; font-weight: 500;
  color: var(--text-secondary);
  transition: all .18s;
}
.sidebar-other-link:hover { color: var(--primary); background: var(--primary-light); }
.sidebar-other-link svg { width: 14px; height: 14px; opacity: .6; }

/* ══════════════════════════════
   MAIN CONTENT
══════════════════════════════ */
.main { display: flex; flex-direction: column; gap: 12px; }

/* ── Section card ── */
.section {
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  padding: 32px 36px;
  box-shadow: var(--shadow-sm);
  scroll-margin-top: 84px;
  transition: box-shadow .2s, border-color .2s;
}
.section:hover {
  box-shadow: var(--shadow-md);
  border-color: rgba(107,76,230,.12);
}
.section-header {
  display: flex; align-items: flex-start; gap: 14px;
  margin-bottom: 20px;
  padding-bottom: 20px;
  border-bottom: 1px solid var(--border);
}
.section-num {
  display: flex; align-items: center; justify-content: center;
  min-width: 36px; height: 36px;
  border-radius: 10px;
  background: linear-gradient(135deg, var(--primary-light), #ddd6fe);
  color: var(--primary);
  font-size: .8rem; font-weight: 800;
  flex-shrink: 0;
  border: 1px solid rgba(107,76,230,.15);
}
.section-title-wrap {}
.section h2 {
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-size: 1.05rem; font-weight: 700;
  color: var(--text);
  line-height: 1.3;
  margin-bottom: 2px;
}
.section-subtitle {
  font-size: .8rem; color: var(--text-muted); font-weight: 400;
}
.section-body { display: flex; flex-direction: column; gap: 14px; }
.section h3 {
  font-size: .875rem; font-weight: 700;
  color: var(--text);
  margin-top: 6px;
  display: flex; align-items: center; gap: 8px;
}
.section h3::before {
  content: '';
  display: inline-block;
  width: 3px; height: 14px;
  background: var(--primary);
  border-radius: 2px;
  flex-shrink: 0;
}
.section p {
  font-size: .9rem; color: var(--text-secondary);
  line-height: 1.8;
}
.section ul, .section ol {
  padding-left: 0;
  color: var(--text-secondary);
  font-size: .9rem;
  display: flex; flex-direction: column; gap: 8px;
  list-style: none;
}
.section li {
  display: flex; align-items: flex-start; gap: 10px;
  line-height: 1.7;
}
.section ul li::before {
  content: '';
  min-width: 6px; height: 6px;
  border-radius: 50%;
  background: var(--primary);
  margin-top: 8px;
  flex-shrink: 0;
  opacity: .6;
}
.section ol { counter-reset: ol-counter; }
.section ol li { counter-increment: ol-counter; }
.section ol li::before {
  content: counter(ol-counter);
  min-width: 20px; height: 20px;
  border-radius: 6px;
  background: var(--primary-light);
  color: var(--primary);
  font-size: .7rem; font-weight: 700;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0; margin-top: 3px;
}
.section strong { color: var(--text); font-weight: 600; }
.section a { color: var(--primary); font-weight: 500; text-decoration: none; }
.section a:hover { text-decoration: underline; }

/* ── Callout boxes ── */
.callout {
  display: flex; align-items: flex-start; gap: 12px;
  padding: 14px 18px;
  border-radius: var(--radius-sm);
  font-size: .875rem;
  line-height: 1.7;
}
.callout-info {
  background: rgba(107,76,230,.06);
  border-left: 3px solid var(--primary);
  color: #4c1d95;
}
.callout-warning {
  background: rgba(245,158,11,.06);
  border-left: 3px solid #f59e0b;
  color: #92400e;
}
.callout-success {
  background: rgba(16,185,129,.06);
  border-left: 3px solid #10b981;
  color: #065f46;
}
.callout-icon { font-size: 1rem; line-height: 1.7; flex-shrink: 0; }

/* ── Tag list ── */
.tag-row { display: flex; flex-wrap: wrap; gap: 8px; }
.tag {
  display: inline-flex; align-items: center; gap: 5px;
  padding: 4px 12px;
  background: var(--bg);
  border: 1px solid var(--border);
  border-radius: 100px;
  font-size: .78rem; font-weight: 500;
  color: var(--text-secondary);
}
.tag-dot { width: 6px; height: 6px; border-radius: 50%; }

/* ══════════════════════════════
   CONTACT CTA
══════════════════════════════ */
.cta-card {
  background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 50%, var(--primary-mid) 100%);
  border-radius: var(--radius);
  padding: 40px;
  text-align: center;
  position: relative;
  overflow: hidden;
  margin-top: 4px;
}
.cta-card::before {
  content: '';
  position: absolute;
  top: -40px; right: -40px;
  width: 200px; height: 200px;
  border-radius: 50%;
  background: rgba(255,255,255,.05);
}
.cta-card::after {
  content: '';
  position: absolute;
  bottom: -60px; left: -40px;
  width: 240px; height: 240px;
  border-radius: 50%;
  background: rgba(255,255,255,.04);
}
.cta-card-inner { position: relative; z-index: 1; }
.cta-card h3 {
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-size: 1.3rem; font-weight: 800;
  color: #fff; margin-bottom: 8px;
}
.cta-card p { color: rgba(255,255,255,.65); font-size: .9rem; margin-bottom: 24px; }
.cta-btn {
  display: inline-flex; align-items: center; gap: 8px;
  background: #fff;
  color: var(--primary);
  font-size: .875rem; font-weight: 700;
  padding: 12px 28px;
  border-radius: 100px;
  text-decoration: none;
  transition: all .2s;
  box-shadow: 0 4px 20px rgba(0,0,0,.15);
}
.cta-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 28px rgba(0,0,0,.2);
}
.cta-btn svg { width: 16px; height: 16px; }

/* ══════════════════════════════
   FOOTER
══════════════════════════════ */
footer {
  background: var(--surface);
  border-top: 1px solid var(--border);
  padding: 28px;
  text-align: center;
}
.footer-inner {
  max-width: 1140px; margin: 0 auto;
  display: flex; align-items: center; justify-content: space-between;
  flex-wrap: wrap; gap: 12px;
}
.footer-left {
  font-size: .82rem; color: var(--text-muted);
}
.footer-links {
  display: flex; align-items: center; gap: 20px;
}
.footer-links a {
  font-size: .82rem; font-weight: 500;
  color: var(--text-muted); text-decoration: none;
  transition: color .2s;
}
.footer-links a:hover, .footer-links a.active { color: var(--primary); }

/* ══════════════════════════════
   RESPONSIVE
══════════════════════════════ */
@media (max-width: 900px) {
  .page-body {
    grid-template-columns: 1fr;
    padding: 28px 20px 60px;
  }
  .sidebar {
    position: static;
  }
  .sidebar-card {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
  }
  .sidebar-title { grid-column: 1 / -1; margin-bottom: 0; }
  .sidebar-nav { grid-column: 1 / -1; display: grid; grid-template-columns: 1fr 1fr; gap: 4px; }
}
@media (max-width: 600px) {
  .nav-pills { display: none; }
  .hero { padding: 52px 20px 72px; }
  .section { padding: 24px 20px; }
  .sidebar-card { display: block; }
  .sidebar-nav { grid-template-columns: 1fr; }
  .footer-inner { flex-direction: column; text-align: center; }
  .cta-card { padding: 28px 24px; }
}
</style>
</head>
<body>

{{-- ── Navbar ── --}}
<nav class="nav">
  <div class="nav-inner">
    <a href="/" class="nav-brand">
      <img src="{{ asset('images/logo.png') }}" alt="Nikahin Logo" style="height: 56px; width: auto;">
    </a>
    <div class="nav-pills">
      <a href="/terms" class="nav-pill @if(Request::is('terms')) active @endif">Ketentuan Layanan</a>
      <a href="/privacy" class="nav-pill @if(Request::is('privacy')) active @endif">Kebijakan Privasi</a>
    </div>
  </div>
</nav>

{{-- ── Hero ── --}}
<div class="hero">
  <div class="hero-inner">
    <div class="hero-badge">
      <div class="hero-badge-dot"></div>
      Dokumen Resmi
    </div>
    <h1>@yield('hero_title')</h1>
    <div class="hero-sub">
      <span>Nikahin App</span>
      <div class="hero-sub-sep"></div>
      <span>Terakhir diperbarui: 1 Juni 2025</span>
      <div class="hero-sub-sep"></div>
      <span>Berlaku untuk versi 1.0+</span>
    </div>
  </div>
</div>

{{-- ── Page Body ── --}}
<div class="page-body">

  {{-- Sidebar --}}
  <aside class="sidebar">
    <div class="sidebar-card">
      <div class="sidebar-title">Daftar Isi</div>
      <nav class="sidebar-nav">
        @yield('sidebar_nav')
      </nav>
      <div class="sidebar-other">
        <div class="sidebar-other-title">Dokumen Lain</div>
        @if(Request::is('terms'))
          <a href="/privacy" class="sidebar-other-link">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            Kebijakan Privasi
          </a>
        @else
          <a href="/terms" class="sidebar-other-link">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            Ketentuan Layanan
          </a>
        @endif
      </div>
    </div>
  </aside>

  {{-- Main content --}}
  <main class="main">
    @yield('sections')

    <div class="cta-card">
      <div class="cta-card-inner">
        <h3>Ada Pertanyaan?</h3>
        <p>Tim Nikahin siap membantu menjawab segala pertanyaan seputar kebijakan kami.</p>
        <a href="mailto:pamudanyiptakarya@gmail.com" class="cta-btn">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
          pamudanyiptakarya@gmail.com
        </a>
      </div>
    </div>
  </main>

</div>

{{-- ── Footer ── --}}
<footer>
  <div class="footer-inner">
    <div class="footer-left">&copy; {{ date('Y') }} Nikahin. Hak cipta dilindungi undang-undang.</div>
    <div class="footer-links">
      <a href="/terms" @if(Request::is('terms')) class="active" @endif>Ketentuan Layanan</a>
      <a href="/privacy" @if(Request::is('privacy')) class="active" @endif>Kebijakan Privasi</a>
      <a href="mailto:pamudanyiptakarya@gmail.com">Hubungi Kami</a>
    </div>
  </div>
</footer>

<script>
// Highlight active sidebar link on scroll
const sections = document.querySelectorAll('[data-section]');
const links    = document.querySelectorAll('.sidebar-link');
if (sections.length && links.length) {
  const obs = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        links.forEach(l => l.classList.remove('active'));
        const id = entry.target.getAttribute('data-section');
        const active = document.querySelector(`.sidebar-link[href="#${id}"]`);
        if (active) active.classList.add('active');
      }
    });
  }, { rootMargin: '-20% 0px -70% 0px' });
  sections.forEach(s => obs.observe(s));
}
</script>
</body>
</html>
