@extends('layouts.app')

@section('title', 'Manajemen User')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Manajemen User</h4>
        <a href="{{ route('users.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i>
            Tambah User
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h6 class="mb-0">Daftar User</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th width="5%">No</th>
                            <th width="20%">Nama</th>
                            <th width="20%">Email</th>
                            <th width="15%">Jabatan</th>
                            <th width="15%">Departemen</th>
                            <th width="10%">Role</th>
                            <th width="10%">Terdaftar</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $index => $user)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    {{ $user->name }}
                                    @if($user->id === Auth::id())
                                        <span class="badge bg-info ms-1">Anda</span>
                                    @endif
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->jabatan }}</td>
                                <td>
                                    @if($user->departemenRelation)
                                        <span class="badge bg-primary">{{ $user->departemenRelation->kode_departemen }}</span><br>
                                        <small>{{ $user->departemenRelation->nama_departemen }}</small>
                                    @else
                                        <span class="badge bg-secondary">-</span><br>
                                        <small>{{ $user->departemen ?? 'Tidak ada departemen' }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($user->role === 'super_admin')
                                        <span class="badge bg-danger">Super Admin</span>
                                    @elseif($user->role === 'admin')
                                        <span class="badge bg-warning">Admin</span>
                                    @else
                                        <span class="badge bg-success">User</span>
                                    @endif
                                </td>
                                <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('users.show', $user) }}" class="btn btn-outline-info" title="Lihat">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('users.edit', $user) }}" class="btn btn-outline-warning" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        @if($user->id !== Auth::id())
                                            <form method="POST" action="{{ route('users.destroy', $user) }}" style="display: inline;"
                                                onsubmit="return confirmDeleteUser(event, '{{ $user->name }}')">
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
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Tidak ada data user</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function confirmDeleteUser(event, userName) {
            event.preventDefault();
            
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: `Apakah Anda yakin ingin menghapus user "${userName}"? Tindakan ini tidak dapat dibatalkan!`,
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
