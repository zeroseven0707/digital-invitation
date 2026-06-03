@php
    use Illuminate\Support\Facades\Storage;
    use App\Models\Setting;
    $invitationPrice = Setting::get('invitation_price', 50000);
    $formattedPrice = 'Rp ' . number_format($invitationPrice, 0, ',', '.');
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>nikahin - Undangan Digital Premium | Mulai 49rb</title>
    <meta name="description" content="Buat undangan pernikahan digital yang modern, elegan, dan interaktif. Mulai dari {{ $formattedPrice }} dengan 9+ template premium. Bagikan kebahagiaan Anda dengan cara yang berbeda.">

    <!-- Canonical URL -->
    <meta property="og:url" content="{{ url('/') }}">
    <link rel="canonical" href="{{ url('/') }}">

    <!-- Open Graph / Facebook / WhatsApp -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="nikahin">
    <meta property="og:title" content="nikahin - Undangan Digital Premium | Mulai 49rb">
    <meta property="og:description" content="Buat undangan pernikahan digital yang modern, elegan, dan interaktif. Mulai dari {{ $formattedPrice }} dengan 9+ template premium. Bagikan kebahagiaan Anda dengan cara yang berbeda.">
    <meta property="og:image" content="{{ asset('images/logo.png') }}">
    <meta property="og:image:secure_url" content="{{ asset('images/logo.png') }}">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="nikahin - Undangan Digital Premium">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@nikahin">
    <meta name="twitter:title" content="nikahin - Undangan Digital Premium | Mulai 49rb">
    <meta name="twitter:description" content="Buat undangan pernikahan digital yang modern, elegan, dan interaktif. Mulai dari {{ $formattedPrice }} dengan 9+ template premium.">
    <meta name="twitter:image" content="{{ asset('images/logo.png') }}">

    <!-- WhatsApp Specific -->
    <meta property="og:locale" content="id_ID">

    <!-- Additional Meta Tags -->
    <meta name="robots" content="index, follow">
    <meta name="author" content="nikahin">
    <meta name="keywords" content="undangan digital, undangan pernikahan, undangan online, wedding invitation, undangan nikah, undangan murah">
    <meta name="theme-color" content="#6b4ce6">

    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600;700&family=Great+Vibes&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #1a1a2e;
            --secondary: #2d2d44;
            --accent: #6b4ce6;
            --accent-dark: #5538d4;
            --accent-light: #8b6ff0;
            --gold: #d4af37;
            --gold-light: #f0d060;
            --light: #f8f9fd;
            --text: #1a1a2e;
            --gradient-1: #6b4ce6;
            --gradient-2: #5538d4;
            --dark-gold: #b8941e;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--light);
            color: var(--text);
            overflow-x: hidden;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: var(--light);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--accent);
            border-radius: 5px;
        }

        /* Navigation */
        nav {
            position: fixed;
            top: 0;
            width: 100%;
            padding: 20px 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
            transition: all 0.4s ease;
            background: transparent;
        }

        nav.scrolled {
            background: rgba(26, 26, 46, 0.97);
            backdrop-filter: blur(20px);
            box-shadow: 0 10px 40px rgba(107,76,230,0.2);
            padding: 15px 5%;
        }

        .logo {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            font-weight: 700;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: color 0.3s;
        }

        nav.scrolled .logo {
            color: white;
        }

        .logo i {
            color: var(--gold);
        }

        .logo-text {
            font-family: 'Great Vibes', cursive;
            font-size: 2rem;
            font-weight: 400;
            color: var(--gold-light);
            letter-spacing: 1px;
            line-height: 1;
            transition: color 0.3s;
        }

        nav.scrolled .logo-text {
            color: var(--gold-light);
        }

        .nav-links {
            display: flex;
            gap: 40px;
            list-style: none;
        }

        .nav-links a {
            text-decoration: none;
            color: rgba(255,255,255,0.9);
            font-weight: 500;
            font-size: 0.95rem;
            transition: all 0.3s;
            position: relative;
        }

        nav.scrolled .nav-links a {
            color: rgba(255,255,255,0.85);
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--gold);
            transition: width 0.3s;
        }

        .nav-links a:hover::after {
            width: 100%;
        }

        .nav-links a:hover {
            color: var(--gold);
        }

        .btn-nav {
            background: linear-gradient(135deg, var(--accent), var(--accent-dark));
            color: white !important;
            padding: 10px 25px;
            border-radius: 50px;
            transition: all 0.3s !important;
            box-shadow: 0 4px 15px rgba(107,76,230,0.4);
        }

        .btn-nav:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(107,76,230,0.5);
        }

        .btn-nav::after {
            display: none !important;
        }

        /* Hero Section */
        .hero {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--primary) 0%, #2d1b69 50%, var(--secondary) 100%);
            display: flex;
            align-items: center;
            padding: 100px 5% 50px;
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="40" fill="none" stroke="rgba(107,76,230,0.15)" stroke-width="0.5"/></svg>');
            background-size: 100px;
            animation: float 20s infinite linear;
        }

        /* Hero orbs */
        .hero::after {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(107,76,230,0.3) 0%, transparent 70%);
            top: -100px;
            right: -100px;
            border-radius: 50%;
            pointer-events: none;
        }

        @keyframes float {
            0% { transform: translateY(0) rotate(0deg); }
            100% { transform: translateY(-100px) rotate(360deg); }
        }

        .hero-container {
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
            position: relative;
            z-index: 2;
        }

        .hero-content {
            color: white;
        }

        .hero-badge {
            display: inline-block;
            background: rgba(107, 76, 230, 0.2);
            border: 1px solid rgba(107, 76, 230, 0.5);
            color: var(--accent-light);
            padding: 8px 20px;
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 30px;
            animation: fadeInUp 0.8s ease;
        }

        .hero-title {
            font-family: 'Playfair Display', serif;
            font-size: 4rem;
            line-height: 1.1;
            margin-bottom: 20px;
            animation: fadeInUp 0.8s ease 0.2s both;
        }

        .hero-title span {
            color: var(--gold-light);
            font-family: 'Great Vibes', cursive;
            font-size: 5rem;
            display: block;
            margin-top: 10px;
        }

        .hero-desc {
            font-size: 1.1rem;
            line-height: 1.8;
            opacity: 0.9;
            margin-bottom: 40px;
            max-width: 500px;
            animation: fadeInUp 0.8s ease 0.4s both;
        }

        .hero-price {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
            padding: 20px 30px;
            border-radius: 15px;
            display: inline-block;
            margin-bottom: 30px;
            animation: fadeInUp 0.8s ease 0.6s both;
        }

        .hero-price-label {
            font-size: 0.9rem;
            opacity: 0.8;
            margin-bottom: 5px;
        }

        .hero-price-amount {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--gold-light);
        }

        .hero-buttons {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            animation: fadeInUp 0.8s ease 0.8s both;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--accent), var(--accent-dark));
            color: white;
            padding: 15px 40px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s;
            border: none;
            cursor: pointer;
            font-size: 1rem;
            box-shadow: 0 8px 25px rgba(107,76,230,0.4);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(107,76,230,0.5);
        }

        .btn-secondary {
            background: transparent;
            color: white;
            padding: 15px 40px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s;
            border: 2px solid rgba(212,175,55,0.5);
        }

        .btn-secondary:hover {
            background: rgba(212,175,55,0.15);
            border-color: var(--gold);
            color: var(--gold-light);
        }

        .hero-visual {
            position: relative;
            animation: fadeIn 1s ease 0.5s both;
        }

        .phone-mockup {
            width: 100%;
            max-width: 350px;
            margin: 0 auto;
            position: relative;
        }

        .phone-frame {
            background: #1a1a1a;
            border-radius: 40px;
            padding: 15px;
            box-shadow: 0 50px 100px rgba(0,0,0,0.5);
            border: 2px solid #333;
            position: relative;
            overflow: hidden;
        }

        .phone-screen {
            background: linear-gradient(135deg, var(--accent) 0%, var(--dark-gold) 100%);
            border-radius: 30px;
            aspect-ratio: 9/19;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
            padding: 20px;
        }

        .phone-content h3 {
            font-family: 'Great Vibes', cursive;
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .phone-content p {
            font-size: 0.8rem;
            opacity: 0.9;
        }

        .floating-elements {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            pointer-events: none;
        }

        .float-card {
            position: absolute;
            background: white;
            padding: 15px 20px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text);
            animation: float-card 3s ease-in-out infinite;
        }

        .float-card:nth-child(1) {
            top: 20%;
            left: -50px;
            animation-delay: 0s;
        }

        .float-card:nth-child(2) {
            top: 50%;
            right: -30px;
            animation-delay: 1s;
        }

        .float-card:nth-child(3) {
            bottom: 20%;
            left: -30px;
            animation-delay: 2s;
        }

        .float-card i {
            color: var(--accent);
            font-size: 1.2rem;
        }

        @keyframes float-card {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* Features Section */
        .features {
            padding: 100px 5%;
            background: var(--light);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .section-header {
            text-align: center;
            margin-bottom: 80px;
        }

        .section-tag {
            color: var(--gold);
            font-weight: 600;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 3px;
            margin-bottom: 15px;
            display: block;
        }

        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: 3rem;
            color: var(--primary);
            margin-bottom: 20px;
        }

        .section-desc {
            color: #666;
            font-size: 1.1rem;
            max-width: 600px;
            margin: 0 auto;
            line-height: 1.8;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }

        .feature-card {
            background: white;
            padding: 40px;
            border-radius: 20px;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(107,76,230,0.08);
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--accent), var(--gold));
            transform: scaleX(0);
            transition: transform 0.4s;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(107,76,230,0.15);
            border-color: rgba(107,76,230,0.2);
        }

        .feature-card:hover::before {
            transform: scaleX(1);
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--accent), var(--accent-dark));
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            margin-bottom: 25px;
            transition: transform 0.3s;
            box-shadow: 0 8px 20px rgba(107,76,230,0.3);
        }

        .feature-card:hover .feature-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .feature-title {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 15px;
            color: var(--primary);
        }

        .feature-desc {
            color: #666;
            line-height: 1.7;
            font-size: 0.95rem;
        }

        /* Pricing Section */
        .pricing {
            padding: 100px 5%;
            background: linear-gradient(135deg, var(--primary) 0%, #2d1b69 100%);
            position: relative;
            overflow: hidden;
        }

        .pricing .section-tag {
            color: var(--gold);
        }

        .pricing .section-title {
            color: white;
        }

        .pricing .section-desc {
            color: rgba(255,255,255,0.7);
        }

        .pricing::before {
            content: '';
            position: absolute;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(107,76,230,0.4) 0%, transparent 70%);
            border-radius: 50%;
            top: -300px;
            right: -300px;
        }

        .pricing-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            max-width: 1000px;
            margin: 0 auto;
        }

        .pricing-card {
            background: rgba(255,255,255,0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 30px;
            padding: 40px;
            position: relative;
            transition: all 0.4s ease;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }

        .pricing-card.featured {
            transform: scale(1.05);
            border: 2px solid var(--gold);
            box-shadow: 0 20px 50px rgba(212,175,55,0.2);
            background: rgba(255,255,255,0.08);
        }

        .pricing-card.featured::before {
            content: 'POPULER';
            position: absolute;
            top: -12px;
            left: 50%;
            transform: translateX(-50%);
            background: linear-gradient(135deg, var(--gold), var(--gold-light));
            color: var(--primary);
            padding: 5px 20px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .pricing-card:hover {
            transform: translateY(-10px);
        }

        .pricing-card.featured:hover {
            transform: scale(1.05) translateY(-10px);
        }

        .pricing-name {
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
            margin-bottom: 10px;
        }

        .pricing-desc {
            color: rgba(255,255,255,0.6);
            font-size: 0.9rem;
            margin-bottom: 30px;
        }

        .pricing-price {
            font-size: 3rem;
            font-weight: 700;
            color: var(--gold-light);
            margin-bottom: 10px;
            display: flex;
            align-items: baseline;
            gap: 5px;
        }

        .pricing-price span {
            font-size: 1rem;
            color: rgba(255,255,255,0.5);
            font-weight: 400;
        }

        .pricing-original {
            text-decoration: line-through;
            color: rgba(255,255,255,0.4);
            margin-bottom: 30px;
            display: block;
        }

        .pricing-features {
            list-style: none;
            margin-bottom: 30px;
        }

        .pricing-features li {
            padding: 10px 0;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.95rem;
            color: rgba(255,255,255,0.85);
        }

        .pricing-features li i {
            color: var(--gold);
        }

        .btn-pricing {
            width: 100%;
            padding: 15px;
            border-radius: 50px;
            border: 2px solid var(--gold);
            background: transparent;
            color: var(--gold-light);
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 1rem;
        }

        .btn-pricing:hover {
            background: linear-gradient(135deg, var(--gold), var(--gold-light));
            color: var(--primary);
            border-color: transparent;
        }

        .pricing-card.featured .btn-pricing {
            background: linear-gradient(135deg, var(--gold), var(--gold-light));
            color: var(--primary);
            border-color: transparent;
        }

        .pricing-card.featured .btn-pricing:hover {
            background: linear-gradient(135deg, var(--gold-light), var(--gold));
            transform: scale(1.02);
        }

        /* Pricing extras */
        .pricing-grid {
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            max-width: 820px;
        }

        .pricing-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(107,76,230,0.15);
            border: 1px solid rgba(107,76,230,0.35);
            color: var(--accent-light);
            padding: 5px 14px;
            border-radius: 100px;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            margin-bottom: 20px;
        }

        .pricing-badge-core {
            background: rgba(212,175,55,0.15);
            border-color: rgba(212,175,55,0.4);
            color: var(--gold-light);
        }

        .pricing-icon {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, var(--accent), var(--accent-dark));
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            color: white;
            margin-bottom: 18px;
            box-shadow: 0 8px 20px rgba(107,76,230,0.35);
        }

        .pricing-icon-core {
            background: linear-gradient(135deg, var(--gold), var(--gold-light));
            color: var(--primary);
            box-shadow: 0 8px 20px rgba(212,175,55,0.35);
        }

        .pricing-card-core {
            border-color: rgba(212,175,55,0.4) !important;
            box-shadow: 0 20px 50px rgba(212,175,55,0.15) !important;
        }

        .price-currency {
            font-size: 1.2rem !important;
            font-weight: 600;
            color: rgba(255,255,255,0.6) !important;
            align-self: flex-start;
            margin-top: 8px;
        }

        .price-per {
            font-size: 0.9rem !important;
            color: rgba(255,255,255,0.45) !important;
            font-weight: 400;
            align-self: flex-end;
            margin-bottom: 4px;
        }

        .pricing-feature-highlight {
            color: var(--gold-light) !important;
            font-weight: 600;
        }

        .pricing-feature-highlight i {
            color: var(--gold) !important;
        }

        .btn-pricing-core {
            background: linear-gradient(135deg, var(--gold), var(--gold-light)) !important;
            color: var(--primary) !important;
            border-color: transparent !important;
            font-weight: 700;
        }

        .btn-pricing-core:hover {
            background: linear-gradient(135deg, var(--gold-light), var(--gold)) !important;
            transform: translateY(-2px) scale(1.02) !important;
            box-shadow: 0 12px 30px rgba(212,175,55,0.4) !important;
        }

        /* Hero price tiers */
        .hero-price-tiers {
            display: flex;
            gap: 8px;
            margin-top: 12px;
            flex-wrap: wrap;
        }

        .tier-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 12px;
            border-radius: 100px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .tier-premium {
            background: rgba(107,76,230,0.25);
            border: 1px solid rgba(107,76,230,0.5);
            color: var(--accent-light);
        }

        .tier-core {
            background: rgba(212,175,55,0.2);
            border: 1px solid rgba(212,175,55,0.5);
            color: var(--gold-light);
        }

        /* Templates Section */
        .templates {
            padding: 100px 5%;
            background: var(--light);
        }

        .templates-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 32px;
            margin-top: 60px;
            justify-items: center;
        }

        .template-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 14px;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .template-card:hover {
            transform: translateY(-8px);
        }

        /* Phone frame wrapper */
        .template-phone {
            position: relative;
            width: 160px;
            background: #1a1a2e;
            border-radius: 28px;
            padding: 10px 8px;
            box-shadow:
                0 0 0 2px #2d2d44,
                0 20px 50px rgba(107,76,230,0.25),
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
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            width: 40px;
            height: 6px;
            background: #2d2d44;
            border-radius: 3px;
            z-index: 2;
        }

        /* Side buttons */
        .template-phone::after {
            content: '';
            position: absolute;
            right: -3px;
            top: 60px;
            width: 3px;
            height: 30px;
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
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.4s ease;
        }

        .template-card:hover .template-screen img {
            transform: scale(1.04);
        }

        .template-screen-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Overlay on hover */
        .template-overlay {
            position: absolute;
            inset: 0;
            background: rgba(26, 26, 46, 0.6);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s;
            border-radius: 20px;
        }

        .template-card:hover .template-overlay {
            opacity: 1;
        }

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

        .template-card:hover .btn-preview {
            transform: translateY(0);
        }

        .btn-preview:hover {
            background: var(--accent);
            color: white;
            border-color: var(--accent);
        }

        /* Name below phone */
        .template-footer {
            text-align: center;
        }

        .template-name {
            font-family: 'Inter', sans-serif;
            font-size: 0.88rem;
            font-weight: 600;
            color: var(--primary);
            margin: 0 0 2px;
        }

        .template-style {
            font-size: 0.75rem;
            color: rgba(107,76,230,0.65);
            font-weight: 500;
        }

        /* Stats Section */
        .stats {
            padding: 80px 5%;
            background: linear-gradient(135deg, var(--accent) 0%, var(--accent-dark) 100%);
            color: white;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 40px;
            max-width: 1000px;
            margin: 0 auto;
            text-align: center;
        }

        .stat-item {
            padding: 20px;
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 700;
            color: var(--gold-light);
            margin-bottom: 10px;
            font-family: 'Playfair Display', serif;
        }

        .stat-label {
            font-size: 1rem;
            opacity: 0.9;
        }

        /* Testimonials */
        .testimonials {
            padding: 100px 5%;
            background: var(--light);
        }

        .testimonials-slider {
            max-width: 800px;
            margin: 60px auto 0;
            position: relative;
        }

        .testimonial-card {
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(107,76,230,0.1);
            border: 1px solid rgba(107,76,230,0.08);
            text-align: center;
            display: none;
            animation: fadeIn 0.5s;
        }

        .testimonial-card.active {
            display: block;
        }

        .testimonial-text {
            font-size: 1.2rem;
            line-height: 1.8;
            color: #555;
            font-style: italic;
            margin-bottom: 30px;
        }

        .testimonial-author {
            font-weight: 700;
            color: var(--accent);
            margin-bottom: 5px;
        }

        .testimonial-role {
            color: #999;
            font-size: 0.9rem;
        }

        .stars {
            color: var(--gold);
            margin-bottom: 20px;
            font-size: 1.2rem;
        }

        .slider-dots {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 30px;
        }

        .dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: rgba(107,76,230,0.2);
            cursor: pointer;
            transition: all 0.3s;
        }

        .dot.active {
            background: var(--accent);
            width: 30px;
            border-radius: 6px;
        }

        /* CTA Section */
        .cta {
            padding: 100px 5%;
            background: linear-gradient(135deg, var(--primary) 0%, #2d1b69 100%);
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .cta::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(107,76,230,0.15) 1px, transparent 1px);
            background-size: 50px 50px;
            animation: move 20s linear infinite;
        }

        .cta::after {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(212,175,55,0.1) 0%, transparent 70%);
            bottom: -100px;
            left: 50%;
            transform: translateX(-50%);
            border-radius: 50%;
            pointer-events: none;
        }

        @keyframes move {
            0% { transform: translate(0, 0); }
            100% { transform: translate(50px, 50px); }
        }

        .cta-content {
            position: relative;
            z-index: 2;
            max-width: 700px;
            margin: 0 auto;
        }

        .cta-title {
            font-family: 'Playfair Display', serif;
            font-size: 3rem;
            margin-bottom: 20px;
        }

        .cta-desc {
            font-size: 1.2rem;
            margin-bottom: 40px;
            opacity: 0.95;
        }

        .btn-cta {
            background: linear-gradient(135deg, var(--gold), var(--gold-light));
            color: var(--primary);
            padding: 18px 50px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 700;
            font-size: 1.1rem;
            display: inline-block;
            transition: all 0.3s;
            box-shadow: 0 10px 30px rgba(212,175,55,0.3);
        }

        .btn-cta:hover {
            transform: translateY(-3px);
            box-shadow: 0 20px 40px rgba(212,175,55,0.4);
        }

        /* Footer */
        footer {
            background: #0a0a18;
            color: white;
            padding: 72px 5% 0;
            border-top: 1px solid rgba(255,255,255,.06);
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1.8fr 1fr 1fr 1.2fr;
            gap: 48px;
            padding-bottom: 56px;
            border-bottom: 1px solid rgba(255,255,255,.08);
        }

        .footer-brand {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .footer-brand i {
            color: var(--accent);
        }

        .footer-desc {
            line-height: 1.8;
            opacity: 0.8;
            margin-bottom: 20px;
        }

        .social-links {
            display: flex;
            gap: 15px;
        }

        .social-links a {
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            transition: all 0.3s;
        }

        .social-links a:hover {
            background: var(--accent);
            transform: translateY(-3px);
        }

        .footer-title {
            font-weight: 700;
            margin-bottom: 20px;
            color: var(--gold-light);
        }

        .footer-links {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 12px;
        }

        .footer-links a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer-links a:hover {
            color: var(--gold);
        }

        .footer-bottom {
            border-top: none;
            padding: 24px 0;
            text-align: center;
            max-width: 1200px;
            margin: 0 auto;
            opacity: 0.55;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }

        .footer-bottom-links {
            display: flex;
            gap: 24px;
        }

        .footer-bottom-links a {
            color: rgba(255,255,255,.75);
            text-decoration: none;
            font-size: 0.82rem;
            transition: color .2s;
        }

        .footer-bottom-links a:hover {
            color: var(--gold);
            opacity: 1;
        }

        .footer-bottom-wrap {
            max-width: 1200px;
            margin: 0 auto;
        }

        .footer-contact-item {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 14px;
        }

        .footer-contact-item i {
            width: 18px;
            margin-top: 2px;
            color: var(--gold);
            font-size: 0.85rem;
            flex-shrink: 0;
        }

        .footer-contact-item a,
        .footer-contact-item span {
            color: rgba(255,255,255,.75);
            text-decoration: none;
            font-size: 0.875rem;
            line-height: 1.5;
            transition: color .2s;
            word-break: break-all;
        }

        .footer-contact-item a:hover {
            color: var(--gold);
        }

        .footer-divider {
            width: 40px;
            height: 2px;
            background: linear-gradient(to right, var(--gold), transparent);
            border-radius: 2px;
            margin-bottom: 16px;
        }

        /* Mobile Menu */
        .mobile-menu {
            display: none;
            font-size: 1.5rem;
            color: white;
            cursor: pointer;
            z-index: 1001;
        }

        nav.scrolled .mobile-menu {
            color: white;
        }

        .mobile-nav {
            position: fixed;
            top: 0;
            right: -100%;
            width: 280px;
            height: 100vh;
            background: var(--primary);
            border-left: 1px solid rgba(107,76,230,0.3);
            box-shadow: -5px 0 30px rgba(107,76,230,0.2);
            padding: 80px 30px 30px;
            transition: right 0.4s ease;
            z-index: 1000;
        }

        .mobile-nav.active {
            right: 0;
        }

        .mobile-nav ul {
            list-style: none;
        }

        .mobile-nav ul li {
            margin-bottom: 25px;
        }

        .mobile-nav ul li a {
            color: rgba(255,255,255,0.85);
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
            display: block;
            padding: 10px 0;
            transition: color 0.3s;
        }

        .mobile-nav ul li a:hover {
            color: var(--gold);
        }

        .mobile-nav .btn-nav {
            display: block;
            text-align: center;
            margin-top: 20px;
        }

        .mobile-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            background: rgba(0,0,0,0.6);
            opacity: 0;
            visibility: hidden;
            transition: all 0.4s ease;
            z-index: 999;
        }

        .mobile-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .mobile-close {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 1.8rem;
            color: rgba(255,255,255,0.7);
            cursor: pointer;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.3s;
        }

        .mobile-close:hover {
            background: rgba(107,76,230,0.3);
            color: white;
        }

        /* Responsive */
        @media (max-width: 968px) {
            .hero-container {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .hero-title {
                font-size: 2.8rem;
            }

            .hero-title span {
                font-size: 3.5rem;
            }

            .hero-desc {
                margin: 0 auto 40px;
            }

            .hero-buttons {
                justify-content: center;
            }

            .phone-mockup {
                max-width: 280px;
            }

            .float-card {
                display: none;
            }

            .nav-links {
                display: none;
            }

            .mobile-menu {
                display: block;
            }

            .section-title {
                font-size: 2.2rem;
            }

            .pricing-card.featured {
                transform: scale(1);
            }

            .footer-content {
                grid-template-columns: 1fr 1fr;
                gap: 36px;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        /* Animations on Scroll */
        .animate-on-scroll {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s ease;
        }

        .animate-on-scroll.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* Play Store Button */
        .btn-playstore {
            display: inline-flex;
            align-items: center;
            background: #09090e;
            color: white;
            padding: 8px 24px;
            border-radius: 50px;
            text-decoration: none;
            border: 2px solid rgba(255, 255, 255, 0.15);
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            height: 54px;
        }

        .btn-playstore:hover {
            background: #11111a;
            border-color: var(--accent);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(107, 76, 230, 0.4);
        }

        .btn-playstore i {
            font-size: 1.4rem;
            margin-right: 10px;
            color: #3bccff;
        }

        .btn-playstore-text {
            display: flex;
            flex-direction: column;
            text-align: left;
            line-height: 1.2;
        }

        .btn-playstore-text .download-label {
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            opacity: 0.8;
            color: rgba(255, 255, 255, 0.7);
        }

        .btn-playstore-text .store-name {
            font-size: 1.05rem;
            font-weight: 700;
        }

        /* Download App Section */
        .download-app {
            padding: 100px 5%;
            background: linear-gradient(135deg, #16162a 0%, #1c1c38 100%);
            color: white;
            position: relative;
            overflow: hidden;
            border-top: 1px solid rgba(255,255,255,0.05);
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        .download-app::before {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(107,76,230,0.2) 0%, transparent 70%);
            bottom: -200px;
            right: -200px;
            border-radius: 50%;
            pointer-events: none;
        }

        .download-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 60px;
            align-items: center;
        }

        .download-content {
            z-index: 2;
        }

        .download-title {
            font-family: 'Playfair Display', serif;
            font-size: 2.8rem;
            margin-bottom: 20px;
            line-height: 1.2;
        }

        .download-title span {
            color: var(--gold-light);
        }

        .download-desc {
            font-size: 1.1rem;
            line-height: 1.8;
            opacity: 0.9;
            margin-bottom: 30px;
        }

        .download-features-list {
            list-style: none;
            margin-bottom: 40px;
        }

        .download-features-list li {
            margin-bottom: 15px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            font-size: 1.05rem;
            opacity: 0.9;
        }

        .download-features-list li i {
            color: var(--gold);
            font-size: 1.2rem;
            margin-top: 4px;
        }

        .download-buttons {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .download-visual {
            position: relative;
            display: flex;
            justify-content: center;
            z-index: 2;
        }

        .download-phone-mockup {
            width: 100%;
            max-width: 320px;
            position: relative;
        }

        .download-phone-frame {
            background: #111;
            border-radius: 40px;
            padding: 12px;
            box-shadow: 0 30px 60px rgba(0,0,0,0.6);
            border: 2px solid #2d2d44;
        }

        .download-phone-screen {
            border-radius: 30px;
            overflow: hidden;
            aspect-ratio: 9/19;
            background: #1a1a2e;
        }

        .download-phone-screen img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        @media (max-width: 968px) {
            .download-container {
                grid-template-columns: 1fr;
                text-align: center;
            }
            .download-features-list li {
                justify-content: center;
                text-align: left;
            }
            .download-buttons {
                justify-content: center;
            }
            .download-phone-mockup {
                max-width: 260px;
                margin: 0 auto;
            }
            .download-title {
                font-size: 2.2rem;
            }
        }
    </style>
</head>
<body>

    <!-- Navigation -->
    <nav id="navbar">
        <a href="#hero" class="logo">
            <img src="{{ asset('images/logo.png') }}" alt="Nikahin Logo" style="height: 70px; width: auto;">
            <span class="logo-text">Nikahin</span>
        </a>
        <ul class="nav-links">
            <li><a href="#features">Fitur</a></li>
            <li><a href="#templates">Template</a></li>
            <li><a href="#download-app">Aplikasi</a></li>
            <li><a href="#testimonials">Testimoni</a></li>
            <li><a href="{{ route('register') }}" class="btn-nav">Daftar</a></li>
        </ul>
        <div class="mobile-menu" id="mobileMenuBtn">
            <i class="fas fa-bars"></i>
        </div>
    </nav>

    <!-- Mobile Navigation -->
    <div class="mobile-overlay" id="mobileOverlay"></div>
    <div class="mobile-nav" id="mobileNav">
        <div class="mobile-close" id="mobileClose">
            <i class="fas fa-times"></i>
        </div>
        <ul>
            <li><a href="#features" class="mobile-link">Fitur</a></li>
            <li><a href="#templates" class="mobile-link">Template</a></li>
            <li><a href="#download-app" class="mobile-link">Aplikasi</a></li>
            <li><a href="#testimonials" class="mobile-link">Testimoni</a></li>
            <li><a href="{{ route('register') }}" class="btn-nav">Daftar Sekarang</a></li>
        </ul>
    </div>

    <!-- Hero Section -->
    <section class="hero" id="hero">
        <div class="hero-container">
            <div class="hero-content">
                <div class="hero-badge">
                    <i class="fas fa-star"></i> #1 Undangan Digital Indonesia
                </div>
                <h1 class="hero-title">
                    Buat Undangan Pernikahan
                    <span>Modern & Elegan</span>
                </h1>
                <p class="hero-desc">
                    Tampilkan momen spesial Anda dengan undangan digital yang interaktif,
                    responsif, dan penuh animasi indah. Bagikan kebahagiaan dengan cara yang berbeda.
                </p>
                <div class="hero-price">
                    <div class="hero-price-label">Mulai dari</div>
                    <div class="hero-price-amount">{{ $formattedPrice }}</div>
                    <small style="color: rgba(255,255,255,0.8); display: block; margin-top: 5px;">
                    </small>
                </div>
                <div class="hero-buttons">
                    <a href="#templates" class="btn-secondary">
                        <i class="fas fa-eye"></i> Lihat Template
                    </a>
                    <a href="https://play.google.com/store/apps/details?id=com.nikahin.app" target="_blank" class="btn-playstore">
                        <i class="fab fa-google-play"></i>
                        <span class="btn-playstore-text">
                            <span class="download-label">GET IT ON</span>
                            <span class="store-name">Google Play</span>
                        </span>
                    </a>
                </div>
            </div>
            <div class="hero-visual">
                <div class="phone-mockup">
                    <div class="phone-frame">
                        <div class="phone-screen" style="padding: 0; overflow: hidden;">
                            <img src="{{ asset('images/gambar-app.jpeg') }}" alt="App Preview" style="width: 100%; height: 100%; object-fit: cover; display: block;">
                        </div>
                    </div>
                    <div class="floating-elements">
                        <div class="float-card">
                            <i class="fas fa-check-circle"></i>
                            <span>Responsif</span>
                        </div>
                        <div class="float-card">
                            <i class="fas fa-magic"></i>
                            <span>Animasi Smooth</span>
                        </div>
                        <div class="float-card">
                            <i class="fas fa-music"></i>
                            <span>Musik Latar</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="features">
        <div class="container">
            <div class="section-header animate-on-scroll">
                <span class="section-tag">Mengapa Memilih Kami</span>
                <h2 class="section-title">Fitur Unggulan</h2>
                <p class="section-desc">
                    Kami menghadirkan pengalaman terbaik dalam membuat undangan digital
                    dengan fitur-fitur modern yang akan membuat pernikahan Anda lebih berkesan.
                </p>
            </div>
            <div class="features-grid">
                <div class="feature-card animate-on-scroll">
                    <div class="feature-icon">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h3 class="feature-title">Fully Responsive</h3>
                    <p class="feature-desc">
                        Tampilan sempurna di semua perangkat, dari smartphone hingga desktop.
                        Tamu undangan dapat membuka dari mana saja.
                    </p>
                </div>
                <div class="feature-card animate-on-scroll">
                    <div class="feature-icon">
                        <i class="fas fa-paint-brush"></i>
                    </div>
                    <h3 class="feature-title">Desain Premium</h3>
                    <p class="feature-desc">
                        Puluhan template elegan dengan animasi smooth, efek parallax,
                        dan transisi yang memukau.
                    </p>
                </div>
                <div class="feature-card animate-on-scroll">
                    <div class="feature-icon">
                        <i class="fas fa-music"></i>
                    </div>
                    <h3 class="feature-title">Musik & Galeri</h3>
                    <p class="feature-desc">
                        Tambahkan backsound romantis dan galeri foto pre-wedding
                        untuk memperindah undangan Anda.
                    </p>
                </div>
                <div class="feature-card animate-on-scroll">
                    <div class="feature-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h3 class="feature-title">Countdown Timer</h3>
                    <p class="feature-desc">
                        Hitung mundur otomatis menuju hari bahagia Anda dengan tampilan
                        yang menarik dan real-time.
                    </p>
                </div>
                <div class="feature-card animate-on-scroll">
                    <div class="feature-icon">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                    <h3 class="feature-title">Google Maps</h3>
                    <p class="feature-desc">
                        Integrasi peta lokasi pernikahan yang memudahkan tamu
                        menemukan venue acara Anda.
                    </p>
                </div>
                <div class="feature-card animate-on-scroll">
                    <div class="feature-icon">
                        <i class="fas fa-paper-plane"></i>
                    </div>
                    <h3 class="feature-title">Share Mudah</h3>
                    <p class="feature-desc">
                        Bagikan undangan via WhatsApp, Instagram, atau media sosial
                        lainnya dengan satu klik saja.
                    </p>
                </div>
            </div>
        </div>
    </section>



    <!-- Templates Section -->
    <section class="templates" id="templates">
        <div class="container">
            <div class="section-header animate-on-scroll">
                <span class="section-tag">Koleksi Template</span>
                <h2 class="section-title">Pilihan Desain Eksklusif</h2>
                <p class="section-desc">
                    Berbagai tema dari yang klasik hingga modern, semua dirancang dengan
                    detail dan animasi yang memukau.
                </p>
            </div>
            <div class="templates-grid">
                @forelse($templates as $template)
                <div class="template-card animate-on-scroll">
                    <div class="template-phone">
                        <div class="template-screen">
                            @if($template->thumbnail_path && Storage::disk('public')->exists($template->thumbnail_path))
                                <img src="{{ Storage::disk('public')->url($template->thumbnail_path) }}"
                                     alt="{{ $template->name }}">
                            @else
                                <div class="template-screen-placeholder">
                                    <i class="fas fa-image" style="font-size: 2rem; color: rgba(255,255,255,0.25);"></i>
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
                <div style="grid-column: 1/-1; text-align: center; padding: 60px 0; color: #999;">
                    <i class="fas fa-images" style="font-size: 3rem; margin-bottom: 16px; display: block; opacity: 0.3;"></i>
                    <p>Belum ada template tersedia.</p>
                </div>
                @endforelse
            </div>

            @if($templates->count() > 0)
            <div style="text-align: center; margin-top: 50px;">
                <a href="{{ route('public.templates.index') }}" class="btn-primary">
                    <i class="fas fa-th"></i> Lihat Semua Template
                </a>
            </div>
            @endif
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats">
        <div class="stats-grid">
            <div class="stat-item animate-on-scroll">
                <div class="stat-number" data-target="{{ $stats['invitations'] }}">0</div>
                <div class="stat-label">Undangan Terbuat</div>
            </div>
            <div class="stat-item animate-on-scroll">
                <div class="stat-number" data-target="{{ $stats['templates'] }}">0</div>
                <div class="stat-label">Template Tersedia</div>
            </div>
            <div class="stat-item animate-on-scroll">
                <div class="stat-number" data-target="{{ $stats['views'] }}">0</div>
                <div class="stat-label">Total Views</div>
            </div>
            <div class="stat-item animate-on-scroll">
                <div class="stat-number" data-target="{{ $stats['rsvps'] }}">0</div>
                <div class="stat-label">Ucapan & Doa</div>
            </div>
        </div>
    </section>

    <!-- App Showcase Section -->
    <section class="download-app" id="download-app">
        <div class="download-container">
            <div class="download-content animate-on-scroll">
                <h2 class="download-title">Kelola Undangan Lebih Praktis dengan <span>Aplikasi Nikahin</span></h2>
                <p class="download-desc">
                    Nikmati kemudahan mengelola seluruh persiapan undangan pernikahan digital Anda secara langsung dari genggaman. Pantau tamu, edit konten, dan terima notifikasi instan.
                </p>
                <ul class="download-features-list">
                    <li>
                        <i class="fas fa-check-circle"></i>
                        <span><strong>Scan QR Code Tamu:</strong> Check-in tamu undangan secara cepat & real-time di venue pernikahan.</span>
                    </li>
                    <li>
                        <i class="fas fa-check-circle"></i>
                        <span><strong>Notifikasi Real-time:</strong> Dapatkan notifikasi instan ketika tamu mengisi kehadiran/RSVP.</span>
                    </li>
                    <li>
                        <i class="fas fa-check-circle"></i>
                        <span><strong>Kelola Musik & Galeri:</strong> Unggah foto pre-wedding dan ubah lagu latar langsung dari smartphone.</span>
                    </li>
                    <li>
                        <i class="fas fa-check-circle"></i>
                        <span><strong>Edit Instan:</strong> Koreksi nama mempelai, tanggal acara, atau peta lokasi kapan pun Anda mau.</span>
                    </li>
                </ul>
                <div class="download-buttons">
                    <a href="https://play.google.com/store/apps/details?id=com.nikahin.app" target="_blank" class="btn-playstore">
                        <i class="fab fa-google-play"></i>
                        <span class="btn-playstore-text">
                            <span class="download-label">GET IT ON</span>
                            <span class="store-name">Google Play</span>
                        </span>
                    </a>
                </div>
            </div>
            <div class="download-visual animate-on-scroll">
                <div class="download-phone-mockup">
                    <div class="download-phone-frame">
                        <div class="download-phone-screen">
                            <img src="{{ asset('images/gambar-app.jpeg') }}" alt="Nikahin App Mobile Screen">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="testimonials" id="testimonials">
        <div class="container">
            <div class="section-header animate-on-scroll">
                <span class="section-tag">Testimoni</span>
                <h2 class="section-title">Apa Kata Mereka?</h2>
            </div>
            <div class="testimonials-slider">
                <div class="testimonial-card active">
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="testimonial-text">
                        "Undangannya sangat bagus dan animasinya smooth banget! Tamu-tamu
                        pada bilang undangan digitalnya elegan dan mudah diakses. Highly recommended!"
                    </p>
                    <div class="testimonial-author">Rina & Andi</div>
                    <div class="testimonial-role">Jakarta</div>
                </div>
                <div class="testimonial-card">
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="testimonial-text">
                        "Pelayanan cepat dan hasilnya memuaskan. Harga 65rb dapat undangan
                        sebagus ini, worth it banget! Terima kasih nikahin."
                    </p>
                    <div class="testimonial-author">Dewi & Budi</div>
                    <div class="testimonial-role">Bandung</div>
                </div>
                <div class="testimonial-card">
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="testimonial-text">
                        "Desain custom yang kami minta dibuat dengan sangat baik. Timnya
                        responsif dan sabar dalam merevisi. Sukses terus!"
                    </p>
                    <div class="testimonial-author">Sinta & Joko</div>
                    <div class="testimonial-role">Surabaya</div>
                </div>
                <div class="slider-dots">
                    <span class="dot active" onclick="currentSlide(0)"></span>
                    <span class="dot" onclick="currentSlide(1)"></span>
                    <span class="dot" onclick="currentSlide(2)"></span>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta" id="contact">
        <div class="cta-content">
            <h2 class="cta-title">Siap Membuat Undangan Digital?</h2>
            <p class="cta-desc">
                Jadikan momen pernikahan Anda lebih berkesan dengan undangan digital
                yang modern dan elegan. Pesan sekarang dan dapatkan promo spesial!
            </p>
            <a href="https://wa.me/6282342742787" class="btn-cta">
                <i class="fab fa-whatsapp"></i> Chat WhatsApp Sekarang
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            {{-- Col 1: Brand --}}
            <div>
                <div class="footer-brand">
                    <img src="{{ asset('images/logo.png') }}" alt="Nikahin Logo" style="height: 64px; width: auto;">
                </div>
                <div class="footer-divider"></div>
                <p class="footer-desc">
                    Platform undangan pernikahan digital untuk pasangan modern Indonesia.
                    Buat, kelola, dan bagikan undangan dalam hitungan menit.
                </p>
                <div class="social-links" style="margin-top: 20px;">
                    <a href="https://instagram.com/nikahin.id" target="_blank" rel="noopener" aria-label="Instagram">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="https://wa.me/6281394454900" target="_blank" rel="noopener" aria-label="WhatsApp">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                    <a href="https://tiktok.com/@nikahin.id" target="_blank" rel="noopener" aria-label="TikTok">
                        <i class="fab fa-tiktok"></i>
                    </a>
                    <a href="https://youtube.com/@nikahin" target="_blank" rel="noopener" aria-label="YouTube">
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>
                <div style="margin-top: 24px;">
                    <a href="https://play.google.com/store/apps/details?id=com.nikahin.app" target="_blank" rel="noopener"
                       class="btn-playstore"
                       style="height:auto;padding:9px 20px;border-radius:10px;border:1px solid rgba(255,255,255,.15);display:inline-flex;">
                        <i class="fab fa-google-play" style="font-size:1.1rem;margin-right:10px;color:#3bccff;"></i>
                        <span class="btn-playstore-text">
                            <span class="download-label" style="font-size:.5rem;letter-spacing:1px;">GET IT ON</span>
                            <span class="store-name" style="font-size:.88rem;font-weight:600;">Google Play</span>
                        </span>
                    </a>
                </div>
            </div>

            {{-- Col 2: Fitur --}}
            <div>
                <h4 class="footer-title">Fitur Aplikasi</h4>
                <div class="footer-divider"></div>
                <ul class="footer-links">
                    <li><a href="#"><i class="fas fa-plus-circle" style="margin-right:8px;opacity:.5;font-size:.75rem;"></i>Buat Undangan</a></li>
                    <li><a href="#"><i class="fas fa-users" style="margin-right:8px;opacity:.5;font-size:.75rem;"></i>Manajemen Tamu</a></li>
                    <li><a href="#"><i class="fas fa-qrcode" style="margin-right:8px;opacity:.5;font-size:.75rem;"></i>Scan QR Check-in</a></li>
                    <li><a href="#"><i class="fab fa-whatsapp" style="margin-right:8px;opacity:.5;font-size:.75rem;"></i>WA Blast Tamu</a></li>
                    <li><a href="#"><i class="fas fa-images" style="margin-right:8px;opacity:.5;font-size:.75rem;"></i>Galeri Foto</a></li>
                    <li><a href="#"><i class="fas fa-chart-bar" style="margin-right:8px;opacity:.5;font-size:.75rem;"></i>Statistik Kunjungan</a></li>
                </ul>
            </div>

            {{-- Col 3: Informasi --}}
            <div>
                <h4 class="footer-title">Informasi</h4>
                <div class="footer-divider"></div>
                <ul class="footer-links">
                    <li><a href="/templates"><i class="fas fa-palette" style="margin-right:8px;opacity:.5;font-size:.75rem;"></i>Template Undangan</a></li>
                    <li><a href="/guide"><i class="fas fa-book-open" style="margin-right:8px;opacity:.5;font-size:.75rem;"></i>Panduan Penggunaan</a></li>
                    <li><a href="/terms"><i class="fas fa-file-alt" style="margin-right:8px;opacity:.5;font-size:.75rem;"></i>Ketentuan Layanan</a></li>
                    <li><a href="/privacy"><i class="fas fa-shield-alt" style="margin-right:8px;opacity:.5;font-size:.75rem;"></i>Kebijakan Privasi</a></li>
                </ul>
            </div>

            {{-- Col 4: Kontak --}}
            <div>
                <h4 class="footer-title">Hubungi Kami</h4>
                <div class="footer-divider"></div>
                <div class="footer-contact-item">
                    <i class="fab fa-whatsapp"></i>
                    <a href="https://wa.me/6281394454900" target="_blank" rel="noopener">0813-9445-4900</a>
                </div>
                <div class="footer-contact-item">
                    <i class="fas fa-envelope"></i>
                    <a href="mailto:pamudanyiptakarya@gmail.com">pamudanyiptakarya@gmail.com</a>
                </div>
                <div class="footer-contact-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>Tasikmalaya, Jawa Barat, Indonesia</span>
                </div>
                <div class="footer-contact-item" style="margin-top: 8px;">
                    <i class="fas fa-clock"></i>
                    <span>Senin – Sabtu, 08.00 – 21.00 WIB</span>
                </div>
            </div>
        </div>

        {{-- Bottom bar --}}
        <div style="max-width:1200px;margin:0 auto;padding:22px 0;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;opacity:.5;font-size:.82rem;">
            <span>&copy; {{ date('Y') }} Nikahin &mdash; Dibuat dengan ❤️ di Indonesia</span>
            <div style="display:flex;gap:20px;">
                <a href="/terms" style="color:rgba(255,255,255,.8);text-decoration:none;">Ketentuan Layanan</a>
                <a href="/privacy" style="color:rgba(255,255,255,.8);text-decoration:none;">Kebijakan Privasi</a>
            </div>
        </div>
    </footer>

    <script>
        // Mobile Menu Toggle
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileNav = document.getElementById('mobileNav');
        const mobileOverlay = document.getElementById('mobileOverlay');
        const mobileClose = document.getElementById('mobileClose');
        const mobileLinks = document.querySelectorAll('.mobile-link');

        function openMobileMenu() {
            mobileNav.classList.add('active');
            mobileOverlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeMobileMenu() {
            mobileNav.classList.remove('active');
            mobileOverlay.classList.remove('active');
            document.body.style.overflow = '';
        }

        mobileMenuBtn.addEventListener('click', openMobileMenu);
        mobileClose.addEventListener('click', closeMobileMenu);
        mobileOverlay.addEventListener('click', closeMobileMenu);

        // Close mobile menu when clicking a link
        mobileLinks.forEach(link => {
            link.addEventListener('click', () => {
                closeMobileMenu();
            });
        });

        // Navbar Scroll Effect
        window.addEventListener('scroll', () => {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Intersection Observer for Animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');

                    // Animate numbers if it's a stat number
                    if (entry.target.querySelector('.stat-number')) {
                        animateNumber(entry.target.querySelector('.stat-number'));
                    }
                }
            });
        }, observerOptions);

        document.querySelectorAll('.animate-on-scroll').forEach(el => {
            observer.observe(el);
        });

        // Number Animation
        function animateNumber(element) {
            const target = parseInt(element.getAttribute('data-target'));
            const duration = 2000;
            const step = target / (duration / 16);
            let current = 0;

            const timer = setInterval(() => {
                current += step;
                if (current >= target) {
                    element.textContent = target + (target === 99 ? '%' : '+');
                    clearInterval(timer);
                } else {
                    element.textContent = Math.floor(current) + (target === 99 ? '%' : '+');
                }
            }, 16);
        }

        // Testimonial Slider
        let currentIndex = 0;
        const slides = document.querySelectorAll('.testimonial-card');
        const dots = document.querySelectorAll('.dot');

        function currentSlide(index) {
            showSlide(index);
        }

        function showSlide(index) {
            slides.forEach(slide => slide.classList.remove('active'));
            dots.forEach(dot => dot.classList.remove('active'));

            slides[index].classList.add('active');
            dots[index].classList.add('active');
            currentIndex = index;
        }

        // Auto slide
        setInterval(() => {
            currentIndex = (currentIndex + 1) % slides.length;
            showSlide(currentIndex);
        }, 5000);

        // Smooth Scroll for Navigation Links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add parallax effect to hero
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const hero = document.querySelector('.hero');
            if (hero) {
                hero.style.backgroundPositionY = scrolled * 0.5 + 'px';
            }
        });
    </script>
</body>
</html>
