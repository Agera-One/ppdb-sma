<?php
// ============================================================
// ROUTES: web.php  (routes/web.php)
// ============================================================
// Semua rute aplikasi PPDB SMA didefinisikan di sini.
// Middleware 'auth'  → harus login
// Middleware 'role:admin' → hanya admin
// Middleware 'role:siswa' → hanya siswa
// ============================================================

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminSiswaController;
use App\Http\Controllers\Admin\JurusanController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Siswa\CalonSiswaController;
use App\Http\Controllers\Siswa\PendaftaranController;
use App\Http\Controllers\Siswa\SiswaDashboardController;
use Illuminate\Support\Facades\Route;

// ============================================================
// RUTE PUBLIK (tanpa login)
// ============================================================

/** Halaman utama → redirect ke login */
Route::get('/', fn() => redirect()->route('login'));

/** Login */
Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login')
    ->middleware('guest');                          // Hanya untuk yang belum login

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.post')
    ->middleware('guest');

/** Register */
Route::get('/register', [AuthController::class, 'showRegister'])
    ->name('register')
    ->middleware('guest');

Route::post('/register', [AuthController::class, 'register'])
    ->name('register.post')
    ->middleware('guest');

// ============================================================
// RUTE AUTENTIKASI (harus login)
// ============================================================

Route::middleware('auth')->group(function () {

    /** Logout (POST untuk keamanan CSRF) */
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // ----------------------------------------------------------
    // RUTE SISWA — hanya untuk role 'siswa'
    // ----------------------------------------------------------
    Route::prefix('siswa')                          // URL: /siswa/...
        ->name('siswa.')                            // Prefix nama route: siswa.
        ->middleware('role:siswa')
        ->group(function () {

            /** Dashboard siswa */
            Route::get('/dashboard', [SiswaDashboardController::class, 'index'])
                ->name('dashboard');

            /** Data diri calon siswa */
            Route::get('/data-diri', [CalonSiswaController::class, 'edit'])
                ->name('data-diri.edit');
            Route::post('/data-diri', [CalonSiswaController::class, 'update'])
                ->name('data-diri.update');

            /** Data orang tua */
            Route::get('/data-ortu', [CalonSiswaController::class, 'editOrangTua'])
                ->name('data-ortu.edit');
            Route::post('/data-ortu', [CalonSiswaController::class, 'updateOrangTua'])
                ->name('data-ortu.update');

            /** Pendaftaran */
            Route::get('/pendaftaran', [PendaftaranController::class, 'create'])
                ->name('pendaftaran.create');
            Route::post('/pendaftaran', [PendaftaranController::class, 'store'])
                ->name('pendaftaran.store');
            Route::get('/pendaftaran/status', [PendaftaranController::class, 'status'])
                ->name('pendaftaran.status');

            /** Berkas (upload dokumen) */
            Route::get('/berkas', [PendaftaranController::class, 'berkasIndex'])
                ->name('berkas.index');
            Route::post('/berkas', [PendaftaranController::class, 'berkasStore'])
                ->name('berkas.store');

            /** Upload bukti pembayaran */
            Route::post('/pembayaran/upload', [PendaftaranController::class, 'uploadBuktiPembayaran'])
                ->name('pembayaran.upload');
        });

    // ----------------------------------------------------------
    // RUTE ADMIN — hanya untuk role 'admin'
    // ----------------------------------------------------------
    Route::prefix('admin')                          // URL: /admin/...
        ->name('admin.')                            // Prefix nama route: admin.
        ->middleware('role:admin')
        ->group(function () {

            /** Dashboard admin */
            Route::get('/dashboard', [AdminDashboardController::class, 'index'])
                ->name('dashboard');

            /** CRUD Jurusan */
            Route::resource('jurusan', JurusanController::class);   // Generate 7 rute CRUD otomatis

            /** Data siswa & pendaftaran */
            Route::get('/siswa', [AdminSiswaController::class, 'index'])
                ->name('siswa.index');
            Route::get('/siswa/{pendaftaran}', [AdminSiswaController::class, 'show'])
                ->name('siswa.show');

            /** Verifikasi status pendaftaran */
            Route::patch('/siswa/{pendaftaran}/status', [AdminSiswaController::class, 'updateStatus'])
                ->name('siswa.update-status');

            /** Verifikasi berkas */
            Route::patch('/berkas/{berkas}/verifikasi', [AdminSiswaController::class, 'verifikasiBerkas'])
                ->name('berkas.verifikasi');

            /** Verifikasi pembayaran */
            Route::patch('/pembayaran/{pembayaran}/verifikasi', [AdminSiswaController::class, 'verifikasiPembayaran'])
                ->name('pembayaran.verifikasi');
        });
});
