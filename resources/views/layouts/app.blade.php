{{-- ============================================================ --}}
{{-- LAYOUT: resources/views/layouts/app.blade.php             --}}
{{-- Layout utama yang dipakai oleh semua halaman terproteksi  --}}
{{-- ============================================================ --}}
<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'PPDB') — SMA Tunas Bangsa</title>

    {{-- Google Fonts: Fraunces (display) + Plus Jakarta Sans (body) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,400;0,9..144,700;1,9..144,400&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Tailwind CSS via CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Konfigurasi Tailwind custom
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        display: ['Fraunces', 'serif'],
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50:  '#f0f9ff',
                            100: '#e0f2fe',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            900: '#0c4a6e',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #94a3b8; border-radius: 3px; }

        /* Animasi fade in halaman */
        .page-fade { animation: pageFade 0.35s ease forwards; }
        @keyframes pageFade { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }

        /* Sidebar active state */
        .nav-active { background: #0ea5e9; color: white !important; }
        .nav-active svg { color: white !important; }
    </style>
</head>
<body class="h-full bg-slate-50 font-sans">

{{-- Wrapper layout dua kolom: sidebar + konten --}}
<div class="flex h-full min-h-screen">

    {{-- ===== SIDEBAR ===== --}}
    <aside class="w-64 bg-slate-900 text-slate-300 flex flex-col flex-shrink-0 fixed inset-y-0 left-0 z-30 transition-transform duration-300" id="sidebar">

        {{-- Logo & nama sekolah --}}
        <div class="px-6 py-5 border-b border-slate-700/50">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-sky-500 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 14l9-5-9-5-9 5 9 5z M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                    </svg>
                </div>
                <div>
                    <div class="font-display font-bold text-white text-sm leading-tight">SMA Tunas Bangsa</div>
                    <div class="text-xs text-slate-500">PPDB {{ date('Y') }}/{{ date('Y') + 1 }}</div>
                </div>
            </div>
        </div>

        {{-- Profil user singkat --}}
        <div class="px-4 py-3 border-b border-slate-700/50">
            <div class="flex items-center gap-2 bg-slate-800 rounded-lg px-3 py-2">
                <div class="w-7 h-7 rounded-full bg-sky-500 flex items-center justify-center text-xs text-white font-bold flex-shrink-0">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <div class="text-xs font-medium text-white truncate">{{ auth()->user()->name }}</div>
                    <div class="text-xs text-slate-500 capitalize">{{ auth()->user()->role }}</div>
                </div>
            </div>
        </div>

        {{-- Navigasi --}}
        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
            @yield('sidebar-nav')
        </nav>

        {{-- Tombol logout --}}
        <div class="px-3 py-4 border-t border-slate-700/50">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-slate-400 hover:text-white hover:bg-red-500/20 transition-colors text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- ===== AREA KONTEN UTAMA ===== --}}
    <div class="flex-1 flex flex-col ml-64">

        {{-- Header / Topbar --}}
        <header class="bg-white border-b border-slate-200 sticky top-0 z-20">
            <div class="flex items-center justify-between px-6 py-3">
                {{-- Tombol toggle sidebar (mobile) --}}
                <button onclick="toggleSidebar()"
                    class="lg:hidden p-2 rounded-lg hover:bg-slate-100 text-slate-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                {{-- Judul halaman --}}
                <h1 class="text-slate-700 font-semibold text-sm">@yield('page-title', 'Dashboard')</h1>

                {{-- Tanggal --}}
                <span class="text-xs text-slate-400">{{ now()->isoFormat('dddd, D MMMM Y') }}</span>
            </div>
        </header>

        {{-- Flash notifications --}}
        @if(session('success'))
        <div class="mx-6 mt-4 bg-green-50 border border-green-200 text-green-800 rounded-xl px-4 py-3 text-sm flex items-center gap-2"
             x-data x-init="setTimeout(() => $el.remove(), 4000)">
            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="mx-6 mt-4 bg-red-50 border border-red-200 text-red-800 rounded-xl px-4 py-3 text-sm flex items-center gap-2">
            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            {{ session('error') }}
        </div>
        @endif

        {{-- Konten halaman --}}
        <main class="flex-1 p-6 page-fade">
            @yield('content')
        </main>
    </div>
</div>

<script>
    // Toggle sidebar untuk mobile
    function toggleSidebar() {
        const sb = document.getElementById('sidebar');
        sb.classList.toggle('-translate-x-full');
    }

    // Auto-hilangkan flash message setelah 4 detik
    document.querySelectorAll('[data-flash]').forEach(el => {
        setTimeout(() => el.style.display = 'none', 4000);
    });
</script>

@stack('scripts')
</body>
</html>
