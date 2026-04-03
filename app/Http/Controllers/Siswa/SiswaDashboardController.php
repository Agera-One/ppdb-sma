<?php
namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;

class SiswaDashboardController extends Controller
{
    /** Tampilkan dashboard siswa dengan progres pendaftaran */
    public function index()
    {
        $user        = auth()->user()->load('calonSiswa.pendaftaranAktif.pembayaran');
        $calonSiswa  = $user->calonSiswa;                                   // Data pribadi siswa
        $pendaftaran = $calonSiswa?->pendaftaranAktif;                      // Pendaftaran terbaru
        $pembayaran  = $pendaftaran?->pembayaran;                           // Status pembayaran

        // Hitung progres kelengkapan data (untuk progress bar)
        $progres = $this->hitungProgres($calonSiswa, $pendaftaran);

        return view('siswa.dashboard', compact('user', 'calonSiswa', 'pendaftaran', 'pembayaran', 'progres'));
    }

    /** Hitung persentase progres pendaftaran */
    private function hitungProgres($calonSiswa, $pendaftaran): int
    {
        $langkah  = 0;
        $total    = 4; // Total langkah: data siswa, data ortu, pendaftaran, berkas

        if ($calonSiswa) $langkah++;                            // Langkah 1: data diri
        if ($calonSiswa?->orangTua) $langkah++;                 // Langkah 2: data orang tua
        if ($pendaftaran) $langkah++;                           // Langkah 3: pendaftaran
        if ($pendaftaran?->berkas()->exists()) $langkah++;      // Langkah 4: upload berkas

        return (int) round(($langkah / $total) * 100);         // Kembalikan persen
    }
}
