# Role Permission System - Web Pengadaan Barang

## Overview

Sistem role permission telah diimplementasi menggunakan **Spatie Laravel Permission** untuk mengatur akses berdasarkan departemen dan jabatan.

## Roles yang Tersedia

### 1. Super Admin

-   **Role**: `super_admin`
-   **Departemen**: IT (khusus admin@example.com)
-   **Permissions**: Semua permission (full access)

### 2. IT Department

#### IT Admin

-   **Role**: `it_admin`
-   **Jabatan**: Head/Manager/Kepala IT
-   **Permissions**:
    -   Pengadaan: view, create, edit, delete, submit, print, view_all
    -   Kategori: view, create, edit, delete, toggle_status
    -   Users: view, create, edit, delete
    -   Dashboard: view, reports

#### IT Staff

-   **Role**: `it_staff`
-   **Jabatan**: Staff IT
-   **Permissions**:
    -   Pengadaan: view, create, edit, submit, print
    -   Kategori: view
    -   Dashboard: view

### 3. Finance Department

#### Finance Head

-   **Role**: `finance_head`
-   **Jabatan**: Head/Manager Finance
-   **Permissions**:
    -   Pengadaan: view, view_all, approve, reject, approve_department
    -   Dashboard: view, reports

#### Finance Staff

-   **Role**: `finance_staff`
-   **Jabatan**: Staff Finance
-   **Permissions**:
    -   Pengadaan: view, create, edit, submit, print
    -   Dashboard: view

### 4. HR Department

#### HR Head

-   **Role**: `hr_head`
-   **Jabatan**: Head/Manager HR
-   **Permissions**:
    -   Pengadaan: view, create, edit, submit, print, approve_department
    -   Users: manage_department_users
    -   Dashboard: view, reports

#### HR Staff

-   **Role**: `hr_staff`
-   **Jabatan**: Staff HR
-   **Permissions**:
    -   Pengadaan: view, create, edit, submit, print
    -   Dashboard: view

### 5. Operations Department

#### Operations Head

-   **Role**: `operations_head`
-   **Jabatan**: Head/Manager Operations
-   **Permissions**:
    -   Pengadaan: view, create, edit, submit, print, approve_department
    -   Dashboard: view, reports

#### Operations Staff

-   **Role**: `operations_staff`
-   **Jabatan**: Staff Operations
-   **Permissions**:
    -   Pengadaan: view, create, edit, submit, print
    -   Dashboard: view

### 6. Marketing Department

#### Marketing Head

-   **Role**: `marketing_head`
-   **Jabatan**: Head/Manager Marketing
-   **Permissions**:
    -   Pengadaan: view, create, edit, submit, print, approve_department
    -   Dashboard: view, reports

#### Marketing Staff

-   **Role**: `marketing_staff`
-   **Jabatan**: Staff Marketing
-   **Permissions**:
    -   Pengadaan: view, create, edit, submit, print
    -   Dashboard: view

### 7. General Staff

-   **Role**: `staff`
-   **Departemen**: Any
-   **Permissions**:
    -   Pengadaan: view, create, edit, submit, print
    -   Dashboard: view

## Permission List

### Pengadaan Permissions

-   `view_pengadaan` - Melihat daftar pengadaan
-   `create_pengadaan` - Membuat pengadaan baru
-   `edit_pengadaan` - Mengedit pengadaan
-   `delete_pengadaan` - Menghapus pengadaan
-   `submit_pengadaan` - Submit pengadaan untuk approval
-   `approve_pengadaan` - Approve/reject pengadaan (Finance Head)
-   `print_pengadaan` - Print dokumen pengadaan

### Kategori Permissions

-   `view_kategori` - Melihat kategori barang
-   `create_kategori` - Membuat kategori baru
-   `edit_kategori` - Mengedit kategori
-   `delete_kategori` - Menghapus kategori
-   `toggle_kategori_status` - Mengubah status aktif/nonaktif

### User Management Permissions

-   `view_users` - Melihat daftar user
-   `create_users` - Membuat user baru
-   `edit_users` - Mengedit user
-   `delete_users` - Menghapus user

### Dashboard & Reports

-   `view_dashboard` - Akses dashboard
-   `view_reports` - Melihat laporan

### Department Specific

-   `view_all_pengadaan` - Melihat semua pengadaan lintas departemen
-   `approve_department_pengadaan` - Approve pengadaan dalam departemen
-   `manage_department_users` - Mengelola user dalam departemen

## Automatic Role Assignment

Sistem akan otomatis assign role berdasarkan:

1. **Departemen** user (IT, Finance, HR, Operations, Marketing)
2. **Jabatan** user (Head/Manager/Kepala = Head role, lainnya = Staff role)

## Manual Role Assignment

### Via Command Line

```bash
php artisan user:assign-role {email} {role}
```

Contoh:

```bash
php artisan user:assign-role john@example.com it_admin
php artisan user:assign-role jane@example.com finance_staff
```

### Available Roles for Assignment

-   super_admin
-   it_admin, it_staff
-   finance_head, finance_staff
-   hr_head, hr_staff
-   operations_head, operations_staff
-   marketing_head, marketing_staff
-   staff

## Middleware Protection

Semua routes sudah dilindungi dengan permission middleware:

-   `/pengadaan/*` - Permission: view_pengadaan, create_pengadaan, etc.
-   `/kategori/*` - Permission: view_kategori, create_kategori, etc.
-   `/users/*` - Permission: view_users, create_users, etc.

## Navigation Menu

Menu sidebar akan otomatis menyesuaikan berdasarkan permission user:

-   User tanpa `view_kategori` tidak akan melihat menu Kategori Barang
-   User tanpa `view_users` tidak akan melihat menu Manajemen User
-   User tanpa `view_reports` tidak akan melihat menu Laporan

## Permission Checking in Views

```blade
@can('permission_name')
    <!-- Content yang hanya bisa dilihat user dengan permission -->
@endcan

@cannot('permission_name')
    <!-- Content untuk user tanpa permission -->
@endcannot

@canany(['permission1', 'permission2'])
    <!-- Content untuk user dengan salah satu permission -->
@endcanany
```

## Testing

1. Login sebagai admin@example.com (super_admin) - akses penuh
2. Login sebagai user dengan departemen berbeda - akses terbatas sesuai role
3. Coba akses URL langsung yang tidak ada permission - akan error 403

## Benefits

1. **Security**: Akses terkontrol berdasarkan role dan departemen
2. **Scalability**: Mudah menambah role dan permission baru
3. **Flexibility**: Permission bisa di-assign per user atau per role
4. **User Experience**: Menu menyesuaikan dengan hak akses user
5. **Audit Trail**: Track siapa yang punya akses apa

## Future Enhancements

1. Role management UI untuk admin
2. Permission assignment UI
3. Activity logging untuk audit trail
4. Department-based data filtering
5. Approval workflow berdasarkan hierarchy
