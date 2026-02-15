@php
    use Illuminate\Support\Facades\Storage;
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>nikahin - Undangan Digital Premium | Mulai 50rb</title>
    <meta name="description" content="Buat undangan pernikahan digital yang modern, elegan, dan interaktif. Mulai dari Rp 50.000 dengan 9+ template premium. Bagikan kebahagiaan Anda dengan cara yang berbeda.">

    <!-- Canonical URL -->
    <meta property="og:url" content="{{ url('/') }}">
    <link rel="canonical" href="{{ url('/') }}">

    <!-- Open Graph / Facebook / WhatsApp -->
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="nikahin">
    <meta property="og:title" content="nikahin - Undangan Digital Premium | Mulai 50rb">
    <meta property="og:description" content="Buat undangan pernikahan digital yang modern, elegan, dan interaktif. Mulai dari Rp 50.000 dengan 9+ template premium. Bagikan kebahagiaan Anda dengan cara yang berbeda.">
    <meta property="og:image" content="{{ asset('images/logo.png') }}">
    <meta property="og:image:secure_url" content="{{ asset('images/logo.png') }}">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="nikahin - Undangan Digital Premium">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@nikahin">
    <meta name="twitter:title" content="nikahin - Undangan Digital Premium | Mulai 50rb">
    <meta name="twitter:description" content="Buat undangan pernikahan digital yang modern, elegan, dan interaktif. Mulai dari Rp 50.000 dengan 9+ template premium.">
    <meta name="twitter:image" content="{{ asset('images/logo.png') }}">

    <!-- WhatsApp Specific -->
    <meta property="og:locale" content="id_ID">

    <!-- Additional Meta Tags -->
    <meta name="robots" content="index, follow">
    <meta name="author" content="nikahin">
    <meta name="keywords" content="undangan digital, undangan pernikahan, undangan online, wedding invitation, undangan nikah, undangan murah">
    <meta name="theme-color" content="#d4af37">

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
            --primary: #1a1410;
            --secondary: #2d2416;
            --accent: #d4af37;
            --gold: #f4e4c1;
            --light: #faf8f3;
            --text: #2d2d2d;
            --gradient-1: #d4af37;
            --gradient-2: #b8941e;
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
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
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
            color: var(--primary);
        }

        .logo i {
            color: var(--accent);
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
            color: var(--text);
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--accent);
            transition: width 0.3s;
        }

        .nav-links a:hover::after {
            width: 100%;
        }

        .nav-links a:hover {
            color: var(--accent);
        }

        .btn-nav {
            background: var(--accent);
            color: white !important;
            padding: 10px 25px;
            border-radius: 50px;
            transition: all 0.3s !important;
        }

        .btn-nav:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(212, 175, 55, 0.3);
        }

        .btn-nav::after {
            display: none !important;
        }

        /* Hero Section */
        .hero {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
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
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="40" fill="none" stroke="rgba(255,255,255,0.05)" stroke-width="0.5"/></svg>');
            background-size: 100px;
            animation: float 20s infinite linear;
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
            background: rgba(212, 175, 55, 0.2);
            border: 1px solid var(--accent);
            color: var(--accent);
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
            color: var(--gold);
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
            color: var(--gold);
        }

        .hero-buttons {
            display: flex;
            gap: 20px;
            animation: fadeInUp 0.8s ease 0.8s both;
        }

        .btn-primary {
            background: var(--accent);
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
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(212, 175, 55, 0.4);
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
            border: 2px solid rgba(255,255,255,0.3);
        }

        .btn-secondary:hover {
            background: white;
            color: var(--primary);
            border-color: white;
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
            background: white;
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
            color: var(--accent);
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
            background: var(--light);
            padding: 40px;
            border-radius: 20px;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
            border: 1px solid transparent;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, var(--accent), var(--gold));
            transform: scaleX(0);
            transition: transform 0.4s;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            border-color: rgba(233, 69, 96, 0.1);
        }

        .feature-card:hover::before {
            transform: scaleX(1);
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--accent), var(--dark-gold));
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            margin-bottom: 25px;
            transition: transform 0.3s;
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
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            position: relative;
            overflow: hidden;
        }

        .pricing::before {
            content: '';
            position: absolute;
            width: 600px;
            height: 600px;
            background: var(--accent);
            border-radius: 50%;
            opacity: 0.05;
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
            background: white;
            border-radius: 30px;
            padding: 40px;
            position: relative;
            transition: all 0.4s ease;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .pricing-card.featured {
            transform: scale(1.05);
            border: 2px solid var(--accent);
            box-shadow: 0 20px 50px rgba(212, 175, 55, 0.3);
        }

        .pricing-card.featured::before {
            content: 'POPULER';
            position: absolute;
            top: -12px;
            left: 50%;
            transform: translateX(-50%);
            background: var(--accent);
            color: white;
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
            color: var(--primary);
            margin-bottom: 10px;
        }

        .pricing-desc {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 30px;
        }

        .pricing-price {
            font-size: 3rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 10px;
            display: flex;
            align-items: baseline;
            gap: 5px;
        }

        .pricing-price span {
            font-size: 1rem;
            color: #999;
            font-weight: 400;
        }

        .pricing-original {
            text-decoration: line-through;
            color: #999;
            margin-bottom: 30px;
            display: block;
        }

        .pricing-features {
            list-style: none;
            margin-bottom: 30px;
        }

        .pricing-features li {
            padding: 10px 0;
            border-bottom: 1px solid #eee;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.95rem;
        }

        .pricing-features li i {
            color: var(--accent);
        }

        .btn-pricing {
            width: 100%;
            padding: 15px;
            border-radius: 50px;
            border: 2px solid var(--accent);
            background: transparent;
            color: var(--accent);
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 1rem;
        }

        .btn-pricing:hover {
            background: var(--accent);
            color: white;
        }

        .pricing-card.featured .btn-pricing {
            background: var(--accent);
            color: white;
        }

        .pricing-card.featured .btn-pricing:hover {
            background: var(--dark-gold);
            transform: scale(1.02);
        }

        /* Templates Section */
        .templates {
            padding: 100px 5%;
            background: white;
        }

        .templates-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
            margin-top: 60px;
        }

        .template-card {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.4s ease;
            position: relative;
            cursor: pointer;
        }

        .template-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 30px 60px rgba(0,0,0,0.2);
        }

        .template-preview {
            height: 400px;
            background: linear-gradient(135deg, var(--accent) 0%, var(--dark-gold) 100%);
            position: relative;
            overflow: hidden;
        }

        .template-preview::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom, transparent 50%, rgba(0,0,0,0.7));
        }

        .template-info {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            padding: 30px;
            color: white;
            transform: translateY(20px);
            opacity: 0;
            transition: all 0.3s;
        }

        .template-card:hover .template-info {
            transform: translateY(0);
            opacity: 1;
        }

        .template-name {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            margin-bottom: 5px;
        }

        .template-style {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .template-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(212, 175, 55, 0.95);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .template-card:hover .template-overlay {
            opacity: 1;
        }

        .btn-preview {
            background: white;
            color: var(--accent);
            padding: 12px 30px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transform: scale(0.8);
            transition: transform 0.3s;
            border: 2px solid var(--accent);
        }

        .template-card:hover .btn-preview {
            transform: scale(1);
        }

        /* Stats Section */
        .stats {
            padding: 80px 5%;
            background: var(--primary);
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
            color: var(--gold);
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
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
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
            color: var(--primary);
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
            background: #ddd;
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
            background: linear-gradient(135deg, var(--accent) 0%, var(--dark-gold) 100%);
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
            background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
            background-size: 50px 50px;
            animation: move 20s linear infinite;
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
            background: white;
            color: var(--accent);
            padding: 18px 50px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 700;
            font-size: 1.1rem;
            display: inline-block;
            transition: all 0.3s;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }

        .btn-cta:hover {
            transform: translateY(-3px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        }

        /* Footer */
        footer {
            background: var(--primary);
            color: white;
            padding: 60px 5% 30px;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 60px;
            margin-bottom: 40px;
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
            color: var(--gold);
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
            color: var(--accent);
        }

        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.1);
            padding-top: 30px;
            text-align: center;
            opacity: 0.7;
            font-size: 0.9rem;
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
            color: var(--primary);
        }

        .mobile-nav {
            position: fixed;
            top: 0;
            right: -100%;
            width: 280px;
            height: 100vh;
            background: white;
            box-shadow: -5px 0 20px rgba(0,0,0,0.1);
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
            color: var(--text);
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
            display: block;
            padding: 10px 0;
            transition: color 0.3s;
        }

        .mobile-nav ul li a:hover {
            color: var(--accent);
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
            background: rgba(0,0,0,0.5);
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
            color: var(--text);
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
            background: var(--light);
            color: var(--accent);
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
                grid-template-columns: 1fr;
                gap: 40px;
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
    </style>
</head>
<body>

    <!-- Navigation -->
    <nav id="navbar">
        <a href="#" class="logo">
            <img src="{{ asset('images/logo.png') }}" alt="Nikahin Logo" style="height: 70px; width: auto;">
        </a>
        <ul class="nav-links">
            <li><a href="#features">Fitur</a></li>
            <li><a href="#templates">Template</a></li>
            <li><a href="#pricing">Harga</a></li>
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
            <li><a href="#pricing" class="mobile-link">Harga</a></li>
            <li><a href="#testimonials" class="mobile-link">Testimoni</a></li>
            <li><a href="{{ route('register') }}" class="btn-nav">Daftar Sekarang</a></li>
        </ul>
    </div>

    <!-- Hero Section -->
    <section class="hero">
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
                    <div class="hero-price-amount">Rp 50.000</div>
                    <small style="color: rgba(255,255,255,0.8); display: block; margin-top: 5px;">
                        <i class="fas fa-heart"></i> Lebih? Se-ikhlas-nya!
                    </small>
                </div>
                <div class="hero-buttons">
                    <a href="https://wa.me/6281394454900" target="_blank" class="btn-primary">
                        <i class="fas fa-rocket"></i> Pesan Sekarang
                    </a>
                    <a href="#templates" class="btn-secondary">
                        <i class="fas fa-eye"></i> Lihat Template
                    </a>
                </div>
            </div>
            <div class="hero-visual">
                <div class="phone-mockup">
                    <div class="phone-frame">
                        <div class="phone-screen">
                            <i class="fas fa-heart" style="font-size: 3rem; margin-bottom: 20px;"></i>
                            <div class="phone-content">
                                <h3>Dian & Anisa</h3>
                                <p>29 Maret 2026</p>
                            </div>
                            <div style="margin-top: 30px; font-size: 0.7rem; opacity: 0.8;">
                                <i class="fas fa-scroll"></i> Scroll untuk membuka
                            </div>
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

    <!-- Pricing Section -->
    <section class="pricing" id="pricing">
        <div class="container">
            <div class="section-header animate-on-scroll">
                <span class="section-tag">Paket Harga</span>
                <h2 class="section-title">Pilih Paket Sesuai Kebutuhan</h2>
                <p class="section-desc">
                    Harga terjangkau dengan kualitas premium. Semua paket sudah termasuk
                    hosting dan domain custom.
                </p>
            </div>
            <div class="pricing-grid">
                <div class="pricing-card featured animate-on-scroll">
                    <h3 class="pricing-name">Paket Premium</h3>
                    <p class="pricing-desc">Fitur lengkap dengan harga terjangkau</p>
                    <div class="pricing-price">
                        50rb <span>/ undangan</span>
                    </div>
                    <div class="alert alert-success" style="margin: 10px 0; padding: 8px 12px; background: rgba(40, 167, 69, 0.1); border: 1px solid rgba(40, 167, 69, 0.3); border-radius: 8px;">
                        <small class="font-weight-bold" style="color: #28a745;">
                            <i class="fas fa-heart"></i> Lebih dari 50rb? Se-ikhlas-nya!
                        </small>
                    </div>
                    <ul class="pricing-features">
                        <li><i class="fas fa-check"></i> 18+ Pilihan Template</li>
                        <li><i class="fas fa-check"></i> Informasi Mempelai Lengkap</li>
                        <li><i class="fas fa-check"></i> Detail Acara (Akad & Resepsi)</li>
                        <li><i class="fas fa-check"></i> Google Maps Terintegrasi</li>
                        <li><i class="fas fa-check"></i> Musik Latar</li>
                        <li><i class="fas fa-check"></i> Countdown Timer</li>
                        <li><i class="fas fa-check"></i> Galeri Foto Unlimited</li>
                        <li><i class="fas fa-check"></i> RSVP & Ucapan</li>
                        <li><i class="fas fa-check"></i> Kelola Tamu</li>
                        <li><i class="fas fa-check"></i> Statistik Views</li>
                        <li><i class="fas fa-check"></i> Custom Domain</li>
                    </ul>
                    <a href="https://wa.me/6281394454900" target="_blank" class="btn-pricing">Pesan Sekarang</a>
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
                    <div class="template-preview" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center;">
                        @if($template->thumbnail_path && Storage::disk('public')->exists($template->thumbnail_path))
                            <img src="{{ Storage::disk('public')->url($template->thumbnail_path) }}"
                                 alt="{{ $template->name }}"
                                 style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <i class="fas fa-image" style="font-size: 4rem; color: rgba(255,255,255,0.3);"></i>
                        @endif
                        <div class="template-info">
                            <h4 class="template-name">{{ $template->name }}</h4>
                            <p class="template-style">{{ Str::limit($template->description, 50) }}</p>
                        </div>
                        <div class="template-overlay">
                            <a href="{{ route('public.templates.preview', $template->id) }}" class="btn-preview">Preview</a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center">
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
            <a href="https://wa.me/6281394454900" class="btn-cta">
                <i class="fab fa-whatsapp"></i> Chat WhatsApp Sekarang
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div>
                <div class="footer-brand">
                    <img src="{{ asset('images/logo.png') }}" alt="Nikahin Logo" style="height: 80px; width: auto;">
                </div>
                <p class="footer-desc">
                    Jasa pembuatan undangan digital premium dengan harga terjangkau.
                    Kami berkomitmen memberikan yang terbaik untuk momen spesial Anda.
                </p>
                <div class="social-links">
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-tiktok"></i></a>
                    <a href="#"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
            <div>
                <h4 class="footer-title">Layanan</h4>
                <ul class="footer-links">
                    <li><a href="#">Undangan Basic</a></li>
                    <li><a href="#">Undangan Premium</a></li>
                    <li><a href="#">Undangan Exclusive</a></li>
                    <li><a href="#">Custom Design</a></li>
                </ul>
            </div>
            <div>
                <h4 class="footer-title">Perusahaan</h4>
                <ul class="footer-links">
                    <li><a href="#">Tentang Kami</a></li>
                    <li><a href="#">Portfolio</a></li>
                    <li><a href="#">Blog</a></li>
                    <li><a href="#">Karir</a></li>
                </ul>
            </div>
            <div>
                <h4 class="footer-title">Kontak</h4>
                <ul class="footer-links">
                    <li><a href="#"><i class="fab fa-whatsapp"></i> 0813-9445-4900</a></li>
                    <li><a href="#"><i class="fas fa-envelope"></i> support@nikahin.id</a></li>
                    <li><a href="#"><i class="fas fa-map-marker-alt"></i> Jakarta, Indonesia</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2026 nikahin. All rights reserved. | Dibuat dengan Dunia Karya di Indonesia</p>
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
