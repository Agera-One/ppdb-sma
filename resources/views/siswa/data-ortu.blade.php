{{-- ============================================================ --}}
{{-- VIEW: resources/views/siswa/data-ortu.blade.php            --}}
{{-- Form isi / edit data orang tua / wali calon siswa          --}}
{{-- ============================================================ --}}
@extends('layouts.app')
@section('title', 'Data Orang Tua')
@section('page-title', 'Formulir Data Orang Tua')

@section('sidebar-nav')
    @foreach([
        ['route' => 'siswa.dashboard',         'label' => 'Dashboard'],
        ['route' => 'siswa.data-diri.edit',    'label' => 'Data Diri'],
        ['route' => 'siswa.data-ortu.edit',    'label' => 'Data Orang Tua'],
        ['route' => 'siswa.pendaftaran.create','label' => 'Pendaftaran'],
        ['route' => 'siswa.berkas.index',      'label' => 'Upload Berkas'],
        ['route' => 'siswa.pendaftaran.status','label' => 'Status Pendaftaran'],
    ] as $item)
    <a href="{{ route($item['route']) }}"
       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all
              {{ request()->routeIs($item['route']) ? 'nav-active' : 'hover:bg-slate-700/50 hover:text-white' }}">
        {{ $item['label'] }}
    </a>
    @endforeach
@endsection

