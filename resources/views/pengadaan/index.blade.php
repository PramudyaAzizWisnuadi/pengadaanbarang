@extends('layouts.app')

@section('title', 'Dashboard Pengadaan')

@push('styles')
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

        /* Custom Pagination Styling */
        .pagination {
            margin-bottom: 0;
        }
        
        .pagination .page-link {
            color: #6c757d;
            border-color: #dee2e6;
        }
        
        .pagination .page-item.active .page-link {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }
        
        .pagination .page-link:hover {
            color: #0d6efd;
            background-color: #e9ecef;
            border-color: #dee2e6;
        }

        /* Search highlight */
        .search-highlight {
            background-color: #fff3cd;
            padding: 1px 3px;
            border-radius: 3px;
            font-weight: 600;
        }

        /* Search info styling */
        .search-info {
            background-color: #e7f3ff;
            border: 1px solid #b6d7ff;
            border-radius: 6px;
            padding: 8px 12px;
            margin-bottom: 15px;
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
                        <div class="col-md-2">
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
                        <div class="col-md-2">
                            <label for="filter_pemohon" class="form-label">Pemohon</label>
                            <input type="text" class="form-control form-control-sm" id="filter_pemohon" name="pemohon"
                                value="{{ request('pemohon') }}" placeholder="Nama pemohon...">
                        </div>
                        <div class="col-md-2">
                            <label for="filter_tanggal" class="form-label">Tanggal Pengajuan</label>
                            <input type="date" class="form-control form-control-sm" id="filter_tanggal" name="tanggal"
                                value="{{ request('tanggal') }}">
                        </div>
                        <div class="col-md-2">
                            <label for="per_page" class="form-label">Per Halaman</label>
                            <select class="form-select form-select-sm" id="per_page" name="per_page">
                                <option value="15" {{ request('per_page') == '15' ? 'selected' : '' }}>15</option>
                                <option value="25" {{ request('per_page') == '25' ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request('per_page') == '100' ? 'selected' : '' }}>100</option>
                            </select>
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
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

        <!-- Search Bar for All Users -->
        <div class="card-body border-top">
            <form method="GET" action="{{ route('pengadaan.index') }}" class="row g-3 align-items-end">
                <!-- Preserve existing filters -->
                @if (Auth::user()->role === 'super_admin')
                    <input type="hidden" name="departemen" value="{{ request('departemen') }}">
                    <input type="hidden" name="status" value="{{ request('status') }}">
                    <input type="hidden" name="pemohon" value="{{ request('pemohon') }}">
                    <input type="hidden" name="tanggal" value="{{ request('tanggal') }}">
                @endif
                <input type="hidden" name="per_page" value="{{ request('per_page', 15) }}">
                
                <div class="col-md-10">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" class="form-control" name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Cari berdasarkan kode pengadaan, nama pemohon, departemen, atau keterangan...">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="btn-group w-100" role="group">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search me-1"></i>
                            Cari
                        </button>
                        @if (request('search'))
                            <a href="{{ route('pengadaan.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <div class="card-body">
            @if (request('search'))
                <div class="search-info">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="bi bi-search me-2"></i>
                            <strong>Hasil pencarian untuk:</strong> "{{ request('search') }}"
                            @if ($pengadaans->total() > 0)
                                <span class="text-muted">({{ $pengadaans->total() }} data ditemukan)</span>
                            @endif
                        </div>
                        <a href="{{ route('pengadaan.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-x me-1"></i>
                            Hapus Pencarian
                        </a>
                    </div>
                </div>
            @endif

            @if ($pengadaans->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Kode Pengadaan</th>
                                <th>Pemohon</th>
                                <th>Departemen</th>
                                <th>Tanggal</th>
                                <th>Jumlah Barang</th>
                                <th>Keperluan</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pengadaans as $index => $pengadaan)
                                <tr>
                                    <td>{{ ($pengadaans->currentPage() - 1) * $pengadaans->perPage() + $index + 1 }}</td>
                                    <td>{{ $pengadaan->kode_pengadaan }}</td>
                                    <td>{{ $pengadaan->nama_pemohon }}</td>
                                    <td>{{ $pengadaan->departemen }}</td>
                                    <td>{{ $pengadaan->created_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ $pengadaan->barangPengadaan->sum('jumlah') }}</td>
                                    <td>{{ Str::limit($pengadaan->keterangan ?? '', 30) }}</td>
                                    <td>
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

                                            @if ($pengadaan->status === 'draft' || (in_array(Auth::user()->role, ['admin', 'super_admin']) && $pengadaan->status === 'submitted'))
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
                
                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted small">
                        @if($pengadaans->total() > 0)
                            Menampilkan {{ $pengadaans->firstItem() }} sampai {{ $pengadaans->lastItem() }} 
                            dari {{ $pengadaans->total() }} data
                            @if(request()->hasAny(['departemen', 'status', 'pemohon', 'tanggal', 'search']))
                                @if(request('search'))
                                    (hasil pencarian: "{{ request('search') }}")
                                @else
                                    (difilter)
                                @endif
                            @endif
                        @else
                            @if(request('search'))
                                Tidak ada data ditemukan untuk pencarian: "{{ request('search') }}"
                            @else
                                Tidak ada data ditemukan
                            @endif
                        @endif
                    </div>
                    <div>
                        {{ $pengadaans->appends(request()->query())->links() }}
                    </div>
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
<script>
    // Function to highlight search terms
    function highlightSearchTerms() {
        const searchTerm = '{{ request("search") }}';
        if (searchTerm) {
            const cells = document.querySelectorAll('table tbody td');
            cells.forEach(cell => {
                if (cell.textContent.toLowerCase().includes(searchTerm.toLowerCase())) {
                    const regex = new RegExp(`(${searchTerm})`, 'gi');
                    cell.innerHTML = cell.innerHTML.replace(regex, '<span class="search-highlight">$1</span>');
                }
            });
        }
    }

    // Run highlighting when page loads
    document.addEventListener('DOMContentLoaded', highlightSearchTerms);
</script>
@endpush
