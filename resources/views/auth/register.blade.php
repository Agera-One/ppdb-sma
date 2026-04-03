<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register — PPDB SMA Tunas Bangsa</title>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,600&family=Plus+Jakarta+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config={theme:{extend:{fontFamily:{display:['Fraunces','serif'],sans:['Plus Jakarta Sans','sans-serif']}}}}</script>
    <style>.auth-bg{background:linear-gradient(135deg,#0c4a6e 0%,#0369a1 40%,#0ea5e9 100%)}.card-shadow{box-shadow:0 25px 60px -10px rgba(0,0,0,.3)}</style>
</head>
<body class="font-sans min-h-screen auth-bg flex items-center justify-center p-4">
    <div class="w-full max-w-md">

        <div class="text-center mb-6">
            <div class="inline-flex w-14 h-14 rounded-2xl bg-white/10 backdrop-blur items-center justify-center mb-3">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <h1 class="font-display text-2xl text-white font-semibold">Buat Akun Baru</h1>
            <p class="text-sky-200 text-xs mt-1">PPDB SMA Tunas Bangsa {{ date('Y') }}/{{ date('Y') + 1 }}</p>
        </div>

        <div class="bg-white rounded-3xl card-shadow p-8">

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-xl p-3 mb-4 text-sm text-red-700 space-y-1">
                @foreach($errors->all() as $err)<p>• {{ $err }}</p>@endforeach
            </div>
            @endif

            <form method="POST" action="{{ route('register.post') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Nama sesuai KK"
                        class="w-full px-4 py-3 rounded-xl border @error('name') border-red-400 @else border-slate-200 @enderror text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-sky-400 transition"
                        required autofocus>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com"
                        class="w-full px-4 py-3 rounded-xl border @error('email') border-red-400 @else border-slate-200 @enderror text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-sky-400 transition"
                        required>
                    @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">Password</label>
                    <input type="password" name="password" placeholder="Minimal 8 karakter"
                        class="w-full px-4 py-3 rounded-xl border @error('password') border-red-400 @else border-slate-200 @enderror text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-sky-400 transition"
                        required>
                    @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" placeholder="Ulangi password"
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 text-slate-800 text-sm focus:outline-none focus:ring-2 focus:ring-sky-400 transition"
                        required>
                </div>

                <button type="submit"
                    class="w-full bg-sky-500 hover:bg-sky-600 text-white font-semibold py-3 rounded-xl transition text-sm mt-2 shadow-lg shadow-sky-200">
                    Buat Akun
                </button>
            </form>

            <p class="text-center text-sm text-slate-500 mt-5">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="text-sky-600 font-semibold hover:underline">Masuk di sini</a>
            </p>
        </div>
    </div>
</body>
</html>
