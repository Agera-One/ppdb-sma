{{-- ============================================================ --}}
{{-- VIEW: resources/views/siswa/dashboard.blade.php            --}}
{{-- Dashboard siswa dengan progress tracker                    --}}
{{-- ============================================================ --}}
@extends('layouts.app')
@section('title', 'Dashboard Siswa')
@section('page-title', 'Dashboard Saya')

@section('sidebar-nav')
    @php
        $menu = [
            ['route' => 'siswa.dashboard',        'label' => 'Dashboard'],
            ['route' => 'siswa.data-diri.edit',   'label' => 'Data Diri'],
            ['route' => 'siswa.data-ortu.edit',   'label' => 'Data Orang Tua'],
            ['route' => 'siswa.pendaftaran.create','label' => 'Pendaftaran'],
            ['route' => 'siswa.berkas.index',      'label' => 'Upload Berkas'],
            ['route' => 'siswa.pendaftaran.status','label' => 'Status Pendaftaran'],
        ];
    @endphp
    @foreach($menu as $item)
    <a href="{{ route($item['route']) }}"
       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all
              {{ request()->routeIs($item['route']) ? 'nav-active' : 'hover:bg-slate-700/50 hover:text-white' }}">
        {{ $item['label'] }}
    </a>
    @endforeach
@endsection

@section('content')
<div class="space-y-6 max-w-3xl">

    {{-- Sambutan --}}
    <div class="bg-gradient-to-br from-sky-500 to-blue-600 rounded-3xl p-6 text-white relative overflow-hidden">
        {{-- Dekorasi latar belakang --}}
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-8 translate-x-8"></div>
        <div class="absolute bottom-0 right-12 w-20 h-20 bg-white/10 rounded-full translate-y-6"></div>

        <div class="relative">
            <p class="text-sky-100 text-sm">Selamat datang,</p>
            <h2 class="font-display text-2xl font-semibold mt-0.5">{{ $user->name }} 👋</h2>
            <p class="text-sky-100 text-sm mt-2">PPDB SMA Tunas Bangsa {{ date('Y') }}/{{ date('Y') + 1 }}</p>
        </div>
    </div>

    {{-- Progress tracker --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-slate-800">Progres Pendaftaran</h3>
            <span class="font-display text-2xl text-sky-500">{{ $progres }}%</span>
        </div>

        {{-- Progress bar --}}
        <div class="h-2.5 bg-slate-100 rounded-full mb-5 overflow-hidden">
            <div class="h-full bg-sky-500 rounded-full transition-all duration-1000"
                 style="width: {{ $progres }}%"
                 id="progress-bar">
            </div>
        </div>

        {{-- Langkah-langkah --}}
        <div class="space-y-3">
            @php
                $langkah = [
                    ['label' => 'Isi Data Diri',       'route' => 'siswa.data-diri.edit',    'done' => (bool)$calonSiswa],
                    ['label' => 'Isi Data Orang Tua',  'route' => 'siswa.data-ortu.edit',    'done' => (bool)$calonSiswa?->orangTua],
                    ['label' => 'Daftar & Pilih Jurusan','route' => 'siswa.pendaftaran.create','done' => (bool)$pendaftaran],
                    ['label' => 'Upload Berkas',       'route' => 'siswa.berkas.index',      'done' => (bool)($pendaftaran?->berkas()->exists())],
                ];
            @endphp

            @foreach($langkah as $i => $step)
            <div class="flex items-center gap-3">
                {{-- Ikon centang atau angka --}}
                <div class="w-7 h-7 rounded-full flex items-center justify-center flex-shrink-0 text-xs font-bold
                    {{ $step['done'] ? 'bg-green-500 text-white' : 'bg-slate-100 text-slate-400' }}">
                    @if($step['done'])
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                    @else
                        {{ $i + 1 }}
                    @endif
                </div>
                <span class="text-sm {{ $step['done'] ? 'text-slate-500 line-through' : 'text-slate-700 font-medium' }}">
                    {{ $step['label'] }}
                </span>
                @if(!$step['done'])
                <a href="{{ route($step['route']) }}"
                   class="ml-auto text-xs bg-sky-50 text-sky-600 border border-sky-200 px-3 py-1 rounded-full hover:bg-sky-100 transition">
                    Mulai →
                </a>
                @endif
            </div>
            @endforeach
        </div>
    </div>

    {{-- Status pendaftaran (jika sudah daftar) --}}
    @if($pendaftaran)
    @php $badge = $pendaftaran->status_badge; @endphp
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
        <h3 class="font-semibold text-slate-800 mb-4">Informasi Pendaftaran</h3>
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <dt class="text-slate-400 text-xs">No. Pendaftaran</dt>
                <dd class="font-mono font-semibold text-slate-800 mt-0.5">{{ $pendaftaran->no_pendaftaran }}</dd>
            </div>
            <div>
                <dt class="text-slate-400 text-xs">Jurusan Dipilih</dt>
                <dd class="font-medium text-slate-700 mt-0.5">{{ $pendaftaran->jurusan->nama_jurusan }}</dd>
            </div>
            <div>
                <dt class="text-slate-400 text-xs">Status</dt>
                <dd class="mt-0.5">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        bg-{{ $badge['color'] }}-100 text-{{ $badge['color'] }}-700">
                        {{ $badge['label'] }}
                    </span>
                </dd>
            </div>
            @if($pembayaran)
            @php $pmBadge = $pembayaran->status_badge; @endphp
            <div>
                <dt class="text-slate-400 text-xs">Pembayaran</dt>
                <dd class="mt-0.5">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        bg-{{ $pmBadge['color'] }}-100 text-{{ $pmBadge['color'] }}-700">
                        {{ $pmBadge['label'] }}
                    </span>
                </dd>
            </div>
            @endif
        </div>

        {{-- Catatan admin jika ada --}}
        @if($pendaftaran->catatan_admin)
        <div class="mt-4 bg-amber-50 border border-amber-200 rounded-xl p-3 text-sm text-amber-800">
            <strong>Catatan Admin:</strong> {{ $pendaftaran->catatan_admin }}
        </div>
        @endif
    </div>
    @endif

</div>
@endsection


{{-- ============================================================ --}}
{{-- VIEW: resources/views/siswa/berkas.blade.php                --}}
{{-- Halaman upload berkas pendaftaran                           --}}
{{-- ============================================================ --}}
{{-- CATATAN: File ini terpisah di views/siswa/berkas.blade.php  --}}
