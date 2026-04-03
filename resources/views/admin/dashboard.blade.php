@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Admin')

{{-- Navigasi sidebar admin --}}
@section('sidebar-nav')
    @php
        $menu = [
            ['route' => 'admin.dashboard',   'label' => 'Dashboard',       'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
            ['route' => 'admin.siswa.index', 'label' => 'Data Pendaftar',  'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
            ['route' => 'admin.jurusan.index','label' => 'Kelola Jurusan', 'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10'],
        ];
    @endphp

    @foreach($menu as $item)
    <a href="{{ route($item['route']) }}"
       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all
              {{ request()->routeIs($item['route']) ? 'nav-active' : 'hover:bg-slate-700/50 hover:text-white' }}">
        <svg class="w-4 h-4 flex-shrink-0 {{ request()->routeIs($item['route']) ? 'text-white' : 'text-slate-400' }}"
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
        </svg>
        {{ $item['label'] }}
    </a>
    @endforeach
@endsection

@section('content')
<div class="space-y-6">

    {{-- Kartu statistik utama --}}
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">

        @php
            $cards = [
                ['label' => 'Total Pendaftar',       'value' => $stats['total_pendaftar'],  'color' => 'sky',   'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
                ['label' => 'Pending',                'value' => $stats['pending'],           'color' => 'yellow','icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['label' => 'Menunggu Verifikasi',    'value' => $stats['menunggu_verif'],    'color' => 'blue',  'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                ['label' => 'Diterima',               'value' => $stats['diterima'],          'color' => 'green', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['label' => 'Ditolak',                'value' => $stats['ditolak'],           'color' => 'red',   'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'],
            ];
            $colorMap = [
                'sky'    => 'bg-sky-50 text-sky-600 border-sky-100',
                'yellow' => 'bg-yellow-50 text-yellow-600 border-yellow-100',
                'blue'   => 'bg-blue-50 text-blue-600 border-blue-100',
                'green'  => 'bg-green-50 text-green-600 border-green-100',
                'red'    => 'bg-red-50 text-red-600 border-red-100',
            ];
        @endphp

        @foreach($cards as $card)
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm hover:shadow-md transition">
            <div class="flex items-start justify-between mb-3">
                <div class="w-9 h-9 rounded-xl {{ $colorMap[$card['color']] }} border flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"/>
                    </svg>
                </div>
            </div>
            <div class="font-display text-2xl font-semibold text-slate-800">{{ number_format($card['value']) }}</div>
            <div class="text-xs text-slate-500 mt-0.5">{{ $card['label'] }}</div>
        </div>
        @endforeach
    </div>

    {{-- Baris kedua: Per jurusan + Pembayaran --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Per jurusan --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <h3 class="font-semibold text-slate-800 mb-4 flex items-center gap-2">
                <span class="w-2 h-4 rounded-full bg-sky-500 inline-block"></span>
                Statistik Per Jurusan
            </h3>
            <div class="space-y-3">
                @forelse($perJurusan as $j)
                @php
                    $persen = $j->kuota > 0 ? round(($j->diterima_count / $j->kuota) * 100) : 0;
                @endphp
                <div>
                    <div class="flex justify-between items-center text-sm mb-1">
                        <span class="font-medium text-slate-700">{{ $j->nama_jurusan }}</span>
                        <span class="text-slate-500 text-xs">{{ $j->diterima_count }}/{{ $j->kuota }} kursi</span>
                    </div>
                    {{-- Progress bar --}}
                    <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full bg-sky-500 rounded-full transition-all duration-700"
                             style="width: {{ $persen }}%"></div>
                    </div>
                    <div class="flex gap-3 text-xs text-slate-400 mt-1">
                        <span>{{ $j->pendaftaran_count }} mendaftar</span>
                        <span>{{ $j->diterima_count }} diterima</span>
                        <span>Sisa: {{ $j->sisa_kuota }}</span>
                    </div>
                </div>
                @empty
                    <p class="text-slate-400 text-sm text-center py-4">Belum ada data jurusan.</p>
                @endforelse
            </div>
        </div>

        {{-- Status pembayaran --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <h3 class="font-semibold text-slate-800 mb-4 flex items-center gap-2">
                <span class="w-2 h-4 rounded-full bg-emerald-500 inline-block"></span>
                Status Pembayaran
            </h3>
            <div class="space-y-3">
                @foreach([
                    ['label' => 'Lunas',       'val' => $pembayaran['lunas'],       'color' => 'bg-green-500'],
                    ['label' => 'DP',          'val' => $pembayaran['dp'],          'color' => 'bg-yellow-400'],
                    ['label' => 'Belum Bayar', 'val' => $pembayaran['belum_bayar'], 'color' => 'bg-red-400'],
                ] as $p)
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full {{ $p['color'] }}"></span>
                        <span class="text-sm text-slate-600">{{ $p['label'] }}</span>
                    </div>
                    <span class="font-semibold text-slate-800 font-display">{{ $p['val'] }}</span>
                </div>
                @endforeach
                <div class="border-t border-slate-100 pt-3 mt-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Total</span>
                        <span class="font-semibold text-slate-800">
                            {{ array_sum($pembayaran) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel pendaftar terbaru --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <h3 class="font-semibold text-slate-800">Pendaftar Terbaru</h3>
            <a href="{{ route('admin.siswa.index') }}"
               class="text-xs text-sky-600 font-medium hover:underline">Lihat semua →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wide">
                    <tr>
                        <th class="text-left px-6 py-3">No. Daftar</th>
                        <th class="text-left px-6 py-3">Nama</th>
                        <th class="text-left px-6 py-3">Jurusan</th>
                        <th class="text-left px-6 py-3">Tgl. Daftar</th>
                        <th class="text-left px-6 py-3">Status</th>
                        <th class="text-left px-6 py-3">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($terbaru as $p)
                    @php $badge = $p->status_badge; @endphp
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="px-6 py-3 font-mono text-xs text-slate-600">{{ $p->no_pendaftaran }}</td>
                        <td class="px-6 py-3 font-medium text-slate-800">{{ $p->calonSiswa->nama_lengkap }}</td>
                        <td class="px-6 py-3 text-slate-600">{{ $p->jurusan->nama_jurusan }}</td>
                        <td class="px-6 py-3 text-slate-500">{{ $p->tanggal_daftar->format('d M Y') }}</td>
                        <td class="px-6 py-3">
                            {{-- Badge status dengan warna dinamis --}}
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                bg-{{ $badge['color'] }}-100 text-{{ $badge['color'] }}-700">
                                {{ $badge['label'] }}
                            </span>
                        </td>
                        <td class="px-6 py-3">
                            <a href="{{ route('admin.siswa.show', $p) }}"
                               class="text-sky-600 hover:text-sky-800 text-xs font-medium">Detail →</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-slate-400 text-sm">
                            Belum ada pendaftar.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
