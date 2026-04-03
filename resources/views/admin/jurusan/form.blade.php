@extends('layouts.app')
@section('title', isset($jurusan) ? 'Edit Jurusan' : 'Tambah Jurusan')
@section('page-title', isset($jurusan) ? 'Edit Jurusan' : 'Tambah Jurusan Baru')

@section('sidebar-nav')
    <a href="{{ route('admin.dashboard') }}"
        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm hover:bg-slate-700/50 hover:text-white transition">
        Dashboard
    </a>
    <a href="{{ route('admin.siswa.index') }}"
        class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm hover:bg-slate-700/50 hover:text-white transition">
        Data Pendaftar
    </a>
    <a href="{{ route('admin.jurusan.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm nav-active">
        Kelola Jurusan
    </a>
@endsection

@section('content')
    <div class="max-w-xl">

        {{-- Tombol kembali --}}
        <a href="{{ route('admin.jurusan.index') }}"
            class="inline-flex items-center gap-1.5 text-sm text-slate-500 hover:text-slate-700 mb-5 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Daftar Jurusan
        </a>

        {{-- Form --}}
        <form method="POST"
            action="{{ isset($jurusan) && $jurusan->exists ? route('admin.jurusan.update', $jurusan) : route('admin.jurusan.store') }}"
            class="bg-white rounded-2xl border border-slate-100 shadow-sm">

            @csrf
            @if (isset($jurusan) && $jurusan->exists)
                @method('PUT')
            @endif

            <div class="px-6 py-5 border-b border-slate-100">
                <h2 class="font-display text-xl font-semibold text-slate-800">
                    {{ isset($jurusan) ? 'Edit Jurusan: ' . $jurusan->nama_jurusan : 'Tambah Jurusan Baru' }}
                </h2>
            </div>

            <div class="px-6 py-6 space-y-4">

                {{-- Error summary --}}
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 rounded-xl p-3 text-sm text-red-700 space-y-1">
                        @foreach ($errors->all() as $err)
                            <p>• {{ $err }}</p>
                        @endforeach
                    </div>
                @endif

                {{-- Kode jurusan --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">
                        Kode Jurusan <span class="text-red-400">*</span>
                    </label>
                    <input type="text" name="kode_jurusan"
                        value="{{ old('kode_jurusan', $jurusan?->kode_jurusan ?? '') }}" maxlength="10"
                        placeholder="Contoh: IPA, IPS, BAHASA"
                        class="w-full px-4 py-2.5 rounded-xl border @error('kode_jurusan') border-red-400 bg-red-50 @else border-slate-200 @enderror
                              text-sm font-mono focus:outline-none focus:ring-2 focus:ring-sky-400 transition uppercase"
                        style="text-transform: uppercase" required>
                    <p class="text-xs text-slate-400 mt-1">Maksimal 10 karakter, akan ditampilkan sebagai label</p>
                    @error('kode_jurusan')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nama jurusan --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">
                        Nama Jurusan <span class="text-red-400">*</span>
                    </label>
                    <input type="text" name="nama_jurusan"
                        value="{{ old('nama_jurusan', $jurusan?->nama_jurusan ?? '') }}"
                        placeholder="Contoh: Ilmu Pengetahuan Alam"
                        class="w-full px-4 py-2.5 rounded-xl border @error('nama_jurusan') border-red-400 bg-red-50 @else border-slate-200 @enderror
                              text-sm focus:outline-none focus:ring-2 focus:ring-sky-400 transition"
                        required>
                    @error('nama_jurusan')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Deskripsi --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Deskripsi</label>
                    <textarea name="deskripsi" rows="3" placeholder="Deskripsi singkat tentang jurusan ini..."
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm
                                 focus:outline-none focus:ring-2 focus:ring-sky-400 transition resize-none">{{ old('deskripsi', $jurusan?->deskripsi) }}</textarea>
                </div>

                {{-- Kuota --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">
                        Kuota Siswa <span class="text-red-400">*</span>
                    </label>
                    <div class="flex items-center gap-3">
                        <input type="number" name="kuota" min="1" max="500"
                            value="{{ old('kuota', $jurusan?->kuota ?? 30) }}"
                            class="w-32 px-4 py-2.5 rounded-xl border @error('kuota') border-red-400 bg-red-50 @else border-slate-200 @enderror
                                  text-sm focus:outline-none focus:ring-2 focus:ring-sky-400 transition text-center"
                            required>
                        <span class="text-sm text-slate-500">kursi tersedia</span>
                    </div>
                    @error('kuota')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Status aktif --}}
                <div>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <div class="relative">
                            <input type="checkbox" name="is_aktif" value="1" id="toggle-aktif"
                                {{ old('is_aktif', $jurusan?->is_aktif ?? true) ? 'checked' : '' }} class="sr-only peer">
                            {{-- Custom toggle switch --}}
                            <div
                                class="w-11 h-6 bg-slate-200 peer-focus:ring-2 peer-focus:ring-sky-400
                                    rounded-full peer peer-checked:bg-sky-500 transition-colors">
                            </div>
                            <div
                                class="absolute left-0.5 top-0.5 bg-white w-5 h-5 rounded-full transition-transform
                                    peer-checked:translate-x-5 shadow-sm">
                            </div>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-slate-700">Jurusan Aktif</span>
                            <p class="text-xs text-slate-400">Jurusan aktif akan ditampilkan kepada calon siswa</p>
                        </div>
                    </label>
                </div>

            </div>

            {{-- Footer --}}
            <div class="px-6 py-4 bg-slate-50 rounded-b-2xl border-t border-slate-100 flex items-center justify-between">
                <a href="{{ route('admin.jurusan.index') }}"
                    class="text-sm text-slate-500 hover:text-slate-700 transition">Batal</a>
                <button type="submit"
                    class="bg-sky-500 hover:bg-sky-600 text-white font-semibold text-sm px-6 py-2.5 rounded-xl transition shadow-lg shadow-sky-100">
                    {{ isset($jurusan) ? 'Simpan Perubahan' : 'Tambah Jurusan' }}
                </button>
            </div>

        </form>
    </div>
@endsection
