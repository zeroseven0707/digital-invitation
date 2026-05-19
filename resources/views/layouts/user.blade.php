<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard' }} - NIKAHIN</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- AdminLTE -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    @stack('styles')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="{{ route('dashboard') }}" class="nav-link">Dashboard</a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <!-- Quick Actions -->
            <li class="nav-item">
                <a href="{{ route('invitations.create') }}" class="nav-link font-weight-bold" style="color: #6b4ce6 !important;" title="Buat Undangan Baru">
                    <i class="fas fa-plus-circle"></i>
                    <span class="d-none d-md-inline ml-1">Buat Undangan</span>
                </a>
            </li>

            <!-- Notifications Dropdown (placeholder) -->
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#" title="Notifikasi">
                    <i class="far fa-bell"></i>
                    @if(Auth::check() && Auth::user()->invitations()->where('status', 'draft')->count() > 0)
                        <span class="badge badge-warning navbar-badge">{{ Auth::user()->invitations()->where('status', 'draft')->count() }}</span>
                    @endif
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <span class="dropdown-item dropdown-header">Notifikasi</span>
                    <div class="dropdown-divider"></div>
                    @if(Auth::check() && Auth::user()->invitations()->where('status', 'draft')->count() > 0)
                        <a href="{{ route('dashboard') }}" class="dropdown-item">
                            <i class="fas fa-envelope mr-2"></i> {{ Auth::user()->invitations()->where('status', 'draft')->count() }} undangan draft
                        </a>
                    @else
                        <span class="dropdown-item text-muted">Tidak ada notifikasi</span>
                    @endif
                </div>
            </li>

            <!-- User Dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-user"></i>
                    <span class="d-none d-md-inline ml-1">{{ Auth::user()->name }}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <a href="{{ route('profile.edit') }}" class="dropdown-item">
                        <i class="fas fa-user-edit mr-2"></i> Profil Saya
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('dashboard') }}" class="dropdown-item">
                        <i class="fas fa-envelope mr-2"></i> Undangan Saya
                        <span class="float-right text-muted text-sm">{{ Auth::user()->invitations()->count() }}</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <i class="fas fa-sign-out-alt mr-2"></i> Logout
                        </button>
                    </form>
                </div>
            </li>
        </ul>
    </nav>

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
         <a href="{{ route('dashboard') }}" class="brand-link text-center">
            <img src="{{ asset('images/logo.png') }}" alt="NIKAHIN Logo" style="max-height: 130px; width: auto; display: inline-block; vertical-align: middle;">
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <!-- Dashboard -->
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Dashboard
                                @if(Auth::check() && Auth::user()->invitations()->count() > 0)
                                    <span class="badge badge-info right">{{ Auth::user()->invitations()->count() }}</span>
                                @endif
                            </p>
                        </a>
                    </li>

                    <!-- Template -->
                    <li class="nav-item">
                        <a href="{{ route('templates.index') }}" class="nav-link {{ request()->routeIs('templates.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-palette"></i>
                            <p>Template</p>
                        </a>
                    </li>

                    <!-- Guide -->
                    <li class="nav-item">
                        <a href="{{ route('guide') }}" class="nav-link {{ request()->routeIs('guide') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-book"></i>
                            <p>Panduan</p>
                        </a>
                    </li>

                    <li class="nav-header">PENGATURAN</li>

                    <!-- Profile -->
                    <li class="nav-item">
                        <a href="{{ route('profile.edit') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-cog"></i>
                            <p>Profil Saya</p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <!-- Content Header -->
        @if(isset($header))
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 font-weight-bold" style="color: #1a1a2e; font-size: 1.8rem; font-family: 'Source Sans Pro', sans-serif;">{{ $header }}</h1>
                    </div>
                    @if(isset($breadcrumbs))
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            {{ $breadcrumbs }}
                        </ol>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" style="border-radius: 12px; border: none; box-shadow: 0 4px 12px rgba(40,167,69,0.15);">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" style="border-radius: 12px; border: none; box-shadow: 0 4px 12px rgba(220,53,69,0.15);">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
                </div>
                @endif

                {{ $slot }}
            </div>
        </section>
    </div>

    <!-- Footer -->
    <footer class="main-footer bg-white" style="border-top: 1px solid #e2e8f0; font-size: 0.9rem;">
        <strong>Copyright &copy; {{ date('Y') }} <a href="{{ route('dashboard') }}" style="color: #6b4ce6; font-weight: 600;">NIKAHIN</a>.</strong>
        All rights reserved.
        <div class="float-right d-none d-sm-inline-block">
            <b>Version</b> 1.0.0
        </div>
    </footer>
