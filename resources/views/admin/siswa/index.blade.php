@extends('layouts.app')
@section('title', 'Data Pendaftar')
@section('page-title', 'Data Pendaftar PPDB')

@section('sidebar-nav')
    <a href="{{ route('admin.dashboard') }}"
       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm hover:bg-slate-700/50 hover:text-white transition {{ request()->routeIs('admin.dashboard') ? 'nav-active' : '' }}">
        Dashboard
    </a>
    <a href="{{ route('admin.siswa.index') }}"
       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm hover:bg-slate-700/50 hover:text-white transition {{ request()->routeIs('admin.siswa.*') ? 'nav-active' : '' }}">
        Data Pendaftar
    </a>
    <a href="{{ route('admin.jurusan.index') }}"
       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm hover:bg-slate-700/50 hover:text-white transition {{ request()->routeIs('admin.jurusan.*') ? 'nav-active' : '' }}">
        Kelola Jurusan
    </a>
@endsection

@section('content')
<div class="space-y-5">

    {{-- Filter & Search bar --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm px-5 py-4">
        <form method="GET" action="{{ route('admin.siswa.index') }}"
              class="flex flex-wrap items-end gap-3">

            {{-- Search nama / NISN / no. daftar --}}
            <div class="flex-1 min-w-52">
                <label class="block text-xs font-medium text-slate-500 mb-1.5">Cari Siswa</label>
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Nama, NISN, atau nomor pendaftaran..."
                           class="w-full pl-9 pr-4 py-2 rounded-xl border border-slate-200 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-sky-400 transition">
                </div>
            </div>

            {{-- Filter status --}}
            <div class="min-w-40">
                <label class="block text-xs font-medium text-slate-500 mb-1.5">Status</label>
                <select name="status"
                        class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm bg-white
                               focus:outline-none focus:ring-2 focus:ring-sky-400 transition">
                    <option value="">Semua Status</option>
                    @foreach([
                        'pending'             => 'Pending',
                        'menunggu_verifikasi' => 'Menunggu Verifikasi',
                        'diterima'            => 'Diterima',
                        'ditolak'             => 'Ditolak',
                    ] as $val => $label)
                    <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- Filter jurusan --}}
            <div class="min-w-40">
                <label class="block text-xs font-medium text-slate-500 mb-1.5">Jurusan</label>
                <select name="jurusan"
                        class="w-full px-3 py-2 rounded-xl border border-slate-200 text-sm bg-white
                               focus:outline-none focus:ring-2 focus:ring-sky-400 transition">
                    <option value="">Semua Jurusan</option>
                    @foreach($jurusan as $j)
                    <option value="{{ $j->id }}" {{ request('jurusan') == $j->id ? 'selected' : '' }}>
                        {{ $j->nama_jurusan }}
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- Tombol filter --}}
            <div class="flex gap-2">
                <button type="submit"
                        class="bg-sky-500 hover:bg-sky-600 text-white text-sm font-medium px-4 py-2 rounded-xl transition">
                    Filter
                </button>
                @if(request()->hasAny(['search','status','jurusan']))
                <a href="{{ route('admin.siswa.index') }}"
                   class="bg-slate-100 hover:bg-slate-200 text-slate-600 text-sm font-medium px-4 py-2 rounded-xl transition">
                    Reset
                </a>
                @endif
            </div>

        </form>
    </div>

    {{-- Tabel data pendaftar --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">

        {{-- Header tabel + info jumlah hasil --}}
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-semibold text-slate-800">
                Daftar Pendaftar
                <span class="text-slate-400 font-normal text-sm ml-1">
                    ({{ $pendaftaran->total() }} data)
                </span>
            </h3>
            {{-- Export (placeholder) --}}
            <button class="text-xs text-slate-500 border border-slate-200 hover:bg-slate-50 px-3 py-1.5 rounded-lg transition">
                Export CSV
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wide">
                    <tr>
                        <th class="text-left px-6 py-3 font-semibold">No.</th>
                        <th class="text-left px-6 py-3 font-semibold">No. Daftar</th>
                        <th class="text-left px-6 py-3 font-semibold">Nama Siswa</th>
                        <th class="text-left px-6 py-3 font-semibold">NISN</th>
                        <th class="text-left px-6 py-3 font-semibold">Jurusan</th>
                        <th class="text-left px-6 py-3 font-semibold">Tgl. Daftar</th>
                        <th class="text-left px-6 py-3 font-semibold">Status</th>
                        <th class="text-left px-6 py-3 font-semibold">Pembayaran</th>
                        <th class="text-left px-6 py-3 font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($pendaftaran as $i => $p)
                    @php
                        $badge   = $p->status_badge;
                        $pmBadge = $p->pembayaran?->status_badge ?? ['label' => '-', 'color' => 'gray'];
                    @endphp
                    <tr class="hover:bg-slate-50/60 transition">
                        {{-- Nomor urut (sesuai paginasi) --}}
                        <td class="px-6 py-3.5 text-slate-400 text-xs">
                            {{ $pendaftaran->firstItem() + $i }}
                        </td>
                        <td class="px-6 py-3.5 font-mono text-xs text-slate-600">
                            {{ $p->no_pendaftaran }}
                        </td>
                        <td class="px-6 py-3.5">
                            <div class="font-medium text-slate-800">{{ $p->calonSiswa->nama_lengkap }}</div>
                            <div class="text-xs text-slate-400 mt-0.5">{{ $p->calonSiswa->asal_sekolah }}</div>
                        </td>
                        <td class="px-6 py-3.5 font-mono text-xs text-slate-600">
                            {{ $p->calonSiswa->nisn }}
                        </td>
                        <td class="px-6 py-3.5 text-slate-600">
                            {{ $p->jurusan->nama_jurusan }}
                        </td>
                        <td class="px-6 py-3.5 text-slate-500 text-xs">
                            {{ $p->tanggal_daftar->format('d M Y') }}
                        </td>
                        <td class="px-6 py-3.5">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                bg-{{ $badge['color'] }}-100 text-{{ $badge['color'] }}-700">
                                {{ $badge['label'] }}
                            </span>
                        </td>
                        <td class="px-6 py-3.5">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                bg-{{ $pmBadge['color'] }}-100 text-{{ $pmBadge['color'] }}-700">
                                {{ $pmBadge['label'] }}
                            </span>
                        </td>
                        <td class="px-6 py-3.5">
                            <a href="{{ route('admin.siswa.show', $p) }}"
                               class="inline-flex items-center gap-1 text-xs text-sky-600 hover:text-sky-800
                                      font-medium border border-sky-200 hover:border-sky-400 bg-sky-50 hover:bg-sky-100
                                      px-3 py-1.5 rounded-lg transition">
                                Detail
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center">
                            <div class="text-slate-300 mb-2">
                                <svg class="w-10 h-10 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <p class="text-slate-400 text-sm">Tidak ada data yang sesuai filter.</p>
                            <a href="{{ route('admin.siswa.index') }}"
                               class="text-sky-500 text-xs hover:underline mt-1 inline-block">Reset filter</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginasi --}}
        @if($pendaftaran->hasPages())
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $pendaftaran->appends(request()->query())->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
