<?php
namespace App\Http\Requests\Siswa;

use App\Models\Berkas;
use Illuminate\Foundation\Http\FormRequest;

class BerkasRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'jenis_berkas' => ['required', 'in:' . implode(',', array_keys(Berkas::labelJenis()))],
            'file_berkas'  => [
                'required',
                'file',
                'mimes:pdf,jpg,jpeg,png',   // Hanya PDF dan gambar
                'max:5120',                 // Maksimal 5MB
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'file_berkas.mimes' => 'File harus berformat PDF, JPG, atau PNG.',
            'file_berkas.max'   => 'Ukuran file maksimal 5MB.',
        ];
    }
}