</div>

<!-- Floating WhatsApp Button -->
<a href="https://wa.me/6282342742787" target="_blank" class="whatsapp-float" title="Hubungi Kami via WhatsApp">
    <i class="fab fa-whatsapp"></i>
</a>

<style>
    /* User Dashboard Core Theme Styling Overrides */
    @media (min-width: 992px) {
        .sidebar-mini.sidebar-collapse .content-wrapper,
        .sidebar-mini.sidebar-collapse .main-header,
        .sidebar-mini.sidebar-collapse .main-footer {
            margin-left: 4.6rem !important;
        }
        .sidebar-mini:not(.sidebar-collapse) .content-wrapper,
        .sidebar-mini:not(.sidebar-collapse) .main-header,
        .sidebar-mini:not(.sidebar-collapse) .main-footer {
            margin-left: 250px !important;
        }
        .main-sidebar {
            width: 250px !important;
        }
    }

    .brand-link {
        font-size: 1.5rem;
        font-weight: 600;
        background: linear-gradient(135deg, #2d1b69 0%, #1a103c 100%) !important;
        border-bottom: 1px solid rgba(255,255,255,0.08) !important;
        padding: 18px 10px !important;
    }
    .main-sidebar {
        background: linear-gradient(180deg, #2d1b69 0%, #1a103c 100%) !important;
        border-right: 1px solid rgba(255, 255, 255, 0.05) !important;
    }
    .sidebar {
        background: transparent !important;
    }
    
    /* Top Navigation Bar Styling */
    .main-header {
        background: white !important;
        border-bottom: 1px solid #e2e8f0 !important;
        padding: 8px 16px !important;
    }
    .main-header .nav-link {
        color: #64748b !important;
        font-weight: 500;
        transition: color 0.3s;
    }
    .main-header .nav-link:hover {
        color: #6b4ce6 !important;
    }
    
    /* Sidebar active link background */
    .sidebar-dark-primary .nav-sidebar>.nav-item>.nav-link.active {
        background: linear-gradient(135deg, #f0d060 0%, #d4af37 100%) !important;
        color: #1a103c !important;
        font-weight: 700;
        box-shadow: 0 6px 18px rgba(240, 208, 96, 0.35) !important;
        border-radius: 12px;
    }
    .content-wrapper {
        background-color: #f8fafc;
        padding-bottom: 30px;
    }
    
    /* Active Sidebar Badges Customization */
    .nav-sidebar .nav-item>.nav-link.active .badge {
        background-color: #1a103c !important;
        color: #f0d060 !important;
    }
    .small-box {
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(107, 76, 230, 0.06) !important;
        border: 1px solid rgba(107,76,230,0.04);
        overflow: hidden;
        transition: all 0.3s ease;
    }
    .small-box:hover {
        transform: translateY(-4px);
        box-shadow: 0 15px 35px rgba(107, 76, 230, 0.1) !important;
    }
    .card {
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.02) !important;
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }
    .card-header {
        background-color: white !important;
        border-bottom: 1px solid #f1f5f9 !important;
        padding: 16px 20px !important;
    }
    .info-box {
        border-radius: 16px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.015);
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    /* Custom Purple Theme Overrides */
    :root {
        --accent: #6b4ce6;
        --accent-hover: #5538d4;
    }

    .btn-primary {
        background: linear-gradient(135deg, #6b4ce6 0%, #5538d4 100%) !important;
        border: none !important;
        transition: all 0.3s ease;
        box-shadow: 0 4px 10px rgba(107, 76, 230, 0.2);
        border-radius: 10px;
        padding: 10px 20px;
        font-weight: 600;
    }

    .btn-primary:hover, .btn-primary:focus, .btn-primary:active {
        background: linear-gradient(135deg, #5538d4 0%, #462eb5 100%) !important;
        box-shadow: 0 6px 18px rgba(107, 76, 230, 0.35) !important;
        transform: translateY(-1px);
    }

    .btn-info {
        background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%) !important;
        border: none !important;
        border-radius: 10px;
        font-weight: 600;
    }
    .btn-info:hover {
        background: linear-gradient(135deg, #0369a1 0%, #075985 100%) !important;
    }
    
    .btn-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
        border: none !important;
        border-radius: 10px;
        font-weight: 600;
    }
    .btn-success:hover {
        background: linear-gradient(135deg, #059669 0%, #047857 100%) !important;
    }

    a {
        color: var(--accent);
    }
    a:hover {
        color: var(--accent-hover);
    }

    .badge-primary, .badge-info {
        background-color: #6b4ce6 !important;
        padding: 5px 10px;
        border-radius: 50px;
        font-weight: 600;
    }
    
    .badge-success {
        background-color: #10b981 !important;
        padding: 5px 10px;
        border-radius: 50px;
        font-weight: 600;
    }

    .card-primary.card-outline {
        border-top: 3px solid var(--accent) !important;
    }

    .form-control {
        border-radius: 10px;
        border: 1px solid #cbd5e1;
        padding: 10px 16px;
        transition: all 0.3s;
    }

    .form-control:focus {
        border-color: var(--accent) !important;
        box-shadow: 0 0 0 4px rgba(107, 76, 230, 0.15) !important;
    }

    /* Sidebar item styling hover */
    .nav-sidebar .nav-item>.nav-link {
        transition: all 0.3s;
        border-radius: 10px;
        margin-bottom: 5px;
        color: #cbd5e1 !important;
    }
    .nav-sidebar .nav-item>.nav-link:hover {
        background-color: rgba(255,255,255,0.06) !important;
        color: #fff !important;
    }
    .nav-sidebar .nav-item>.nav-link i {
        color: rgba(255,255,255,0.7) !important;
    }
    .nav-sidebar .nav-item>.nav-link.active i {
        color: #1a103c !important;
    }
    
    /* Table Styles Upgrades */
    .table {
        border-collapse: separate;
        border-spacing: 0;
        width: 100% !important;
    }
    .table th {
        background-color: #f8fafc !important;
        color: #475569 !important;
        font-weight: 600 !important;
        border-bottom: 2px solid #e2e8f0 !important;
        padding: 14px 18px !important;
    }
    .table td {
        border-bottom: 1px solid #e2e8f0 !important;
        padding: 14px 18px !important;
        vertical-align: middle !important;
        color: #334155;
    }
    .table tr:last-child td {
        border-bottom: none !important;
    }

    .whatsapp-float {
        position: fixed;
        width: 60px;
        height: 60px;
        bottom: 30px;
        right: 30px;
        background-color: #25d366;
        color: #FFF;
        border-radius: 50px;
        text-align: center;
        font-size: 30px;
        box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.3);
        z-index: 1000;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        animation: pulse 2s infinite;
    }

    .whatsapp-float:hover {
        background-color: #128c7e;
        color: #FFF;
        transform: scale(1.1);
        box-shadow: 2px 2px 15px rgba(0, 0, 0, 0.4);
        text-decoration: none;
    }

    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(37, 211, 102, 0.7);
        }
        70% {
            box-shadow: 0 0 0 15px rgba(37, 211, 102, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(37, 211, 102, 0);
        }
    }

    /* Responsive adjustments */
    @media screen and (max-width: 768px) {
        .whatsapp-float {
            width: 50px;
            height: 50px;
            bottom: 20px;
            right: 20px;
            font-size: 25px;
        }
    }
</style>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap 4 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
@stack('scripts')
</body>
</html>
