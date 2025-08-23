@extends('layouts.app')

@section('title', 'Dashboard Pengadaan')

@push('styles')
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">
    
    <style>
        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .status-draft {
            background-color: #6c757d;
            color: white;
        }

        .status-submitted {
            background-color: #ffc107;
            color: #212529;
        }

        .status-approved {
            background-color: #198754;
            color: white;
        }

        .status-rejected {
            background-color: #dc3545;
            color: white;
        }

        .status-completed {
            background-color: #0d6efd;
            color: white;
        }

        /* Custom DataTables styling */
        .dataTables_wrapper .dataTables_length select {
            padding: 0.375rem 0.75rem;
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
        }

        .dataTables_wrapper .dataTables_filter input {
            padding: 0.375rem 0.75rem;
            border: 1px solid #ced4da;
            border-radius: 0.375rem;
            margin-left: 0.5rem;
        }

        .table th {
            cursor: pointer;
            user-select: none;
        }

        .table th:hover {
            background-color: #f8f9fa;
        }
    </style>
@endpush

@section('content')
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Total Pengadaan</h5>
                            <h2 class="mb-0">{{ $statistics->total ?? 0 }}</h2>
                        </div>
                        <i class="bi bi-clipboard-data fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Menunggu Approval</h5>
                            <h2 class="mb-0">{{ $statistics->submitted ?? 0 }}</h2>
                        </div>
                        <i class="bi bi-clock fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Disetujui</h5>
                            <h2 class="mb-0">{{ $statistics->approved ?? 0 }}</h2>
                        </div>
                        <i class="bi bi-check-circle fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Ditolak</h5>
                            <h2 class="mb-0">{{ $statistics->rejected ?? 0 }}</h2>
                        </div>
                        <i class="bi bi-x-circle fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Statistics Row -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-secondary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Draft</h5>
                            <h2 class="mb-0">{{ $statistics->draft ?? 0 }}</h2>
                        </div>
                        <i class="bi bi-file-text fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Selesai</h5>
                            <h2 class="mb-0">{{ $statistics->completed ?? 0 }}</h2>
                        </div>
                        <i class="bi bi-check2-all fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-dark text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Nilai Total (Approved)</h5>
                            <h2 class="mb-0">Rp
                                {{ number_format($pengadaans->where('status', 'completed')->sum('total_estimasi'), 0, ',', '.') }}
                            </h2>
                        </div>
                        <i class="bi bi-currency-dollar fs-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Pengadaan Barang</h5>
            <div class="d-flex gap-2">
                @if (Auth::user()->role === 'super_admin')
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="collapse"
                        data-bs-target="#filterCollapse" aria-expanded="false" aria-controls="filterCollapse">
                        <i class="bi bi-funnel me-1"></i>
                        Filter
                    </button>
                @endif
                <a href="{{ route('pengadaan.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus me-1"></i>
                    Buat Pengadaan
                </a>
            </div>
        </div>

        @if (Auth::user()->role === 'super_admin')
            <div class="collapse" id="filterCollapse">
                <div class="card-body border-top">
                    <form method="GET" action="{{ route('pengadaan.index') }}" class="row g-3">
                        <div class="col-md-3">
                            <label for="filter_departemen" class="form-label">Departemen</label>
                            <select class="form-select form-select-sm" id="filter_departemen" name="departemen">
                                <option value="">Semua Departemen</option>
                                @php
                                    $departemens = \App\Models\Departemen::orderBy('nama_departemen')->get();
                                @endphp
                                @foreach ($departemens as $dept)
                                    <option value="{{ $dept->nama_departemen }}"
                                        {{ request('departemen') == $dept->nama_departemen ? 'selected' : '' }}>
                                        {{ $dept->nama_departemen }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="filter_status" class="form-label">Status</label>
                            <select class="form-select form-select-sm" id="filter_status" name="status">
                                <option value="">Semua Status</option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>
                                    Submitted</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved
                                </option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected
                                </option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>
                                    Completed</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="filter_tanggal" class="form-label">Tanggal Pengajuan</label>
                            <input type="date" class="form-control form-control-sm" id="filter_tanggal" name="tanggal"
                                value="{{ request('tanggal') }}">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <div class="btn-group w-100" role="group">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="bi bi-search me-1"></i>
                                    Filter
                                </button>
                                <a href="{{ route('pengadaan.index') }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="bi bi-x-circle me-1"></i>
                                    Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        <div class="card-body">
            @if ($pengadaans->count() > 0)
                <div class="table-responsive">
                    <table id="pengadaanTable" class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Kode Pengadaan</th>
                                <th>Pemohon</th>
                                <th>Departemen</th>
                                <th>Tanggal</th>
                                <th>Jumlah Barang</th>
                                <th>Total Estimasi</th>
                                <th>Keperluan</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pengadaans as $index => $pengadaan)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $pengadaan->kode_pengadaan }}</td>
                                    <td>{{ $pengadaan->nama_pemohon }}</td>
                                    <td>{{ $pengadaan->departemen }}</td>
                                    <td data-order="{{ $pengadaan->created_at->timestamp }}">{{ $pengadaan->created_at->format('d/m/Y H:i') }}</td>
                                    <td data-order="{{ $pengadaan->barangPengadaan->sum('jumlah') }}">{{ $pengadaan->barangPengadaan->sum('jumlah') }}</td>
                                    <td data-order="{{ $pengadaan->total_estimasi }}">Rp {{ number_format($pengadaan->total_estimasi, 0, ',', '.') }}</td>
                                    <td>{{ Str::limit($pengadaan->keterangan ?? '', 30) }}</td>
                                    <td data-order="{{ $pengadaan->status }}">
                                        @switch($pengadaan->status)
                                            @case('draft')
                                                <span class="status-badge status-draft">Draft</span>
                                            @break

                                            @case('submitted')
                                                <span class="status-badge status-submitted">Submitted</span>
                                            @break

                                            @case('approved')
                                                <span class="status-badge status-approved">
                                                    @if ($pengadaan->skip_approval)
                                                        <i class="bi bi-lightning"></i>
                                                    @endif
                                                    Approved
                                                </span>
                                            @break

                                            @case('rejected')
                                                <span class="status-badge status-rejected">Rejected</span>
                                            @break

                                            @case('completed')
                                                <span class="status-badge status-completed">Completed</span>
                                            @break

                                            @default
                                                <span class="badge bg-secondary">{{ $pengadaan->status }}</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('pengadaan.show', $pengadaan) }}"
                                                class="btn btn-outline-primary" title="Lihat Detail">
                                                <i class="bi bi-eye"></i>
                                            </a>

                                            @if ($pengadaan->status === 'draft')
                                                <a href="{{ route('pengadaan.edit', $pengadaan) }}"
                                                    class="btn btn-outline-warning" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                            @endif

                                            @if ($pengadaan->status === 'approved')
                                                <a href="{{ route('pengadaan.print', $pengadaan) }}"
                                                    class="btn btn-outline-success" title="Print" target="_blank">
                                                    <i class="bi bi-printer"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-inbox display-1 text-muted"></i>
                    <h5 class="mt-3 text-muted">Belum ada data pengadaan</h5>
                    <p class="text-muted">Silakan buat pengadaan baru untuk memulai.</p>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            console.log('jQuery version:', $.fn.jquery);
            console.log('DataTables available:', typeof $.fn.DataTable);
            
            // Check if table exists
            if ($('#pengadaanTable').length === 0) {
                console.error('Table #pengadaanTable not found!');
                return;
            }

            console.log('Table found, initializing DataTables...');
            
            try {
                var table = $('#pengadaanTable').DataTable({
                    pageLength: 25,
                    order: [[4, 'desc']], // Default sort by tanggal (descending)
                    columnDefs: [
                        {
                            targets: [0, 9], // Column "No" dan "Aksi"
                            orderable: false,
                            searchable: false
                        }
                    ],
                    language: {
                        search: "Cari:",
                        lengthMenu: "Tampilkan _MENU_ data per halaman",
                        info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                        infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                        infoFiltered: "(difilter dari _MAX_ total data)",
                        paginate: {
                            first: "Pertama",
                            last: "Terakhir", 
                            next: "Selanjutnya",
                            previous: "Sebelumnya"
                        },
                        emptyTable: "Tidak ada data yang tersedia",
                        zeroRecords: "Tidak ditemukan data yang sesuai"
                    }
                });
                
                console.log('DataTables initialized successfully!');
                
            } catch (error) {
                console.error('Error initializing DataTables:', error);
            }
        });
    </script>
@endpush
