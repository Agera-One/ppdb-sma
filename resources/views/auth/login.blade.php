<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — PPDB SMA Tunas Bangsa</title>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,600;1,9..144,400&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: {
                fontFamily: { display: ['Fraunces','serif'], sans: ['Plus Jakarta Sans','sans-serif'] }
            }}
        }
    </script>
    <style>
        .auth-bg {
            background: linear-gradient(135deg, #0c4a6e 0%, #0369a1 40%, #0ea5e9 100%);
        }
        .card-shadow { box-shadow: 0 25px 60px -10px rgba(0,0,0,0.3); }
    </style>
</head>
<body class="font-sans min-h-screen auth-bg flex items-center justify-center p-4">

    <div class="w-full max-w-md">

        {{-- Logo & header --}}
        <div class="text-center mb-8">
            <div class="inline-flex w-16 h-16 rounded-2xl bg-white/10 backdrop-blur items-center justify-center mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 14l9-5-9-5-9 5 9 5z M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                </svg>
            </div>
            <h1 class="font-display text-3xl text-white font-semibold">SMA Tunas Bangsa</h1>
            <p class="text-sky-200 text-sm mt-1">Sistem PPDB {{ date('Y') }}/{{ date('Y') + 1 }}</p>
        </div>

        {{-- Card form login --}}
        <div class="bg-white rounded-3xl card-shadow p-8">
            <h2 class="font-display text-2xl text-slate-800 font-semibold mb-1">Selamat Datang</h2>
            <p class="text-slate-500 text-sm mb-6">Masuk untuk melanjutkan pendaftaran Anda</p>

            {{-- Error global --}}
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-xl p-3 mb-4 text-sm text-red-700">
                {{ $errors->first() }}
            </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
                @csrf

                {{-- Email --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        placeholder="nama@email.com"
                        class="w-full px-4 py-3 rounded-xl border @error('email') border-red-400 bg-red-50 @else border-slate-200 @enderror
                               text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-transparent transition"
                        required autofocus>
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">Password</label>
                    <div class="relative">
                        <input type="password" name="password" id="pw-input"
                            placeholder="••••••••"
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 text-slate-800 text-sm
                                   focus:outline-none focus:ring-2 focus:ring-sky-400 focus:border-transparent transition pr-10"
                            required>
                        {{-- Toggle show/hide password --}}
                        <button type="button" onclick="togglePassword()"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                            <svg id="eye-icon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Remember me --}}
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="remember" id="remember"
                        class="w-4 h-4 rounded text-sky-500 border-slate-300 focus:ring-sky-400">
                    <label for="remember" class="text-sm text-slate-600">Ingat saya</label>
                </div>

                {{-- Submit --}}
                <button type="submit"
                    class="w-full bg-sky-500 hover:bg-sky-600 active:bg-sky-700 text-white font-semibold py-3 rounded-xl
                           transition duration-150 text-sm mt-2 shadow-lg shadow-sky-200">
                    Masuk
                </button>
            </form>

            {{-- Link ke register --}}
            <p class="text-center text-sm text-slate-500 mt-5">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-sky-600 font-semibold hover:underline">
                    Daftar sekarang
                </a>
            </p>
        </div>

        <p class="text-center text-xs text-sky-200/70 mt-6">
            © {{ date('Y') }} SMA Tunas Bangsa. Semua hak dilindungi.
        </p>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('pw-input');
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>
</body>
</html>
