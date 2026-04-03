{{-- ============================================================ --}}
{{-- VIEW: resources/views/siswa/berkas.blade.php               --}}
{{-- Upload berkas & bukti pembayaran                           --}}
{{-- ============================================================ --}}
@extends('layouts.app')
@section('title', 'Upload Berkas')
@section('page-title', 'Upload Berkas Pendaftaran')

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
<div class="max-w-2xl space-y-6">

    {{-- Info nomor pendaftaran --}}
    <div class="bg-sky-50 border border-sky-200 rounded-2xl px-5 py-4 flex items-center gap-3">
        <svg class="w-5 h-5 text-sky-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div class="text-sm">
            <span class="font-semibold text-sky-800">No. Pendaftaran: {{ $pendaftaran->no_pendaftaran }}</span>
            <span class="text-sky-600 ml-2">— Upload semua berkas yang diperlukan</span>
        </div>
    </div>

    {{-- Daftar berkas yang harus diupload --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm divide-y divide-slate-50">
        <div class="px-6 py-4 border-b border-slate-100">
            <h3 class="font-semibold text-slate-800">Daftar Berkas</h3>
            <p class="text-xs text-slate-500 mt-0.5">Format: PDF, JPG, atau PNG • Maks. 5MB per file</p>
        </div>

        @foreach($labelJenis as $kode => $namaJenis)
        @php $sudahUpload = $berkasUpload->get($kode); @endphp
        <div class="px-6 py-4">
            <div class="flex items-start justify-between gap-4">
                <div class="flex items-center gap-3">
                    {{-- Status ikon --}}
                    @if($sudahUpload)
                    <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0
                        {{ $sudahUpload->status_berkas === 'valid' ? 'bg-green-100' : ($sudahUpload->status_berkas === 'tidak_valid' ? 'bg-red-100' : 'bg-blue-100') }}">
                        @if($sudahUpload->status_berkas === 'valid')
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                        @elseif($sudahUpload->status_berkas === 'tidak_valid')
                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        @else
                            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        @endif
                    </div>
                    @else
                    <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    @endif

                    <div>
                        <div class="text-sm font-medium text-slate-700">{{ $namaJenis }}</div>
                        @if($sudahUpload)
                            <div class="text-xs text-slate-400 mt-0.5">{{ $sudahUpload->nama_file }}</div>
                            @if($sudahUpload->catatan_berkas)
                            <div class="text-xs text-red-500 mt-0.5">⚠ {{ $sudahUpload->catatan_berkas }}</div>
                            @endif
                        @else
                            <div class="text-xs text-slate-400 mt-0.5">Belum diupload</div>
                        @endif
                    </div>
                </div>

                {{-- Tombol upload / re-upload --}}
                <button onclick="toggleUploadForm('{{ $kode }}')"
                    class="flex-shrink-0 text-xs px-3 py-1.5 rounded-lg transition font-medium
                    {{ $sudahUpload ? 'bg-slate-100 hover:bg-slate-200 text-slate-600' : 'bg-sky-500 hover:bg-sky-600 text-white' }}">
                    {{ $sudahUpload ? 'Upload Ulang' : 'Upload' }}
                </button>
            </div>

            {{-- Form upload (toggle) --}}
            <div id="form-upload-{{ $kode }}" class="hidden mt-3">
                <form method="POST" action="{{ route('siswa.berkas.store') }}"
                      enctype="multipart/form-data"
                      class="bg-slate-50 rounded-xl p-4 space-y-3">
                    @csrf
                    <input type="hidden" name="jenis_berkas" value="{{ $kode }}">

                    {{-- Input file custom --}}
                    <div>
                        <label class="block text-xs text-slate-600 mb-1.5">File {{ $namaJenis }}</label>
                        <div class="border-2 border-dashed border-slate-200 rounded-xl p-4 text-center cursor-pointer hover:border-sky-400 hover:bg-sky-50 transition"
                             onclick="document.getElementById('file-{{ $kode }}').click()">
                            <svg class="w-6 h-6 text-slate-400 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                            <p class="text-xs text-slate-500" id="file-label-{{ $kode }}">Klik untuk pilih file</p>
                        </div>
                        <input type="file" id="file-{{ $kode }}" name="file_berkas"
                               accept=".pdf,.jpg,.jpeg,.png"
                               class="hidden"
                               onchange="updateFileLabel('{{ $kode }}', this)">
                        @error('file_berkas')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="flex gap-2">
                        <button type="submit"
                            class="bg-sky-500 hover:bg-sky-600 text-white text-xs px-4 py-2 rounded-lg transition font-medium">
                            Simpan
                        </button>
                        <button type="button" onclick="toggleUploadForm('{{ $kode }}')"
                            class="bg-slate-200 hover:bg-slate-300 text-slate-600 text-xs px-4 py-2 rounded-lg transition">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Upload bukti pembayaran --}}
    @if($pendaftaran->pembayaran)
    @php $pm = $pendaftaran->pembayaran; @endphp
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
        <h3 class="font-semibold text-slate-800 mb-1">Bukti Pembayaran</h3>
        <p class="text-xs text-slate-500 mb-4">
            Tagihan: <strong>Rp {{ number_format($pm->jumlah_tagihan) }}</strong>
        </p>

        @if($pm->bukti_pembayaran)
        <div class="flex items-center gap-2 mb-3 bg-green-50 border border-green-200 rounded-xl px-3 py-2 text-sm text-green-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            Bukti sudah diupload —
            <a href="{{ $pm->bukti_bayar_url }}" target="_blank" class="underline">Lihat file</a>
        </div>
        @endif

        <form method="POST" action="{{ route('siswa.pembayaran.upload') }}" enctype="multipart/form-data" class="space-y-3">
            @csrf
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="text-xs text-slate-600 block mb-1">Metode Pembayaran</label>
                    <select name="metode_pembayaran" required
                        class="w-full text-sm border border-slate-200 rounded-xl px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-sky-400">
                        <option value="">-- Pilih --</option>
                        <option value="Transfer BCA">Transfer BCA</option>
                        <option value="Transfer BNI">Transfer BNI</option>
                        <option value="Transfer Mandiri">Transfer Mandiri</option>
                        <option value="Tunai">Tunai</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs text-slate-600 block mb-1">Jumlah Dibayar (Rp)</label>
                    <input type="number" name="jumlah_bayar" min="1"
                        placeholder="500000"
                        class="w-full text-sm border border-slate-200 rounded-xl px-3 py-2.5 focus:outline-none focus:ring-2 focus:ring-sky-400">
                </div>
            </div>
            <div>
                <label class="text-xs text-slate-600 block mb-1">File Bukti Pembayaran</label>
                <input type="file" name="bukti_pembayaran" accept=".jpg,.jpeg,.png,.pdf" required
                    class="w-full text-sm border border-slate-200 rounded-xl px-3 py-2.5 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:bg-sky-50 file:text-sky-600 hover:file:bg-sky-100">
            </div>
            <button type="submit"
                class="bg-emerald-500 hover:bg-emerald-600 text-white text-sm px-6 py-2.5 rounded-xl font-medium transition">
                Upload Bukti Pembayaran
            </button>
        </form>
    </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
    // Toggle form upload per jenis berkas
    function toggleUploadForm(kode) {
        const form = document.getElementById('form-upload-' + kode);
        form.classList.toggle('hidden');
    }

    // Update label nama file yang dipilih
    function updateFileLabel(kode, input) {
        const label = document.getElementById('file-label-' + kode);
        if (input.files.length > 0) {
            const nama  = input.files[0].name;
            const ukuran = (input.files[0].size / 1024).toFixed(0);
            label.textContent = nama + ' (' + ukuran + ' KB)';
            label.classList.add('text-sky-600', 'font-medium');
        } else {
            label.textContent = 'Klik untuk pilih file';
        }
    }
</script>
@endpush
