# Sistem Pengadaan Barang Perangkat Komputer

## Deskripsi

Sistem web untuk mengelola pengadaan barang perangkat komputer yang memungkinkan user mengisi data pengadaan, mencetak form, mendapatkan approval dari atasan, dan melanjutkan ke tahap pembelian.

## Fitur Utama

### 1. **Autentikasi**

-   Login dan Register user
-   Demo account tersedia:
    -   Admin: `admin@example.com` / `password`
    -   Staff: `staff@example.com` / `password`

### 2. **Dashboard**

-   Overview statistik pengadaan
-   Daftar semua pengadaan dengan status
-   Quick actions untuk setiap pengadaan

### 3. **Kelola Kategori Barang (DINAMIS)** 🆕

-   **Admin dapat mengelola kategori secara dinamis**
-   CRUD (Create, Read, Update, Delete) kategori
-   Status aktif/nonaktif untuk setiap kategori
-   Kategori nonaktif tidak muncul di form pengadaan
-   Statistik penggunaan kategori
-   Validasi: kategori tidak bisa dihapus jika masih digunakan
-   Preview real-time saat menambah kategori

### 4. **Form Pengadaan**

-   Input informasi pemohon (nama, jabatan, departemen)
-   Multiple barang dalam satu pengadaan
-   **Kategori barang dinamis** - hanya menampilkan kategori yang aktif
-   Default kategori yang tersedia:
    -   Komputer Desktop
    -   Laptop
    -   Monitor
    -   Printer
    -   Scanner
    -   Keyboard & Mouse
    -   Storage
    -   Network Equipment
    -   UPS & Power Supply
    -   Software

### 5. **Status Pengadaan**

-   **Draft**: Masih dapat diedit dan dihapus
-   **Submitted**: Menunggu approval, tidak dapat diedit
-   **Approved**: Disetujui atasan, siap untuk pembelian
-   **Rejected**: Ditolak dengan catatan alasan
-   **Completed**: Proses pengadaan selesai

### 6. **Print/Export**

-   Form dapat dicetak ke PDF atau di-print langsung
-   Format profesional dengan kop surat
-   Area tanda tangan pemohon dan atasan
-   Timestamp dokumen

### 7. **Approval System**

-   Admin dapat approve/reject pengadaan
-   Catatan approval untuk feedback
-   Timeline tracking perubahan status

## Workflow Penggunaan

### 1. **Kelola Kategori (Admin Only)** 🆕

1. Login sebagai admin
2. Akses menu "Kategori Barang" di sidebar
3. **Menambah Kategori Baru:**
    - Klik "Tambah Kategori"
    - Isi nama kategori (harus unik)
    - Tambahkan deskripsi (opsional)
    - Set status aktif/nonaktif
    - Preview real-time tersedia
    - Simpan kategori
4. **Mengedit Kategori:**
    - Klik tombol edit pada kategori
    - Ubah nama, deskripsi, atau status
    - Lihat statistik penggunaan kategori
5. **Mengelola Status:**
    - Toggle aktif/nonaktif kategori
    - Kategori nonaktif tidak muncul di form pengadaan
6. **Menghapus Kategori:**
    - Hanya bisa dihapus jika tidak digunakan dalam pengadaan
    - Sistem akan mencegah penghapusan jika masih terpakai

### 2. **Pembuatan Pengadaan**

1. Login ke sistem
2. Klik "Pengadaan Baru" atau tombol "+" di dashboard
3. Isi informasi pemohon:
    - Nama pemohon
    - Jabatan
    - Departemen
    - Tanggal dibutuhkan
    - Alasan pengadaan
4. Tambah barang yang dibutuhkan:
    - **Pilih kategori** (hanya kategori aktif yang muncul)
    - Nama barang
    - Spesifikasi detail
    - Merk (optional)
    - Jumlah dan satuan
    - Harga estimasi
    - Prioritas (Rendah/Sedang/Tinggi)
    - Keterangan (optional)
5. Klik "Simpan Pengadaan" (status: Draft)

### 3. **Edit & Submit**

1. Pada status Draft, pengadaan masih dapat diedit
2. Setelah yakin dengan data, klik "Submit untuk Approval"
3. Status berubah menjadi "Submitted"
4. Pengadaan tidak dapat diedit lagi setelah disubmit

### 4. **Print Form**

1. Klik tombol "Print/Download" pada detail pengadaan
2. Form akan terbuka di tab baru dengan format print-ready
3. Print dan minta tanda tangan atasan

### 5. **Approval Process** (Admin only)

1. Admin dapat melihat semua pengadaan yang submitted
2. Review detail pengadaan
3. Klik "Approve" atau "Reject" dengan catatan
4. Status berubah sesuai keputusan

### 6. **Lanjut ke Pembelian**

1. Setelah mendapat approval dan tanda tangan
2. Status dapat diubah menjadi "Completed"
3. Proses pengadaan selesai

## Teknologi

-   **Backend**: Laravel 12 (PHP 8.2+)
-   **Frontend**: Bootstrap 5, Blade Templates
-   **Database**: SQLite (default Laravel)
-   **Authentication**: Laravel built-in auth

## Struktur Database

### Tables:

1. **users** - Data user login
2. **kategori_barangs** - Master kategori perangkat
3. **pengadaan_barangs** - Header pengadaan
4. **barang_pengadaans** - Detail barang per pengadaan

## Installation & Setup

### 1. Requirements

-   PHP 8.2+
-   Composer
-   Node.js & NPM

### 2. Installation

```bash
# Clone or download project
cd pengadaanbarang

# Install PHP dependencies
composer install

# Install Node dependencies
npm install

# Copy environment file
cp .env.example .env

# Generate app key
php artisan key:generate

# Run migrations and seeders
php artisan migrate --seed

# Start development server
php artisan serve
```

### 3. Default Access

-   URL: http://localhost:8000
-   Admin: admin@example.com / password
-   Staff: staff@example.com / password

## Customization

### Mengelola Kategori Barang (Dinamis) 🆕

Kategori sekarang dapat dikelola secara dinamis melalui web interface:

**Via Web Interface (Recommended):**

1. Login sebagai admin
2. Masuk ke menu "Kategori Barang"
3. Gunakan CRUD interface untuk mengelola kategori
4. Toggle status aktif/nonaktif sesuai kebutuhan

**Via Database (Advanced):**

-   Edit data langsung di tabel `kategori_barangs`
-   Jalankan: `php artisan migrate:refresh --seed` untuk reset ke default

### Mengubah Role/Permission

Edit logika di `PengadaanController@index` untuk mengatur siapa yang bisa melihat semua pengadaan.
Default: `admin@example.com` memiliki akses penuh.

### Customisasi Form Print

Edit file `resources/views/pengadaan/print.blade.php` untuk mengubah layout print.

### Menambah Kategori Default (Via Seeder)

Edit file `database/seeders/KategoriBarangSeeder.php` dan jalankan:

```bash
php artisan db:seed --class=KategoriBarangSeeder
```

## Security Features

-   CSRF Protection
-   Authentication middleware
-   Input validation
-   SQL injection protection (Eloquent ORM)

## Support

Sistem ini dibuat untuk memudahkan proses pengadaan barang perangkat komputer dengan workflow yang jelas dan audit trail yang lengkap.
