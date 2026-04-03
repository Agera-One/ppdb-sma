<?php
namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Http\Requests\Siswa\BerkasRequest;
use App\Http\Requests\Siswa\PendaftaranRequest;
use App\Models\Berkas;
use App\Models\Jurusan;
use App\Models\Pendaftaran;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PendaftaranController extends Controller
{
    /** Form pilih jurusan dan daftar */
    public function create()
    {
        $calonSiswa = auth()->user()->calonSiswa;

        // Validasi langkah sebelumnya sudah selesai
        if (!$calonSiswa || !$calonSiswa->orangTua) {
            return redirect()->route('siswa.data-diri.edit')
                ->with('error', 'Lengkapi data diri dan data orang tua terlebih dahulu.');
        }

        // Cek apakah sudah pernah daftar
        $existing = $calonSiswa->pendaftaranAktif;
        if ($existing) {
            return redirect()->route('siswa.pendaftaran.status')
                ->with('info', 'Anda sudah melakukan pendaftaran.');
        }

        $jurusan = Jurusan::where('is_aktif', true)->get();     // Ambil jurusan yang aktif saja
        return view('siswa.pendaftaran', compact('jurusan', 'calonSiswa'));
    }

    /** Proses submit pendaftaran */
    public function store(PendaftaranRequest $request)
    {
        $calonSiswa = auth()->user()->calonSiswa;

        // Cegah double submit
        if ($calonSiswa->pendaftaranAktif) {
            return redirect()->route('siswa.pendaftaran.status');
        }

        // Buat record pendaftaran baru
        $pendaftaran = Pendaftaran::create([
            'no_pendaftaran' => Pendaftaran::generateNomor(),   // Generate nomor otomatis
            'id_siswa'       => $calonSiswa->id,
            'id_jurusan'     => $request->id_jurusan,
            'status'         => 'pending',
        ]);

        // Otomatis buat record pembayaran dengan tagihan default
        Pembayaran::create([
            'id_pendaftaran'  => $pendaftaran->id,
            'jumlah_tagihan'  => 500000,    // Nominal tagihan (sesuaikan dengan sekolah)
            'status_pembayaran' => 'belum_bayar',
        ]);

        return redirect()->route('siswa.berkas.index')
            ->with('success', "Pendaftaran berhasil! No: {$pendaftaran->no_pendaftaran}. Silakan upload berkas.");
    }

    /** Halaman status pendaftaran siswa */
    public function status()
    {
        $pendaftaran = auth()->user()->calonSiswa?->pendaftaranAktif?->load([
            'jurusan', 'berkas', 'pembayaran'
        ]);

        return view('siswa.status-pendaftaran', compact('pendaftaran'));
    }

    /** Halaman daftar & upload berkas */
    public function berkasIndex()
    {
        $pendaftaran = auth()->user()->calonSiswa?->pendaftaranAktif?->load('berkas');

        if (!$pendaftaran) {
            return redirect()->route('siswa.pendaftaran.create')
                ->with('error', 'Lakukan pendaftaran terlebih dahulu.');
        }

        $labelJenis    = Berkas::labelJenis();
        $berkasUpload  = $pendaftaran->berkas->keyBy('jenis_berkas'); // Group by jenis

        return view('siswa.berkas', compact('pendaftaran', 'labelJenis', 'berkasUpload'));
    }

    /** Upload berkas pendaftaran */
    public function berkasStore(BerkasRequest $request)
    {
        $pendaftaran = auth()->user()->calonSiswa?->pendaftaranAktif;

        if (!$pendaftaran) {
            return back()->with('error', 'Pendaftaran tidak ditemukan.');
        }

        $file = $request->file('file_berkas');

        // Hapus berkas lama jika ada (jenis yang sama)
        $lama = $pendaftaran->berkas()->where('jenis_berkas', $request->jenis_berkas)->first();
        if ($lama) {
            Storage::disk('public')->delete($lama->path_file);  // Hapus file dari storage
            $lama->delete();                                     // Hapus record dari DB
        }

        // Simpan file ke folder berkas/{id_pendaftaran}/
        $path = $file->store("berkas/{$pendaftaran->id}", 'public');

        // Buat record berkas baru
        $pendaftaran->berkas()->create([
            'jenis_berkas'   => $request->jenis_berkas,
            'nama_file'      => $file->getClientOriginalName(), // Nama asli file
            'path_file'      => $path,
            'status_berkas'  => 'belum_diverifikasi',
        ]);

        // Update status pendaftaran menjadi menunggu verifikasi
        $pendaftaran->update(['status' => 'menunggu_verifikasi']);

        return back()->with('success', 'Berkas berhasil diupload!');
    }

    /** Upload bukti pembayaran */
    public function uploadBuktiPembayaran(Request $request)
    {
        $request->validate([
            'bukti_pembayaran' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:3072'],
            'metode_pembayaran'=> ['required', 'string', 'max:50'],
            'jumlah_bayar'     => ['required', 'numeric', 'min:1'],
        ]);

        $pendaftaran = auth()->user()->calonSiswa?->pendaftaranAktif;
        $pembayaran  = $pendaftaran?->pembayaran;

        if (!$pembayaran) {
            return back()->with('error', 'Data pembayaran tidak ditemukan.');
        }

        // Hapus bukti lama jika ada
        if ($pembayaran->bukti_pembayaran) {
            Storage::disk('public')->delete($pembayaran->bukti_pembayaran);
        }

        // Simpan file bukti pembayaran
        $path = $request->file('bukti_pembayaran')
            ->store("pembayaran/{$pendaftaran->id}", 'public');

        // Update record pembayaran
        $pembayaran->update([
            'bukti_pembayaran'  => $path,
            'metode_pembayaran' => $request->metode_pembayaran,
            'jumlah_bayar'      => $request->jumlah_bayar,
            'status_pembayaran' => 'dp',           // Default DP, admin yang ubah ke lunas
            'tanggal_bayar'     => now(),           // Catat waktu upload
        ]);

        return back()->with('success', 'Bukti pembayaran berhasil diupload!');
    }
}
