<?php
namespace App\Http\Requests\Siswa;

use Illuminate\Foundation\Http\FormRequest;

class OrangTuaRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'nama_ayah'        => ['required', 'string', 'max:100'],
            'pekerjaan_ayah'   => ['nullable', 'string', 'max:100'],
            'pendidikan_ayah'  => ['nullable', 'string', 'max:50'],
            'no_hp_ayah'       => ['nullable', 'string', 'max:15'],
            'nama_ibu'         => ['required', 'string', 'max:100'],
            'pekerjaan_ibu'    => ['nullable', 'string', 'max:100'],
            'pendidikan_ibu'   => ['nullable', 'string', 'max:50'],
            'no_hp_ibu'        => ['nullable', 'string', 'max:15'],
            'nama_wali'        => ['nullable', 'string', 'max:100'],
            'penghasilan_ortu' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
