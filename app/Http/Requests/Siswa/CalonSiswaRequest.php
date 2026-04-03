<?php
namespace App\Http\Requests\Siswa;

use Illuminate\Foundation\Http\FormRequest;

class CalonSiswaRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $siswaId = auth()->user()->id_siswa; // Untuk update, abaikan record sendiri

        return [
            'nik'            => ['required', 'digits:16', "unique:calon_siswa,nik,{$siswaId}"],
            'nisn'           => ['required', 'digits:10', "unique:calon_siswa,nisn,{$siswaId}"],
            'nama_lengkap'   => ['required', 'string', 'max:100'],
            'jenis_kelamin'  => ['required', 'in:L,P'],
            'tanggal_lahir'  => ['required', 'date', 'before:today'],
            'tempat_lahir'   => ['required', 'string', 'max:100'],
            'agama'          => ['required', 'string', 'max:50'],
            'alamat'         => ['required', 'string', 'max:255'],
            'no_telepon'     => ['nullable', 'string', 'max:15'],
            'asal_sekolah'   => ['required', 'string', 'max:150'],
            'tahun_lulus'    => ['required', 'digits:4', 'integer', 'min:2000'],
            'nilai_rata_rata'=> ['nullable', 'numeric', 'min:0', 'max:100'],
            'foto'           => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],  // Max 2MB
        ];
    }
}
