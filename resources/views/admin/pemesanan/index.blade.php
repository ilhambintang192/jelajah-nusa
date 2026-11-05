@extends('layouts.admin', ['title' => 'Pemesanan & Peserta'])

@section('content')
<div class="py-6 space-y-6">
    
    <!-- Title -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Pemesanan &amp; Dokumen Peserta</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Pantau seluruh pemesanan masuk, dokumen identitas terverifikasi, dan perubahan status</p>
        </div>
    </div>

    <!-- 2 Column Layout (Frame 107:2128) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- Left: Tabel Pemesanan Masuk (7 Cols) -->
        <div class="lg:col-span-7 bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs space-y-4">
            
            <!-- Filters & Search -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-100">
                <form action="{{ route('admin.pemesanan.index') }}" method="GET" class="relative flex-1">
                    <input type="text" 
                           name="cari" 
                           value="{{ $search }}" 
                           placeholder="Cari #PSN, nama pemesan, atau paket..." 
                           class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-teal-500/20 text-slate-700">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </form>

                <div class="flex items-center gap-1.5 text-xs">
                    <a href="{{ route('admin.pemesanan.index', ['cari' => $search]) }}" class="px-2.5 py-1.5 rounded-lg font-bold {{ empty($status) ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">Semua</a>
                    <a href="{{ route('admin.pemesanan.index', ['status' => 'menunggu', 'cari' => $search]) }}" class="px-2.5 py-1.5 rounded-lg font-bold {{ $status === 'menunggu' ? 'bg-amber-600 text-white' : 'bg-amber-50 text-amber-700 hover:bg-amber-100' }}">Menunggu</a>
                    <a href="{{ route('admin.pemesanan.index', ['status' => 'dikonfirmasi', 'cari' => $search]) }}" class="px-2.5 py-1.5 rounded-lg font-bold {{ $status === 'dikonfirmasi' ? 'bg-emerald-600 text-white' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}">Dikonfirmasi</a>
                    <a href="{{ route('admin.pemesanan.index', ['status' => 'dibatalkan', 'cari' => $search]) }}" class="px-2.5 py-1.5 rounded-lg font-bold {{ $status === 'dibatalkan' ? 'bg-rose-600 text-white' : 'bg-rose-50 text-rose-700 hover:bg-rose-100' }}">Dibatalkan</a>
                </div>
            </div>

            <!-- Table of Orders -->
            <div class="overflow-x-auto border border-slate-100 rounded-2xl">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 border-b border-slate-100 text-slate-500 font-bold uppercase tracking-wider">
                        <tr>
                            <th class="py-3 px-3">Kode</th>
                            <th class="py-3 px-3">Pemesan</th>
                            <th class="py-3 px-3">Paket / Trip</th>
                            <th class="py-3 px-2 text-center">Orang</th>
                            <th class="py-3 px-3">Status</th>
                            <th class="py-3 px-2 text-right">Pilih</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @forelse($pemesananList as $item)
                            <tr class="hover:bg-slate-50/60 {{ $selectedPemesanan && $selectedPemesanan->id === $item->id ? 'bg-teal-50/50 font-bold' : '' }}">
                                <td class="py-3.5 px-3 font-mono text-teal-800">
                                    {{ $item->display_kode }}
                                </td>
                                <td class="py-3.5 px-3">
                                    <span class="block font-bold text-slate-900">{{ $item->user->nama_lengkap ?? '—' }}</span>
                                    <span class="block text-[11px] text-slate-400 font-normal">{{ $item->created_at->format('d/m/Y H:i') }}</span>
                                </td>
                                <td class="py-3.5 px-3 max-w-[160px] truncate">
                                    {{ $item->judul_trip }}
                                </td>
                                <td class="py-3.5 px-2 text-center">
                                    <span class="px-2 py-0.5 rounded-md bg-slate-100 font-bold">{{ $item->jumlah_peserta }}</span>
                                </td>
                                <td class="py-3.5 px-3 space-y-1">
                                    <span class="inline-block px-2.5 py-1 rounded-full text-[10px] font-bold uppercase {{ $item->status === 'dikonfirmasi' ? 'bg-emerald-50 text-emerald-700' : ($item->status === 'menunggu' ? 'bg-amber-50 text-amber-700' : 'bg-rose-50 text-rose-700') }}">
                                        {{ $item->status }}
                                    </span>
                                    @if($item->pembayaran_menunggu)
                                        <span class="block text-[10px] font-bold text-teal-800 bg-teal-100/90 px-2 py-0.5 rounded-md text-center">
                                            ● Bukti Diunggah
                                        </span>
                                    @endif
                                    @if($item->pengajuan_batal && $item->status !== 'dibatalkan')
                                        <span class="block text-[10px] font-bold text-rose-800 bg-rose-100/90 px-2 py-0.5 rounded-md text-center">
                                            ⚠ Ajukan Batal
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-2 text-right">
                                    <a href="{{ route('admin.pemesanan.index', ['selected' => $item->id, 'status' => $status, 'cari' => $search]) }}" class="px-2.5 py-1 bg-white border border-slate-200 hover:border-teal-600 hover:text-teal-700 text-slate-700 rounded-lg text-[11px] font-bold shadow-2xs">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-slate-400">
                                    Belum ada pemesanan yang sesuai dengan filter ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pt-2">
                {{ $pemesananList->links() }}
            </div>
        </div>

        <!-- Right: Panel Dokumen Peserta & Kontrol Status (5 Cols, Frame 107:2128) -->
        <div class="lg:col-span-5 space-y-6">
            @if($selectedPemesanan)
                <div class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs space-y-6">
                    
                    <!-- Selected Order Header -->
                    <div class="pb-4 border-b border-slate-100 flex items-start justify-between">
                        <div>
                            <span class="text-xs font-mono font-bold text-teal-800">{{ $selectedPemesanan->display_kode }}</span>
                            <h3 class="text-lg font-extrabold text-slate-900 mt-1">{{ $selectedPemesanan->judul_trip }}</h3>
                            <p class="text-xs text-slate-400">Pemesan: {{ $selectedPemesanan->user->nama_lengkap }} ({{ $selectedPemesanan->user->no_hp }})</p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase {{ $selectedPemesanan->status === 'dikonfirmasi' ? 'bg-emerald-50 text-emerald-700' : ($selectedPemesanan->status === 'menunggu' ? 'bg-amber-50 text-amber-700' : 'bg-rose-50 text-rose-700') }}">
                            {{ $selectedPemesanan->status }}
                        </span>
                    </div>

                    <!-- Keuangan Ringkas -->
                    <div class="grid grid-cols-2 gap-3 p-4 bg-slate-50 rounded-2xl text-xs">
                        <div>
                            <span class="text-slate-400 block">Total Tagihan</span>
                            <span class="text-sm font-extrabold text-slate-900">{{ $selectedPemesanan->total_biaya_format }}</span>
                        </div>
                        <div>
                            <span class="text-slate-400 block">Total Terbayar</span>
                            <span class="text-sm font-extrabold text-emerald-600">{{ $selectedPemesanan->total_terbayar_format }}</span>
                        </div>
                    </div>

                    <!-- Notifikasi Pembayaran Menunggu Verifikasi -->
                    @if($selectedPemesanan->pembayaran_menunggu)
                        <div class="p-4 bg-teal-50 border border-teal-200 rounded-2xl flex items-center justify-between gap-3">
                            <div class="min-w-0">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-100 text-amber-800 uppercase tracking-wider">Bukti Transfer Diunggah</span>
                                <p class="text-xs font-bold text-slate-900 mt-1">Nominal: {{ $selectedPemesanan->pembayaran_menunggu->nominal_format }}</p>
                                <p class="text-[11px] text-slate-500">Tgl: {{ $selectedPemesanan->pembayaran_menunggu->tanggal_bayar->format('d/m/Y') }}</p>
                            </div>
                            <a href="{{ route('admin.pembayaran.index', ['selected' => $selectedPemesanan->pembayaran_menunggu->id]) }}" class="px-3.5 py-2 bg-teal-700 hover:bg-teal-800 text-white text-xs font-bold rounded-xl transition shrink-0 shadow-2xs">
                                Verifikasi Sekarang &rarr;
                            </a>
                        </div>
                    @endif

                    <!-- Notifikasi Pengajuan Pembatalan / Refund oleh Wisatawan -->
                    @if($selectedPemesanan->pengajuan_batal)
                        <div class="p-4 bg-rose-50 border border-rose-200 rounded-2xl space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-rose-100 text-rose-800">
                                    Pengajuan Pembatalan Wisatawan
                                </span>
                                <span class="text-[10px] text-slate-400">{{ $selectedPemesanan->pengajuan_batal['tanggal'] ?? '' }}</span>
                            </div>
                            <p class="text-xs text-slate-700"><strong>Alasan:</strong> {{ $selectedPemesanan->pengajuan_batal['alasan'] ?? '—' }}</p>
                            <div class="p-2.5 bg-white rounded-xl border border-rose-100 text-[11px] text-slate-600 space-y-0.5">
                                <p><strong>Rekening Refund:</strong> {{ $selectedPemesanan->pengajuan_batal['nama_bank'] ?? '' }} - {{ $selectedPemesanan->pengajuan_batal['no_rekening'] ?? '' }}</p>
                                <p><strong>Atas Nama:</strong> {{ $selectedPemesanan->pengajuan_batal['atas_nama'] ?? '' }}</p>
                                @if(!empty($selectedPemesanan->pengajuan_batal['no_whatsapp']))
                                    <p><strong>WhatsApp:</strong> {{ $selectedPemesanan->pengajuan_batal['no_whatsapp'] }}</p>
                                @endif
                            </div>
                            @if($selectedPemesanan->status !== 'dibatalkan')
                                <div class="pt-1">
                                    <a href="{{ route('admin.pengembalian.index', ['pemesanan_id' => $selectedPemesanan->id]) }}" class="px-3.5 py-1.5 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl transition inline-flex items-center gap-1 shadow-2xs">
                                        Proses Pembatalan &amp; Refund &rarr;
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Dokumen Peserta Terdaftar (F3-02, BR-13, BR-14) -->
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Dokumen Identitas Peserta</h4>
                            <span class="text-[10px] text-emerald-600 font-semibold flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                Akses Privat Terenkripsi
                            </span>
                        </div>

                        <div class="space-y-3">
                            @foreach($selectedPemesanan->peserta as $pst)
                                <div class="p-3.5 bg-slate-50 border border-slate-200/80 rounded-2xl flex items-center justify-between gap-3">
                                    <div class="min-w-0">
                                        <h5 class="text-xs font-bold text-slate-900 truncate">{{ $pst->nama_lengkap }}</h5>
                                        <p class="text-[11px] text-slate-500 font-mono">{{ strtoupper($pst->jenis_identitas) }}: {{ $pst->no_identitas }}</p>
                                        @if($pst->no_hp)
                                            <p class="text-[10px] text-slate-400">HP: {{ $pst->no_hp }}</p>
                                        @endif
                                    </div>
                                    <a href="{{ route('dokumen.identitas', $pst->id) }}" target="_blank" class="px-3 py-1.5 bg-white border border-slate-200 hover:bg-teal-50 hover:border-teal-300 hover:text-teal-700 text-slate-700 text-xs font-bold rounded-xl transition shadow-2xs flex-shrink-0 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        Lihat KTP
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Kontrol Ubah Status Pemesanan (F3-03, Bagian 21) -->
                    <div class="pt-4 border-t border-slate-100">
                        <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-2">Ubah Status Pemesanan</h4>
                        <form action="{{ route('admin.pemesanan.status', $selectedPemesanan->id) }}" method="POST" class="space-y-3">
                            @csrf
                            @method('PATCH')
                            <div class="flex gap-2">
                                <select name="status" class="flex-1 px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800">
                                    <option value="menunggu" {{ $selectedPemesanan->status === 'menunggu' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                                    <option value="dikonfirmasi" {{ $selectedPemesanan->status === 'dikonfirmasi' ? 'selected' : '' }}>Dikonfirmasi (Terbitkan Tiket)</option>
                                    <option value="dibatalkan" {{ $selectedPemesanan->status === 'dibatalkan' ? 'selected' : '' }}>Dibatalkan (Kembalikan Kursi)</option>
                                </select>
                                <button type="submit" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold rounded-xl transition">
                                    Simpan
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            @else
                <div class="bg-white p-12 rounded-3xl border border-slate-200/80 text-center text-slate-400 text-xs">
                    Pilih salah satu baris pemesanan untuk memeriksa dokumen KTP dan rincian.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
