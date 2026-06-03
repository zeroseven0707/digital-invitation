<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'NIKAHIN') }} - Admin Panel</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@1.13.1/css/OverlayScrollbars.min.css">

    <style>
        /* Base Admin Layout Theme Upgrades */
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

        /* ── Sidebar Enhanced Styles ─────────────────────────── */

        /* User panel */
        .sidebar-user-panel {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.07);
            border-radius: 14px;
            margin: 12px 12px 4px;
        }
        .sidebar-user-avatar {
            width: 38px;
            height: 38px;
            border-radius: 11px;
            background: linear-gradient(135deg, #7c5fe6 0%, #f0d060 100%);
            color: #1a103c;
            font-weight: 800;
            font-size: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .sidebar-user-name {
            font-size: 0.85rem;
            font-weight: 700;
            color: #fff;
            line-height: 1.3;
        }
        .sidebar-user-role {
            font-size: 0.7rem;
            font-weight: 500;
            color: rgba(255,255,255,0.45);
            display: flex;
            align-items: center;
            gap: 5px;
            margin-top: 1px;
        }
        .sidebar-role-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #f0d060;
            display: inline-block;
            animation: rolePulse 2.5s ease-in-out infinite;
        }
        @keyframes rolePulse {
            0%,100% { box-shadow: 0 0 0 0 rgba(240,208,96,0.5); }
            50%      { box-shadow: 0 0 0 4px rgba(240,208,96,0); }
        }

        /* Section headers */
        .nav-header-custom {
            font-size: 0.62rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            color: rgba(255,255,255,0.25);
            padding: 4px 8px 2px;
            list-style: none;
        }

        /* Nav links */
        .nav-sidebar .nav-item > .nav-link {
            display: flex;
            align-items: center;
            padding: 10px 12px;
            border-radius: 12px;
            margin-bottom: 2px;
            color: rgba(255,255,255,0.65) !important;
            font-weight: 500;
            font-size: 0.875rem;
            transition: all 0.2s ease;
        }
        .nav-sidebar .nav-item > .nav-link:hover {
            background: rgba(255,255,255,0.07) !important;
            color: #fff !important;
            transform: translateX(3px);
        }
        .nav-sidebar .nav-item > .nav-link i.nav-icon {
            width: 32px;
            height: 32px;
            border-radius: 9px;
            background: rgba(255,255,255,0.08);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            color: rgba(255,255,255,0.6) !important;
            margin-right: 10px;
            flex-shrink: 0;
            transition: all 0.2s;
        }
        .nav-sidebar .nav-item > .nav-link:hover i.nav-icon {
            background: rgba(255,255,255,0.14);
            color: #fff !important;
        }

        /* Active link */
        .sidebar-dark-primary .nav-sidebar > .nav-item > .nav-link.active {
            background: linear-gradient(135deg, #f0d060 0%, #d4af37 100%) !important;
            color: #1a103c !important;
            font-weight: 700;
            box-shadow: 0 4px 16px rgba(240,208,96,0.35) !important;
            border-radius: 12px;
            transform: none;
        }
        .sidebar-dark-primary .nav-sidebar > .nav-item > .nav-link.active i.nav-icon {
            background: rgba(26,16,60,0.2) !important;
            color: #1a103c !important;
        }

        /* Logout link */
        .nav-link-logout:hover {
            background: rgba(239,68,68,0.12) !important;
            color: #fca5a5 !important;
        }
        .nav-link-logout:hover i.nav-icon {
            background: rgba(239,68,68,0.2) !important;
            color: #fca5a5 !important;
        }

        /* Count badges */
        .nav-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 22px;
            height: 22px;
            padding: 0 6px;
            border-radius: 7px;
            background: rgba(255,255,255,0.1);
            color: rgba(255,255,255,0.65);
            font-size: 0.68rem;
            font-weight: 700;
            flex-shrink: 0;
        }
        .nav-badge-green { background: rgba(16,185,129,0.2); color: #6ee7b7; }
        .nav-sidebar .nav-item > .nav-link.active .nav-badge {
            background: rgba(26,16,60,0.2);
            color: #1a103c;
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
    </style>

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
                <a href="{{ route('admin.dashboard') }}" class="nav-link">Dashboard</a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <!-- User Dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-user"></i>
                    <span class="d-none d-md-inline ml-1">{{ Auth::user()->name }}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <span class="dropdown-item dropdown-header">{{ Auth::user()->email }}</span>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('profile.edit') }}" class="dropdown-item">
                        <i class="fas fa-user-cog mr-2"></i> Profile
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
            <li class="nav-item">
                <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                    <i class="fas fa-expand-arrows-alt"></i>
                </a>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="{{ route('admin.dashboard') }}" class="brand-link text-center">
            <img src="{{ asset('images/logo.png') }}" alt="NIKAHIN Logo" style="max-height: 130px; width: auto; display: inline-block; vertical-align: middle;">
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Admin user info -->
            <div class="sidebar-user-panel d-flex align-items-center py-3 px-3 mb-1">
                <div class="sidebar-user-avatar mr-3 flex-shrink-0">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="sidebar-user-info overflow-hidden">
                    <div class="sidebar-user-name text-truncate">{{ Auth::user()->name }}</div>
                    <div class="sidebar-user-role">
                        <span class="sidebar-role-dot"></span>Administrator
                    </div>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="mt-1 px-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                    {{-- ── MAIN ── --}}
                    <li class="nav-header-custom">MAIN</li>
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    {{-- ── MANAJEMEN ── --}}
                    <li class="nav-header-custom mt-2">MANAJEMEN</li>
                    <li class="nav-item">
                        <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                Pengguna
                                <span class="nav-badge ml-auto">{{ \App\Models\User::count() }}</span>
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.templates.index') }}" class="nav-link {{ request()->routeIs('admin.templates.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-palette"></i>
                            <p>
                                Template
                                <span class="nav-badge nav-badge-green ml-auto">{{ \App\Models\Template::where('is_active', true)->count() }}</span>
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.invitations.index') }}" class="nav-link {{ request()->routeIs('admin.invitations.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-envelope-open-text"></i>
                            <p>
                                Undangan
                                <span class="nav-badge ml-auto">{{ \App\Models\Invitation::count() }}</span>
                            </p>
                        </a>
                    </li>

                    {{-- ── SISTEM ── --}}
                    <li class="nav-header-custom mt-2">SISTEM</li>
                    <li class="nav-item">
                        <a href="{{ route('admin.settings.index') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-sliders-h"></i>
                            <p>Pengaturan</p>
                        </a>
                    </li>

                    {{-- ── AKUN ── --}}
                    <li class="nav-header-custom mt-2">AKUN</li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}" id="sidebar-logout-form">
                            @csrf
                            <a href="#" class="nav-link nav-link-logout"
                               onclick="event.preventDefault(); document.getElementById('sidebar-logout-form').submit();">
                                <i class="nav-icon fas fa-sign-out-alt"></i>
                                <p>Logout</p>
                            </a>
                        </form>
                    </li>

                </ul>
            </nav>
        </div>
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">@yield('page-title', 'Dashboard')</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            @yield('breadcrumb')
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Alert Messages -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @yield('content')
            </div>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Footer -->
    <footer class="main-footer">
        <strong>Copyright &copy; {{ date('Y') }} <a href="#">{{ config('app.name', 'NIKAHIN') }}</a>.</strong>
        All rights reserved.
        <div class="float-right d-none d-sm-inline-block">
            <b>Version</b> 1.0.0
        </div>
    </footer>
</div>
<!-- ./wrapper -->

<!-- Floating WhatsApp Button -->
<a href="https://wa.me/6282342742787" target="_blank" class="whatsapp-float" title="Hubungi Kami via WhatsApp">
    <i class="fab fa-whatsapp"></i>
</a>

<style>
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
<!-- overlayScrollbars -->
<script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@1.13.1/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

<script>
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
</script>

@stack('scripts')
</body>
</html>
