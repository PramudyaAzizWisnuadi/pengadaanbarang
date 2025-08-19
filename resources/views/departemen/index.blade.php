@extends('layouts.app')

@section('title', 'Kelola Departemen')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Departemen</h5>
            <a href="{{ route('departemen.create') }}" class="btn btn-primary">
                <i class="bi bi-plus me-1"></i>
                Tambah Departemen
            </a>
        </div>
        <div class="card-body">
            @if ($departemens->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Nama Departemen</th>
                                <th>Kepala Departemen</th>
                                <th>Jumlah User</th>
                                <th>Jumlah Pengadaan</th>
                                <th>Jumlah Kategori</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($departemens as $index => $departemen)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td><span class="badge bg-secondary">{{ $departemen->kode_departemen }}</span></td>
                                    <td>{{ $departemen->nama_departemen }}</td>
                                    <td>{{ $departemen->kepala_departemen ?? '-' }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $departemen->users_count }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning">{{ $departemen->pengadaan_barangs_count }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">{{ $departemen->kategori_barangs_count }}</span>
                                    </td>
                                    <td>
                                        @if ($departemen->is_active)
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-danger">Tidak Aktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('departemen.show', $departemen) }}"
                                                class="btn btn-outline-primary" title="Lihat Detail">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('departemen.edit', $departemen) }}"
                                                class="btn btn-outline-warning" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            @if ($departemen->users_count == 0 && $departemen->pengadaan_barangs_count == 0)
                                                <form action="{{ route('departemen.destroy', $departemen) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus departemen ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger" title="Hapus">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-building display-1 text-muted"></i>
                    <h5 class="mt-3 text-muted">Belum ada departemen</h5>
                    <p class="text-muted">Silakan tambah departemen baru untuk memulai.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
