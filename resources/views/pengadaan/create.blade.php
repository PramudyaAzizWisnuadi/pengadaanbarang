@extends('layouts.app')

@section('title', 'Pengadaan Baru')

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Form Pengadaan Barang Baru</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('pengadaan.store') }}" method="POST" id="pengadaanForm">
                @csrf

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
                                value="{{ old('nama_pemohon', auth()->user()->name) }}" required readonly>
                            @error('nama_pemohon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="jabatan" class="form-label">Jabatan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('jabatan') is-invalid @enderror" id="jabatan"
                                name="jabatan" value="{{ old('jabatan', auth()->user()->jabatan) }}" required readonly>
                            @error('jabatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="departemen" class="form-label">Departemen <span class="text-danger">*</span></label>
                            @if(Auth::user()->role === 'super_admin')
                                <select class="form-select @error('departemen') is-invalid @enderror" id="departemen" name="departemen" required>
                                    <option value="">Pilih Departemen</option>
                                    @php
                                        $departemens = \App\Models\Departemen::orderBy('nama_departemen')->get();
                                    @endphp
                                    @foreach($departemens as $dept)
                                        <option value="{{ $dept->nama_departemen }}" {{ old('departemen') == $dept->nama_departemen ? 'selected' : '' }}>
                                            {{ $dept->nama_departemen }}
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                <input type="text" class="form-control @error('departemen') is-invalid @enderror"
                                    id="departemen" name="departemen"
                                    value="{{ old('departemen', auth()->user()->departemen) }}" required readonly>
                            @endif
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
                                id="tanggal_dibutuhkan" name="tanggal_dibutuhkan" value="{{ old('tanggal_dibutuhkan') }}"
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
                                rows="3" required>{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Skip Approval Section -->
                    <div class="col-12">
                        <div class="card border-warning">
                            <div class="card-header bg-warning text-dark">
                                <h6 class="mb-0">Pengadaan Darurat / Tanpa Approval</h6>
                            </div>
                            <div class="card-body">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" value="1" id="skip_approval"
                                        name="skip_approval" {{ old('skip_approval') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="skip_approval">
                                        <strong>Lewati proses approval (Pengadaan Darurat)</strong>
                                    </label>
                                </div>
                                <div class="mb-3" id="alasan_skip_section" style="display: none;">
                                    <label for="alasan_skip_approval" class="form-label">Alasan Skip Approval <span
                                            class="text-danger">*</span></label>
                                    <textarea class="form-control @error('alasan_skip_approval') is-invalid @enderror" id="alasan_skip_approval"
                                        name="alasan_skip_approval" rows="2"
                                        placeholder="Jelaskan alasan mengapa pengadaan ini perlu diproses tanpa approval...">{{ old('alasan_skip_approval') }}</textarea>
                                    @error('alasan_skip_approval')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Contoh: Pengadaan darurat untuk perbaikan kerusakan kritis,
                                        kebutuhan mendadak untuk proyek urgent, dll.</small>
                                </div>
                                <div class="alert alert-warning mb-0" id="skip_warning" style="display: none;">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    <strong>Perhatian:</strong> Pengadaan dengan skip approval akan langsung disetujui dan
                                    dapat segera diproses.
                                </div>
                            </div>
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
                            <!-- Items will be added here dynamically -->
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('pengadaan.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i>
                        Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>
                        Simpan Pengadaan
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

        let itemIndex = 0;

        document.addEventListener('DOMContentLoaded', function() {
            // Add first item automatically
            addNewItem();

            document.getElementById('addItem').addEventListener('click', addNewItem);
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
                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan!',
                        text: 'Minimal harus ada satu barang',
                        timer: 2000,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top-end'
                    });
                }
            });

            itemIndex++;
            updateItemNumbers(); // Update all item numbers after adding new item
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

        @if(Auth::user()->role === 'super_admin')
        // Handle departemen change for super admin - update kategori options
        const departemenSelect = document.getElementById('departemen');
        if (departemenSelect) {
            departemenSelect.addEventListener('change', function() {
                const selectedDepartemen = this.value;
                updateKategoriOptions(selectedDepartemen);
            });
        }

        function updateKategoriOptions(departemenName) {
            if (!departemenName) {
                // Reset all kategori selects to show all categories
                document.querySelectorAll('.kategori-select').forEach(select => {
                    const currentValue = select.value;
                    select.innerHTML = '<option value="">Pilih Kategori</option>';
                    @foreach ($kategoris as $kategori)
                        const option{{ $kategori->id }} = new Option('{{ $kategori->nama_kategori }}', '{{ $kategori->id }}');
                        select.appendChild(option{{ $kategori->id }});
                    @endforeach
                    select.value = currentValue;
                });
                return;
            }

            // Filter categories based on selected department
            fetch(`/api/kategori-by-departemen/${encodeURIComponent(departemenName)}`)
                .then(response => response.json())
                .then(data => {
                    document.querySelectorAll('.kategori-select').forEach(select => {
                        const currentValue = select.value;
                        select.innerHTML = '<option value="">Pilih Kategori</option>';
                        
                        data.forEach(kategori => {
                            const option = new Option(kategori.nama_kategori, kategori.id);
                            select.appendChild(option);
                        });
                        
                        // Restore value if still valid
                        if (currentValue && data.some(k => k.id == currentValue)) {
                            select.value = currentValue;
                        }
                    });
                })
                .catch(error => {
                    console.error('Error fetching categories:', error);
                });
        }
        @endif

        // Handle skip approval checkbox
        const skipApprovalCheckbox = document.getElementById('skip_approval');
        const alasanSkipSection = document.getElementById('alasan_skip_section');
        const skipWarning = document.getElementById('skip_warning');
        const alasanSkipInput = document.getElementById('alasan_skip_approval');

        skipApprovalCheckbox.addEventListener('change', function() {
            if (this.checked) {
                alasanSkipSection.style.display = 'block';
                skipWarning.style.display = 'block';
                alasanSkipInput.required = true;
                alasanSkipInput.disabled = false;
            } else {
                alasanSkipSection.style.display = 'none';
                skipWarning.style.display = 'none';
                alasanSkipInput.required = false;
                alasanSkipInput.disabled = true;
                alasanSkipInput.value = '';
            }
        });

        // Show/hide based on initial state
        if (skipApprovalCheckbox.checked) {
            alasanSkipSection.style.display = 'block';
            skipWarning.style.display = 'block';
            alasanSkipInput.required = true;
            alasanSkipInput.disabled = false;
        } else {
            alasanSkipInput.disabled = true;
        }
    </script>
@endpush
