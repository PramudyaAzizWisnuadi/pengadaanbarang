@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Edit User</h4>
        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>
            Kembali
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h6 class="mb-0">Form Edit User</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="jabatan" class="form-label">Jabatan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('jabatan') is-invalid @enderror" id="jabatan"
                                name="jabatan" value="{{ old('jabatan', $user->jabatan) }}" required>
                            @error('jabatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="departemen_id" class="form-label">Departemen <span
                                    class="text-danger">*</span></label>
                            <select class="form-select @error('departemen_id') is-invalid @enderror" id="departemen_id"
                                name="departemen_id" required onchange="updateDepartemenName()">
                                <option value="">Pilih Departemen</option>
                                @foreach ($departemens as $departemen)
                                    <option value="{{ $departemen->id }}" data-nama="{{ $departemen->nama_departemen }}"
                                        {{ old('departemen_id', $user->departemen_id) == $departemen->id ? 'selected' : '' }}>
                                        {{ $departemen->kode_departemen }} - {{ $departemen->nama_departemen }}
                                    </option>
                                @endforeach
                            </select>
                            @error('departemen_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <input type="hidden" name="departemen" id="departemen_name"
                                value="{{ old('departemen', $user->departemen) }}">
                        </div>
                    </div>
                </div>

                <hr>

                <h6 class="mb-3">Ubah Password (Opsional)</h6>
                <p class="text-muted small">Kosongkan jika tidak ingin mengubah password</p>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="password" class="form-label">Password Baru</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                id="password" name="password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" class="form-control" id="password_confirmation"
                                name="password_confirmation">
                        </div>
                    </div>
                </div>

                <hr>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('users.show', $user) }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle me-1"></i>
                        Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i>
                        Update User
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function updateDepartemenName() {
            const select = document.getElementById('departemen_id');
            const hiddenInput = document.getElementById('departemen_name');
            const selectedOption = select.options[select.selectedIndex];

            if (selectedOption.value) {
                hiddenInput.value = selectedOption.getAttribute('data-nama');
            } else {
                hiddenInput.value = '';
            }
        }

        // Set initial value if there's old input
        document.addEventListener('DOMContentLoaded', function() {
            updateDepartemenName();
        });
    </script>
@endsection
