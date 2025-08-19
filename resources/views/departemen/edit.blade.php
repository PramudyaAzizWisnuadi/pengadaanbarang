@extends('layouts.app')

@section('title', 'Edit Departemen')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Edit Departemen: {{ $departemen->nama_departemen }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('departemen.update', $departemen) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Nama Departemen <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_departemen') is-invalid @enderror"
                                name="nama_departemen" value="{{ old('nama_departemen', $departemen->nama_departemen) }}"
                                required>
                            @error('nama_departemen')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Kode Departemen <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('kode_departemen') is-invalid @enderror"
                                name="kode_departemen" value="{{ old('kode_departemen', $departemen->kode_departemen) }}"
                                placeholder="Contoh: IT, HR, FIN" maxlength="10" required>
                            @error('kode_departemen')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Kepala Departemen</label>
                    <input type="text" class="form-control @error('kepala_departemen') is-invalid @enderror"
                        name="kepala_departemen" value="{{ old('kepala_departemen', $departemen->kepala_departemen) }}">
                    @error('kepala_departemen')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" name="deskripsi" rows="3">{{ old('deskripsi', $departemen->deskripsi) }}</textarea>
                    @error('deskripsi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active"
                            {{ old('is_active', $departemen->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            Departemen Aktif
                        </label>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('departemen.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i>
                        Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>
                        Perbarui
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
