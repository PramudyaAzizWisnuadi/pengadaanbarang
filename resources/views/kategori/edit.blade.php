@extends('layouts.app')

@section('title', 'Edit Kategori - ' . $kategori->nama_kategori)

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Edit Kategori Barang</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('kategori.update', $kategori) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="nama_kategori" class="form-label">Nama Kategori <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_kategori') is-invalid @enderror"
                                id="nama_kategori" name="nama_kategori"
                                value="{{ old('nama_kategori', $kategori->nama_kategori) }}"
                                placeholder="Contoh: Server & Storage" required>
                            @error('nama_kategori')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Nama kategori harus unik dan deskriptif.</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <div class="form-check form-switch">
                                <input type="hidden" name="is_active" value="0">
                                <input class="form-check-input" type="checkbox" role="switch" id="is_active"
                                    name="is_active" value="1"
                                    {{ old('is_active', $kategori->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Aktif
                                </label>
                            </div>
                            <div class="form-text">Kategori aktif akan muncul di form pengadaan.</div>
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="deskripsi" class="form-label">Deskripsi</label>
                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="4"
                        placeholder="Deskripsi detail tentang kategori ini, jenis barang yang termasuk, dll.">{{ old('deskripsi', $kategori->deskripsi) }}</textarea>
                    @error('deskripsi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Opsional. Berikan deskripsi yang jelas untuk membantu user memilih kategori yang
                        tepat.</div>
                </div>

                @if ($kategori->barangPengadaan()->count() > 0)
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Perhatian:</strong> Kategori ini sudah digunakan dalam
                        {{ $kategori->barangPengadaan()->count() }} pengadaan barang.
                        Perubahan nama kategori akan mempengaruhi data pengadaan yang sudah ada.
                    </div>
                @endif

                <div class="d-flex justify-content-between">
                    <a href="{{ route('kategori.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i>
                        Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>
                        Update Kategori
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Usage Statistics -->
    @if ($kategori->barangPengadaan()->count() > 0)
        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0">Statistik Penggunaan</h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-4">
                        <div class="border rounded p-3">
                            <h4 class="text-primary">{{ $kategori->barangPengadaan()->count() }}</h4>
                            <small class="text-muted">Total Pengadaan</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded p-3">
                            <h4 class="text-success">
                                {{ $kategori->barangPengadaan()->whereHas('pengadaanBarang', function ($q) {$q->where('status', 'approved');})->count() }}
                            </h4>
                            <small class="text-muted">Pengadaan Approved</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="border rounded p-3">
                            <h4 class="text-info">{{ $kategori->barangPengadaan()->sum('jumlah') }}</h4>
                            <small class="text-muted">Total Item</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
