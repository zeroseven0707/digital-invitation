<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard' }} - NIKAHIN</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- AdminLTE -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
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
                <a href="{{ route('invitations.create') }}" class="nav-link" title="Buat Undangan Baru">
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
        <a href="{{ route('dashboard') }}" class="brand-link">
            <i class="fas fa-heart brand-image ml-3"></i>
            <span class="brand-text font-weight-light">NIKAHIN</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user panel -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <i class="fas fa-user-circle fa-2x text-white"></i>
                </div>
                <div class="info">
                    <a href="{{ route('profile.edit') }}" class="d-block">{{ Auth::user()->name }}</a>
                </div>
            </div>

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
                        <h1 class="m-0">{{ $header }}</h1>
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
                <div class="alert alert-success alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    {{ session('success') }}
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    {{ session('error') }}
                </div>
                @endif

                {{ $slot }}
            </div>
        </section>
    </div>

    <!-- Footer -->
    <footer class="main-footer">
        <strong>Copyright &copy; {{ date('Y') }} <a href="{{ route('dashboard') }}">NIKAHIN</a>.</strong>
        All rights reserved.
        <div class="float-right d-none d-sm-inline-block">
            <b>Version</b> 1.0.0
        </div>
    </footer>
</div>

<!-- Floating WhatsApp Button -->
<a href="https://wa.me/6281394454900" target="_blank" class="whatsapp-float" title="Hubungi Kami via WhatsApp">
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
<!-- AdminLTE App -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
@stack('scripts')
</body>
</html>
