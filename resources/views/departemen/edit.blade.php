@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="bi bi-pencil me-2"></i>
                            Edit Departemen
                        </h4>
                        <a href="{{ route('departemen.show', $departemen) }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i>
                            Kembali
                        </a>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('departemen.update', $departemen) }}">
                            @csrf
                            @method('PUT')

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="kode_departemen" class="form-label">
                                        Kode Departemen <span class="text-danger">*</span>
                                    </label>
                                    <input id="kode_departemen" type="text"
                                        class="form-control @error('kode_departemen') is-invalid @enderror"
                                        name="kode_departemen"
                                        value="{{ old('kode_departemen', $departemen->kode_departemen) }}" required
                                        autofocus maxlength="10" placeholder="Contoh: IT, HR, FIN">

                                    @error('kode_departemen')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="nama_departemen" class="form-label">
                                        Nama Departemen <span class="text-danger">*</span>
                                    </label>
                                    <input id="nama_departemen" type="text"
                                        class="form-control @error('nama_departemen') is-invalid @enderror"
                                        name="nama_departemen"
                                        value="{{ old('nama_departemen', $departemen->nama_departemen) }}" required
                                        maxlength="100" placeholder="Contoh: Information Technology">

                                    @error('nama_departemen')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="keterangan" class="form-label">Keterangan</label>
                                    <textarea id="keterangan" class="form-control @error('keterangan') is-invalid @enderror" name="keterangan"
                                        rows="3" placeholder="Deskripsi atau keterangan tambahan tentang departemen">{{ old('keterangan', $departemen->keterangan) }}</textarea>

                                    @error('keterangan')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                                            value="1"
                                            {{ old('is_active', $departemen->is_active ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Departemen Aktif
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">
                                        Departemen yang tidak aktif tidak akan muncul dalam pilihan saat membuat pengadaan
                                        baru
                                    </small>
                                </div>
                            </div>

                            <hr>

                            <!-- Informasi Statistik (Read Only) -->
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <h6 class="text-muted">Informasi Statistik</h6>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="card bg-light">
                                                <div class="card-body text-center">
                                                    <h5 class="text-primary">{{ $departemen->users->count() }}</h5>
                                                    <small class="text-muted">Total Users</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="card bg-light">
                                                <div class="card-body text-center">
                                                    <h5 class="text-success">{{ $departemen->pengadaanBarangs->count() }}
                                                    </h5>
                                                    <small class="text-muted">Total Pengadaan</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="card bg-light">
                                                <div class="card-body text-center">
                                                    <h5 class="text-info">{{ $departemen->kategoriBarangs->count() }}</h5>
                                                    <small class="text-muted">Total Kategori</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted">
                                        Pastikan untuk mempertimbangkan dampak perubahan terhadap data yang sudah ada
                                    </small>
                                </div>
                            </div>

                            <hr>

                            <div class="row mb-0">
                                <div class="col-md-12 d-flex justify-content-between">
                                    <div>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-check-circle me-1"></i>
                                            Update Departemen
                                        </button>
                                        <a href="{{ route('departemen.show', $departemen) }}"
                                            class="btn btn-secondary ms-2">
                                            <i class="bi bi-x-circle me-1"></i>
                                            Batal
                                        </a>
                                    </div>

                                    @if ($departemen->users->count() == 0 && $departemen->pengadaanBarangs->count() == 0)
                                        <div>
                                            <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#deleteModal">
                                                <i class="bi bi-trash me-1"></i>
                                                Hapus Departemen
                                            </button>
                                        </div>
                                    @endif
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
                            Informasi Penting
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info mb-0">
                            <h6 class="alert-heading">Perhatikan hal berikut:</h6>
                            <ul class="mb-0">
                                <li>Kode departemen harus unik dan tidak boleh sama dengan departemen lain</li>
                                <li>Perubahan kode departemen dapat mempengaruhi data pengadaan yang sudah ada</li>
                                <li>Departemen yang memiliki users atau pengadaan aktif tidak dapat dihapus</li>
                                <li>Status tidak aktif akan menyembunyikan departemen dari pilihan saat membuat pengadaan
                                    baru</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    @if ($departemen->users->count() == 0 && $departemen->pengadaanBarangs->count() == 0)
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Konfirmasi Hapus
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menghapus departemen
                            <strong>{{ $departemen->nama_departemen }}</strong>?</p>
                        <p class="text-danger"><small>Tindakan ini tidak dapat dibatalkan!</small></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <form action="{{ route('departemen.destroy', $departemen) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-trash me-1"></i>
                                Ya, Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
