<?php
namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Http\Requests\Siswa\CalonSiswaRequest;
use App\Http\Requests\Siswa\OrangTuaRequest;
use App\Models\CalonSiswa;
use App\Models\OrangTua;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CalonSiswaController extends Controller
{
    /** Form isi/edit data diri siswa */
    public function edit()
    {
        $calonSiswa = auth()->user()->calonSiswa;   // Ambil data yang sudah ada
        return view('siswa.data-diri', compact('calonSiswa'));
    }

    /** Simpan atau update data diri siswa */
    public function update(CalonSiswaRequest $request)
    {
        DB::beginTransaction(); // Gunakan transaksi untuk keamanan

        try {
            $data = $request->validated();          // Ambil data yang sudah divalidasi

            // Jika ada upload foto baru
            if ($request->hasFile('foto')) {
                $foto = $request->file('foto');
                // Hapus foto lama jika ada
                if (auth()->user()->calonSiswa?->foto) {
                    Storage::disk('public')->delete(auth()->user()->calonSiswa->foto);
                }
                // Simpan foto baru ke storage/app/public/foto-siswa
                $data['foto'] = $foto->store('foto-siswa', 'public');
            }

            // Cek apakah sudah punya data atau belum
            if (auth()->user()->id_siswa) {
                // Update data yang sudah ada
                $calonSiswa = auth()->user()->calonSiswa;
                $calonSiswa->update($data);
            } else {
                // Buat data baru dan hubungkan ke user
                $calonSiswa = CalonSiswa::create($data);
                auth()->user()->update(['id_siswa' => $calonSiswa->id]);
            }

            DB::commit();
            return redirect()->route('siswa.data-ortu.edit')
                ->with('success', 'Data diri berhasil disimpan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /** Form isi/edit data orang tua */
    public function editOrangTua()
    {
        // Pastikan sudah isi data diri dulu
        if (!auth()->user()->id_siswa) {
            return redirect()->route('siswa.data-diri.edit')
                ->with('error', 'Silakan isi data diri terlebih dahulu.');
        }
        $orangTua = auth()->user()->calonSiswa->orangTua;
        return view('siswa.data-ortu', compact('orangTua'));
    }

    /** Simpan atau update data orang tua */
    public function updateOrangTua(OrangTuaRequest $request)
    {
        $siswaId = auth()->user()->id_siswa;

        // updateOrCreate: update jika ada, create jika belum
        OrangTua::updateOrCreate(
            ['id_siswa' => $siswaId],           // Kondisi pencarian
            $request->validated()               // Data yang akan disimpan
        );

        return redirect()->route('siswa.pendaftaran.create')
            ->with('success', 'Data orang tua berhasil disimpan!');
    }
}
