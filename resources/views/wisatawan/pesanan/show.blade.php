@extends('layouts.wisatawan', ['title' => 'Detail Pesanan ' . $pemesanan->display_kode])

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
    
    <!-- Top Nav & Status -->
    <div class="flex items-center justify-between">
        <a href="{{ route('pesanan-saya.index') }}" class="text-xs font-bold text-teal-700 hover:text-teal-800 flex items-center gap-1">
            &larr; Kembali ke Riwayat Pesanan
        </a>
        <span class="px-3.5 py-1.5 rounded-full text-xs font-bold uppercase {{ $pemesanan->status === 'dikonfirmasi' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($pemesanan->status === 'menunggu' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-rose-50 text-rose-700 border border-rose-200') }}">
            {{ $pemesanan->status === 'dikonfirmasi' ? 'Dikonfirmasi (Tiket Aktif)' : ($pemesanan->status === 'menunggu' ? 'Menunggu Pembayaran' : 'Dibatalkan') }}
        </span>
    </div>

    <!-- Order Header Card -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/90 shadow-xs space-y-5">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 pb-4 border-b border-slate-100">
            <div>
                <span class="text-xs font-mono font-bold text-teal-700">{{ $pemesanan->display_kode }}</span>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-1">{{ $pemesanan->judul_trip }}</h1>
                <p class="text-xs text-slate-500 mt-1">Dipesan pada {{ $pemesanan->created_at->translatedFormat('d F Y H:i') }} WIB</p>
            </div>
            <div class="text-left sm:text-right">
                <span class="text-xs text-slate-400 block font-medium">Total Pembayaran</span>
                <span class="text-2xl sm:text-3xl font-extrabold text-teal-800 font-mono">{{ $pemesanan->total_biaya_format }}</span>
            </div>
        </div>

        <!-- Quick Summary 3 Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                <span class="text-slate-400 block font-medium">Tanggal Berangkat</span>
                <strong class="text-slate-900 text-sm block mt-0.5">
                    {{ $pemesanan->tanggal_berangkat ? $pemesanan->tanggal_berangkat->translatedFormat('d F Y') : '—' }}
                </strong>
            </div>

            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                <span class="text-slate-400 block font-medium">Jumlah Peserta</span>
                <strong class="text-slate-900 text-sm block mt-0.5">{{ $pemesanan->jumlah_peserta }} Orang</strong>
            </div>

            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                <span class="text-slate-400 block font-medium">Status Pembayaran</span>
                <strong class="text-sm block mt-0.5 {{ $pemesanan->is_lunas ? 'text-emerald-700' : 'text-amber-700' }}">
                    {{ $pemesanan->is_lunas ? 'Lunas Terverifikasi' : 'Sisa ' . $pemesanan->sisa_tagihan_format }}
                </strong>
            </div>
        </div>

        @if($pemesanan->status === 'menunggu')
            <div class="pt-2 flex justify-end">
                <a href="{{ route('pembayaran.show', $pemesanan->id) }}" class="px-6 py-2.5 bg-orange-500 hover:bg-orange-600 text-white font-bold text-xs rounded-xl shadow-xs transition">
                    Selesaikan Pembayaran &rarr;
                </a>
            </div>
        @endif
    </div>

    <!-- Tiket Digital Section matching Figma Tiket Digital — Desktop.png -->
    @if($pemesanan->tiket->count() > 0)
        <div class="space-y-4">
            <div class="text-center sm:text-left">
                <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Tiket Digital Anda</h2>
                <p class="text-xs text-slate-500 mt-0.5">Tunjukkan QR Code ini pada staf atau pemandu saat keberangkatan wisata.</p>
            </div>

            <div class="space-y-6">
                @foreach($pemesanan->tiket as $tkt)
                    <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-lg flex flex-col md:flex-row">
                        <!-- Left Teal Card -->
                        <div class="bg-teal-700 text-white p-6 sm:p-8 flex-1 flex flex-col justify-between space-y-6">
                            <div class="space-y-2">
                                <span class="inline-block px-3 py-1 rounded-full text-[11px] font-bold bg-white/20 text-white border border-white/20">
                                    Terverifikasi
                                </span>
                                <h3 class="text-2xl font-extrabold text-white tracking-tight">{{ $pemesanan->judul_trip }}</h3>
                                <p class="text-sm text-teal-100 font-medium">
                                    Peserta: <strong>{{ $tkt->peserta->nama_lengkap ?? 'Wisatawan' }}</strong>
                                </p>
                            </div>

                            <!-- 4 Column Meta Grid matching Figma -->
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs pt-4 border-t border-teal-600/60">
                                <div>
                                    <span class="text-teal-200 block text-[11px]">Berangkat</span>
                                    <strong class="text-white block mt-0.5">
                                        {{ $pemesanan->tanggal_berangkat ? $pemesanan->tanggal_berangkat->translatedFormat('d M Y') : '—' }}
                                    </strong>
                                </div>
                                <div>
                                    <span class="text-teal-200 block text-[11px]">Jam kumpul</span>
                                    <strong class="text-white block mt-0.5">
                                        {{ substr($pemesanan->jadwal->paket->jam_kumpul ?? '05:00', 0, 5) }} WIB
                                    </strong>
                                </div>
                                <div>
                                    <span class="text-teal-200 block text-[11px]">Meeting point</span>
                                    <strong class="text-white block mt-0.5 truncate" title="{{ $pemesanan->jadwal->paket->meeting_points[0] ?? 'Solo / Titik Resmi' }}">
                                        {{ $pemesanan->jadwal->paket->meeting_points[0] ?? 'Solo' }}
                                    </strong>
                                </div>
                                <div>
                                    <span class="text-teal-200 block text-[11px]">Kendaraan</span>
                                    <strong class="text-white block mt-0.5">
                                        {{ $pemesanan->jadwal->kendaraan->jenis_kendaraan ?? 'Hiace Premio' }}
                                    </strong>
                                </div>
                            </div>

                            <div class="text-xs text-teal-200 font-mono">
                                Kode tiket: <strong class="text-white">{{ $tkt->kode_tiket }}</strong>
                            </div>
                        </div>

                        <!-- Right White Card with QR Code matching Figma -->
                        <div class="bg-white p-6 sm:p-8 md:w-64 shrink-0 flex flex-col items-center justify-center text-center space-y-4 border-t md:border-t-0 md:border-l border-slate-200/80">
                            <!-- QR Box -->
                            <div class="p-3 bg-white rounded-2xl border border-slate-200 shadow-xs inline-block">
                                <div class="w-36 h-36 flex items-center justify-center">
                                    {!! $tkt->qr_code_svg !!}
                                </div>
                            </div>
                            <p class="text-[11px] text-slate-400 font-medium">Pindai untuk verifikasi</p>
                            
                            <a href="{{ route('tiket.unduh', $tkt->id) }}" class="w-full py-2.5 px-4 rounded-xl border border-teal-700 text-teal-700 hover:bg-teal-50 text-xs font-bold transition flex items-center justify-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                Unduh PDF
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Manifest Peserta Terdaftar -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/90 shadow-xs space-y-4">
        <h3 class="text-base font-extrabold text-slate-900 pb-3 border-b border-slate-100">Daftar Manifest Peserta ({{ $pemesanan->peserta->count() }} Orang)</h3>
        
        <div class="space-y-3">
            @foreach($pemesanan->peserta as $pst)
                <div class="p-4 bg-slate-50 border border-slate-200/80 rounded-2xl flex items-center justify-between gap-3 text-xs">
                    <div>
                        <strong class="text-slate-900 text-sm block">{{ $pst->nama_lengkap }}</strong>
                        <span class="text-slate-400 mt-0.5 block font-mono">
                            {{ strtoupper($pst->jenis_identitas) }}: {{ $pst->no_identitas }} &bull; HP: {{ $pst->no_hp ?: '—' }}
                        </span>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700">
                        Dokumen Terverifikasi
                    </span>
                </div>
            @endforeach
        </div>
    </div>

</div>
@endsection
