<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PengadaanController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DepartemenController;

Route::get('/', function () {
    return redirect('/login');
});

// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    // Dashboard route
    Route::get('/dashboard', [PengadaanController::class, 'index'])->name('dashboard');

    // Pengadaan routes
    Route::get('pengadaan', [PengadaanController::class, 'index'])->name('pengadaan.index');
    Route::get('pengadaan/create', [PengadaanController::class, 'create'])->name('pengadaan.create');
    Route::post('pengadaan', [PengadaanController::class, 'store'])->name('pengadaan.store');
    Route::get('pengadaan/{pengadaan}', [PengadaanController::class, 'show'])->name('pengadaan.show');
    Route::get('pengadaan/{pengadaan}/edit', [PengadaanController::class, 'edit'])->name('pengadaan.edit');
    Route::put('pengadaan/{pengadaan}', [PengadaanController::class, 'update'])->name('pengadaan.update');
    Route::delete('pengadaan/{pengadaan}', [PengadaanController::class, 'destroy'])->name('pengadaan.destroy');
    Route::post('pengadaan/{pengadaan}/submit', [PengadaanController::class, 'submit'])->name('pengadaan.submit');
    Route::get('pengadaan/{pengadaan}/print', [PengadaanController::class, 'print'])->name('pengadaan.print');
    Route::post('pengadaan/{pengadaan}/approve', [PengadaanController::class, 'approve'])->name('pengadaan.approve');
    Route::post('pengadaan/{pengadaan}/reject', [PengadaanController::class, 'reject'])->name('pengadaan.reject');
    Route::post('pengadaan/{pengadaan}/bypass-approval', [PengadaanController::class, 'bypassApproval'])->name('pengadaan.bypass-approval');
    Route::post('pengadaan/{pengadaan}/complete', [PengadaanController::class, 'complete'])->name('pengadaan.complete');

    // Laporan routes
    Route::get('laporan', [PengadaanController::class, 'laporan'])->name('pengadaan.laporan');
    Route::get('laporan/export', [PengadaanController::class, 'exportLaporan'])->name('pengadaan.export-laporan');
    Route::get('laporan/print', [PengadaanController::class, 'printLaporan'])->name('pengadaan.print-laporan');

    // Statistik routes
    Route::get('statistik', [PengadaanController::class, 'statistik'])->name('pengadaan.statistik');

    // Kategori routes
    Route::get('kategori', [KategoriController::class, 'index'])->name('kategori.index');
    Route::get('kategori/create', [KategoriController::class, 'create'])->name('kategori.create');
    Route::post('kategori', [KategoriController::class, 'store'])->name('kategori.store');
    Route::get('kategori/{kategori}', [KategoriController::class, 'show'])->name('kategori.show');
    Route::get('kategori/{kategori}/edit', [KategoriController::class, 'edit'])->name('kategori.edit');
    Route::put('kategori/{kategori}', [KategoriController::class, 'update'])->name('kategori.update');
    Route::delete('kategori/{kategori}', [KategoriController::class, 'destroy'])->name('kategori.destroy');
    Route::post('kategori/{kategori}/toggle-status', [KategoriController::class, 'toggleStatus'])->name('kategori.toggle-status');

    // User routes
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('users', [UserController::class, 'store'])->name('users.store');
    Route::get('users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    // Departemen routes
    Route::get('departemen', [DepartemenController::class, 'index'])->name('departemen.index');
    Route::get('departemen/create', [DepartemenController::class, 'create'])->name('departemen.create');
    Route::post('departemen', [DepartemenController::class, 'store'])->name('departemen.store');
    Route::get('departemen/{departemen}', [DepartemenController::class, 'show'])->name('departemen.show');
    Route::get('departemen/{departemen}/edit', [DepartemenController::class, 'edit'])->name('departemen.edit');
    Route::put('departemen/{departemen}', [DepartemenController::class, 'update'])->name('departemen.update');
    Route::delete('departemen/{departemen}', [DepartemenController::class, 'destroy'])->name('departemen.destroy');

    // API routes for AJAX
    Route::get('api/kategori-by-departemen/{departemen}', [KategoriController::class, 'getKategoriByDepartemen'])->name('api.kategori-by-departemen');
});
