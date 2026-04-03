@extends('layouts.app')

@section('title', 'Detail Pendaftar')
@section('page-title', 'Detail Pendaftar — ' . $pendaftaran->no_pendaftaran)

@section('sidebar-nav')
    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm hover:bg-slate-700/50 hover:text-white transition">Dashboard</a>
    <a href="{{ route('admin.siswa.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm nav-active">Data Pendaftar</a>
    <a href="{{ route('admin.jurusan.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm hover:bg-slate-700/50 hover:text-white transition">Kelola Jurusan</a>
@endsection

@section('content')
<div class="space-y-6 max-w-5xl">

    {{-- Header info + tombol kembali --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.siswa.index') }}"
           class="p-2 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
        </a>
        <div>
            <h2 class="font-display text-xl text-slate-800">{{ $pendaftaran->calonSiswa->nama_lengkap }}</h2>
            <p class="text-xs text-slate-500">{{ $pendaftaran->no_pendaftaran }} • {{ $pendaftaran->jurusan->nama_jurusan }}</p>
        </div>
        {{-- Badge status --}}
        @php $badge = $pendaftaran->status_badge; @endphp
        <span class="ml-auto inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
            bg-{{ $badge['color'] }}-100 text-{{ $badge['color'] }}-700 border border-{{ $badge['color'] }}-200">
            {{ $badge['label'] }}
        </span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Kolom kiri: Data diri & ortu --}}
        <div class="lg:col-span-2 space-y-4">

            {{-- Data diri siswa --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                <h3 class="font-semibold text-slate-800 mb-4 text-sm uppercase tracking-wide text-slate-500">Data Diri Siswa</h3>
                @php $s = $pendaftaran->calonSiswa; @endphp
                <div class="grid grid-cols-2 gap-x-6 gap-y-3 text-sm">
                    @foreach([
                        ['NIK', $s->nik], ['NISN', $s->nisn],
                        ['Jenis Kelamin', $s->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan'],
                        ['TTL', $s->tempat_lahir . ', ' . $s->tanggal_lahir->format('d M Y')],
                        ['Agama', $s->agama], ['Asal Sekolah', $s->asal_sekolah],
                        ['Tahun Lulus', $s->tahun_lulus], ['No. HP', $s->no_telepon ?? '-'],
                        ['Nilai Rata-rata', $s->nilai_rata_rata ?? '-'],
                    ] as [$label, $val])
                    <div>
                        <dt class="text-slate-400 text-xs">{{ $label }}</dt>
                        <dd class="font-medium text-slate-700 mt-0.5">{{ $val }}</dd>
                    </div>
                    @endforeach
                </div>
                <div class="mt-3 pt-3 border-t border-slate-100 text-sm">
                    <dt class="text-slate-400 text-xs">Alamat</dt>
                    <dd class="font-medium text-slate-700 mt-0.5">{{ $s->alamat }}</dd>
                </div>
            </div>

            {{-- Data orang tua --}}
            @if($pendaftaran->calonSiswa->orangTua)
            @php $o = $pendaftaran->calonSiswa->orangTua; @endphp
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                <h3 class="font-semibold text-slate-800 mb-4 text-sm uppercase tracking-wide text-slate-500">Data Orang Tua</h3>
                <div class="grid grid-cols-2 gap-x-6 gap-y-3 text-sm">
                    @foreach([
                        ['Nama Ayah', $o->nama_ayah], ['Pekerjaan Ayah', $o->pekerjaan_ayah ?? '-'],
                        ['Nama Ibu', $o->nama_ibu],   ['Pekerjaan Ibu', $o->pekerjaan_ibu ?? '-'],
                        ['No. HP Ayah', $o->no_hp_ayah ?? '-'], ['No. HP Ibu', $o->no_hp_ibu ?? '-'],
                        ['Penghasilan', $o->penghasilan_ortu ? 'Rp ' . number_format($o->penghasilan_ortu) : '-'],
                    ] as [$label, $val])
                    <div>
                        <dt class="text-slate-400 text-xs">{{ $label }}</dt>
                        <dd class="font-medium text-slate-700 mt-0.5">{{ $val }}</dd>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Daftar berkas --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                <h3 class="font-semibold text-slate-800 mb-4 text-sm uppercase tracking-wide text-slate-500">Berkas Pendaftaran</h3>
                @forelse($pendaftaran->berkas as $berkas)
                <div class="flex items-center justify-between py-3 border-b border-slate-50 last:border-0">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center">
                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-slate-700">
                                {{ \App\Models\Berkas::labelJenis()[$berkas->jenis_berkas] ?? $berkas->jenis_berkas }}
                            </div>
                            <div class="text-xs text-slate-400">{{ $berkas->nama_file }}</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        {{-- Status badge berkas --}}
                        @php
                            $bc = ['belum_diverifikasi' => 'yellow', 'valid' => 'green', 'tidak_valid' => 'red'][$berkas->status_berkas];
                        @endphp
                        <span class="text-xs px-2 py-0.5 rounded-full bg-{{ $bc }}-100 text-{{ $bc }}-700">
                            {{ str_replace('_', ' ', $berkas->status_berkas) }}
                        </span>
                        <a href="{{ $berkas->url_file }}" target="_blank"
                           class="text-xs text-sky-600 hover:underline">Lihat</a>
                        {{-- Form verifikasi berkas (mini) --}}
                        <button onclick="toggleVerifikasiBerkas({{ $berkas->id }})"
                            class="text-xs bg-slate-100 hover:bg-slate-200 px-2 py-1 rounded-lg text-slate-600 transition">
                            Verifikasi
                        </button>
                    </div>
                </div>

                {{-- Form verifikasi berkas (tersembunyi, toggle JS) --}}
                <div id="form-berkas-{{ $berkas->id }}" class="hidden mt-3 bg-slate-50 rounded-xl p-4">
                    <form method="POST" action="{{ route('admin.berkas.verifikasi', $berkas) }}" class="flex flex-wrap gap-3 items-end">
                        @csrf @method('PATCH')
                        <div>
                            <label class="text-xs text-slate-600 block mb-1">Status</label>
                            <select name="status_berkas"
                                class="text-sm border border-slate-200 rounded-lg px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-sky-400">
                                <option value="valid" {{ $berkas->status_berkas === 'valid' ? 'selected' : '' }}>Valid</option>
                                <option value="tidak_valid" {{ $berkas->status_berkas === 'tidak_valid' ? 'selected' : '' }}>Tidak Valid</option>
                            </select>
                        </div>
                        <div class="flex-1 min-w-40">
                            <label class="text-xs text-slate-600 block mb-1">Catatan (opsional)</label>
                            <input type="text" name="catatan_berkas" value="{{ $berkas->catatan_berkas }}"
                                placeholder="Catatan untuk siswa..."
                                class="w-full text-sm border border-slate-200 rounded-lg px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-sky-400">
                        </div>
                        <button type="submit"
                            class="bg-sky-500 hover:bg-sky-600 text-white text-xs px-4 py-2 rounded-lg transition font-medium">
                            Simpan
                        </button>
                    </form>
                </div>
                @empty
                    <p class="text-slate-400 text-sm text-center py-4">Belum ada berkas diupload.</p>
                @endforelse
            </div>
        </div>

        {{-- Kolom kanan: Verifikasi & Pembayaran --}}
        <div class="space-y-4">

            {{-- Form verifikasi status pendaftaran --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                <h3 class="font-semibold text-slate-800 mb-4 text-sm uppercase tracking-wide text-slate-500">Verifikasi Pendaftaran</h3>
                <form method="POST" action="{{ route('admin.siswa.update-status', $pendaftaran) }}" class="space-y-3">
                    @csrf @method('PATCH')
                    <div>
                        <label class="text-xs text-slate-600 block mb-1">Keputusan</label>
                        <select name="status" required
                            class="w-full text-sm border border-slate-200 rounded-xl px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-sky-400">
                            <option value="">-- Pilih --</option>
                            <option value="diterima" {{ $pendaftaran->status === 'diterima' ? 'selected' : '' }}>✅ Diterima</option>
                            <option value="ditolak"  {{ $pendaftaran->status === 'ditolak'  ? 'selected' : '' }}>❌ Ditolak</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-xs text-slate-600 block mb-1">Catatan Admin</label>
                        <textarea name="catatan_admin" rows="3" placeholder="Alasan/keterangan..."
                            class="w-full text-sm border border-slate-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-sky-400 resize-none">{{ $pendaftaran->catatan_admin }}</textarea>
                    </div>
                    <button type="submit"
                        class="w-full bg-slate-800 hover:bg-slate-900 text-white text-sm py-2.5 rounded-xl font-medium transition">
                        Simpan Keputusan
                    </button>
                </form>
            </div>

            {{-- Verifikasi pembayaran --}}
            @if($pendaftaran->pembayaran)
            @php $pm = $pendaftaran->pembayaran; $pmBadge = $pm->status_badge; @endphp
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                <h3 class="font-semibold text-slate-800 mb-4 text-sm uppercase tracking-wide text-slate-500">Pembayaran</h3>
                <div class="space-y-2 text-sm mb-4">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Tagihan</span>
                        <span class="font-medium">Rp {{ number_format($pm->jumlah_tagihan) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Dibayar</span>
                        <span class="font-medium">Rp {{ number_format($pm->jumlah_bayar ?? 0) }}</span>
                    </div>
                    <div class="flex justify-between items-center pt-2 border-t border-slate-100">
                        <span class="text-slate-500">Status</span>
                        <span class="text-xs px-2 py-0.5 rounded-full bg-{{ $pmBadge['color'] }}-100 text-{{ $pmBadge['color'] }}-700">
                            {{ $pmBadge['label'] }}
                        </span>
                    </div>
                </div>
                @if($pm->bukti_pembayaran)
                <a href="{{ $pm->bukti_bayar_url }}" target="_blank"
                   class="block text-center text-xs text-sky-600 hover:underline mb-3">
                    Lihat Bukti Pembayaran →
                </a>
                @endif
                <form method="POST" action="{{ route('admin.pembayaran.verifikasi', $pm) }}" class="space-y-2">
                    @csrf @method('PATCH')
                    <select name="status_pembayaran" required
                        class="w-full text-sm border border-slate-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-sky-400">
                        <option value="dp"    {{ $pm->status_pembayaran === 'dp'    ? 'selected' : '' }}>💛 DP</option>
                        <option value="lunas" {{ $pm->status_pembayaran === 'lunas' ? 'selected' : '' }}>✅ Lunas</option>
                    </select>
                    <input type="text" name="catatan_pembayaran" value="{{ $pm->catatan_pembayaran }}"
                        placeholder="Catatan pembayaran..."
                        class="w-full text-sm border border-slate-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-sky-400">
                    <button type="submit"
                        class="w-full bg-emerald-500 hover:bg-emerald-600 text-white text-sm py-2 rounded-xl font-medium transition">
                        Update Pembayaran
                    </button>
                </form>
            </div>
            @endif

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Toggle form verifikasi berkas per item
    function toggleVerifikasiBerkas(id) {
        const el = document.getElementById('form-berkas-' + id);
        el.classList.toggle('hidden');
    }
</script>
@endpush
