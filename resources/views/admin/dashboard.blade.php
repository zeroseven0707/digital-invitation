@extends('layouts.admin')

@section('page-title', 'Dashboard Admin')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<!-- Welcome Premium Hero Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="admin-welcome-hero p-4 text-white d-flex flex-column flex-md-row justify-content-between align-items-md-center shadow-sm">
            <div>
                <h3 class="font-weight-bold mb-1"><i class="fas fa-chart-line mr-2" style="color: #f0d060;"></i>Ringkasan Platform Nikahin</h3>
                <p class="mb-0 text-white-50">Selamat datang kembali, Admin. Pantau performa bisnis dan pertumbuhan pengguna secara real-time.</p>
            </div>
            <div class="mt-3 mt-md-0">
                <a href="{{ route('admin.templates.index') }}" class="btn btn-light font-weight-bold px-4" style="color: #6b4ce6; border-radius: 10px;">
                    <i class="fas fa-plus mr-1"></i> Kelola Template
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row">
    <!-- Total Invitations -->
    <div class="col-lg-3 col-6">
        <div class="small-box admin-stat-box text-white" style="background: linear-gradient(135deg, #6b4ce6 0%, #462eb5 100%);">
            <div class="inner">
                <h3>{{ number_format($platformStats['totalInvitations']) }}</h3>
                <p class="font-weight-bold">Total Undangan</p>
            </div>
            <div class="icon">
                <i class="fas fa-envelope" style="opacity: 0.25;"></i>
            </div>
            <a href="{{ route('admin.invitations.index') }}" class="small-box-footer" style="background: rgba(0,0,0,0.12) !important;">
                Lihat Detail <i class="fas fa-arrow-circle-right ml-1"></i>
            </a>
        </div>
    </div>

    <!-- Total Templates -->
    <div class="col-lg-3 col-6">
        <div class="small-box admin-stat-box text-white" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
            <div class="inner">
                <h3>{{ number_format($platformStats['totalTemplates']) }}</h3>
                <p class="font-weight-bold">Template Aktif</p>
            </div>
            <div class="icon">
                <i class="fas fa-palette" style="opacity: 0.25;"></i>
            </div>
            <a href="{{ route('admin.templates.index') }}" class="small-box-footer" style="background: rgba(0,0,0,0.12) !important;">
                Lihat Detail <i class="fas fa-arrow-circle-right ml-1"></i>
            </a>
        </div>
    </div>

    <!-- Total Views -->
    <div class="col-lg-3 col-6">
        <div class="small-box admin-stat-box text-white" style="background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);">
            <div class="inner">
                <h3>{{ number_format($platformStats['totalViews']) }}</h3>
                <p class="font-weight-bold">Total Views</p>
            </div>
            <div class="icon">
                <i class="fas fa-eye" style="opacity: 0.25;"></i>
            </div>
            <a href="{{ route('admin.invitations.index') }}" class="small-box-footer" style="background: rgba(0,0,0,0.12) !important;">
                Lihat Detail <i class="fas fa-arrow-circle-right ml-1"></i>
            </a>
        </div>
    </div>

    <!-- Total RSVPs -->
    <div class="col-lg-3 col-6">
        <div class="small-box admin-stat-box text-white" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
            <div class="inner">
                <h3>{{ number_format($platformStats['totalRsvps']) }}</h3>
                <p class="font-weight-bold">Total RSVP</p>
            </div>
            <div class="icon">
                <i class="fas fa-comments" style="opacity: 0.25;"></i>
            </div>
            <a href="{{ route('admin.invitations.index') }}" class="small-box-footer" style="background: rgba(0,0,0,0.12) !important;">
                Lihat Detail <i class="fas fa-arrow-circle-right ml-1"></i>
            </a>
        </div>
    </div>
</div>

