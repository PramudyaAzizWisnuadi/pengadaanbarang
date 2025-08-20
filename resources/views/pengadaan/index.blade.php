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
            <div class="table-responsive">
                <table class="table table-hover" id="pengadaanTable">
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
                        <!-- Data will be loaded via AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
        <!-- DataTables CSS & JS -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
        <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

        <script>
            $(document).ready(function() {
                // Initialize DataTable
                var table = $('#pengadaanTable').DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    ajax: {
                        url: "{{ route('pengadaan.index') }}",
                        type: 'GET'
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'kode_pengadaan',
                            name: 'kode_pengadaan'
                        },
                        {
                            data: 'nama_pemohon',
                            name: 'nama_pemohon'
                        },
                        {
                            data: 'departemen',
                            name: 'departemen'
                        },
                        {
                            data: 'tanggal_formatted',
                            name: 'created_at'
                        },
                        {
                            data: 'jumlah_barang',
                            name: 'jumlah_barang',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'keterangan_limited',
                            name: 'keterangan'
                        },
                        {
                            data: 'status_badge',
                            name: 'status'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        }
                    ],
                    language: {
                        processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
                        lengthMenu: "Tampilkan _MENU_ data per halaman",
                        zeroRecords: "Tidak ada data yang ditemukan",
                        info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                        infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                        infoFiltered: "(difilter dari _MAX_ total data)",
                        search: "Cari:",
                        paginate: {
                            first: "Pertama",
                            last: "Terakhir",
                            next: "Selanjutnya",
                            previous: "Sebelumnya"
                        }
                    },
                    order: [
                        [1, 'desc']
                    ],
                    pageLength: 10,
                    lengthMenu: [
                        [10, 25, 50, 100],
                        [10, 25, 50, 100]
                    ]
                });

                // Handle delete pengadaan
                $('#pengadaanTable').on('click', '.delete-pengadaan', function() {
                    var id = $(this).data('id');

                    Swal.fire({
                        title: 'Konfirmasi Hapus',
                        text: 'Apakah Anda yakin ingin menghapus pengadaan ini? Tindakan ini tidak dapat dibatalkan.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: "{{ route('pengadaan.destroy', ':id') }}".replace(':id',
                                    id),
                                type: 'DELETE',
                                data: {
                                    '_token': '{{ csrf_token() }}'
                                },
                                success: function(response) {
                                    if (response.success) {
                                        table.ajax.reload(null, false);
                                        Swal.fire({
                                            title: 'Berhasil!',
                                            text: response.message,
                                            icon: 'success',
                                            toast: true,
                                            position: 'top-end',
                                            showConfirmButton: false,
                                            timer: 3000
                                        });
                                    } else {
                                        Swal.fire({
                                            title: 'Error!',
                                            text: response.message ||
                                                'Terjadi kesalahan',
                                            icon: 'error',
                                            toast: true,
                                            position: 'top-end',
                                            showConfirmButton: false,
                                            timer: 3000
                                        });
                                    }
                                },
                                error: function() {
                                    Swal.fire({
                                        title: 'Error!',
                                        text: 'Terjadi kesalahan saat menghapus pengadaan',
                                        icon: 'error',
                                        toast: true,
                                        position: 'top-end',
                                        showConfirmButton: false,
                                        timer: 3000
                                    });
                                }
                            });
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
