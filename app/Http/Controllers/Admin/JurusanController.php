<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\JurusanRequest;
use App\Models\Jurusan;

class JurusanController extends Controller
{
    /** Daftar semua jurusan */
    public function index()
    {
        $jurusan = Jurusan::withCount('pendaftaran')->latest()->paginate(10);
        return view('admin.jurusan.index', compact('jurusan'));
    }

    /** Form tambah jurusan */
    public function create()
    {
        $jurusan = new Jurusan();
        return view('admin.jurusan.form', compact('jurusan'));
    }

    /** Simpan jurusan baru */
    public function store(JurusanRequest $request)
    {
        Jurusan::create($request->validated());
        return redirect()->route('admin.jurusan.index')
            ->with('success', 'Jurusan berhasil ditambahkan!');
    }

    /** Form edit jurusan */
    public function edit(Jurusan $jurusan)
    {
        return view('admin.jurusan.form', compact('jurusan'));
    }

    /** Update jurusan */
    public function update(JurusanRequest $request, Jurusan $jurusan)
    {
        $jurusan->update($request->validated());
        return redirect()->route('admin.jurusan.index')
            ->with('success', 'Jurusan berhasil diperbarui!');
    }

    /** Hapus jurusan (cek dulu apakah ada pendaftar) */
    public function destroy(Jurusan $jurusan)
    {
        // Cegah hapus jika sudah ada yang daftar
        if ($jurusan->pendaftaran()->exists()) {
            return back()->with('error', 'Jurusan tidak bisa dihapus karena sudah ada pendaftar.');
        }

        $jurusan->delete();
        return redirect()->route('admin.jurusan.index')
            ->with('success', 'Jurusan berhasil dihapus.');
    }
}
