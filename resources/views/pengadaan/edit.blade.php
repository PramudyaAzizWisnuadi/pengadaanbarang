@extends('layouts.app')

@section('title', 'Edit Pengadaan - ' . $pengadaan->kode_pengadaan)

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Edit Pengadaan Barang - {{ $pengadaan->kode_pengadaan }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('pengadaan.update', $pengadaan) }}" method="POST" id="pengadaanForm">
                @csrf
                @method('PUT')

                <!-- Informasi Pemohon -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h6 class="border-bottom pb-2 mb-3">Informasi Pemohon</h6>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nama_pemohon" class="form-label">Nama Pemohon <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_pemohon') is-invalid @enderror"
                                id="nama_pemohon" name="nama_pemohon"
                                value="{{ old('nama_pemohon', $pengadaan->nama_pemohon) }}" required>
                            @error('nama_pemohon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="jabatan" class="form-label">Jabatan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('jabatan') is-invalid @enderror" id="jabatan"
                                name="jabatan" value="{{ old('jabatan', $pengadaan->jabatan) }}" required>
                            @error('jabatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="departemen" class="form-label">Departemen <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('departemen') is-invalid @enderror"
                                id="departemen" name="departemen" value="{{ old('departemen', $pengadaan->departemen) }}"
                                required>
                            @error('departemen')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="tanggal_dibutuhkan" class="form-label">Tanggal Dibutuhkan <span
                                    class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('tanggal_dibutuhkan') is-invalid @enderror"
                                id="tanggal_dibutuhkan" name="tanggal_dibutuhkan"
                                value="{{ old('tanggal_dibutuhkan', $pengadaan->tanggal_dibutuhkan->format('Y-m-d')) }}"
                                required>
                            @error('tanggal_dibutuhkan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan"
                                rows="3" required>{{ old('keterangan', $pengadaan->keterangan) }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Daftar Barang -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="border-bottom pb-2 mb-0">Daftar Barang yang Dibutuhkan</h6>
                            <button type="button" class="btn btn-sm btn-success" id="addItem">
                                <i class="bi bi-plus"></i> Tambah Barang
                            </button>
                        </div>
                    </div>
                    <div class="col-12">
                        <div id="itemsList">
                            @foreach ($pengadaan->barangPengadaan as $index => $barang)
                                <div class="card mb-3 item-card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h6 class="mb-0">Barang <span class="item-number">{{ $index + 1 }}</span>
                                            </h6>
                                            <button type="button" class="btn btn-sm btn-outline-danger remove-item">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Kategori <span
                                                            class="text-danger">*</span></label>
                                                    <select class="form-select kategori-select"
                                                        name="barang[{{ $index }}][kategori_barang_id]" required>
                                                        <option value="">Pilih Kategori</option>
                                                        @foreach ($kategoris as $kategori)
                                                            <option value="{{ $kategori->id }}"
                                                                {{ $barang->kategori_barang_id == $kategori->id ? 'selected' : '' }}>
                                                                {{ $kategori->nama_kategori }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Nama Barang <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" class="form-control"
                                                        name="barang[{{ $index }}][nama_barang]"
                                                        value="{{ $barang->nama_barang }}" required>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="mb-3">
                                                    <label class="form-label">Spesifikasi <span
                                                            class="text-danger">*</span></label>
                                                    <textarea class="form-control" name="barang[{{ $index }}][spesifikasi]" rows="2" required>{{ $barang->spesifikasi }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Merk</label>
                                                    <input type="text" class="form-control"
                                                        name="barang[{{ $index }}][merk]"
                                                        value="{{ $barang->merk }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Prioritas <span
                                                            class="text-danger">*</span></label>
                                                    <select class="form-select"
                                                        name="barang[{{ $index }}][prioritas]" required>
                                                        <option value="1"
                                                            {{ $barang->prioritas == 1 ? 'selected' : '' }}>Rendah</option>
                                                        <option value="2"
                                                            {{ $barang->prioritas == 2 ? 'selected' : '' }}>Sedang</option>
                                                        <option value="3"
                                                            {{ $barang->prioritas == 3 ? 'selected' : '' }}>Tinggi</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label class="form-label">Jumlah <span
                                                            class="text-danger">*</span></label>
                                                    <input type="number" class="form-control jumlah-input"
                                                        name="barang[{{ $index }}][jumlah]" min="1"
                                                        value="{{ $barang->jumlah }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label class="form-label">Satuan <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" class="form-control"
                                                        name="barang[{{ $index }}][satuan]"
                                                        placeholder="pcs, set, unit" value="{{ $barang->satuan }}"
                                                        required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Harga Estimasi <span
                                                            class="text-danger">*</span></label>
                                                    <input type="number" class="form-control harga-input"
                                                        name="barang[{{ $index }}][harga_estimasi]" min="0"
                                                        step="0.01" value="{{ $barang->harga_estimasi }}" required>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="mb-3">
                                                    <label class="form-label">Keterangan</label>
                                                    <textarea class="form-control" name="barang[{{ $index }}][keterangan]" rows="2">{{ $barang->keterangan }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('pengadaan.show', $pengadaan) }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i>
                        Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>
                        Update Pengadaan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Template for new item -->
    <template id="itemTemplate">
        <div class="card mb-3 item-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">Barang <span class="item-number"></span></h6>
                    <button type="button" class="btn btn-sm btn-outline-danger remove-item">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select class="form-select kategori-select" name="barang[INDEX][kategori_barang_id]" required>
                                <option value="">Pilih Kategori</option>
                                @foreach ($kategoris as $kategori)
                                    <option value="{{ $kategori->id }}">{{ $kategori->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Nama Barang <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="barang[INDEX][nama_barang]" required>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label">Spesifikasi <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="barang[INDEX][spesifikasi]" rows="2" required></textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Merk</label>
                            <input type="text" class="form-control" name="barang[INDEX][merk]">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Prioritas <span class="text-danger">*</span></label>
                            <select class="form-select" name="barang[INDEX][prioritas]" required>
                                <option value="1">Rendah</option>
                                <option value="2">Sedang</option>
                                <option value="3">Tinggi</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label">Jumlah <span class="text-danger">*</span></label>
                            <input type="number" class="form-control jumlah-input" name="barang[INDEX][jumlah]"
                                min="1" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label class="form-label">Satuan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="barang[INDEX][satuan]"
                                placeholder="pcs, set, unit" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Harga Estimasi <span class="text-danger">*</span></label>
                            <input type="number" class="form-control harga-input" name="barang[INDEX][harga_estimasi]"
                                min="0" step="0.01" required>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label">Keterangan</label>
                            <textarea class="form-control" name="barang[INDEX][keterangan]" rows="2"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>
@endsection

@push('styles')
    <style>
        /* Hide number input spinners/arrows */
        input[type=number]::-webkit-outer-spin-button,
        input[type=number]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Disable scroll on number inputs
        document.addEventListener('DOMContentLoaded', function() {
            // Function to disable wheel on number inputs
            function disableNumberInputScroll() {
                const numberInputs = document.querySelectorAll('input[type=number]');
                numberInputs.forEach(function(input) {
                    input.addEventListener('wheel', function(e) {
                        e.preventDefault();
                    });

                    // Also disable on focus to prevent accidental changes
                    input.addEventListener('focus', function() {
                        this.addEventListener('wheel', function(e) {
                            e.preventDefault();
                        });
                    });
                });
            }

            // Initial call
            disableNumberInputScroll();

            // Re-apply when new items are added
            const originalAddNewItem = addNewItem;
            addNewItem = function() {
                originalAddNewItem();
                setTimeout(disableNumberInputScroll, 100); // Small delay to ensure DOM is updated
            };
        });

        let itemIndex = {{ $pengadaan->barangPengadaan->count() }};

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('addItem').addEventListener('click', addNewItem);

            // Add remove functionality to existing items
            document.querySelectorAll('.remove-item').forEach(button => {
                button.addEventListener('click', function() {
                    if (document.querySelectorAll('.item-card').length > 1) {
                        this.closest('.item-card').remove();
                        updateItemNumbers();
                    } else {
                        alert('Minimal harus ada satu barang');
                    }
                });
            });
        });

        function addNewItem() {
            const template = document.getElementById('itemTemplate');
            const clone = template.content.cloneNode(true);

            // Replace INDEX with actual index
            const html = clone.querySelector('.item-card').outerHTML.replace(/INDEX/g, itemIndex);

            const itemsList = document.getElementById('itemsList');
            itemsList.insertAdjacentHTML('afterbegin', html);

            // Update item number
            const newItem = itemsList.firstElementChild;
            newItem.querySelector('.item-number').textContent = itemIndex + 1;

            // Add remove functionality
            newItem.querySelector('.remove-item').addEventListener('click', function() {
                if (document.querySelectorAll('.item-card').length > 1) {
                    this.closest('.item-card').remove();
                    updateItemNumbers();
                } else {
                    alert('Minimal harus ada satu barang');
                }
            });

            itemIndex++;
            updateItemNumbers();
        }

        function updateItemNumbers() {
            document.querySelectorAll('.item-card').forEach((card, index) => {
                card.querySelector('.item-number').textContent = index + 1;
            });
        }

        // Set minimum date to tomorrow
        document.getElementById('tanggal_dibutuhkan').setAttribute('min',
            new Date(Date.now() + 86400000).toISOString().split('T')[0]
        );
    </script>
@endpush
