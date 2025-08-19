@extends('layouts.app')

@section('title', 'Laporan Pengadaan Barang')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="bi bi-file-earmark-text me-2"></i>
                Laporan Pengadaan Barang
            </h1>
            <div>
                <a href="{{ route('pengadaan.statistik') }}" class="btn btn-info me-2">
                    <i class="bi bi-graph-up me-1"></i>
                    Lihat Statistik
                </a>
                <button type="button" class="btn btn-success me-2" onclick="exportLaporan('excel')">
                    <i class="bi bi-file-earmark-excel me-1"></i>
                    Export Excel
                </button>
                <button type="button" class="btn btn-secondary" onclick="printLaporan()">
                    <i class="bi bi-printer me-1"></i>
                    Print
                </button>
            </div>
        </div>

        <!-- Filter Form -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-funnel me-2"></i>
                    Filter Laporan
                </h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('pengadaan.laporan') }}" class="row g-3">
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
                        <select class="form-select" id="departemen" name="departemen"
                            @if (Auth::user()->role !== 'super_admin') disabled @endif>
                            @if (Auth::user()->role === 'super_admin')
                                <option value="all" {{ request('departemen') == 'all' ? 'selected' : '' }}>Semua
                                    Departemen</option>
                                @foreach ($departemens as $dept)
                                    <option value="{{ $dept }}"
                                        {{ request('departemen') == $dept ? 'selected' : '' }}>
                                        {{ $dept }}
                                    </option>
                                @endforeach
                            @else
                                <option value="{{ Auth::user()->departemenRelation->nama_departemen }}" selected>
                                    {{ Auth::user()->departemenRelation->nama_departemen }}
                                </option>
                                <!-- Hidden input for form submission -->
                                <input type="hidden" name="departemen"
                                    value="{{ Auth::user()->departemenRelation->nama_departemen }}">
                            @endif
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
                        <a href="{{ route('pengadaan.laporan') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-clockwise me-1"></i>
                            Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Ringkasan Singkat -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card border-left-info">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="text-info font-weight-bold mb-2">
                                    <i class="bi bi-info-circle me-2"></i>
                                    Ringkasan Laporan
                                </h6>
                                <p class="mb-1">
                                    <strong>Total Data:</strong> {{ $statistics['total_pengadaan'] }} pengadaan
                                    | <strong>Total Nilai:</strong> Rp
                                    {{ number_format($statistics['total_estimasi'], 0, ',', '.') }}
                                    | <strong>Disetujui:</strong> {{ $statistics['approved'] + $statistics['completed'] }}
                                    | <strong>Ditolak:</strong> {{ $statistics['rejected'] }}
                                    | <strong>Pending:</strong> {{ $statistics['submitted'] }}
                                </p>
                                <p class="mb-0">
                                    <strong>Filter yang diterapkan:</strong>
                                    @if (request('start_date') ||
                                            request('end_date') ||
                                            (request('status') && request('status') !== 'all') ||
                                            (request('departemen') && request('departemen') !== 'all') ||
                                            (request('kategori') && request('kategori') !== 'all'))
                                        @if (request('start_date') || request('end_date'))
                                            Periode
                                            ({{ request('start_date') ? \Carbon\Carbon::parse(request('start_date'))->format('d/m/Y') : '-' }}
                                            s/d
                                            {{ request('end_date') ? \Carbon\Carbon::parse(request('end_date'))->format('d/m/Y') : 'sekarang' }})
                                        @endif
                                        @if (request('status') && request('status') !== 'all')
                                            , Status ({{ ucfirst(request('status')) }})
                                        @endif
                                        @if (request('departemen') && request('departemen') !== 'all')
                                            , Departemen ({{ request('departemen') }})
                                        @endif
                                        @if (request('kategori') && request('kategori') !== 'all')
                                            , Kategori
                                            ({{ $kategoris->where('id', request('kategori'))->first()->nama_kategori ?? 'Unknown' }})
                                        @endif
                                    @else
                                        Tidak ada filter, menampilkan semua data
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Laporan -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-table me-2"></i>
                    Data Laporan Pengadaan
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="laporanTable">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Kode Pengadaan</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Pemohon</th>
                                <th>Departemen</th>
                                <th>Total Estimasi</th>
                                <th>Status</th>
                                <th>Tanggal Approval</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pengadaans as $index => $pengadaan)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <a href="{{ route('pengadaan.show', $pengadaan) }}" class="text-decoration-none">
                                            {{ $pengadaan->kode_pengadaan }}
                                        </a>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($pengadaan->tanggal_pengajuan)->format('d/m/Y') }}</td>
                                    <td>{{ $pengadaan->nama_pemohon }}</td>
                                    <td>{{ $pengadaan->departemen }}</td>
                                    <td>Rp {{ number_format($pengadaan->total_estimasi, 0, ',', '.') }}</td>
                                    <td>
                                        @php
                                            $statusClass = match ($pengadaan->status) {
                                                'draft' => 'secondary',
                                                'submitted' => 'warning',
                                                'approved' => 'success',
                                                'rejected' => 'danger',
                                                'completed' => 'primary',
                                                default => 'secondary',
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $statusClass }}">
                                            {{ ucfirst($pengadaan->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($pengadaan->tanggal_approval)
                                            {{ \Carbon\Carbon::parse($pengadaan->tanggal_approval)->format('d/m/Y') }}
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('pengadaan.show', $pengadaan) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('pengadaan.print', $pengadaan) }}"
                                            class="btn btn-sm btn-outline-secondary" target="_blank">
                                            <i class="bi bi-printer"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted">
                                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                        Tidak ada data pengadaan ditemukan
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden form for export -->
    <form id="exportForm" action="{{ route('pengadaan.export-laporan') }}" method="GET" style="display: none;">
        <input type="hidden" name="start_date" value="{{ request('start_date') }}">
        <input type="hidden" name="end_date" value="{{ request('end_date') }}">
        <input type="hidden" name="status" value="{{ request('status') }}">
        <input type="hidden" name="departemen" value="{{ request('departemen') }}">
        <input type="hidden" name="kategori" value="{{ request('kategori') }}">
    </form>
@endsection

@push('styles')
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

        .card-body {
            padding: 1rem;
        }

        .text-xs {
            font-size: 0.7rem;
        }

        .h-100 {
            height: 100% !important;
        }

        .progress {
            background-color: #e9ecef;
        }

        @media print {

            .btn,
            .card-header,
            form {
                display: none !important;
            }

            .card {
                border: none !important;
                box-shadow: none !important;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        function exportLaporan(format) {
            // Update hidden form with format
            const exportForm = document.getElementById('exportForm');

            // Add format input if not exists
            let formatInput = exportForm.querySelector('input[name="format"]');
            if (!formatInput) {
                formatInput = document.createElement('input');
                formatInput.type = 'hidden';
                formatInput.name = 'format';
                exportForm.appendChild(formatInput);
            }
            formatInput.value = format;

            // Submit the form
            exportForm.submit();
        }

        function printLaporan() {
            // Construct print URL with current filters
            const form = document.querySelector('form[action="{{ route('pengadaan.laporan') }}"]');
            const formData = new FormData(form);
            const params = new URLSearchParams();

            for (let [key, value] of formData.entries()) {
                if (value) {
                    params.append(key, value);
                }
            }

            const printUrl = '{{ route('pengadaan.print-laporan') }}?' + params.toString();
            window.open(printUrl, '_blank');
        }

        // Auto-submit form when filter changes
        document.addEventListener('DOMContentLoaded', function() {
            const filterForm = document.querySelector('form[action="{{ route('pengadaan.laporan') }}"]');
            const selects = filterForm.querySelectorAll('select');

            selects.forEach(select => {
                select.addEventListener('change', function() {
                    // Optional: auto-submit on change
                    // filterForm.submit();
                });
            });
        });
    </script>
