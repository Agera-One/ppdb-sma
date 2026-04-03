{{-- ============================================================ --}}
{{-- VIEW: resources/views/siswa/pendaftaran.blade.php          --}}
{{-- Form pilih jurusan & konfirmasi pendaftaran                --}}
{{-- ============================================================ --}}
@extends('layouts.app')
@section('title', 'Pendaftaran')
@section('page-title', 'Formulir Pendaftaran')

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
        <span class="w-5 h-5 rounded-full bg-green-500 text-white flex items-center justify-center text-xs">✓</span>
        <span class="text-slate-400 line-through">Data Orang Tua</span>
        <span class="text-slate-300">›</span>
        <span class="w-5 h-5 rounded-full bg-sky-500 text-white flex items-center justify-center text-xs font-bold">3</span>
        <span class="font-medium text-sky-600">Pendaftaran</span>
        <span class="text-slate-300">›</span>
        <span>Upload Berkas</span>
    </div>

    <form method="POST" action="{{ route('siswa.pendaftaran.store') }}"
          onsubmit="return konfirmasiDaftar()"
          class="space-y-5">
        @csrf

        {{-- Ringkasan data diri --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Ringkasan Data Pendaftar</h3>
            <div class="grid grid-cols-2 gap-3 text-sm">
                <div>
                    <dt class="text-slate-400 text-xs">Nama</dt>
                    <dd class="font-semibold text-slate-800">{{ $calonSiswa->nama_lengkap }}</dd>
                </div>
                <div>
                    <dt class="text-slate-400 text-xs">NISN</dt>
                    <dd class="font-mono font-medium text-slate-700">{{ $calonSiswa->nisn }}</dd>
                </div>
                <div>
                    <dt class="text-slate-400 text-xs">Asal Sekolah</dt>
                    <dd class="font-medium text-slate-700">{{ $calonSiswa->asal_sekolah }}</dd>
                </div>
                <div>
                    <dt class="text-slate-400 text-xs">Tahun Lulus</dt>
                    <dd class="font-medium text-slate-700">{{ $calonSiswa->tahun_lulus }}</dd>
                </div>
            </div>
        </div>

        {{-- Pilih jurusan --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <h2 class="font-display text-xl font-semibold text-slate-800 mb-1">Pilih Program Peminatan</h2>
            <p class="text-slate-500 text-sm mb-5">Pilih satu jurusan yang sesuai dengan minat dan kemampuan Anda</p>

            @error('id_jurusan')
            <div class="bg-red-50 border border-red-200 rounded-xl p-3 mb-4 text-sm text-red-700">
                {{ $message }}
            </div>
            @enderror

            <div class="grid grid-cols-1 gap-3" id="jurusan-list">
                @forelse($jurusan as $j)
                <label class="jurusan-card relative block cursor-pointer" data-id="{{ $j->id }}">
                    <input type="radio" name="id_jurusan" value="{{ $j->id }}"
                           class="absolute opacity-0 w-0 h-0"
                           {{ old('id_jurusan') == $j->id ? 'checked' : '' }}
                           onchange="piliJurusan({{ $j->id }})"
                           required>

                    <div class="border-2 rounded-2xl p-4 transition-all duration-150 hover:border-sky-300
                                {{ old('id_jurusan') == $j->id ? 'border-sky-500 bg-sky-50' : 'border-slate-200 bg-white' }}"
                         id="card-jurusan-{{ $j->id }}">

                        <div class="flex items-start justify-between">
                            <div class="flex items-center gap-3">
                                {{-- Indikator pemilihan --}}
                                <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center flex-shrink-0
                                            {{ old('id_jurusan') == $j->id ? 'border-sky-500' : 'border-slate-300' }}"
                                     id="radio-{{ $j->id }}">
                                    <div class="w-2.5 h-2.5 rounded-full bg-sky-500
                                                {{ old('id_jurusan') == $j->id ? '' : 'hidden' }}"
                                         id="dot-{{ $j->id }}"></div>
                                </div>

                                <div>
                                    <div class="font-semibold text-slate-800">{{ $j->nama_jurusan }}</div>
                                    <div class="text-xs text-slate-500 font-mono">{{ $j->kode_jurusan }}</div>
                                </div>
                            </div>

                            {{-- Info kuota --}}
                            <div class="text-right text-xs">
                                <div class="font-semibold text-slate-700">{{ $j->sisa_kuota }} kursi</div>
                                <div class="text-slate-400">dari {{ $j->kuota }}</div>
                                {{-- Mini progress bar kuota --}}
                                @php $persenKuota = $j->kuota > 0 ? round(($j->siswa_diterima / $j->kuota) * 100) : 0; @endphp
                                <div class="h-1.5 w-16 bg-slate-100 rounded-full mt-1 overflow-hidden">
                                    <div class="h-full rounded-full {{ $persenKuota > 80 ? 'bg-red-400' : 'bg-sky-400' }}"
                                         style="width: {{ $persenKuota }}%"></div>
                                </div>
                            </div>
                        </div>

                        @if($j->deskripsi)
                        <p class="text-xs text-slate-500 mt-2 ml-8">{{ $j->deskripsi }}</p>
                        @endif
                    </div>
                </label>
                @empty
                <div class="text-center py-8 text-slate-400 text-sm">
                    Belum ada jurusan yang tersedia. Hubungi panitia PPDB.
                </div>
                @endforelse
            </div>
        </div>

        {{-- Pernyataan & submit --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <h3 class="font-semibold text-slate-800 mb-3">Pernyataan Pendaftar</h3>
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 text-sm text-amber-800 mb-4">
                <p>Dengan mendaftar, saya menyatakan bahwa:</p>
                <ul class="list-disc ml-4 mt-1.5 space-y-1">
                    <li>Semua data yang saya isi adalah <strong>benar dan dapat dipertanggungjawabkan</strong></li>
                    <li>Saya <strong>belum pernah terdaftar</strong> di SMA/SMK/MA lain pada tahun ajaran ini</li>
                    <li>Saya bersedia mematuhi seluruh peraturan sekolah</li>
                </ul>
            </div>

            <label class="flex items-start gap-2 cursor-pointer">
                <input type="checkbox" id="setuju" required
                       class="mt-0.5 w-4 h-4 rounded text-sky-500 border-slate-300 focus:ring-sky-400">
                <span class="text-sm text-slate-700">
                    Saya menyetujui pernyataan di atas dan menyatakan data yang saya isi adalah benar.
                </span>
            </label>
        </div>

        <div class="flex items-center justify-between">
            <a href="{{ route('siswa.data-ortu.edit') }}"
               class="text-sm text-slate-500 hover:text-slate-700 transition">
                ← Data Orang Tua
            </a>
            <button type="submit"
                    class="bg-sky-500 hover:bg-sky-600 text-white font-semibold text-sm px-8 py-3 rounded-xl
                           transition shadow-lg shadow-sky-100">
                Daftarkan Sekarang →
            </button>
        </div>

    </form>
</div>
@endsection

@push('scripts')
<script>
    // Update tampilan card saat jurusan dipilih
    function piliJurusan(id) {
        // Reset semua card
        document.querySelectorAll('[id^="card-jurusan-"]').forEach(el => {
            el.className = el.className.replace('border-sky-500 bg-sky-50', 'border-slate-200 bg-white');
        });
        document.querySelectorAll('[id^="radio-"]').forEach(el => {
            el.className = el.className.replace('border-sky-500', 'border-slate-300');
        });
        document.querySelectorAll('[id^="dot-"]').forEach(el => {
            el.classList.add('hidden');
        });

        // Aktifkan card yang dipilih
        const card = document.getElementById('card-jurusan-' + id);
        card.className = card.className.replace('border-slate-200 bg-white', 'border-sky-500 bg-sky-50');

        const radio = document.getElementById('radio-' + id);
        radio.className = radio.className.replace('border-slate-300', 'border-sky-500');

        document.getElementById('dot-' + id).classList.remove('hidden');
    }

    // Konfirmasi sebelum submit
    function konfirmasiDaftar() {
        const selected = document.querySelector('input[name="id_jurusan"]:checked');
        if (!selected) {
            alert('Silakan pilih jurusan terlebih dahulu.');
            return false;
        }
        const jurusanLabel = selected.closest('.jurusan-card').querySelector('.font-semibold').textContent;
        return confirm(`Anda akan mendaftar ke jurusan:\n"${jurusanLabel}"\n\nLanjutkan pendaftaran?`);
    }
</script>
@endpush
