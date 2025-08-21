@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="bi bi-building me-2"></i>
                            Detail Departemen: {{ $departemen->nama_departemen }}
                        </h4>
                        <div>
                            <a href="{{ route('departemen.edit', $departemen) }}" class="btn btn-warning">
                                <i class="bi bi-pencil me-1"></i>
                                Edit
                            </a>
                            <a href="{{ route('departemen.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-1"></i>
                                Kembali
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- Informasi Departemen -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">Informasi Departemen</h5>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-borderless">
                                            <tr>
                                                <td><strong>Kode Departemen:</strong></td>
                                                <td>{{ $departemen->kode_departemen }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Nama Departemen:</strong></td>
                                                <td>{{ $departemen->nama_departemen }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Keterangan:</strong></td>
                                                <td>{{ $departemen->keterangan ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Tanggal Dibuat:</strong></td>
                                                <td>{{ $departemen->created_at->format('d/m/Y H:i') }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Terakhir Diupdate:</strong></td>
                                                <td>{{ $departemen->updated_at->format('d/m/Y H:i') }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">Statistik</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row text-center">
                                            <div class="col-4">
                                                <div class="border-end">
                                                    <h3 class="text-primary">{{ $departemen->users->count() }}</h3>
                                                    <small class="text-muted">Users</small>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <div class="border-end">
                                                    <h3 class="text-success">{{ $departemen->pengadaanBarangs->count() }}
                                                    </h3>
                                                    <small class="text-muted">Pengadaan</small>
                                                </div>
                                            </div>
                                            <div class="col-4">
                                                <h3 class="text-info">{{ $departemen->kategoriBarangs->count() }}</h3>
                                                <small class="text-muted">Kategori</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Daftar Users -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="bi bi-people me-2"></i>
                                    Daftar Users ({{ $departemen->users->count() }})
                                </h5>
                            </div>
                            <div class="card-body">
                                @if ($departemen->users->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Nama</th>
                                                    <th>Email</th>
                                                    <th>Jabatan</th>
                                                    <th>Role</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($departemen->users as $user)
                                                    <tr>
                                                        <td>{{ $user->name }}</td>
                                                        <td>{{ $user->email }}</td>
                                                        <td>{{ $user->jabatan ?? '-' }}</td>
                                                        <td>
                                                            <span
                                                                class="badge bg-{{ $user->role === 'super_admin' ? 'danger' : ($user->role === 'admin' ? 'warning' : 'primary') }}">
                                                                {{ ucfirst($user->role) }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-success">Active</span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center text-muted py-4">
                                        <i class="bi bi-people display-1"></i>
                                        <p class="mt-2">Belum ada user di departemen ini</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Daftar Pengadaan -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="bi bi-cart me-2"></i>
                                    Daftar Pengadaan ({{ $departemen->pengadaanBarangs->count() }})
                                </h5>
                            </div>
                            <div class="card-body">
                                @if ($departemen->pengadaanBarangs->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Kode</th>
                                                    <th>Nama Pemohon</th>
                                                    <th>Keterangan</th>
                                                    <th>Total Estimasi</th>
                                                    <th>Status</th>
                                                    <th>Tanggal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($departemen->pengadaanBarangs->take(10) as $pengadaan)
                                                    <tr>
                                                        <td>
                                                            <a href="{{ route('pengadaan.show', $pengadaan) }}"
                                                                class="text-decoration-none">
                                                                {{ $pengadaan->kode_pengadaan }}
                                                            </a>
                                                        </td>
                                                        <td>{{ $pengadaan->nama_pemohon }}</td>
                                                        <td>{{ Str::limit($pengadaan->keterangan, 50) }}</td>
                                                        <td>Rp
                                                            {{ number_format($pengadaan->total_estimasi ?? 0, 0, ',', '.') }}
                                                        </td>
                                                        <td>
                                                            @switch($pengadaan->status)
                                                                @case('draft')
                                                                    <span class="badge bg-secondary">Draft</span>
                                                                @break

                                                                @case('submitted')
                                                                    <span class="badge bg-warning">Submitted</span>
                                                                @break

                                                                @case('approved')
                                                                    <span class="badge bg-success">Approved</span>
                                                                @break

                                                                @case('rejected')
                                                                    <span class="badge bg-danger">Rejected</span>
                                                                @break

                                                                @case('completed')
                                                                    <span class="badge bg-info">Completed</span>
                                                                @break

                                                                @default
                                                                    <span
                                                                        class="badge bg-light text-dark">{{ ucfirst($pengadaan->status) }}</span>
                                                            @endswitch
                                                        </td>
                                                        <td>{{ $pengadaan->tanggal_pengajuan ? $pengadaan->tanggal_pengajuan->format('d/m/Y') : '-' }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        @if ($departemen->pengadaanBarangs->count() > 10)
                                            <div class="text-center mt-3">
                                                <small class="text-muted">Menampilkan 10 dari
                                                    {{ $departemen->pengadaanBarangs->count() }} pengadaan</small>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <div class="text-center text-muted py-4">
                                        <i class="bi bi-cart display-1"></i>
                                        <p class="mt-2">Belum ada pengadaan di departemen ini</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Daftar Kategori Barang -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="bi bi-tags me-2"></i>
                                    Daftar Kategori Barang ({{ $departemen->kategoriBarangs->count() }})
                                </h5>
                            </div>
                            <div class="card-body">
                                @if ($departemen->kategoriBarangs->count() > 0)
                                    <div class="row">
                                        @foreach ($departemen->kategoriBarangs as $kategori)
                                            <div class="col-md-3 mb-2">
                                                <div class="card card-body text-center">
                                                    <h6 class="card-title">{{ $kategori->nama_kategori }}</h6>
                                                    <small class="text-muted">{{ $kategori->keterangan ?? '-' }}</small>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center text-muted py-4">
                                        <i class="bi bi-tags display-1"></i>
                                        <p class="mt-2">Belum ada kategori barang di departemen ini</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
