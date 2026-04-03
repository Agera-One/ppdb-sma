{{-- ============================================================ --}}
{{-- VIEW: resources/views/siswa/data-diri.blade.php            --}}
{{-- Form isi / edit data diri calon siswa                      --}}
{{-- ============================================================ --}}
@extends('layouts.app')
@section('title', 'Data Diri')
@section('page-title', 'Formulir Data Diri')

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

    {{-- Breadcrumb / keterangan langkah --}}
    <div class="flex items-center gap-2 mb-6 text-xs text-slate-500">
        <span class="w-5 h-5 rounded-full bg-sky-500 text-white flex items-center justify-center text-xs font-bold">1</span>
        <span class="font-medium text-sky-600">Data Diri</span>
        <span class="text-slate-300">›</span>
        <span>Data Orang Tua</span>
        <span class="text-slate-300">›</span>
        <span>Pendaftaran</span>
        <span class="text-slate-300">›</span>
        <span>Upload Berkas</span>
    </div>

    <form method="POST" action="{{ route('siswa.data-diri.update') }}" enctype="multipart/form-data"
          class="bg-white rounded-2xl border border-slate-100 shadow-sm">

        @csrf

        {{-- Header form --}}
        <div class="px-6 py-5 border-b border-slate-100">
            <h2 class="font-display text-xl font-semibold text-slate-800">Data Diri Calon Siswa</h2>
            <p class="text-slate-500 text-sm mt-1">Isi data sesuai dengan dokumen resmi (KK / Akta Kelahiran)</p>
        </div>

        <div class="px-6 py-6 space-y-6">

            {{-- ===== SEKSI 1: Identitas Pokok ===== --}}
            <div>
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Identitas Pokok</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    {{-- NIK --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">
                            NIK <span class="text-red-400">*</span>
                        </label>
                        <input type="text" name="nik" value="{{ old('nik', $calonSiswa?->nik) }}"
                               maxlength="16" placeholder="16 digit nomor NIK"
                               class="w-full px-4 py-2.5 rounded-xl border @error('nik') border-red-400 bg-red-50 @else border-slate-200 @enderror
                                      text-sm focus:outline-none focus:ring-2 focus:ring-sky-400 transition"
                               required>
                        @error('nik')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- NISN --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">
                            NISN <span class="text-red-400">*</span>
                        </label>
                        <input type="text" name="nisn" value="{{ old('nisn', $calonSiswa?->nisn) }}"
                               maxlength="10" placeholder="10 digit NISN"
                               class="w-full px-4 py-2.5 rounded-xl border @error('nisn') border-red-400 bg-red-50 @else border-slate-200 @enderror
                                      text-sm focus:outline-none focus:ring-2 focus:ring-sky-400 transition"
                               required>
                        @error('nisn')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Nama lengkap (full width) --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">
                            Nama Lengkap <span class="text-red-400">*</span>
                        </label>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $calonSiswa?->nama_lengkap) }}"
                               placeholder="Nama sesuai akta kelahiran"
                               class="w-full px-4 py-2.5 rounded-xl border @error('nama_lengkap') border-red-400 bg-red-50 @else border-slate-200 @enderror
                                      text-sm focus:outline-none focus:ring-2 focus:ring-sky-400 transition"
                               required>
                        @error('nama_lengkap')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Jenis kelamin --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">
                            Jenis Kelamin <span class="text-red-400">*</span>
                        </label>
                        <select name="jenis_kelamin" required
                                class="w-full px-4 py-2.5 rounded-xl border @error('jenis_kelamin') border-red-400 @else border-slate-200 @enderror
                                       text-sm focus:outline-none focus:ring-2 focus:ring-sky-400 transition bg-white">
                            <option value="">-- Pilih --</option>
                            <option value="L" {{ old('jenis_kelamin', $calonSiswa?->jenis_kelamin) === 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin', $calonSiswa?->jenis_kelamin) === 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Agama --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">
                            Agama <span class="text-red-400">*</span>
                        </label>
                        <select name="agama" required
                                class="w-full px-4 py-2.5 rounded-xl border @error('agama') border-red-400 @else border-slate-200 @enderror
                                       text-sm focus:outline-none focus:ring-2 focus:ring-sky-400 transition bg-white">
                            <option value="">-- Pilih --</option>
                            @foreach(['Islam','Kristen','Katolik','Hindu','Budha','Konghucu'] as $agama)
                            <option value="{{ $agama }}" {{ old('agama', $calonSiswa?->agama) === $agama ? 'selected' : '' }}>
                                {{ $agama }}
                            </option>
                            @endforeach
                        </select>
                        @error('agama')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Tempat lahir --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">
                            Tempat Lahir <span class="text-red-400">*</span>
                        </label>
                        <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $calonSiswa?->tempat_lahir) }}"
                               placeholder="Kota / Kabupaten"
                               class="w-full px-4 py-2.5 rounded-xl border @error('tempat_lahir') border-red-400 bg-red-50 @else border-slate-200 @enderror
                                      text-sm focus:outline-none focus:ring-2 focus:ring-sky-400 transition"
                               required>
                        @error('tempat_lahir')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Tanggal lahir --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">
                            Tanggal Lahir <span class="text-red-400">*</span>
                        </label>
                        <input type="date" name="tanggal_lahir"
                               value="{{ old('tanggal_lahir', $calonSiswa?->tanggal_lahir?->format('Y-m-d')) }}"
                               max="{{ date('Y-m-d') }}"
                               class="w-full px-4 py-2.5 rounded-xl border @error('tanggal_lahir') border-red-400 bg-red-50 @else border-slate-200 @enderror
                                      text-sm focus:outline-none focus:ring-2 focus:ring-sky-400 transition"
                               required>
                        @error('tanggal_lahir')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- No. HP --}}
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">No. HP / WA</label>
                        <input type="text" name="no_telepon" value="{{ old('no_telepon', $calonSiswa?->no_telepon) }}"
                               placeholder="08xxxxxxxxxx"
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm
                                      focus:outline-none focus:ring-2 focus:ring-sky-400 transition">
                    </div>

                </div>
            </div>

            {{-- ===== SEKSI 2: Alamat ===== --}}
            <div class="border-t border-slate-100 pt-6">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Alamat Tempat Tinggal</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">
                            Alamat Lengkap <span class="text-red-400">*</span>
                        </label>
                        <textarea name="alamat" rows="2" placeholder="Jl. ..."
                                  class="w-full px-4 py-2.5 rounded-xl border @error('alamat') border-red-400 bg-red-50 @else border-slate-200 @enderror
                                         text-sm focus:outline-none focus:ring-2 focus:ring-sky-400 transition resize-none"
                                  required>{{ old('alamat', $calonSiswa?->alamat) }}</textarea>
                        @error('alamat')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    @foreach([
                        ['kelurahan', 'Kelurahan / Desa'],
                        ['kecamatan', 'Kecamatan'],
                        ['kota',      'Kota / Kabupaten'],
                        ['provinsi',  'Provinsi'],
                    ] as [$name, $label])
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">{{ $label }}</label>
                        <input type="text" name="{{ $name }}"
                               value="{{ old($name, $calonSiswa?->$name) }}"
                               placeholder="{{ $label }}"
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm
                                      focus:outline-none focus:ring-2 focus:ring-sky-400 transition">
                    </div>
                    @endforeach

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Kode Pos</label>
                        <input type="text" name="kode_pos" value="{{ old('kode_pos', $calonSiswa?->kode_pos) }}"
                               maxlength="5" placeholder="5 digit"
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm
                                      focus:outline-none focus:ring-2 focus:ring-sky-400 transition">
                    </div>
                </div>
            </div>

            {{-- ===== SEKSI 3: Riwayat Sekolah ===== --}}
            <div class="border-t border-slate-100 pt-6">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Riwayat Pendidikan</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">
                            Asal Sekolah (SMP/MTs) <span class="text-red-400">*</span>
                        </label>
                        <input type="text" name="asal_sekolah" value="{{ old('asal_sekolah', $calonSiswa?->asal_sekolah) }}"
                               placeholder="Nama lengkap SMP/MTs"
                               class="w-full px-4 py-2.5 rounded-xl border @error('asal_sekolah') border-red-400 bg-red-50 @else border-slate-200 @enderror
                                      text-sm focus:outline-none focus:ring-2 focus:ring-sky-400 transition"
                               required>
                        @error('asal_sekolah')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">
                            Tahun Lulus <span class="text-red-400">*</span>
                        </label>
                        <input type="number" name="tahun_lulus" value="{{ old('tahun_lulus', $calonSiswa?->tahun_lulus) }}"
                               min="2000" max="{{ date('Y') + 1 }}" placeholder="{{ date('Y') }}"
                               class="w-full px-4 py-2.5 rounded-xl border @error('tahun_lulus') border-red-400 bg-red-50 @else border-slate-200 @enderror
                                      text-sm focus:outline-none focus:ring-2 focus:ring-sky-400 transition"
                               required>
                        @error('tahun_lulus')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Nilai Rata-rata Rapor</label>
                        <input type="number" name="nilai_rata_rata" step="0.01" min="0" max="100"
                               value="{{ old('nilai_rata_rata', $calonSiswa?->nilai_rata_rata) }}"
                               placeholder="contoh: 85.50"
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm
                                      focus:outline-none focus:ring-2 focus:ring-sky-400 transition">
                    </div>

                </div>
            </div>

            {{-- ===== SEKSI 4: Foto ===== --}}
            <div class="border-t border-slate-100 pt-6">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Foto Siswa</h3>
                <div class="flex items-start gap-5">

                    {{-- Preview foto saat ini --}}
                    <div class="flex-shrink-0">
                        <div class="w-20 h-24 rounded-xl border-2 border-slate-200 overflow-hidden bg-slate-50" id="preview-wrap">
                            @if($calonSiswa?->foto)
                                <img src="{{ asset('storage/' . $calonSiswa->foto) }}"
                                     alt="Foto siswa" class="w-full h-full object-cover" id="foto-preview">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-300" id="foto-placeholder">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <p class="text-xs text-slate-400 text-center mt-1">3 × 4</p>
                    </div>

                    <div class="flex-1">
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Upload Foto Terbaru</label>
                        <input type="file" name="foto" id="foto-input" accept="image/jpg,image/jpeg,image/png"
                               class="w-full text-sm border border-slate-200 rounded-xl px-3 py-2.5
                                      file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0
                                      file:text-xs file:bg-sky-50 file:text-sky-600 hover:file:bg-sky-100"
                               onchange="previewFoto(this)">
                        <p class="text-xs text-slate-400 mt-1.5">Format JPG/PNG • Maks. 2MB • Latar belakang merah/biru</p>
                        @error('foto')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

        </div>

        {{-- Footer form dengan tombol --}}
        <div class="px-6 py-4 bg-slate-50 rounded-b-2xl border-t border-slate-100 flex items-center justify-between">
            <a href="{{ route('siswa.dashboard') }}" class="text-sm text-slate-500 hover:text-slate-700 transition">
                ← Kembali ke Dashboard
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
    // Preview foto sebelum upload
    function previewFoto(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // Hapus placeholder, tampilkan preview
                const placeholder = document.getElementById('foto-placeholder');
                if (placeholder) placeholder.remove();

                let img = document.getElementById('foto-preview');
                if (!img) {
                    img = document.createElement('img');
                    img.id = 'foto-preview';
                    img.className = 'w-full h-full object-cover';
                    document.getElementById('preview-wrap').appendChild(img);
                }
                img.src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
