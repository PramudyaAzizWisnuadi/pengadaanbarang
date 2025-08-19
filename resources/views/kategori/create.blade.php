@extends('layouts.app')

@section('title', 'Tambah Kategori Baru')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Form Tambah Kategori Barang</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('kategori.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="nama_kategori" class="form-label">Nama Kategori <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_kategori') is-invalid @enderror"
                                id="nama_kategori" name="nama_kategori" value="{{ old('nama_kategori') }}"
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
                                    name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Aktif
                                </label>
                            </div>
                            <div class="form-text">Kategori aktif akan muncul di form pengadaan.</div>
                        </div>
                    </div>
                </div>

                @if ($departemens)
                    <div class="mb-3">
                        <label for="departemen_id" class="form-label">Departemen <span class="text-danger">*</span></label>
                        <select class="form-select @error('departemen_id') is-invalid @enderror" id="departemen_id"
                            name="departemen_id" required>
                            <option value="">Pilih Departemen</option>
                            @foreach ($departemens as $departemen)
                                <option value="{{ $departemen->id }}"
                                    {{ old('departemen_id') == $departemen->id ? 'selected' : '' }}>
                                    {{ $departemen->nama_departemen }}
                                </option>
                            @endforeach
                        </select>
                        @error('departemen_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Pilih departemen yang akan memiliki kategori ini.</div>
                    </div>
                @endif

                <div class="mb-4">
                    <label for="deskripsi" class="form-label">Deskripsi</label>
                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="4"
                        placeholder="Deskripsi detail tentang kategori ini, jenis barang yang termasuk, dll.">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Opsional. Berikan deskripsi yang jelas untuk membantu user memilih kategori yang
                        tepat.</div>
                </div>

                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Tips:</strong> Gunakan nama kategori yang mudah dipahami dan deskripsi yang jelas.
                    Kategori yang sudah dibuat dapat digunakan dalam form pengadaan barang.
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('kategori.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i>
                        Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>
                        Simpan Kategori
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Preview Card -->
    <div class="card mt-4">
        <div class="card-header">
            <h6 class="mb-0">Preview Kategori</h6>
        </div>
        <div class="card-body">
            <div id="preview-kategori" class="text-muted">
                <p>Isi form di atas untuk melihat preview kategori...</p>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const namaInput = document.getElementById('nama_kategori');
            const deskripsiInput = document.getElementById('deskripsi');
            const statusInput = document.getElementById('is_active');
            const preview = document.getElementById('preview-kategori');

            function updatePreview() {
                const nama = namaInput.value.trim();
                const deskripsi = deskripsiInput.value.trim();
                const isActive = statusInput.checked;

                if (nama) {
                    const statusBadge = isActive ?
                        '<span class="badge bg-success">Aktif</span>' :
                        '<span class="badge bg-secondary">Nonaktif</span>';

                    preview.innerHTML = `
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="mb-1">${nama}</h6>
                        <p class="text-muted mb-2">${deskripsi || 'Tidak ada deskripsi'}</p>
                    </div>
                    <div>
                        ${statusBadge}
                    </div>
                </div>
            `;
                } else {
                    preview.innerHTML =
                        '<p class="text-muted">Isi form di atas untuk melihat preview kategori...</p>';
                }
            }

            namaInput.addEventListener('input', updatePreview);
            deskripsiInput.addEventListener('input', updatePreview);
            statusInput.addEventListener('change', updatePreview);
        });
    </script>
@endpush
