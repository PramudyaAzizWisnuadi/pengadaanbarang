@extends('layouts.app')

@section('title', 'Detail User')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Detail User</h4>
        <div>
            <a href="{{ route('users.edit', $user) }}" class="btn btn-warning me-2">
                <i class="bi bi-pencil me-1"></i>
                Edit
            </a>
            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Informasi User</h6>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td class="fw-bold" width="150">Nama:</td>
                            <td>{{ $user->name }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Email:</td>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Jabatan:</td>
                            <td>{{ $user->jabatan }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Departemen:</td>
                            <td>
                                @if ($user->departemen_id && $user->departemenRelation && is_object($user->departemenRelation))
                                    <span
                                        class="badge bg-primary me-2">{{ $user->departemenRelation->kode_departemen }}</span>
                                    {{ $user->departemenRelation->nama_departemen }}
                                @elseif(!empty($user->getAttributes()['departemen']))
                                    <span class="text-muted">{{ $user->getAttributes()['departemen'] }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Terdaftar:</td>
                            <td>{{ $user->created_at->format('d F Y, H:i') }}</td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Update Terakhir:</td>
                            <td>{{ $user->updated_at->format('d F Y, H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Aksi</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('users.edit', $user) }}" class="btn btn-warning">
                            <i class="bi bi-pencil me-1"></i>
                            Edit User
                        </a>

                        @if ($user->id !== Auth::id())
                            <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger w-100"
                                    onclick="return confirm('Yakin ingin menghapus user ini? Tindakan ini tidak dapat dibatalkan.')">
                                    <i class="bi bi-trash me-1"></i>
                                    Hapus User
                                </button>
                            </form>
                        @else
                            <div class="alert alert-info mb-0">
                                <small><i class="bi bi-info-circle me-1"></i>Anda tidak dapat menghapus akun sendiri</small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Stats Card -->
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0">Statistik</h6>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <h4 class="text-primary">0</h4>
                        <small class="text-muted">Total Pengadaan</small>
                        <p class="text-muted small mt-1">Fitur dalam pengembangan</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
