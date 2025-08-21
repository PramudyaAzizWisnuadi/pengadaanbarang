@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="bi bi-plus-circle me-2"></i>
                        Tambah Departemen Baru
                    </h4>
                    <a href="{{ route('departemen.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i>
                        Kembali
                    </a>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('departemen.store') }}">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="kode_departemen" class="form-label">
                                    Kode Departemen <span class="text-danger">*</span>
                                </label>
                                <input id="kode_departemen" type="text" 
                                       class="form-control @error('kode_departemen') is-invalid @enderror" 
                                       name="kode_departemen" 
                                       value="{{ old('kode_departemen') }}" 
                                       required autofocus maxlength="10"
                                       placeholder="Contoh: IT, HR, FIN"
                                       style="text-transform: uppercase;">

                                @error('kode_departemen')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <small class="form-text text-muted">
                                    Kode unik departemen (maksimal 10 karakter)
                                </small>
                            </div>

                            <div class="col-md-6">
                                <label for="nama_departemen" class="form-label">
                                    Nama Departemen <span class="text-danger">*</span>
                                </label>
                                <input id="nama_departemen" type="text" 
                                       class="form-control @error('nama_departemen') is-invalid @enderror" 
                                       name="nama_departemen" 
                                       value="{{ old('nama_departemen') }}" 
                                       required maxlength="100"
                                       placeholder="Contoh: Information Technology">

                                @error('nama_departemen')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <small class="form-text text-muted">
                                    Nama lengkap departemen (maksimal 100 karakter)
                                </small>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="keterangan" class="form-label">Keterangan</label>
                                <textarea id="keterangan" 
                                          class="form-control @error('keterangan') is-invalid @enderror" 
                                          name="keterangan" 
                                          rows="3"
                                          maxlength="500"
                                          placeholder="Deskripsi atau keterangan tambahan tentang departemen (opsional)">{{ old('keterangan') }}</textarea>

                                @error('keterangan')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <small class="form-text text-muted">
                                    Deskripsi singkat tentang departemen (maksimal 500 karakter)
                                </small>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                                           {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Departemen Aktif
                                    </label>
                                </div>
                                <small class="form-text text-muted">
                                    Departemen yang tidak aktif tidak akan muncul dalam pilihan saat membuat pengadaan baru
                                </small>
                            </div>
                        </div>

                        <hr>

                        <div class="row mb-0">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle me-1"></i>
                                    Simpan Departemen
                                </button>
                                <a href="{{ route('departemen.index') }}" class="btn btn-secondary ms-2">
                                    <i class="bi bi-x-circle me-1"></i>
                                    Batal
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Informasi Tambahan -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-info-circle me-2"></i>
                        Panduan Pembuatan Departemen
                    </h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-0">
                        <h6 class="alert-heading">Tips untuk membuat departemen:</h6>
                        <ul class="mb-0">
                            <li><strong>Kode Departemen:</strong> Gunakan singkatan yang mudah diingat (IT, HR, FIN, OPS, dll)</li>
                            <li><strong>Nama Departemen:</strong> Gunakan nama yang jelas dan formal</li>
                            <li><strong>Keterangan:</strong> Jelaskan tugas dan tanggung jawab utama departemen</li>
                            <li><strong>Status Aktif:</strong> Hanya departemen aktif yang bisa digunakan untuk pengadaan</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Preview -->
            <div class="card mt-4" id="preview-card" style="display: none;">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-eye me-2"></i>
                        Preview Departemen
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Kode:</strong> <span id="preview-kode">-</span>
                        </div>
                        <div class="col-md-6">
                            <strong>Nama:</strong> <span id="preview-nama">-</span>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <strong>Keterangan:</strong> <span id="preview-keterangan">-</span>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <strong>Status:</strong> <span id="preview-status" class="badge">-</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-uppercase kode departemen
    const kodeInput = document.getElementById('kode_departemen');
    kodeInput.addEventListener('input', function() {
        this.value = this.value.toUpperCase();
        updatePreview();
    });

    // Update preview when inputs change
    const namaInput = document.getElementById('nama_departemen');
    const keteranganInput = document.getElementById('keterangan');
    const activeCheckbox = document.getElementById('is_active');

    namaInput.addEventListener('input', updatePreview);
    keteranganInput.addEventListener('input', updatePreview);
    activeCheckbox.addEventListener('change', updatePreview);

    function updatePreview() {
        const kode = kodeInput.value.trim();
        const nama = namaInput.value.trim();
        const keterangan = keteranganInput.value.trim();
        const isActive = activeCheckbox.checked;

        if (kode || nama) {
            document.getElementById('preview-card').style.display = 'block';
            document.getElementById('preview-kode').textContent = kode || '-';
            document.getElementById('preview-nama').textContent = nama || '-';
            document.getElementById('preview-keterangan').textContent = keterangan || '-';
            
            const statusSpan = document.getElementById('preview-status');
            statusSpan.textContent = isActive ? 'Aktif' : 'Tidak Aktif';
            statusSpan.className = 'badge ' + (isActive ? 'bg-success' : 'bg-secondary');
        } else {
            document.getElementById('preview-card').style.display = 'none';
        }
    }

    // Character counter for keterangan
    const keteranganCounter = document.createElement('small');
    keteranganCounter.className = 'form-text text-muted float-end';
    keteranganInput.parentNode.appendChild(keteranganCounter);
    
    keteranganInput.addEventListener('input', function() {
        const remaining = 500 - this.value.length;
        keteranganCounter.textContent = `${remaining} karakter tersisa`;
        keteranganCounter.className = `form-text float-end ${remaining < 50 ? 'text-danger' : 'text-muted'}`;
    });
    
    // Initial counter update
    keteranganInput.dispatchEvent(new Event('input'));
});
</script>
@endsection
