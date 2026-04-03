<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Berkas;
use App\Models\Jurusan;
use App\Models\Pembayaran;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;

class AdminSiswaController extends Controller
{
    /** Daftar semua pendaftar dengan filter dan search */
    public function index(Request $request)
    {
        $query = Pendaftaran::with(['calonSiswa', 'jurusan', 'pembayaran'])
            ->latest();

        // Filter berdasarkan status pendaftaran
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan jurusan
        if ($request->filled('jurusan')) {
            $query->where('id_jurusan', $request->jurusan);
        }

        // Search berdasarkan nama atau nomor pendaftaran
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('no_pendaftaran', 'like', "%{$search}%")
                  ->orWhereHas('calonSiswa', function ($q2) use ($search) {
                      $q2->where('nama_lengkap', 'like', "%{$search}%")
                         ->orWhere('nisn', 'like', "%{$search}%");
                  });
            });
        }

        $pendaftaran = $query->paginate(15);                    // Paginasi 15 per halaman
        $jurusan     = Jurusan::where('is_aktif', true)->get(); // Untuk dropdown filter

        return view('admin.siswa.index', compact('pendaftaran', 'jurusan'));
    }

    /** Detail lengkap satu pendaftar */
    public function show(Pendaftaran $pendaftaran)
    {
        // Load semua relasi yang dibutuhkan sekaligus (eager loading)
        $pendaftaran->load([
            'calonSiswa.orangTua',
            'jurusan',
            'berkas',
            'pembayaran',
        ]);

        return view('admin.siswa.detail', compact('pendaftaran'));
    }

    /** Ubah status pendaftaran (terima/tolak) */
    public function updateStatus(Request $request, Pendaftaran $pendaftaran)
    {
        $request->validate([
            'status'        => ['required', 'in:diterima,ditolak'],
            'catatan_admin' => ['nullable', 'string', 'max:500'],
        ]);

        $pendaftaran->update([
            'status'               => $request->status,
            'catatan_admin'        => $request->catatan_admin,
            'tanggal_verifikasi'   => now(),            // Catat waktu verifikasi
        ]);

        $label = $request->status === 'diterima' ? 'diterima' : 'ditolak';
        return back()->with('success', "Pendaftaran berhasil {$label}.");
    }

    /** Verifikasi satu berkas siswa */
    public function verifikasiBerkas(Request $request, Berkas $berkas)
    {
        $request->validate([
            'status_berkas'   => ['required', 'in:valid,tidak_valid'],
            'catatan_berkas'  => ['nullable', 'string', 'max:300'],
        ]);

        $berkas->update([
            'status_berkas'  => $request->status_berkas,
            'catatan_berkas' => $request->catatan_berkas,
        ]);

        return back()->with('success', 'Status berkas berhasil diperbarui.');
    }

    /** Verifikasi pembayaran siswa */
    public function verifikasiPembayaran(Request $request, Pembayaran $pembayaran)
    {
        $request->validate([
            'status_pembayaran'   => ['required', 'in:dp,lunas'],
            'catatan_pembayaran'  => ['nullable', 'string', 'max:300'],
        ]);

        $pembayaran->update([
            'status_pembayaran'  => $request->status_pembayaran,
            'catatan_pembayaran' => $request->catatan_pembayaran,
            'tanggal_verifikasi' => now(),
        ]);

        return back()->with('success', 'Status pembayaran berhasil diperbarui.');
    }
}
