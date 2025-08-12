@extends('layouts.app')

@section('title', 'Detail Pengadaan - ' . $pengadaan->kode_pengadaan)

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Detail Pengadaan</h5>
                    <span class="status-badge status-{{ $pengadaan->status }} fs-6">
                        {{ ucfirst($pengadaan->status) }}
                    </span>
                </div>
                <div class="card-body">
                    <!-- Informasi Pengadaan -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td class="fw-bold">Kode Pengadaan:</td>
                                    <td>{{ $pengadaan->kode_pengadaan }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Pemohon:</td>
                                    <td>{{ $pengadaan->nama_pemohon }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Jabatan:</td>
                                    <td>{{ $pengadaan->jabatan }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Departemen:</td>
                                    <td>{{ $pengadaan->departemen }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td class="fw-bold">Tanggal Pengajuan:</td>
                                    <td>{{ $pengadaan->tanggal_pengajuan->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Tanggal Dibutuhkan:</td>
                                    <td>{{ $pengadaan->tanggal_dibutuhkan->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold">Total Estimasi:</td>
                                    <td class="fw-bold text-primary">Rp
                                        {{ number_format($pengadaan->total_estimasi, 0, ',', '.') }}</td>
                                </tr>
                                @if ($pengadaan->tanggal_approval)
                                    <tr>
                                        <td class="fw-bold">Tanggal Approval:</td>
                                        <td>{{ $pengadaan->tanggal_approval->format('d/m/Y H:i') }}</td>
                                    </tr>
                                    @if ($pengadaan->foto_approval)
                                        <tr>
                                            <td class="fw-bold">Foto Approval:</td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-outline-primary"
                                                    onclick="showImage('{{ asset('storage/' . $pengadaan->foto_approval) }}', 'Foto Approval - {{ $pengadaan->kode_pengadaan }}')">
                                                    <i class="bi bi-image me-1"></i>
                                                    Show Gambar
                                                </button>
                                            </td>
                                        </tr>
                                    @endif
                                @endif
                            </table>
                        </div>
                    </div>

                    <!-- Alasan Pengadaan -->
                    <div class="mb-4">
                        <h6 class="border-bottom pb-2">Alasan Pengadaan</h6>
                        <p class="mb-0">{{ $pengadaan->alasan_pengadaan }}</p>
                    </div>

                    <!-- Catatan Approval -->
                    @if ($pengadaan->catatan_approval)
                        <div class="mb-4">
                            <h6 class="border-bottom pb-2">Catatan Approval</h6>
                            <div class="alert alert-{{ $pengadaan->status === 'approved' ? 'success' : 'danger' }}">
                                <p class="mb-2">{{ $pengadaan->catatan_approval }}</p>
                                @if ($pengadaan->approvedBy)
                                    <small class="text-muted">
                                        <strong>Oleh:</strong> {{ $pengadaan->approvedBy->name }} -
                                        {{ $pengadaan->tanggal_approval->format('d/m/Y H:i') }}
                                    </small>
                                @endif
                                @if ($pengadaan->foto_approval)
                                    <div class="mt-2">
                                        <button type="button" class="btn btn-sm btn-outline-primary"
                                            onclick="showImage('{{ asset('storage/' . $pengadaan->foto_approval) }}', 'Foto Approval - {{ $pengadaan->kode_pengadaan }}')">
                                            <i class="bi bi-image me-1"></i>
                                            Lihat Foto Approval
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Daftar Barang -->
                    <div class="mb-4">
                        <h6 class="border-bottom pb-2">Daftar Barang yang Diminta</h6>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Kategori</th>
                                        <th>Nama Barang</th>
                                        <th>Spesifikasi</th>
                                        <th>Jumlah</th>
                                        <th>Harga Est.</th>
                                        <th>Total</th>
                                        <th>Prioritas</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pengadaan->barangPengadaan as $index => $barang)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $barang->kategoriBarang->nama_kategori }}</td>
                                            <td>
                                                <strong>{{ $barang->nama_barang }}</strong>
                                                @if ($barang->merk)
                                                    <br><small class="text-muted">Merk: {{ $barang->merk }}</small>
                                                @endif
                                            </td>
                                            <td>{{ $barang->spesifikasi }}</td>
                                            <td>{{ $barang->jumlah }} {{ $barang->satuan }}</td>
                                            <td>Rp {{ number_format($barang->harga_estimasi, 0, ',', '.') }}</td>
                                            <td class="fw-bold">Rp {{ number_format($barang->total_harga, 0, ',', '.') }}
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $barang->prioritas == 3 ? 'danger' : ($barang->prioritas == 2 ? 'warning' : 'secondary') }}">
                                                    {{ $barang->prioritas_text }}
                                                </span>
                                            </td>
                                        </tr>
                                        @if ($barang->keterangan)
                                            <tr>
                                                <td></td>
                                                <td colspan="7" class="text-muted">
                                                    <small><strong>Keterangan:</strong> {{ $barang->keterangan }}</small>
                                                </td>
                                            </tr>
                                        @endif
                                        @if ($barang->alasan_pengadaan)
                                            <tr>
                                                <td></td>
                                                <td colspan="7" class="text-muted">
                                                    <small><strong>Alasan Pengadaan:</strong>
                                                        {{ $barang->alasan_pengadaan }}</small>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                                <tfoot class="table-dark">
                                    <tr>
                                        <th colspan="6" class="text-end">Total Keseluruhan:</th>
                                        <th colspan="2">Rp {{ number_format($pengadaan->total_estimasi, 0, ',', '.') }}
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Action Panel -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Aksi</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if ($pengadaan->status === 'draft')
                            <a href="{{ route('pengadaan.edit', $pengadaan) }}" class="btn btn-warning">
                                <i class="bi bi-pencil me-1"></i>
                                Edit Pengadaan
                            </a>

                            <form action="{{ route('pengadaan.submit', $pengadaan) }}" method="POST"
                                onsubmit="return confirm('Yakin ingin submit pengadaan ini? Setelah disubmit tidak dapat diedit lagi.')">
                                @csrf
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-send me-1"></i>
                                    Submit untuk Approval
                                </button>
                            </form>
                        @endif

                        @if ($pengadaan->status === 'submitted' && Auth::user()->email === 'admin@example.com')
                            <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                data-bs-target="#approveModal">
                                <i class="bi bi-check-circle me-1"></i>
                                Approve
                            </button>

                            <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                data-bs-target="#rejectModal">
                                <i class="bi bi-x-circle me-1"></i>
                                Reject
                            </button>

                            <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                data-bs-target="#bypassApprovalModal">
                                <i class="bi bi-lightning me-1"></i>
                                Bypass Approval
                            </button>
                        @endif

                        @if ($pengadaan->status === 'approved')
                            <form action="{{ route('pengadaan.complete', $pengadaan) }}" method="POST"
                                onsubmit="return confirm('Yakin ingin menyelesaikan pengadaan ini? Status akan berubah menjadi Completed.')">
                                @csrf
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="bi bi-check2-all me-1"></i>
                                    Selesaikan Pengadaan
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('pengadaan.print', $pengadaan) }}" target="_blank"
                            class="btn btn-outline-secondary">
                            <i class="bi bi-printer me-1"></i>
                            Print/Download
                        </a>

                        <a href="{{ route('pengadaan.index') }}" class="btn btn-outline-primary">
                            <i class="bi bi-arrow-left me-1"></i>
                            Kembali ke Daftar
                        </a>
                    </div>
                </div>
            </div>

            <!-- Timeline -->
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0">Timeline</h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Pengadaan Dibuat</h6>
                                <p class="text-muted mb-0">{{ $pengadaan->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>

                        @if ($pengadaan->status !== 'draft')
                            <div class="timeline-item">
                                <div class="timeline-marker bg-info"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Disubmit untuk Approval</h6>
                                    <p class="text-muted mb-0">{{ $pengadaan->updated_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        @endif

                        @if ($pengadaan->tanggal_approval)
                            {{-- Debug: Status = {{ $pengadaan->status }} --}}
                            <div class="timeline-item">
                                <div
                                    class="timeline-marker bg-{{ $pengadaan->status === 'approved' ? 'success' : 'danger' }}">
                                </div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">
                                        @if ($pengadaan->status === 'approved')
                                            @if ($pengadaan->skip_approval)
                                                <span class="text-warning">⚡</span> Disetujui (Bypass Approval)
                                            @else
                                                Disetujui
                                            @endif
                                        @elseif($pengadaan->status === 'rejected')
                                            Ditolak
                                        @else
                                            {{ ucfirst($pengadaan->status) }}
                                        @endif
                                    </h6>
                                    <p class="text-muted mb-0">{{ $pengadaan->tanggal_approval->format('d/m/Y H:i') }}</p>
                                    @if ($pengadaan->approvedBy)
                                        <small class="text-muted">Oleh: {{ $pengadaan->approvedBy->name }}</small>
                                    @endif
                                    @if ($pengadaan->skip_approval && $pengadaan->alasan_skip_approval)
                                        <br><small class="text-warning">
                                            <i class="bi bi-lightning"></i> Alasan bypass:
                                            {{ $pengadaan->alasan_skip_approval }}
                                        </small>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Approve Modal -->
    <div class="modal fade" id="approveModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('pengadaan.approve', $pengadaan) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Approve Pengadaan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Yakin ingin menyetujui pengadaan <strong>{{ $pengadaan->kode_pengadaan }}</strong>?</p>

                        <div class="mb-3">
                            <label for="foto_approval" class="form-label">Upload Foto Printout yang Sudah Disetujui <span
                                    class="text-danger">*</span></label>
                            <input type="file" class="form-control" id="foto_approval" name="foto_approval"
                                accept="image/jpeg,image/png,image/jpg" required>
                            <small class="text-muted">
                                <i class="bi bi-info-circle"></i>
                                Upload foto dokumen yang sudah ditandatangani. Gambar akan dikompres otomatis maksimal
                                500KB.
                            </small>
                            <div class="mt-2">
                                <div class="image-preview d-none" id="imagePreview">
                                    <img id="previewImg" src="" alt="Preview" class="img-fluid"
                                        style="max-height: 200px;">
                                    <div class="mt-1">
                                        <small class="text-muted">Ukuran: <span id="imageSize">-</span> KB</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="catatan_approval" class="form-label">Catatan (Opsional)</label>
                            <textarea class="form-control" id="catatan_approval" name="catatan_approval" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Ya, Approve</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('pengadaan.reject', $pengadaan) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Reject Pengadaan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Yakin ingin menolak pengadaan <strong>{{ $pengadaan->kode_pengadaan }}</strong>?</p>
                        <div class="mb-3">
                            <label for="catatan_reject" class="form-label">Alasan Penolakan <span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control" id="catatan_reject" name="catatan_approval" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Ya, Reject</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bypass Approval Modal -->
    <div class="modal fade" id="bypassApprovalModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('pengadaan.bypass-approval', $pengadaan) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title text-warning">
                            <i class="bi bi-lightning me-2"></i>
                            Bypass Approval - Pengadaan Darurat
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <strong>Perhatian:</strong> Pengadaan akan langsung disetujui tanpa melalui proses approval
                            normal.
                        </div>
                        <p>Pengadaan <strong>{{ $pengadaan->kode_pengadaan }}</strong> akan langsung disetujui.</p>
                        <div class="mb-3">
                            <label for="alasan_bypass" class="form-label">Alasan Bypass Approval <span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control" id="alasan_bypass" name="alasan_skip_approval" rows="3"
                                placeholder="Jelaskan mengapa pengadaan ini perlu diproses segera tanpa approval normal..." required></textarea>
                            <small class="text-muted">Contoh: Kerusakan kritis yang perlu perbaikan segera, kebutuhan
                                mendadak untuk proyek urgent, dll.</small>
                        </div>
                        <div class="mb-3">
                            <label for="foto_bypass" class="form-label">Foto Dokumen yang Telah Disetujui <span
                                    class="text-danger">*</span></label>
                            <input type="file" class="form-control" id="foto_bypass" name="foto_approval"
                                accept="image/jpeg,image/png,image/jpg" required onchange="previewBypassImage(this)">
                            <small class="text-muted">Upload foto printout dokumen yang telah disetujui. Foto akan otomatis
                                dikompres menjadi 500KB.</small>
                            <div id="bypassImagePreview" class="mt-2" style="display: none;">
                                <img id="bypassPreviewImg" src="" alt="Preview" class="img-thumbnail"
                                    style="max-width: 200px; max-height: 200px;">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-lightning me-1"></i>
                            Ya, Bypass Approval
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Image Modal untuk menampilkan gambar approval -->
    <div class="modal fade" id="imageModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Foto Approval</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" alt="Foto Approval" class="img-fluid">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <style>
        .timeline {
            position: relative;
            padding-left: 30px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #dee2e6;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 20px;
        }

        .timeline-marker {
            position: absolute;
            left: -23px;
            top: 5px;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            border: 2px solid #fff;
            box-shadow: 0 0 0 2px #dee2e6;
        }

        .timeline-content h6 {
            font-size: 14px;
            margin-bottom: 5px;
        }

        .timeline-content p {
            font-size: 12px;
        }
    </style>

    <script>
        function previewApprovalImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('approvalPreviewImg').src = e.target.result;
                    document.getElementById('approvalImagePreview').style.display = 'block';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function previewBypassImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('bypassPreviewImg').src = e.target.result;
                    document.getElementById('bypassImagePreview').style.display = 'block';
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Show full image modal
        function showImage(imagePath, title) {
            const modal = document.getElementById('imageModal');
            const modalImg = document.getElementById('modalImage');
            const modalTitle = document.getElementById('imageModalLabel');

            modalImg.src = imagePath;
            modalTitle.textContent = title || 'Foto Approval';

            const bsModal = new bootstrap.Modal(modal);
            bsModal.show();
        }
    </script>
@endpush
