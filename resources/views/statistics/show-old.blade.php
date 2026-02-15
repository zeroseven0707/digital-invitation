<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Statistik Undangan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <div class="mb-4">
                        <h3 class="text-2xl font-bold mb-2">{{ $invitation->bride_name }} & {{ $invitation->groom_name }}</h3>
                        <a href="{{ route('invitations.show', $invitation->id) }}" class="text-indigo-600 hover:text-indigo-900">
                            ← Kembali ke Detail Undangan
                        </a>
                    </div>

                    <!-- Total Views -->
                    <div class="bg-indigo-50 rounded-lg p-6 mb-6">
                        <div class="text-center">
                            <p class="text-gray-600 text-sm uppercase tracking-wide">Total Views</p>
                            <p class="text-5xl font-bold text-indigo-600 mt-2">{{ $totalViews }}</p>
                        </div>
                    </div>

                    <!-- Views Chart -->
                    <div class="mb-6">
                        <h4 class="font-semibold mb-3">Views per Hari (30 Hari Terakhir)</h4>
                        <div class="border rounded-lg p-4">
                            <canvas id="viewsChart" height="80"></canvas>
                        </div>
                    </div>

                    <!-- Device and Browser Stats -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-semibold mb-3">Device Breakdown</h4>
                            <div class="border rounded-lg p-4">
                                <canvas id="deviceChart"></canvas>
                            </div>
                        </div>
                        <div>
                            <h4 class="font-semibold mb-3">Browser Breakdown</h4>
                            <div class="border rounded-lg p-4">
                                <canvas id="browserChart"></canvas>
                            </div>
                        </div>
                    </div>
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
                            borderColor: 'rgb(79, 70, 229)',
                            backgroundColor: 'rgba(79, 70, 229, 0.1)',
                            tension: 0.1
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
                    type: 'pie',
                    data: {
                        labels: ['Desktop', 'Mobile', 'Tablet'],
                        datasets: [{
                            data: [data.devices.desktop, data.devices.mobile, data.devices.tablet],
                            backgroundColor: [
                                'rgb(79, 70, 229)',
                                'rgb(99, 102, 241)',
                                'rgb(129, 140, 248)'
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
                    type: 'pie',
                    data: {
                        labels: browserLabels,
                        datasets: [{
                            data: browserData,
                            backgroundColor: [
                                'rgb(79, 70, 229)',
                                'rgb(99, 102, 241)',
                                'rgb(129, 140, 248)',
                                'rgb(165, 180, 252)',
                                'rgb(199, 210, 254)'
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
</x-app-layout>
