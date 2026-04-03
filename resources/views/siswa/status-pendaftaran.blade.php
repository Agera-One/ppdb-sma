{{-- ============================================================ --}}
{{-- VIEW: resources/views/siswa/status-pendaftaran.blade.php   --}}
{{-- Halaman status & timeline pendaftaran siswa                --}}
{{-- ============================================================ --}}
@extends('layouts.app')
@section('title', 'Status Pendaftaran')
@section('page-title', 'Status Pendaftaran Saya')

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
<div class="max-w-2xl space-y-5">

    @if(!$pendaftaran)
    {{-- Belum daftar --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-10 text-center">
        <div class="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
        </div>
        <h3 class="font-display text-lg text-slate-700 font-semibold">Belum Ada Pendaftaran</h3>
        <p class="text-slate-500 text-sm mt-2 mb-5">Anda belum melakukan pendaftaran. Lengkapi data dan mulai daftar sekarang.</p>
        <a href="{{ route('siswa.pendaftaran.create') }}"
           class="inline-flex items-center gap-2 bg-sky-500 hover:bg-sky-600 text-white text-sm font-medium px-5 py-2.5 rounded-xl transition">
            Mulai Pendaftaran →
        </a>
    </div>
    @else

    {{-- Nomor & status utama --}}
    @php $badge = $pendaftaran->status_badge; @endphp
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs text-slate-400 mb-0.5">Nomor Pendaftaran</p>
                <p class="font-mono font-bold text-slate-800 text-lg">{{ $pendaftaran->no_pendaftaran }}</p>
            </div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-semibold
                bg-{{ $badge['color'] }}-100 text-{{ $badge['color'] }}-700 border border-{{ $badge['color'] }}-200">
                {{-- Ikon status --}}
                @if($pendaftaran->status === 'diterima')
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                @elseif($pendaftaran->status === 'ditolak')
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                @else
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                @endif
                {{ $badge['label'] }}
            </span>
        </div>

        <div class="grid grid-cols-2 gap-4 mt-4 pt-4 border-t border-slate-100 text-sm">
            <div>
                <dt class="text-slate-400 text-xs">Jurusan Dipilih</dt>
                <dd class="font-medium text-slate-700 mt-0.5">{{ $pendaftaran->jurusan->nama_jurusan }}</dd>
            </div>
            <div>
                <dt class="text-slate-400 text-xs">Tanggal Daftar</dt>
                <dd class="font-medium text-slate-700 mt-0.5">{{ $pendaftaran->tanggal_daftar->format('d M Y, H:i') }}</dd>
            </div>
        </div>

        {{-- Catatan admin --}}
        @if($pendaftaran->catatan_admin)
        <div class="mt-4 bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 text-sm text-amber-800">
            <strong>📋 Catatan dari Panitia:</strong><br>
            {{ $pendaftaran->catatan_admin }}
        </div>
        @endif
    </div>

    {{-- Timeline status --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
        <h3 class="font-semibold text-slate-800 mb-5">Alur Proses Pendaftaran</h3>

        @php
            $steps = [
                [
                    'label'  => 'Pendaftaran Diterima',
                    'desc'   => 'Formulir pendaftaran berhasil disubmit',
                    'done'   => true,
                    'date'   => $pendaftaran->tanggal_daftar->format('d M Y'),
                ],
                [
                    'label'  => 'Berkas Diupload',
                    'desc'   => 'Semua dokumen berhasil diupload',
                    'done'   => $pendaftaran->berkas->count() > 0,
                    'date'   => $pendaftaran->berkas->first()?->created_at?->format('d M Y'),
                ],
                [
                    'label'  => 'Verifikasi Berkas',
                    'desc'   => 'Berkas sedang/sudah diverifikasi panitia',
                    'done'   => in_array($pendaftaran->status, ['diterima', 'ditolak']),
                    'active' => $pendaftaran->status === 'menunggu_verifikasi',
                    'date'   => $pendaftaran->tanggal_verifikasi?->format('d M Y'),
                ],
                [
                    'label'  => 'Keputusan Akhir',
                    'desc'   => match($pendaftaran->status) {
                        'diterima' => '✅ Selamat! Anda DITERIMA',
                        'ditolak'  => '❌ Mohon maaf, Anda tidak diterima',
                        default    => 'Menunggu keputusan panitia',
                    },
                    'done'   => in_array($pendaftaran->status, ['diterima', 'ditolak']),
                    'active' => false,
                    'date'   => $pendaftaran->tanggal_verifikasi?->format('d M Y'),
                    'special' => $pendaftaran->status,
                ],
            ];
        @endphp

        <div class="relative">
            {{-- Garis vertikal timeline --}}
            <div class="absolute left-3.5 top-0 bottom-0 w-px bg-slate-100"></div>

            <div class="space-y-6">
                @foreach($steps as $i => $step)
                <div class="flex gap-4 relative">
                    {{-- Dot timeline --}}
                    <div class="w-7 h-7 rounded-full flex items-center justify-center flex-shrink-0 z-10
                        @if($step['done'] && ($step['special'] ?? '') !== 'ditolak')
                            bg-green-500 text-white
                        @elseif(($step['special'] ?? '') === 'ditolak')
                            bg-red-500 text-white
                        @elseif($step['active'] ?? false)
                            bg-sky-500 text-white ring-4 ring-sky-100
                        @else
                            bg-white border-2 border-slate-200 text-slate-300
                        @endif">

                        @if($step['done'])
                            @if(($step['special'] ?? '') === 'ditolak')
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            @else
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                </svg>
                            @endif
                        @elseif($step['active'] ?? false)
                            <div class="w-2 h-2 rounded-full bg-white animate-pulse"></div>
                        @else
                            <div class="w-2 h-2 rounded-full bg-slate-300"></div>
                        @endif
                    </div>

                    <div class="flex-1 pb-1">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-semibold {{ $step['done'] ? 'text-slate-800' : 'text-slate-400' }}">
                                {{ $step['label'] }}
                            </p>
                            @if($step['date'])
                            <span class="text-xs text-slate-400">{{ $step['date'] }}</span>
                            @endif
                        </div>
                        <p class="text-xs text-slate-500 mt-0.5
                            {{ ($step['special'] ?? '') === 'diterima' ? 'text-green-600 font-medium' : '' }}
                            {{ ($step['special'] ?? '') === 'ditolak'  ? 'text-red-600 font-medium' : '' }}">
                            {{ $step['desc'] }}
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Status berkas --}}
    @if($pendaftaran->berkas->count() > 0)
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
        <h3 class="font-semibold text-slate-800 mb-4">Status Berkas</h3>
        <div class="space-y-2">
            @foreach($pendaftaran->berkas as $b)
            @php
                $bc = ['belum_diverifikasi' => ['bg-yellow-100','text-yellow-700','Menunggu'],
                       'valid'              => ['bg-green-100', 'text-green-700', 'Valid ✓'],
                       'tidak_valid'        => ['bg-red-100',   'text-red-700',   'Tidak Valid']][$b->status_berkas];
            @endphp
            <div class="flex items-center justify-between py-2 border-b border-slate-50 last:border-0">
                <span class="text-sm text-slate-700">
                    {{ \App\Models\Berkas::labelJenis()[$b->jenis_berkas] ?? $b->jenis_berkas }}
                </span>
                <div class="flex items-center gap-2">
                    @if($b->catatan_berkas)
                    <span class="text-xs text-red-500 max-w-32 truncate" title="{{ $b->catatan_berkas }}">
                        ⚠ {{ $b->catatan_berkas }}
                    </span>
                    @endif
                    <span class="text-xs px-2.5 py-0.5 rounded-full font-medium {{ $bc[0] }} {{ $bc[1] }}">
                        {{ $bc[2] }}
                    </span>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Jika ada berkas tidak valid, arahkan upload ulang --}}
        @if($pendaftaran->berkas->where('status_berkas', 'tidak_valid')->count() > 0)
        <div class="mt-3 pt-3 border-t border-slate-100">
            <a href="{{ route('siswa.berkas.index') }}"
               class="inline-flex items-center gap-1.5 text-sm text-sky-600 font-medium hover:underline">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
                Upload ulang berkas yang tidak valid
            </a>
        </div>
        @endif
    </div>
    @endif

    {{-- Status pembayaran --}}
    @if($pendaftaran->pembayaran && $pendaftaran->status === 'diterima')
    @php $pm = $pendaftaran->pembayaran; $pmBadge = $pm->status_badge; @endphp
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
        <h3 class="font-semibold text-slate-800 mb-4">Status Pembayaran</h3>
        <div class="flex items-center justify-between mb-4">
            <div>
                <p class="text-xs text-slate-400">Total Tagihan</p>
                <p class="font-display text-xl font-semibold text-slate-800">
                    Rp {{ number_format($pm->jumlah_tagihan, 0, ',', '.') }}
                </p>
            </div>
            <span class="px-3 py-1.5 rounded-full text-sm font-semibold
                bg-{{ $pmBadge['color'] }}-100 text-{{ $pmBadge['color'] }}-700">
                {{ $pmBadge['label'] }}
            </span>
        </div>

        @if($pm->status_pembayaran !== 'lunas')
        <a href="{{ route('siswa.berkas.index') }}"
           class="block w-full text-center bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold
                  py-2.5 rounded-xl transition">
            Upload Bukti Pembayaran
        </a>
        @else
        <div class="bg-green-50 border border-green-200 rounded-xl p-3 text-sm text-green-700 text-center">
            🎉 Pembayaran Lunas — Selamat bergabung di SMA Tunas Bangsa!
        </div>
        @endif
    </div>
    @endif

    @endif {{-- end if $pendaftaran --}}
</div>
@endsection
