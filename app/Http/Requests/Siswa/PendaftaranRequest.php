<?php
namespace App\Http\Requests\Siswa;

use Illuminate\Foundation\Http\FormRequest;

class PendaftaranRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'id_jurusan' => ['required', 'exists:jurusan,id'],  // Jurusan harus ada di database
        ];
    }
}
