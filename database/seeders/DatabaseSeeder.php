<?php
// ============================================================
// SEEDER: DatabaseSeeder.php  (database/seeders/DatabaseSeeder.php)
// ============================================================
// Jalankan dengan: php artisan db:seed
// ============================================================
namespace Database\Seeders;

use App\Models\Jurusan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ===== BUAT AKUN ADMIN =====
        User::create([
            'name'     => 'Admin PPDB',
            'email'    => 'admin@sma.sch.id',
            'password' => Hash::make('admin123'),   // Password default (GANTI di produksi!)
            'role'     => 'admin',
        ]);

        // ===== BUAT AKUN SISWA DEMO =====
        User::create([
            'name'     => 'Budi Santoso',
            'email'    => 'budi@example.com',
            'password' => Hash::make('password'),
            'role'     => 'siswa',
        ]);

        // ===== ISI DATA JURUSAN AWAL =====
        $jurusan = [
            [
                'kode_jurusan' => 'IPA',
                'nama_jurusan' => 'Ilmu Pengetahuan Alam',
                'deskripsi'    => 'Fokus pada Matematika, Fisika, Kimia, dan Biologi. Cocok untuk calon dokter, insinyur, dan ilmuwan.',
                'kuota'        => 36,
                'is_aktif'     => true,
            ],
            [
                'kode_jurusan' => 'IPS',
                'nama_jurusan' => 'Ilmu Pengetahuan Sosial',
                'deskripsi'    => 'Fokus pada Ekonomi, Sejarah, Sosiologi, dan Geografi. Cocok untuk bisnis, hukum, dan sosial.',
                'kuota'        => 36,
                'is_aktif'     => true,
            ],
            [
                'kode_jurusan' => 'BAHASA',
                'nama_jurusan' => 'Bahasa dan Budaya',
                'deskripsi'    => 'Fokus pada Sastra, Linguistik, dan Bahasa Asing. Cocok untuk jurnalisme dan linguistik.',
                'kuota'        => 24,
                'is_aktif'     => true,
            ],
        ];

        foreach ($jurusan as $j) {
            Jurusan::create($j);    // Simpan tiap jurusan ke database
        }

        $this->command->info('✅ Seeder selesai!');
        $this->command->info('   Admin: admin@sma.sch.id / admin123');
        $this->command->info('   Siswa: budi@example.com / password');
    }
}


// ============================================================
// CONFIG: bootstrap/app.php  (bagian withMiddleware)
// ============================================================
// Tambahkan alias middleware 'role' di file ini.
// Temukan bagian ->withMiddleware() dan tambahkan kode ini:
//
// use App\Http\Middleware\RoleMiddleware;
// use Illuminate\Foundation\Application;
// use Illuminate\Foundation\Configuration\Exceptions;
// use Illuminate\Foundation\Configuration\Middleware;
//
// return Application::configure(basePath: dirname(__DIR__))
//     ->withRouting(
//         web: __DIR__.'/../routes/web.php',
//         commands: __DIR__.'/../routes/console.php',
//         health: '/up',
//     )
//     ->withMiddleware(function (Middleware $middleware) {
//
//         // Daftarkan alias middleware 'role' agar bisa dipakai di route
//         $middleware->alias([
//             'role' => RoleMiddleware::class,
//         ]);
//
//     })
//     ->withExceptions(function (Exceptions $exceptions) {
//         //
//     })->create();
//
// ============================================================


// ============================================================
// CONFIG: config/filesystems.php  — Disk Storage
// ============================================================
// Pastikan disk 'public' sudah dikonfigurasi (sudah default di Laravel):
//
// 'public' => [
//     'driver' => 'local',
//     'root'   => storage_path('app/public'),
//     'url'    => env('APP_URL').'/storage',
//     'visibility' => 'public',
// ],
//
// Lalu jalankan: php artisan storage:link
// ============================================================
