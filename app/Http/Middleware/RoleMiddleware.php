<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle request masuk.
     *
     * @param  string  $role  Role yang diizinkan ('admin' atau 'siswa')
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Cek apakah user sudah login
        if (!auth()->check()) {
            return redirect()->route('login')   // Redirect ke halaman login
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        // Cek apakah role user sesuai dengan yang dibutuhkan rute
        if (auth()->user()->role !== $role) {
            // Jika tidak sesuai, arahkan ke dashboard masing-masing
            if (auth()->user()->isAdmin()) {
                return redirect()->route('admin.dashboard')
                    ->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
            }

            return redirect()->route('siswa.dashboard')
                ->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
        }

        return $next($request); // Lanjutkan ke controller jika role sesuai
    }
}
