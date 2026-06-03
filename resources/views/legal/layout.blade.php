<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title') — Nikahin</title>
<meta name="description" content="@yield('meta_description')">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
  html { font-size: 16px; scroll-behavior: smooth; }
  body {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    background: #f8f9fd;
    color: #1a1a2e;
    line-height: 1.7;
    -webkit-font-smoothing: antialiased;
  }

  /* ── Nav ── */
  .nav {
    position: sticky; top: 0; z-index: 100;
    background: rgba(255,255,255,.92);
    backdrop-filter: blur(12px);
    border-bottom: 1px solid #e5e7eb;
    padding: 0 24px;
  }
  .nav-inner {
    max-width: 820px; margin: 0 auto;
    display: flex; align-items: center; justify-content: space-between;
    height: 60px;
  }
  .nav-brand {
    font-size: 1.1rem; font-weight: 700;
    color: #6b4ce6; text-decoration: none;
    display: flex; align-items: center; gap: 8px;
  }
  .nav-brand svg { width: 22px; height: 22px; }
  .nav-links { display: flex; gap: 20px; }
  .nav-links a {
    font-size: .875rem; font-weight: 500;
    color: #6b7280; text-decoration: none;
    transition: color .2s;
  }
  .nav-links a:hover, .nav-links a.active { color: #6b4ce6; }

  /* ── Hero ── */
  .hero {
    background: linear-gradient(135deg, #6b4ce6 0%, #5538d4 100%);
    padding: 56px 24px 48px;
    text-align: center;
  }
  .hero-label {
    display: inline-block;
    font-size: .75rem; font-weight: 600;
    letter-spacing: 2px; text-transform: uppercase;
    color: rgba(255,255,255,.6);
    margin-bottom: 12px;
  }
  .hero h1 {
    font-size: clamp(1.75rem, 4vw, 2.5rem);
    font-weight: 700; color: #fff;
    letter-spacing: -.5px; margin-bottom: 10px;
  }
  .hero-updated {
    font-size: .8rem; color: rgba(255,255,255,.55);
  }

  /* ── Container ── */
  .container {
    max-width: 820px; margin: 0 auto;
    padding: 48px 24px 80px;
  }

  /* ── Table of contents ── */
  .toc {
    background: #fff; border: 1px solid #e5e7eb;
    border-radius: 16px; padding: 24px 28px;
    margin-bottom: 40px;
  }
  .toc-title {
    font-size: .8rem; font-weight: 600;
    letter-spacing: 1.5px; text-transform: uppercase;
    color: #9ca3af; margin-bottom: 14px;
  }
  .toc ol {
    padding-left: 20px;
    display: flex; flex-direction: column; gap: 6px;
  }
  .toc li { font-size: .9rem; }
  .toc a {
    color: #6b4ce6; text-decoration: none; font-weight: 500;
    transition: color .2s;
  }
  .toc a:hover { color: #5538d4; text-decoration: underline; }

  /* ── Section ── */
  .section {
    background: #fff; border: 1px solid #e5e7eb;
    border-radius: 16px; padding: 32px 36px;
    margin-bottom: 16px;
    scroll-margin-top: 76px;
  }
  .section-num {
    display: inline-flex; align-items: center; justify-content: center;
    width: 28px; height: 28px; border-radius: 8px;
    background: #ede9fd; color: #6b4ce6;
    font-size: .75rem; font-weight: 700;
    margin-bottom: 10px;
  }
  .section h2 {
    font-size: 1.15rem; font-weight: 700;
    color: #1a1a2e; margin-bottom: 14px;
  }
  .section h3 {
    font-size: .95rem; font-weight: 600;
    color: #374151; margin: 18px 0 8px;
  }
  .section p {
    font-size: .9375rem; color: #4b5563;
    margin-bottom: 12px; line-height: 1.75;
  }
  .section p:last-child { margin-bottom: 0; }
  .section ul, .section ol {
    padding-left: 22px; color: #4b5563;
    font-size: .9375rem; margin-bottom: 12px;
    display: flex; flex-direction: column; gap: 6px;
  }
  .section li { line-height: 1.7; }

  /* ── Highlight box ── */
  .highlight {
    background: #ede9fd; border-left: 3px solid #6b4ce6;
    border-radius: 0 8px 8px 0;
    padding: 14px 18px; margin: 16px 0;
    font-size: .9rem; color: #5538d4; font-weight: 500;
  }

  /* ── Contact card ── */
  .contact-card {
    background: linear-gradient(135deg, #6b4ce6 0%, #8b6ff0 100%);
    border-radius: 16px; padding: 32px 36px;
    text-align: center; margin-top: 40px;
  }
  .contact-card h3 {
    font-size: 1.1rem; font-weight: 700; color: #fff; margin-bottom: 8px;
  }
  .contact-card p { color: rgba(255,255,255,.75); font-size: .9rem; margin-bottom: 18px; }
  .contact-card a {
    display: inline-block;
    background: #fff; color: #6b4ce6;
    font-size: .875rem; font-weight: 600;
    padding: 10px 24px; border-radius: 100px;
    text-decoration: none;
    transition: transform .2s, box-shadow .2s;
  }
  .contact-card a:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(0,0,0,.15); }

  /* ── Footer ── */
  footer {
    text-align: center; padding: 24px;
    font-size: .8rem; color: #9ca3af;
    border-top: 1px solid #e5e7eb;
  }
  footer a { color: #6b4ce6; text-decoration: none; }
  footer a:hover { text-decoration: underline; }

  @media (max-width: 600px) {
    .section { padding: 24px 20px; }
    .toc { padding: 20px; }
    .hero { padding: 40px 20px 36px; }
    .nav-links { display: none; }
  }
</style>
</head>
<body>

<nav class="nav">
  <div class="nav-inner">
    <a href="/" class="nav-brand">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
      </svg>
      Nikahin
    </a>
    <div class="nav-links">
      <a href="/terms" @if(Request::is('terms')) class="active" @endif>Ketentuan Layanan</a>
      <a href="/privacy" @if(Request::is('privacy')) class="active" @endif>Kebijakan Privasi</a>
    </div>
  </div>
</nav>

@yield('content')

<footer>
  <p>
    &copy; {{ date('Y') }} Nikahin. Semua hak dilindungi. &nbsp;·&nbsp;
    <a href="/terms">Ketentuan Layanan</a> &nbsp;·&nbsp;
    <a href="/privacy">Kebijakan Privasi</a>
  </p>
</footer>

</body>
</html>
