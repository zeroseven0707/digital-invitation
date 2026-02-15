@extends('layouts.admin')

@section('page-title', 'Dashboard Admin')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<!-- Statistics Cards -->
<div class="row">
    <!-- Total Users -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $platformStats['totalUsers'] }}</h3>
                <p>Total User</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <a href="{{ route('admin.users.index') }}" class="small-box-footer">
                Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <!-- Total Invitations -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-primary">
            <div class="inner">
                <h3>{{ $platformStats['totalInvitations'] }}</h3>
                <p>Total Undangan</p>
            </div>
            <div class="icon">
                <i class="fas fa-envelope"></i>
            </div>
            <a href="{{ route('admin.invitations.index') }}" class="small-box-footer">
                Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <!-- Published Invitations -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $platformStats['publishedInvitations'] }}</h3>
                <p>Undangan Published</p>
            </div>
            <div class="icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <a href="{{ route('admin.invitations.index') }}?status=published" class="small-box-footer">
                Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>

    <!-- Paid Invitations -->
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ \App\Models\Invitation::where('is_paid', true)->count() }}</h3>
                <p>Undangan Berbayar</p>
            </div>
            <div class="icon">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <a href="{{ route('admin.invitations.index') }}?payment_status=paid" class="small-box-footer">
                Lihat Detail <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

<!-- Second Row Statistics -->
<div class="row">
    <!-- Total Views -->
    <div class="col-lg-3 col-6">
        <div class="info-box">
            <span class="info-box-icon bg-info"><i class="fas fa-eye"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Views</span>
                <span class="info-box-number">{{ number_format($platformStats['totalViews']) }}</span>
            </div>
        </div>
    </div>

    <!-- Total Guests -->
    <div class="col-lg-3 col-6">
        <div class="info-box">
            <span class="info-box-icon bg-success"><i class="fas fa-user-friends"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Tamu</span>
                <span class="info-box-number">{{ number_format($platformStats['totalGuests']) }}</span>
            </div>
        </div>
    </div>

    <!-- Total Templates -->
    <div class="col-lg-3 col-6">
        <div class="info-box">
            <span class="info-box-icon bg-warning"><i class="fas fa-palette"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Template</span>
                <span class="info-box-number">{{ $platformStats['totalTemplates'] }}</span>
            </div>
        </div>
    </div>

    <!-- Active Users -->
    <div class="col-lg-3 col-6">
        <div class="info-box">
            <span class="info-box-icon bg-danger"><i class="fas fa-user-check"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">User Aktif</span>
                <span class="info-box-number">{{ $platformStats['activeUsers'] }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row">
    <!-- User Growth Chart -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-line mr-1"></i>
                    Pertumbuhan User (30 Hari Terakhir)
                </h3>
            </div>
            <div class="card-body">
                <canvas id="userGrowthChart" style="height: 250px;"></canvas>
            </div>
        </div>
    </div>

    <!-- Invitation Growth Chart -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-chart-line mr-1"></i>
                    Pertumbuhan Undangan (30 Hari Terakhir)
                </h3>
            </div>
            <div class="card-body">
                <canvas id="invitationGrowthChart" style="height: 250px;"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Tables Row -->
<div class="row">
    <!-- Top Users -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-trophy mr-1"></i>
                    Top 5 User (Berdasarkan Undangan)
                </h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Email</th>
                            <th class="text-center">Undangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topUsers as $user)
                        <tr>
                            <td>
                                <a href="{{ route('admin.users.show', $user->id) }}">
                                    {{ $user->name }}
                                </a>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td class="text-center">
                                <span class="badge badge-primary">{{ $user->invitations_count }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted">Tidak ada data</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Top Invitations -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-fire mr-1"></i>
                    Top 5 Undangan (Berdasarkan Views)
                </h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>User</th>
                            <th class="text-center">Views</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topInvitations as $invitation)
                        <tr>
                            <td>
                                <a href="{{ route('public.invitation', $invitation->unique_url) }}" target="_blank">
                                    {{ Str::limit($invitation->title, 30) }}
                                </a>
                            </td>
                            <td>{{ $invitation->user->name }}</td>
                            <td class="text-center">
                                <span class="badge badge-info">{{ $invitation->views_count }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted">Tidak ada data</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-clock mr-1"></i>
                    Undangan Terbaru
                </h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>User</th>
                            <th>Template</th>
                            <th>Status</th>
                            <th>Pembayaran</th>
                            <th>Dibuat</th>
                            <th>Aksi</th>
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
                            <td>{{ Str::limit($invitation->title, 40) }}</td>
                            <td>
                                <a href="{{ route('admin.users.show', $invitation->user_id) }}">
                                    {{ $invitation->user->name }}
                                </a>
                            </td>
                            <td><span class="badge badge-info">{{ $invitation->template->name }}</span></td>
                            <td>
                                @if($invitation->status === 'published')
                                    <span class="badge badge-success">Published</span>
                                @else
                                    <span class="badge badge-secondary">Draft</span>
                                @endif
                            </td>
                            <td>
                                @if($invitation->is_paid)
                                    <span class="badge badge-success">Lunas</span>
                                @else
                                    <span class="badge badge-warning">Belum Bayar</span>
                                @endif
                            </td>
                            <td>{{ $invitation->created_at->diffForHumans() }}</td>
                            <td>
                                <a href="{{ route('admin.invitations.index') }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Tidak ada undangan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
// User Growth Chart
const userGrowthCtx = document.getElementById('userGrowthChart').getContext('2d');
const userGrowthChart = new Chart(userGrowthCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($userGrowth->pluck('date')->toArray()) !!},
        datasets: [{
            label: 'User Baru',
            data: {!! json_encode($userGrowth->pluck('count')->toArray()) !!},
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});

// Invitation Growth Chart
const invitationGrowthCtx = document.getElementById('invitationGrowthChart').getContext('2d');
const invitationGrowthChart = new Chart(invitationGrowthCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode($invitationGrowth->pluck('date')->toArray()) !!},
        datasets: [{
            label: 'Undangan Baru',
            data: {!! json_encode($invitationGrowth->pluck('count')->toArray()) !!},
            borderColor: 'rgb(54, 162, 235)',
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});
</script>
@endpush
