<?php
namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /** Semua user boleh mengakses form registrasi */
    public function authorize(): bool { return true; }

    /** Aturan validasi untuk registrasi akun baru */
    public function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'max:100'],
            'email'    => ['required', 'email', 'unique:users,email'],  // Email harus unik
            'password' => ['required', 'min:8', 'confirmed'],           // Harus ada password_confirmation
        ];
    }

    /** Pesan error kustom dalam Bahasa Indonesia */
    public function messages(): array
    {
        return [
            'email.unique'       => 'Email sudah terdaftar, gunakan email lain.',
            'password.min'       => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ];
    }
}