@section('content')
<div class="max-w-3xl">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 mb-6 text-xs text-slate-500">
        <span class="w-5 h-5 rounded-full bg-green-500 text-white flex items-center justify-center text-xs">✓</span>
        <span class="text-slate-400 line-through">Data Diri</span>
        <span class="text-slate-300">›</span>
        <span class="w-5 h-5 rounded-full bg-sky-500 text-white flex items-center justify-center text-xs font-bold">2</span>
        <span class="font-medium text-sky-600">Data Orang Tua</span>
        <span class="text-slate-300">›</span>
        <span>Pendaftaran</span>
        <span class="text-slate-300">›</span>
        <span>Upload Berkas</span>
    </div>

    <form method="POST" action="{{ route('siswa.data-ortu.update') }}"
          class="bg-white rounded-2xl border border-slate-100 shadow-sm">
        @csrf

        <div class="px-6 py-5 border-b border-slate-100">
            <h2 class="font-display text-xl font-semibold text-slate-800">Data Orang Tua / Wali</h2>
            <p class="text-slate-500 text-sm mt-1">Isi data orang tua atau wali yang bertanggung jawab</p>
        </div>

        <div class="px-6 py-6 space-y-6">

            {{-- ===== AYAH ===== --}}
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-7 h-7 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-slate-700">Data Ayah</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">
                            Nama Ayah <span class="text-red-400">*</span>
                        </label>
                        <input type="text" name="nama_ayah" value="{{ old('nama_ayah', $orangTua?->nama_ayah) }}"
                               placeholder="Nama lengkap ayah"
                               class="w-full px-4 py-2.5 rounded-xl border @error('nama_ayah') border-red-400 bg-red-50 @else border-slate-200 @enderror
                                      text-sm focus:outline-none focus:ring-2 focus:ring-sky-400 transition"
                               required>
                        @error('nama_ayah')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    @foreach([
                        ['pekerjaan_ayah', 'Pekerjaan Ayah',         'PNS / Swasta / dll'],
                        ['pendidikan_ayah','Pendidikan Terakhir Ayah','SMA / D3 / S1 / dll'],
                        ['no_hp_ayah',     'No. HP Ayah',             '08xxxxxxxxxx'],
                    ] as [$name, $label, $placeholder])
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">{{ $label }}</label>
                        <input type="text" name="{{ $name }}"
                               value="{{ old($name, $orangTua?->$name) }}"
                               placeholder="{{ $placeholder }}"
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm
                                      focus:outline-none focus:ring-2 focus:ring-sky-400 transition">
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Divider --}}
            <div class="border-t border-dashed border-slate-200"></div>

            {{-- ===== IBU ===== --}}
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-7 h-7 rounded-lg bg-pink-100 text-pink-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-slate-700">Data Ibu</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">
                            Nama Ibu <span class="text-red-400">*</span>
                        </label>
                        <input type="text" name="nama_ibu" value="{{ old('nama_ibu', $orangTua?->nama_ibu) }}"
                               placeholder="Nama lengkap ibu"
                               class="w-full px-4 py-2.5 rounded-xl border @error('nama_ibu') border-red-400 bg-red-50 @else border-slate-200 @enderror
                                      text-sm focus:outline-none focus:ring-2 focus:ring-sky-400 transition"
                               required>
                        @error('nama_ibu')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    @foreach([
                        ['pekerjaan_ibu', 'Pekerjaan Ibu',          'Ibu Rumah Tangga / dll'],
                        ['pendidikan_ibu','Pendidikan Terakhir Ibu', 'SMA / D3 / S1 / dll'],
                        ['no_hp_ibu',     'No. HP Ibu',              '08xxxxxxxxxx'],
                    ] as [$name, $label, $placeholder])
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">{{ $label }}</label>
                        <input type="text" name="{{ $name }}"
                               value="{{ old($name, $orangTua?->$name) }}"
                               placeholder="{{ $placeholder }}"
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm
                                      focus:outline-none focus:ring-2 focus:ring-sky-400 transition">
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- ===== EKONOMI & WALI ===== --}}
            <div class="border-t border-slate-100 pt-6">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Ekonomi & Wali (Opsional)</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">
                            Penghasilan Orang Tua per Bulan (Rp)
                        </label>
                        <input type="number" name="penghasilan_ortu" min="0"
                               value="{{ old('penghasilan_ortu', $orangTua?->penghasilan_ortu) }}"
                               placeholder="Contoh: 5000000"
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm
                                      focus:outline-none focus:ring-2 focus:ring-sky-400 transition">
                        <p class="text-xs text-slate-400 mt-1">Data ini digunakan untuk pertimbangan beasiswa</p>
                    </div>

                    {{-- Toggle wali --}}
                    <div class="md:col-span-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" id="ada-wali"
                                   {{ $orangTua?->nama_wali ? 'checked' : '' }}
                                   class="w-4 h-4 rounded text-sky-500 border-slate-300"
                                   onchange="toggleWali(this)">
                            <span class="text-sm font-medium text-slate-700">Tinggal bersama wali (bukan orang tua)</span>
                        </label>
                    </div>

                    {{-- Bagian wali (hidden jika tidak ada) --}}
                    <div id="seksi-wali" class="{{ $orangTua?->nama_wali ? '' : 'hidden' }} md:col-span-2">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-amber-50 rounded-xl p-4 border border-amber-100">
                            @foreach([
                                ['nama_wali',      'Nama Wali',               'Nama lengkap wali'],
                                ['hubungan_wali',  'Hubungan dengan Siswa',   'Paman / Bibi / dll'],
                                ['no_hp_wali',     'No. HP Wali',             '08xxxxxxxxxx'],
                            ] as [$name, $label, $placeholder])
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1.5">{{ $label }}</label>
                                <input type="text" name="{{ $name }}"
                                       value="{{ old($name, $orangTua?->$name) }}"
                                       placeholder="{{ $placeholder }}"
                                       class="w-full px-4 py-2.5 rounded-xl border border-amber-200 bg-white text-sm
                                              focus:outline-none focus:ring-2 focus:ring-amber-400 transition">
                            </div>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>

        </div>

        {{-- Footer --}}
        <div class="px-6 py-4 bg-slate-50 rounded-b-2xl border-t border-slate-100 flex items-center justify-between">
            <a href="{{ route('siswa.data-diri.edit') }}"
               class="text-sm text-slate-500 hover:text-slate-700 transition">
                ← Data Diri
            </a>
            <button type="submit"
                    class="bg-sky-500 hover:bg-sky-600 text-white font-semibold text-sm px-6 py-2.5 rounded-xl
                           transition shadow-lg shadow-sky-100">
                Simpan & Lanjutkan →
            </button>
        </div>

    </form>
</div>
@endsection

@push('scripts')
<script>
    function toggleWali(checkbox) {
        const seksi = document.getElementById('seksi-wali');
        seksi.classList.toggle('hidden', !checkbox.checked);
    }
</script>
@endpush
