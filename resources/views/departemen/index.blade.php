@extends('layouts.app')

@section('title', 'Kelola Departemen')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Departemen</h5>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createDepartemenModal">
                <i class="bi bi-plus me-1"></i>
                Tambah Departemen
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="departemenTable">
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
                        <!-- Data will be loaded via AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Create Departemen Modal -->
    <div class="modal fade" id="createDepartemenModal" tabindex="-1" aria-labelledby="createDepartemenModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="createDepartemenForm">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="createDepartemenModalLabel">Tambah Departemen</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="create_kode_departemen" class="form-label">Kode Departemen <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="create_kode_departemen" name="kode_departemen"
                                required placeholder="Contoh: IT" maxlength="10">
                            <div class="form-text">Kode unik departemen (max 10 karakter)</div>
                        </div>
                        <div class="mb-3">
                            <label for="create_nama_departemen" class="form-label">Nama Departemen <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="create_nama_departemen" name="nama_departemen"
                                required placeholder="Contoh: Information Technology" maxlength="100">
                        </div>
                        <div class="mb-3">
                            <label for="create_kepala_departemen" class="form-label">Kepala Departemen</label>
                            <input type="text" class="form-control" id="create_kepala_departemen"
                                name="kepala_departemen" placeholder="Nama kepala departemen" maxlength="100">
                        </div>
                        <div class="mb-3">
                            <label for="create_deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="create_deskripsi" name="deskripsi" rows="3"
                                placeholder="Deskripsi departemen (opsional)"></textarea>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="create_is_active" name="is_active"
                                    value="1" checked>
                                <label class="form-check-label" for="create_is_active">
                                    Departemen Aktif
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i>Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Departemen Modal -->
    <div class="modal fade" id="editDepartemenModal" tabindex="-1" aria-labelledby="editDepartemenModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editDepartemenForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_departemen_id" name="departemen_id">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editDepartemenModalLabel">Edit Departemen</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_kode_departemen" class="form-label">Kode Departemen <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_kode_departemen" name="kode_departemen"
                                required placeholder="Contoh: IT" maxlength="10">
                            <div class="form-text">Kode unik departemen (max 10 karakter)</div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_nama_departemen" class="form-label">Nama Departemen <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_nama_departemen" name="nama_departemen"
                                required placeholder="Contoh: Information Technology" maxlength="100">
                        </div>
                        <div class="mb-3">
                            <label for="edit_kepala_departemen" class="form-label">Kepala Departemen</label>
                            <input type="text" class="form-control" id="edit_kepala_departemen"
                                name="kepala_departemen" placeholder="Nama kepala departemen" maxlength="100">
                        </div>
                        <div class="mb-3">
                            <label for="edit_deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="edit_deskripsi" name="deskripsi" rows="3"
                                placeholder="Deskripsi departemen (opsional)"></textarea>
                        </div>
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="edit_is_active" name="is_active"
                                    value="1">
                                <label class="form-check-label" for="edit_is_active">
                                    Departemen Aktif
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-save me-1"></i>Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Departemen Modal -->
    <div class="modal fade" id="viewDepartemenModal" tabindex="-1" aria-labelledby="viewDepartemenModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewDepartemenModalLabel">Detail Departemen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Kode Departemen:</label>
                                <p id="view_kode_departemen" class="mb-0"></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nama Departemen:</label>
                                <p id="view_nama_departemen" class="mb-0"></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Kepala Departemen:</label>
                                <p id="view_kepala_departemen" class="mb-0"></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Status:</label>
                                <p id="view_status" class="mb-0"></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Tanggal Dibuat:</label>
                                <p id="view_created_at" class="mb-0"></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Terakhir Update:</label>
                                <p id="view_updated_at" class="mb-0"></p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Deskripsi:</label>
                                <p id="view_deskripsi" class="mb-0"></p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <i class="bi bi-people fs-1"></i>
                                    <h5 class="card-title mt-2">Users</h5>
                                    <h3 id="view_users_count">0</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <i class="bi bi-box-seam fs-1"></i>
                                    <h5 class="card-title mt-2">Pengadaan</h5>
                                    <h3 id="view_pengadaan_count">0</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <i class="bi bi-tags fs-1"></i>
                                    <h5 class="card-title mt-2">Kategori</h5>
                                    <h3 id="view_kategori_count">0</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- DataTables CSS & JS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTable
            var table = $('#departemenTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: "{{ route('departemen.index') }}",
                    type: 'GET'
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'kode_badge',
                        name: 'kode_departemen'
                    },
                    {
                        data: 'nama_departemen',
                        name: 'nama_departemen'
                    },
                    {
                        data: 'kepala_departemen',
                        name: 'kepala_departemen',
                        render: function(data) {
                            return data || '-';
                        }
                    },
                    {
                        data: 'users_badge',
                        name: 'users_count',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'pengadaan_badge',
                        name: 'pengadaan_barangs_count',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'kategori_badge',
                        name: 'kategori_barangs_count',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'status_badge',
                        name: 'is_active'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                language: {
                    processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>',
                    lengthMenu: "Tampilkan _MENU_ data per halaman",
                    zeroRecords: "Tidak ada data yang ditemukan",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                    infoFiltered: "(difilter dari _MAX_ total data)",
                    search: "Cari:",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Selanjutnya",
                        previous: "Sebelumnya"
                    }
                },
                order: [
                    [2, 'asc']
                ],
                pageLength: 10,
                lengthMenu: [
                    [10, 25, 50, 100],
                    [10, 25, 50, 100]
                ]
            });

            // Handle delete departemen
            $('#departemenTable').on('click', '.delete-departemen', function() {
                var id = $(this).data('id');

                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    text: 'Apakah Anda yakin ingin menghapus departemen ini? Tindakan ini tidak dapat dibatalkan.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('departemen.destroy', ':id') }}".replace(':id',
                                id),
                            type: 'DELETE',
                            data: {
                                '_token': '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success) {
                                    table.ajax.reload(null, false);
                                    Swal.fire({
                                        title: 'Berhasil!',
                                        text: response.message,
                                        icon: 'success',
                                        toast: true,
                                        position: 'top-end',
                                        showConfirmButton: false,
                                        timer: 3000
                                    });
                                } else {
                                    Swal.fire({
                                        title: 'Error!',
                                        text: response.message ||
                                            'Terjadi kesalahan',
                                        icon: 'error',
                                        toast: true,
                                        position: 'top-end',
                                        showConfirmButton: false,
                                        timer: 3000
                                    });
                                }
                            },
                            error: function() {
                                Swal.fire({
                                    title: 'Error!',
                                    text: 'Terjadi kesalahan saat menghapus departemen',
                                    icon: 'error',
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 3000
                                });
                            }
                        });
                    }
                });
            });

            // Handle create departemen form
            $('#createDepartemenForm').on('submit', function(e) {
                e.preventDefault();

                var formData = new FormData(this);

                $.ajax({
                    url: "{{ route('departemen.store') }}",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            $('#createDepartemenModal').modal('hide');
                            $('#createDepartemenForm')[0].reset();
                            table.ajax.reload(null, false);

                            Swal.fire({
                                title: 'Berhasil!',
                                text: response.message,
                                icon: 'success',
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000
                            });
                        }
                    },
                    error: function(xhr) {
                        var errors = xhr.responseJSON.errors;
                        var errorMessage = 'Terjadi kesalahan:\n';

                        if (errors) {
                            Object.keys(errors).forEach(function(key) {
                                errorMessage += '- ' + errors[key][0] + '\n';
                            });
                        } else {
                            errorMessage = xhr.responseJSON.message ||
                                'Terjadi kesalahan saat menyimpan data';
                        }

                        Swal.fire({
                            title: 'Error!',
                            text: errorMessage,
                            icon: 'error',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 5000
                        });
                    }
                });
            });

            // Handle edit departemen
            $('#departemenTable').on('click', '.edit-departemen', function() {
                var id = $(this).data('id');

                $.ajax({
                    url: "{{ route('departemen.show', ':id') }}".replace(':id', id),
                    type: 'GET',
                    success: function(response) {
                        if (response.success) {
                            var dept = response.data;

                            $('#edit_departemen_id').val(dept.id);
                            $('#edit_kode_departemen').val(dept.kode_departemen);
                            $('#edit_nama_departemen').val(dept.nama_departemen);
                            $('#edit_kepala_departemen').val(dept.kepala_departemen);
                            $('#edit_deskripsi').val(dept.deskripsi);
                            $('#edit_is_active').prop('checked', dept.is_active == 1);

                            $('#editDepartemenModal').modal('show');
                        }
                    },
                    error: function() {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Gagal mengambil data departemen',
                            icon: 'error',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                        });
                    }
                });
            });

            // Handle update departemen form
            $('#editDepartemenForm').on('submit', function(e) {
                e.preventDefault();

                var id = $('#edit_departemen_id').val();
                var formData = new FormData(this);

                $.ajax({
                    url: "{{ route('departemen.update', ':id') }}".replace(':id', id),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            $('#editDepartemenModal').modal('hide');
                            table.ajax.reload(null, false);

                            Swal.fire({
                                title: 'Berhasil!',
                                text: response.message,
                                icon: 'success',
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000
                            });
                        }
                    },
                    error: function(xhr) {
                        var errors = xhr.responseJSON.errors;
                        var errorMessage = 'Terjadi kesalahan:\n';

                        if (errors) {
                            Object.keys(errors).forEach(function(key) {
                                errorMessage += '- ' + errors[key][0] + '\n';
                            });
                        } else {
                            errorMessage = xhr.responseJSON.message ||
                                'Terjadi kesalahan saat mengupdate data';
                        }

                        Swal.fire({
                            title: 'Error!',
                            text: errorMessage,
                            icon: 'error',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 5000
                        });
                    }
                });
            });

            // Handle view departemen
            $('#departemenTable').on('click', '.view-departemen', function() {
                var id = $(this).data('id');

                $.ajax({
                    url: "{{ route('departemen.show', ':id') }}".replace(':id', id),
                    type: 'GET',
                    success: function(response) {
                        if (response.success) {
                            var dept = response.data;

                            $('#view_kode_departemen').text(dept.kode_departemen);
                            $('#view_nama_departemen').text(dept.nama_departemen);
                            $('#view_kepala_departemen').text(dept.kepala_departemen || '-');
                            $('#view_deskripsi').text(dept.deskripsi || '-');
                            $('#view_status').html(dept.is_active == 1 ?
                                '<span class="badge bg-success">Aktif</span>' :
                                '<span class="badge bg-danger">Tidak Aktif</span>'
                            );
                            $('#view_created_at').text(new Date(dept.created_at)
                                .toLocaleDateString('id-ID'));
                            $('#view_updated_at').text(new Date(dept.updated_at)
                                .toLocaleDateString('id-ID'));
                            $('#view_users_count').text(dept.users_count || 0);
                            $('#view_pengadaan_count').text(dept.pengadaan_count || 0);
                            $('#view_kategori_count').text(dept.kategori_count || 0);

                            $('#viewDepartemenModal').modal('show');
                        }
                    },
                    error: function() {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Gagal mengambil data departemen',
                            icon: 'error',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                        });
                    }
                });
            });
        });
    </script>
@endpush