<!-- Second Row Statistics Info Boxes -->
<div class="row">
    <!-- Total Users -->
    <div class="col-lg-4 col-6">
        <div class="info-box admin-info-card">
            <span class="info-box-icon text-white shadow-sm" style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border-radius: 12px;"><i class="fas fa-users"></i></span>
            <div class="info-box-content">
                <span class="info-box-text text-muted font-weight-bold">Total Pengguna</span>
                <span class="info-box-number h4 font-weight-bold mb-0 text-dark">{{ number_format($platformStats['totalUsers']) }}</span>
            </div>
        </div>
    </div>

    <!-- Paid Invitations -->
    <div class="col-lg-4 col-6">
        <div class="info-box admin-info-card">
            <span class="info-box-icon text-white shadow-sm" style="background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%); border-radius: 12px;"><i class="fas fa-money-bill-wave"></i></span>
            <div class="info-box-content">
                <span class="info-box-text text-muted font-weight-bold">Undangan Lunas</span>
                <span class="info-box-number h4 font-weight-bold mb-0 text-dark">{{ number_format($platformStats['paidInvitations']) }}</span>
            </div>
        </div>
    </div>

    <!-- Published Invitations -->
    <div class="col-lg-4 col-6">
        <div class="info-box admin-info-card">
            <span class="info-box-icon text-white shadow-sm" style="background: linear-gradient(135deg, #a855f7 0%, #9333ea 100%); border-radius: 12px;"><i class="fas fa-check-circle"></i></span>
            <div class="info-box-content">
                <span class="info-box-text text-muted font-weight-bold">Published</span>
                <span class="info-box-number h4 font-weight-bold mb-0 text-dark">{{ number_format($platformStats['publishedInvitations']) }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row">
    <!-- User Growth Chart -->
    <div class="col-lg-6">
        <div class="card shadow-sm border-0 mb-4" style="border-radius: 15px;">
            <div class="card-header bg-white py-3">
                <h3 class="card-title m-0 font-weight-bold text-dark" style="font-size: 1.05rem;">
                    <i class="fas fa-chart-line text-indigo mr-2" style="color: #6b4ce6;"></i>Pertumbuhan Pengguna (30 Hari Terakhir)
                </h3>
            </div>
            <div class="card-body">
                <canvas id="userGrowthChart" style="height: 250px;"></canvas>
            </div>
        </div>
    </div>

    <!-- Transaction Growth Chart -->
    <div class="col-lg-6">
        <div class="card shadow-sm border-0 mb-4" style="border-radius: 15px;">
            <div class="card-header bg-white py-3">
                <h3 class="card-title m-0 font-weight-bold text-dark" style="font-size: 1.05rem;">
                    <i class="fas fa-chart-area text-warning mr-2" style="color: #f59e0b;"></i>Pertumbuhan Transaksi (30 Hari Terakhir)
                </h3>
            </div>
            <div class="card-body">
                <canvas id="transactionGrowthChart" style="height: 250px;"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Tables Row -->
<div class="row">
    <!-- Top Users -->
    <div class="col-lg-6">
        <div class="card shadow-sm border-0 mb-4" style="border-radius: 15px;">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h3 class="card-title m-0 font-weight-bold text-dark" style="font-size: 1.05rem;">
                    <i class="fas fa-trophy text-warning mr-2"></i>Top 5 Pengguna Teraktif
                </h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Pengguna</th>
                                <th>Email</th>
                                <th class="text-center">Total Undangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topUsers as $user)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.users.show', $user->id) }}" class="font-weight-bold text-dark">
                                        <i class="far fa-user-circle mr-1 text-muted"></i> {{ $user->name }}
                                    </a>
                                </td>
                                <td class="text-muted">{{ $user->email }}</td>
                                <td class="text-center">
                                    <span class="badge font-weight-bold px-3 py-2 text-white shadow-sm" style="background: linear-gradient(135deg, #6b4ce6 0%, #5538d4 100%); border-radius: 50px;">
                                        {{ $user->invitations_count }} Undangan
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">Tidak ada data pengguna</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Invitations -->
    <div class="col-lg-6">
        <div class="card shadow-sm border-0 mb-4" style="border-radius: 15px;">
            <div class="card-header bg-white py-3">
                <h3 class="card-title m-0 font-weight-bold text-dark" style="font-size: 1.05rem;">
                    <i class="fas fa-fire text-danger mr-2"></i>Top 5 Undangan Populer
                </h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Judul Undangan</th>
                                <th>Pengguna</th>
                                <th class="text-center">Views</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topInvitations as $invitation)
                            <tr>
                                <td>
                                    @if($invitation->unique_url)
                                        <a href="{{ route('public.invitation', $invitation->unique_url) }}" target="_blank" class="font-weight-bold text-dark">
                                            <i class="fas fa-link mr-1 text-muted"></i> {{ Str::limit($invitation->title, 28) }}
                                        </a>
                                    @else
                                        <span class="font-weight-bold text-muted">
                                            <i class="fas fa-link mr-1 text-muted"></i> {{ Str::limit($invitation->title, 28) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="text-muted">{{ $invitation->user->name }}</td>
                                <td class="text-center">
                                    <span class="badge font-weight-bold px-3 py-2 text-white shadow-sm" style="background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); border-radius: 50px;">
                                        <i class="far fa-eye mr-1"></i> {{ number_format($invitation->views_count) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">Tidak ada data undangan</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0 mb-4" style="border-radius: 15px;">
            <div class="card-header bg-white py-3">
                <h3 class="card-title m-0 font-weight-bold text-dark" style="font-size: 1.05rem;">
                    <i class="fas fa-history text-muted mr-2"></i>Daftar Undangan Terbaru
                </h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Judul Undangan</th>
                                <th>Pengguna</th>
                                <th>Template</th>
                                <th>Status</th>
                                <th>Status Bayar</th>
                                <th>Waktu Registrasi</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $recentInvitations = \App\Models\Invitation::with(['user', 'template'])
                                    ->latest()
                                    ->limit(10)
                                    ->get();
                            @endphp
                            @forelse($recentInvitations as $invitation)
                            <tr>
                                <td><span class="font-weight-bold text-dark">{{ Str::limit($invitation->title, 35) }}</span></td>
                                <td>
                                    <a href="{{ route('admin.users.show', $invitation->user_id) }}" class="text-muted font-weight-bold">
                                        {{ $invitation->user->name }}
                                    </a>
                                </td>
                                <td><span class="badge bg-light px-2.5 py-1.5 font-weight-bold border" style="color: #4b5563;">{{ $invitation->template->name }}</span></td>
                                <td>
                                    @if($invitation->status === 'published')
                                        <span class="badge bg-success-light text-success font-weight-bold px-3 py-1.5" style="border-radius: 50px;"><i class="fas fa-circle mr-1" style="font-size: 0.6rem;"></i> Published</span>
                                    @else
                                        <span class="badge bg-warning-light text-warning font-weight-bold px-3 py-1.5" style="border-radius: 50px;"><i class="fas fa-circle mr-1" style="font-size: 0.6rem;"></i> Draft</span>
                                    @endif
                                </td>
                                <td>
                                    @if($invitation->is_paid)
                                        <span class="badge bg-success text-white font-weight-bold px-3 py-1.5" style="border-radius: 50px; background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;"><i class="fas fa-check mr-1"></i> Lunas</span>
                                    @else
                                        <span class="badge bg-danger text-white font-weight-bold px-3 py-1.5" style="border-radius: 50px; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;"><i class="fas fa-times mr-1"></i> Belum Bayar</span>
                                    @endif
                                </td>
                                <td class="text-muted"><i class="far fa-clock mr-1"></i> {{ $invitation->created_at->diffForHumans() }}</td>
                                <td class="text-center">
                                    <a href="{{ route('admin.invitations.index') }}" class="btn btn-sm btn-primary px-3" style="border-radius: 8px;">
                                        <i class="fas fa-search-plus mr-1"></i> Kelola
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">Belum ada aktivitas pendaftaran undangan baru</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Welcome Admin Header Hero */
    .admin-welcome-hero {
        background: linear-gradient(135deg, #6b4ce6 0%, #462eb5 100%);
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(107, 76, 230, 0.12);
    }
    
    .admin-stat-box {
        border-radius: 16px !important;
        border: none !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05) !important;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    .admin-stat-box:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1) !important;
    }

    .admin-info-card {
        border-radius: 16px !important;
        border: 1px solid #e2e8f0 !important;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.015) !important;
        background: white !important;
        padding: 10px !important;
    }

    /* Soft badge colors */
    .bg-success-light {
        background-color: rgba(16, 185, 129, 0.12) !important;
    }
    .bg-warning-light {
        background-color: rgba(245, 158, 11, 0.12) !important;
    }
    
    .table td, .table th {
        vertical-align: middle !important;
    }
</style>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
// User Growth Chart
const userGrowthCtx = document.getElementById('userGrowthChart').getContext('2d');

const purpleGrad = userGrowthCtx.createLinearGradient(0, 0, 0, 250);
purpleGrad.addColorStop(0, 'rgba(107, 76, 230, 0.4)');
purpleGrad.addColorStop(1, 'rgba(107, 76, 230, 0.01)');

const userGrowthChart = new Chart(userGrowthCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($userGrowth->pluck('date')->toArray()) !!},
        datasets: [{
            label: 'User Baru Terdaftar',
            data: {!! json_encode($userGrowth->pluck('count')->toArray()) !!},
            borderColor: '#6b4ce6',
            borderWidth: 3,
            backgroundColor: purpleGrad,
            tension: 0.35,
            fill: true,
            pointBackgroundColor: '#6b4ce6',
            pointRadius: 4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    drawBorder: false,
                    color: '#f1f5f9'
                },
                ticks: {
                    stepSize: 1,
                    color: '#94a3b8'
                }
            },
            x: {
                grid: {
                    display: false
                },
                ticks: {
                    color: '#94a3b8'
                }
            }
        },
        plugins: {
            legend: {
                display: false
            }
        }
    }
});

// Transaction Growth Chart
const transactionGrowthCtx = document.getElementById('transactionGrowthChart').getContext('2d');

const amberGrad = transactionGrowthCtx.createLinearGradient(0, 0, 0, 250);
amberGrad.addColorStop(0, 'rgba(245, 158, 11, 0.4)');
amberGrad.addColorStop(1, 'rgba(245, 158, 11, 0.01)');

const transactionGrowthChart = new Chart(transactionGrowthCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($transactionGrowth->pluck('date')->toArray()) !!},
        datasets: [{
            label: 'Aktivasi Berbayar',
            data: {!! json_encode($transactionGrowth->pluck('count')->toArray()) !!},
            borderColor: '#f59e0b',
            borderWidth: 3,
            backgroundColor: amberGrad,
            tension: 0.35,
            fill: true,
            pointBackgroundColor: '#f59e0b',
            pointRadius: 4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    drawBorder: false,
                    color: '#f1f5f9'
                },
                ticks: {
                    stepSize: 1,
                    color: '#94a3b8'
                }
            },
            x: {
                grid: {
                    display: false
                },
                ticks: {
                    color: '#94a3b8'
                }
            }
        },
        plugins: {
            legend: {
                display: false
            }
        }
    }
});
</script>
@endpush
