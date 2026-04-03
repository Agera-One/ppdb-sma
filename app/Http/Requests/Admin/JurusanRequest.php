<?php
namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class JurusanRequest extends FormRequest
{
    public function authorize(): bool { return auth()->user()->isAdmin(); }

    public function rules(): array
    {
        $id = $this->route('jurusan')?->id; // Untuk update, abaikan record sendiri

        return [
            'kode_jurusan'  => ['required', 'string', 'max:10', "unique:jurusan,kode_jurusan,{$id}"],
            'nama_jurusan'  => ['required', 'string', 'max:100'],
            'deskripsi'     => ['nullable', 'string', 'max:500'],
            'kuota'         => ['required', 'integer', 'min:1', 'max:500'],
            'is_aktif'      => ['boolean'],
        ];
    }
}
