<x-user-layout>
    <x-slot name="title">Statistik Undangan</x-slot>
    <x-slot name="header">Statistik Undangan</x-slot>
    <x-slot name="breadcrumbs">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('invitations.show', $invitation->id) }}">Detail Undangan</a></li>
        <li class="breadcrumb-item active">Statistik</li>
    </x-slot>

    <div class="row">
        <!-- Header Info -->
        <div class="col-12">
            <div class="card card-primary card-outline">
                <div class="card-body">
                    <h4 class="mb-0">{{ $invitation->bride_name }} & {{ $invitation->groom_name }}</h4>
                    <a href="{{ route('invitations.show', $invitation->id) }}" class="text-muted">
                        <i class="fas fa-arrow-left"></i> Kembali ke Detail Undangan
                    </a>
                </div>
            </div>
        </div>

        <!-- Total Views Card -->
        <div class="col-12">
            <div class="card bg-gradient-primary">
                <div class="card-body text-center">
                    <h3 class="text-white">Total Views</h3>
                    <h1 class="display-3 text-white font-weight-bold">{{ $totalViews }}</h1>
                </div>
            </div>
        </div>

        <!-- Views Chart -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-chart-line"></i> Views per Hari (30 Hari Terakhir)</h3>
                </div>
                <div class="card-body">
                    <canvas id="viewsChart" height="80"></canvas>
                </div>
            </div>
        </div>

        <!-- Device Stats -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-mobile-alt"></i> Device Breakdown</h3>
                </div>
                <div class="card-body">
                    <canvas id="deviceChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Browser Stats -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-browser"></i> Browser Breakdown</h3>
                </div>
                <div class="card-body">
                    <canvas id="browserChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Fetch and render views chart
        fetch('{{ route('statistics.views-chart', $invitation->id) }}')
            .then(response => response.json())
            .then(data => {
                const ctx = document.getElementById('viewsChart').getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Views',
                            data: data.data,
                            borderColor: 'rgb(0, 123, 255)',
                            backgroundColor: 'rgba(0, 123, 255, 0.1)',
                            tension: 0.1,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
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
            });

        // Fetch and render device/browser stats
        fetch('{{ route('statistics.device-stats', $invitation->id) }}')
            .then(response => response.json())
            .then(data => {
                // Device Chart
                const deviceCtx = document.getElementById('deviceChart').getContext('2d');
                new Chart(deviceCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Desktop', 'Mobile', 'Tablet'],
                        datasets: [{
                            data: [data.devices.desktop, data.devices.mobile, data.devices.tablet],
                            backgroundColor: [
                                'rgb(0, 123, 255)',
                                'rgb(40, 167, 69)',
                                'rgb(255, 193, 7)'
                            ]
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true
                    }
                });

                // Browser Chart
                const browserCtx = document.getElementById('browserChart').getContext('2d');
                const browserLabels = Object.keys(data.browsers);
                const browserData = Object.values(data.browsers);

                new Chart(browserCtx, {
                    type: 'doughnut',
                    data: {
                        labels: browserLabels,
                        datasets: [{
                            data: browserData,
                            backgroundColor: [
                                'rgb(0, 123, 255)',
                                'rgb(40, 167, 69)',
                                'rgb(255, 193, 7)',
                                'rgb(220, 53, 69)',
                                'rgb(108, 117, 125)'
                            ]
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true
                    }
                });
            });
    </script>
    @endpush
</x-user-layout>
