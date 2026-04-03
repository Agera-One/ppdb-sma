<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /** Tampilkan halaman login */
    public function showLogin()
    {
        // Jika sudah login, redirect ke dashboard sesuai role
        if (Auth::check()) {
            return $this->redirectByRole();
        }
        return view('auth.login');
    }

    /** Proses login */
    public function login(Request $request)
    {
        // Validasi input login
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $credentials = $request->only('email', 'password');
        $remember    = $request->boolean('remember'); // Checkbox "ingat saya"

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();          // Regenerate session untuk keamanan
            return $this->redirectByRole();             // Arahkan ke dashboard sesuai role
        }

        // Jika gagal login, kembalikan error
        return back()
            ->withInput($request->only('email'))        // Pertahankan nilai email
            ->withErrors(['email' => 'Email atau password salah.']);
    }

    /** Tampilkan halaman registrasi */
    public function showRegister()
    {
        return view('auth.register');
    }

    /** Proses registrasi akun baru */
    public function register(RegisterRequest $request)
    {
        // Buat user baru dengan role siswa (default)
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),  // Hash password sebelum simpan
            'role'     => 'siswa',                          // Default role: siswa
        ]);

        Auth::login($user);                         // Login otomatis setelah register
        return redirect()->route('siswa.dashboard')
            ->with('success', 'Akun berhasil dibuat! Silakan lengkapi data Anda.');
    }

    /** Proses logout */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();          // Hapus semua session
        $request->session()->regenerateToken();     // Regenerate CSRF token
        return redirect()->route('login')
            ->with('success', 'Berhasil logout.');
    }

    /** Arahkan user ke dashboard berdasarkan role */
    private function redirectByRole()
    {
        return Auth::user()->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('siswa.dashboard');
    }
}
