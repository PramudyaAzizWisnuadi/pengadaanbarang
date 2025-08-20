# 🚀 Deployment Guide - Sistem Pengadaan Barang

## Prerequisites untuk Server Production (cPanel)

### 1. **Server Requirements**

-   PHP 8.1 atau lebih tinggi
-   MySQL 5.7 atau lebih tinggi
-   Composer
-   Extension PHP: PDO, MySQL, Mbstring, Tokenizer, XML, Ctype, JSON, BCMath, Fileinfo, OpenSSL

### 2. **Database Setup**

```sql
CREATE DATABASE pengadaanbarang CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

## 📋 Deployment Steps

### 1. **Upload Files**

-   Upload semua files ke folder public_html atau subdirectory
-   Pastikan folder `storage` dan `bootstrap/cache` writable (755 atau 777)

### 2. **Environment Configuration**

```bash
# Copy .env.example ke .env
cp .env.example .env

# Edit .env dengan data server Anda:
APP_NAME="Sistem Pengadaan Barang"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_db_username
DB_PASSWORD=your_db_password
```

### 3. **Generate Application Key**

```bash
php artisan key:generate
```

### 4. **Install Dependencies**

```bash
composer install --optimize-autoloader --no-dev
```

### 5. **Set Permissions**

```bash
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

### 6. **Run Migrations**

```bash
# Jalankan migrations
php artisan migrate --force

# Jalankan seeders untuk data awal
php artisan db:seed --force
```

### 7. **Optimize for Production**

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 8. **Symlink Storage (jika diperlukan)**

```bash
php artisan storage:link
```

## 🔧 Migration Details

### Migration Order (sudah dioptimasi):

1. `create_users_table` - Tabel users dasar
2. `create_cache_table` - Cache session
3. `create_jobs_table` - Queue jobs
4. `create_departemens_table` - ⭐ **Master departemen** (dependency utama)
5. `create_pengadaan_barangs_table` - Pengadaan barang
6. `create_kategori_barangs_table` - Kategori barang
7. `create_barang_pengadaans_table` - Detail barang per pengadaan
8. `add_jabatan_departemen_to_users_table` - Tambah kolom jabatan & departemen
9. `add_alasan_pengadaan_to_barang_pengadaans_table` - Kolom alasan
10. `add_skip_approval_to_pengadaan_barangs_table` - Skip approval
11. `add_foto_approval_to_pengadaan_barangs_table` - Foto approval
12. `remove_alasan_pengadaan_from_barang_pengadaan_table` - Cleanup kolom
13. `rename_alasan_pengadaan_to_keterangan_in_pengadaan_barangs_table` - Rename
14. `add_departemen_id_to_users_table` - ⭐ **Foreign key users -> departemens**
15. `add_departemen_id_to_pengadaan_barangs_table` - ⭐ **Foreign key pengadaan -> departemens**
16. `add_departemen_id_to_kategori_barangs_table` - ⭐ **Foreign key kategori -> departemens**
17. `add_role_to_users_table` - ⭐ **Role system (super_admin, admin, user)**

## 📊 Seeder Data

### Default Users yang akan dibuat:

-   **Super Admin**: `superadmin@mdgroup.id` / `Murahsetiaphari`
-   **IT Staff**: `staffit@mdgroup.id` / `password`
-   **HR**: `hr@mdgroup.id` / `password`
-   **Finance**: `finance@mdgroup.id` / `password`
-   **Marketing**: `marketing@mdgroup.id` / `password`
-   **Operations**: `operations@mdgroup.id` / `password`
-   **GA**: `ga@mdgroup.id` / `password`

### Default Departments:

-   IT (Information Technology)
-   HR (Human Resource)
-   FIN (Finance)
-   MKT (Marketing)
-   OPS (Operations)
-   GA (General Affairs)

## ⚠️ Production Checklist

### Security:

-   [ ] `APP_DEBUG=false`
-   [ ] `APP_ENV=production`
-   [ ] Strong `APP_KEY` generated
-   [ ] Database credentials secure
-   [ ] HTTPS enabled
-   [ ] File permissions correct (storage: 755, bootstrap/cache: 755)

### Performance:

-   [ ] Config cached (`php artisan config:cache`)
-   [ ] Routes cached (`php artisan route:cache`)
-   [ ] Views cached (`php artisan view:cache`)
-   [ ] Composer optimized (`--optimize-autoloader --no-dev`)

### Functionality:

-   [ ] All migrations ran successfully
-   [ ] Seeders completed
-   [ ] Login system working
-   [ ] CRUD operations working
-   [ ] DataTables loading correctly
-   [ ] File uploads working (if any)
-   [ ] Email sending configured (if needed)

## 🚨 Troubleshooting

### Common Issues:

**1. Migration Errors:**

```bash
# If foreign key constraints fail, check table order
php artisan migrate:status
```

**2. Permission Errors:**

```bash
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

**3. 500 Server Error:**

```bash
# Check log files
tail -f storage/logs/laravel.log
```

**4. Database Connection:**

```bash
# Test connection
php artisan tinker
>> DB::connection()->getPdo();
```

## 📞 Support

Jika mengalami masalah deployment, check:

1. Laravel logs: `storage/logs/laravel.log`
2. Web server error logs
3. PHP error logs
4. Database connection

---

**✅ Migration Status: PRODUCTION READY**

Semua migration files sudah ditest dan berjalan dengan sukses. Urutan dependencies sudah benar dan semua foreign key constraints sudah properti.
