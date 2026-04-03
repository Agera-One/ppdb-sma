@extends('layouts.app')
@section('title', 'Kelola Jurusan')
@section('page-title', 'Kelola Jurusan')

@section('sidebar-nav')
    <a href="{{ route('admin.dashboard') }}"
       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm hover:bg-slate-700/50 hover:text-white transition">
        Dashboard
    </a>
    <a href="{{ route('admin.siswa.index') }}"
       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm hover:bg-slate-700/50 hover:text-white transition">
        Data Pendaftar
    </a>
    <a href="{{ route('admin.jurusan.index') }}"
       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm nav-active">
        Kelola Jurusan
    </a>
@endsection

@section('content')
<div class="max-w-3xl space-y-5">

    {{-- Tombol tambah jurusan baru --}}
    <div class="flex items-center justify-between">
        <p class="text-sm text-slate-500">{{ $jurusan->total() }} jurusan terdaftar</p>
        <a href="{{ route('admin.jurusan.create') }}"
           class="inline-flex items-center gap-2 bg-sky-500 hover:bg-sky-600 text-white text-sm
                  font-semibold px-4 py-2.5 rounded-xl transition shadow-lg shadow-sky-100">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Jurusan
        </a>
    </div>

    {{-- Tabel jurusan --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wide">
                <tr>
                    <th class="text-left px-6 py-3">Kode</th>
                    <th class="text-left px-6 py-3">Nama Jurusan</th>
                    <th class="text-left px-6 py-3">Kuota</th>
                    <th class="text-left px-6 py-3">Pendaftar</th>
                    <th class="text-left px-6 py-3">Status</th>
                    <th class="text-left px-6 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($jurusan as $j)
                <tr class="hover:bg-slate-50/60 transition">
                    <td class="px-6 py-4 font-mono font-semibold text-sky-700 text-xs bg-sky-50/50">
                        {{ $j->kode_jurusan }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-medium text-slate-800">{{ $j->nama_jurusan }}</div>
                        @if($j->deskripsi)
                        <div class="text-xs text-slate-400 mt-0.5 line-clamp-1">{{ $j->deskripsi }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-slate-700 font-medium">{{ $j->kuota }}</div>
                        <div class="text-xs text-slate-400">Sisa: {{ $j->sisa_kuota }}</div>
                    </td>
                    <td class="px-6 py-4 text-slate-600">
                        {{ $j->pendaftaran_count }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            {{ $j->is_aktif ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-500' }}">
                            {{ $j->is_aktif ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            {{-- Edit --}}
                            <a href="{{ route('admin.jurusan.edit', $j) }}"
                               class="text-xs text-amber-600 hover:text-amber-800 border border-amber-200 hover:border-amber-400
                                      bg-amber-50 hover:bg-amber-100 px-2.5 py-1 rounded-lg transition">
                                Edit
                            </a>
                            {{-- Hapus --}}
                            <form method="POST" action="{{ route('admin.jurusan.destroy', $j) }}"
                                  onsubmit="return confirm('Hapus jurusan {{ $j->nama_jurusan }}?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="text-xs text-red-500 hover:text-red-700 border border-red-200 hover:border-red-400
                                               bg-red-50 hover:bg-red-100 px-2.5 py-1 rounded-lg transition">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-10 text-center text-slate-400 text-sm">
                        Belum ada jurusan. Klik "Tambah Jurusan" untuk memulai.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($jurusan->hasPages())
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $jurusan->links() }}
        </div>
        @endif
    </div>

</div>
@endsection


{{-- ============================================================ --}}
{{-- FILE INI JUGA BERISI TEMPLATE FORM (untuk Create & Edit)    --}}
{{-- Simpan terpisah sebagai: resources/views/admin/jurusan/form.blade.php --}}
{{-- ============================================================ --}}
