<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CalonSiswa;
use App\Models\Jurusan;
use App\Models\Pendaftaran;
use App\Models\Pembayaran;

class AdminDashboardController extends Controller
{
    /** Dashboard admin dengan statistik lengkap */
    public function index()
    {
        // Statistik pendaftaran (untuk kartu di dashboard)
        $stats = [
            'total_pendaftar'  => Pendaftaran::count(),
            'pending'          => Pendaftaran::where('status', 'pending')->count(),
            'menunggu_verif'   => Pendaftaran::where('status', 'menunggu_verifikasi')->count(),
            'diterima'         => Pendaftaran::where('status', 'diterima')->count(),
            'ditolak'          => Pendaftaran::where('status', 'ditolak')->count(),
        ];

        // Data per jurusan untuk grafik
        $perJurusan = Jurusan::withCount([
            'pendaftaran',
            'pendaftaran as diterima_count' => fn($q) => $q->where('status', 'diterima'),
        ])->get();

        // Statistik pembayaran
        $pembayaran = [
            'belum_bayar' => Pembayaran::where('status_pembayaran', 'belum_bayar')->count(),
            'dp'          => Pembayaran::where('status_pembayaran', 'dp')->count(),
            'lunas'       => Pembayaran::where('status_pembayaran', 'lunas')->count(),
        ];

        // 5 pendaftar terbaru untuk tabel ringkasan
        $terbaru = Pendaftaran::with(['calonSiswa', 'jurusan'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'perJurusan', 'pembayaran', 'terbaru'));
    }
}
