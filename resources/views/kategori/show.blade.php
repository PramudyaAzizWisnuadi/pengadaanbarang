@extends('layouts.app')

@section('title', 'Detail Kategori - ' . $kategori->nama_kategori)

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Detail Kategori</h5>
                    @if ($kategori->is_active)
                        <span class="badge bg-success fs-6">Aktif</span>
                    @else
                        <span class="badge bg-secondary fs-6">Nonaktif</span>
                    @endif
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td class="fw-bold">Nama Kategori:</td>
                                    <td>{{ $kategori->nama_kategori }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Status:</td>
                                    <td>
                                        @if ($kategori->is_active)
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary">Nonaktif</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Dibuat:</td>
                                    <td>{{ $kategori->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Terakhir Update:</td>
                                    <td>{{ $kategori->updated_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td class="fw-bold">Total Pengadaan:</td>
                                    <td class="fw-bold text-primary">{{ $kategori->barangPengadaan->count() }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Total Item:</td>
                                    <td class="fw-bold text-info">{{ $kategori->barangPengadaan->sum('jumlah') }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Total Nilai:</td>
                                    <td class="fw-bold text-success">Rp
                                        {{ number_format($kategori->barangPengadaan->sum('total_harga'), 0, ',', '.') }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if ($kategori->deskripsi)
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2">Deskripsi</h6>
                            <p class="mb-0">{{ $kategori->deskripsi }}</p>
                        </div>
                    @endif

                    @if ($kategori->barangPengadaan->count() > 0)
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2">Pengadaan Terbaru</h6>
                            <div class="table-responsive">
                                <table class="table table-striped table-sm">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Kode Pengadaan</th>
                                            <th>Nama Barang</th>
                                            <th>Jumlah</th>
                                            <th>Harga</th>
                                            <th>Status</th>
                                            <th>Tanggal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($kategori->barangPengadaan->take(10) as $barang)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('pengadaan.show', $barang->pengadaanBarang) }}"
                                                        class="text-decoration-none">
                                                        {{ $barang->pengadaanBarang->kode_pengadaan }}
                                                    </a>
                                                </td>
                                                <td>{{ $barang->nama_barang }}</td>
                                                <td>{{ $barang->jumlah }} {{ $barang->satuan }}</td>
                                                <td>Rp {{ number_format($barang->total_harga, 0, ',', '.') }}</td>
                                                <td>
                                                    <span
                                                        class="status-badge status-{{ $barang->pengadaanBarang->status }}">
                                                        {{ ucfirst($barang->pengadaanBarang->status) }}
                                                    </span>
                                                </td>
                                                <td>{{ $barang->pengadaanBarang->tanggal_pengajuan->format('d/m/Y') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            @if ($kategori->barangPengadaan->count() > 10)
                                <div class="text-center mt-3">
                                    <small class="text-muted">Dan {{ $kategori->barangPengadaan->count() - 10 }} pengadaan
                                        lainnya...</small>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Action Panel -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Aksi</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('kategori.edit', $kategori) }}" class="btn btn-warning">
                            <i class="bi bi-pencil me-1"></i>
                            Edit Kategori
                        </a>

                        <form action="{{ route('kategori.toggle-status', $kategori) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="btn btn-{{ $kategori->is_active ? 'secondary' : 'success' }} w-100">
                                <i class="bi bi-toggle-{{ $kategori->is_active ? 'off' : 'on' }} me-1"></i>
                                {{ $kategori->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                            </button>
                        </form>

                        @if ($kategori->barangPengadaan->count() == 0)
                            <form action="{{ route('kategori.destroy', $kategori) }}" method="POST"
                                onsubmit="return confirmDeleteKategori(event, '{{ $kategori->nama_kategori }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger w-100">
                                    <i class="bi bi-trash me-1"></i>
                                    Hapus Kategori
                                </button>
                            </form>
                        @else
                            <button type="button" class="btn btn-danger w-100 disabled"
                                title="Tidak dapat dihapus karena masih digunakan">
                                <i class="bi bi-trash me-1"></i>
                                Hapus Kategori
                            </button>
                            <small class="text-muted mt-1">Kategori tidak dapat dihapus karena masih digunakan dalam
                                pengadaan.</small>
                        @endif

                        <a href="{{ route('kategori.index') }}" class="btn btn-outline-primary">
                            <i class="bi bi-arrow-left me-1"></i>
                            Kembali ke Daftar
                        </a>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0">Statistik</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-12 mb-3">
                            <div class="border rounded p-2">
                                <h5 class="text-primary mb-1">{{ $kategori->barangPengadaan->count() }}</h5>
                                <small class="text-muted">Total Pengadaan</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="border rounded p-2">
                                <h6 class="text-success mb-1">
                                    {{ $kategori->barangPengadaan->filter(function ($item) {return $item->pengadaanBarang && $item->pengadaanBarang->status === 'approved';})->count() }}
                                </h6>
                                <small class="text-muted">Approved</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="border rounded p-2">
                                <h6 class="text-warning mb-1">
                                    {{ $kategori->barangPengadaan->filter(function ($item) {return $item->pengadaanBarang && $item->pengadaanBarang->status === 'submitted';})->count() }}
                                </h6>
                                <small class="text-muted">Pending</small>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="border rounded p-2">
                                <h6 class="text-info mb-1">{{ $kategori->barangPengadaan->sum('jumlah') }}</h6>
                                <small class="text-muted">Total Item</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmDeleteKategori(event, namaKategori) {
            event.preventDefault();
            
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: `Apakah Anda yakin ingin menghapus kategori "${namaKategori}"? Tindakan ini tidak dapat dibatalkan!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    event.target.submit();
                }
            });
            
            return false;
        }
    </script>
@endsection
