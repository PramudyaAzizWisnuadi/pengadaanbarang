@extends('layouts.app')

@section('title', 'Statistik Pengadaan Barang')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="bi bi-graph-up me-2"></i>
                Statistik Pengadaan Barang
            </h1>
            <div>
                <a href="{{ route('pengadaan.laporan') }}" class="btn btn-primary">
                    <i class="bi bi-file-earmark-text me-1"></i>
                    Lihat Laporan
                </a>
            </div>
        </div>

        <!-- Filter Form -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-funnel me-2"></i>
                    Filter Statistik
                </h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('pengadaan.statistik') }}" class="row g-3">
                    <div class="col-md-2">
                        <label for="start_date" class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control" id="start_date" name="start_date"
                            value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="end_date" class="form-label">Tanggal Akhir</label>
                        <input type="date" class="form-control" id="end_date" name="end_date"
                            value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Semua Status</option>
                            @foreach ($statuses as $status)
                                <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                    {{ ucfirst($status) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="departemen" class="form-label">Departemen</label>
                        <select class="form-select" id="departemen" name="departemen">
                            <option value="all" {{ request('departemen') == 'all' ? 'selected' : '' }}>Semua Departemen
                            </option>
                            @foreach ($departemens as $dept)
                                <option value="{{ $dept }}" {{ request('departemen') == $dept ? 'selected' : '' }}>
                                    {{ $dept }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="kategori" class="form-label">Kategori</label>
                        <select class="form-select" id="kategori" name="kategori">
                            <option value="all" {{ request('kategori') == 'all' ? 'selected' : '' }}>Semua Kategori
                            </option>
                            @foreach ($kategoris as $kategori)
                                <option value="{{ $kategori->id }}"
                                    {{ request('kategori') == $kategori->id ? 'selected' : '' }}>
                                    {{ $kategori->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="bi bi-search me-1"></i>
                            Filter
                        </button>
                        <a href="{{ route('pengadaan.statistik') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-clockwise me-1"></i>
                            Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Statistik Cards -->
        <div class="row mb-4">
            <!-- Total Pengadaan -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Pengadaan
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $statistics['total_pengadaan'] }}
                                </div>
                                <div class="text-xs text-muted mt-1">
                                    @if (request('start_date') || request('end_date'))
                                        Periode:
                                        {{ request('start_date') ? \Carbon\Carbon::parse(request('start_date'))->format('d/m/Y') : '-' }}
                                        s/d
                                        {{ request('end_date') ? \Carbon\Carbon::parse(request('end_date'))->format('d/m/Y') : \Carbon\Carbon::now()->format('d/m/Y') }}
                                    @else
                                        Semua periode
                                    @endif
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-clipboard-data fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Nilai -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Total Nilai
                                </div>
                                <div class="h6 mb-0 font-weight-bold text-gray-800">
                                    Rp {{ number_format($statistics['total_estimasi'], 0, ',', '.') }}
                                </div>
                                <div class="text-xs text-muted mt-1">
                                    Rata-rata: Rp
                                    {{ $statistics['total_pengadaan'] > 0 ? number_format($statistics['total_estimasi'] / $statistics['total_pengadaan'], 0, ',', '.') : '0' }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-currency-dollar fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tingkat Persetujuan -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Tingkat Persetujuan
                                </div>
                                <div class="row no-gutters align-items-center">
                                    <div class="col-auto">
                                        <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                            {{ number_format($statistics['approval_rate'], 1) }}%</div>
                                    </div>
                                    <div class="col">
                                        <div class="progress progress-sm mr-2">
                                            <div class="progress-bar bg-info" role="progressbar"
                                                style="width: {{ $statistics['approval_rate'] }}%"
                                                aria-valuenow="{{ $statistics['approval_rate'] }}" aria-valuemin="0"
                                                aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-xs text-muted mt-1">
                                    {{ $statistics['approved'] + $statistics['completed'] }} dari
                                    {{ $statistics['submitted'] + $statistics['approved'] + $statistics['rejected'] + $statistics['completed'] }}
                                    disetujui
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-check-circle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Pending -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Menunggu Persetujuan
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $statistics['submitted'] }}</div>
                                <div class="text-xs text-muted mt-1">
                                    {{ $statistics['total_pengadaan'] > 0 ? number_format(($statistics['submitted'] / $statistics['total_pengadaan']) * 100, 1) : '0' }}%
                                    dari total
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-clock fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="row mb-4">
            <!-- Status Distribution Chart -->
            <div class="col-xl-6 col-lg-7">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Distribusi Status</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-pie pt-4 pb-2">
                            <canvas id="statusChart"></canvas>
                        </div>
                        <div class="mt-4 text-center small">
                            <span class="mr-2">
                                <i class="fas fa-circle text-primary"></i> Draft ({{ $statistics['draft'] }})
                            </span>
                            <span class="mr-2">
                                <i class="fas fa-circle text-warning"></i> Submitted ({{ $statistics['submitted'] }})
                            </span>
                            <span class="mr-2">
                                <i class="fas fa-circle text-success"></i> Approved ({{ $statistics['approved'] }})
                            </span>
                            <span class="mr-2">
                                <i class="fas fa-circle text-danger"></i> Rejected ({{ $statistics['rejected'] }})
                            </span>
                            <span class="mr-2">
                                <i class="fas fa-circle text-info"></i> Completed ({{ $statistics['completed'] }})
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Monthly Trend Chart -->
            <div class="col-xl-6 col-lg-5">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Trend 6 Bulan Terakhir</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-line pt-4 pb-2">
                            <canvas id="monthlyChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Departemen Analysis -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Analisis Per Departemen</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Departemen</th>
                                        <th>Total Pengadaan</th>
                                        <th>Disetujui</th>
                                        <th>Pending</th>
                                        <th>Ditolak</th>
                                        <th>Total Estimasi</th>
                                        <th>Progress</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($departmenStats as $dept)
                                        <tr>
                                            <td class="font-weight-bold">{{ $dept['departemen'] }}</td>
                                            <td>{{ $dept['total'] }}</td>
                                            <td>
                                                <span class="badge bg-success">{{ $dept['approved'] }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-warning">{{ $dept['pending'] }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-danger">{{ $dept['rejected'] }}</span>
                                            </td>
                                            <td>Rp {{ number_format($dept['total_estimasi'], 0, ',', '.') }}</td>
                                            <td>
                                                @php
                                                    $approvalRate =
                                                        $dept['total'] > 0
                                                            ? ($dept['approved'] / $dept['total']) * 100
                                                            : 0;
                                                @endphp
                                                <div class="progress" style="height: 20px;">
                                                    <div class="progress-bar
                                                        @if ($approvalRate >= 80) bg-success
                                                        @elseif($approvalRate >= 60) bg-warning
                                                        @else bg-danger @endif"
                                                        role="progressbar" style="width: {{ $approvalRate }}%"
                                                        aria-valuenow="{{ $approvalRate }}" aria-valuemin="0"
                                                        aria-valuemax="100">
                                                        {{ number_format($approvalRate, 1) }}%
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">
                                                <i class="bi bi-info-circle me-2"></i>
                                                Tidak ada data untuk ditampilkan
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Insights Cards -->
        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Insight Positif
                                </div>
                                <div class="text-sm mb-0 text-gray-800">
                                    @if ($statistics['approval_rate'] >= 80)
                                        Tingkat persetujuan sangat baik
                                        ({{ number_format($statistics['approval_rate'], 1) }}%)
                                    @elseif($statistics['approved'] > $statistics['rejected'])
                                        Lebih banyak pengadaan disetujui daripada ditolak
                                    @else
                                        Sistem pengadaan berjalan dengan baik
                                    @endif
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-emoji-smile fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Perhatian
                                </div>
                                <div class="text-sm mb-0 text-gray-800">
                                    @if ($statistics['submitted'] > 5)
                                        {{ $statistics['submitted'] }} pengadaan menunggu persetujuan
                                    @elseif($statistics['rejected'] > $statistics['approved'])
                                        Tingkat penolakan tinggi, perlu evaluasi
                                    @else
                                        Monitoring rutin diperlukan untuk efisiensi
                                    @endif
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-exclamation-triangle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Rekomendasi
                                </div>
                                <div class="text-sm mb-0 text-gray-800">
                                    @if ($statistics['draft'] > 3)
                                        Selesaikan {{ $statistics['draft'] }} draft yang tertunda
                                    @elseif($statistics['total_pengadaan'] > 0)
                                        Lanjutkan proses yang sudah berjalan dengan baik
                                    @else
                                        Mulai proses pengadaan baru sesuai kebutuhan
                                    @endif
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-lightbulb fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Status Distribution Chart
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        const statusChart = new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Draft', 'Submitted', 'Approved', 'Rejected', 'Completed'],
                datasets: [{
                    data: [
                        {{ $statistics['draft'] }},
                        {{ $statistics['submitted'] }},
                        {{ $statistics['approved'] }},
                        {{ $statistics['rejected'] }},
                        {{ $statistics['completed'] }}
                    ],
                    backgroundColor: [
                        '#6c757d',
                        '#ffc107',
                        '#28a745',
                        '#dc3545',
                        '#17a2b8'
                    ],
                    hoverBackgroundColor: [
                        '#5a6268',
                        '#e0a800',
                        '#1e7e34',
                        '#bd2130',
                        '#138496'
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? ((context.parsed / total) * 100).toFixed(1) : 0;
                                return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });

        // Monthly Trend Chart
        const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
        const monthlyChart = new Chart(monthlyCtx, {
            type: 'line',
            data: {
                labels: [
                    @foreach ($monthlyStats as $month)
                        '{{ $month['month'] }}',
                    @endforeach
                ],
                datasets: [{
                    label: 'Total Pengadaan',
                    data: [
                        @foreach ($monthlyStats as $month)
                            {{ $month['total'] }},
                        @endforeach
                    ],
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    tension: 0.3,
                    fill: true
                }, {
                    label: 'Disetujui',
                    data: [
                        @foreach ($monthlyStats as $month)
                            {{ $month['approved'] }},
                        @endforeach
                    ],
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    tension: 0.3,
                    fill: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
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

    <style>
        .border-left-primary {
            border-left: 0.25rem solid #4e73df !important;
        }

        .border-left-success {
            border-left: 0.25rem solid #1cc88a !important;
        }

        .border-left-info {
            border-left: 0.25rem solid #36b9cc !important;
        }

        .border-left-warning {
            border-left: 0.25rem solid #f6c23e !important;
        }

        .border-left-danger {
            border-left: 0.25rem solid #e74a3b !important;
        }

        .progress-sm {
            height: 0.5rem;
        }

        .chart-pie,
        .chart-line {
            position: relative;
            height: 15rem;
        }

        .text-xs {
            font-size: 0.7rem;
        }

        .text-sm {
            font-size: 0.875rem;
        }
    </style>
@endsection
