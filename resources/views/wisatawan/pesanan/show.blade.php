@extends('layouts.wisatawan', ['title' => 'Detail Pesanan ' . $pemesanan->display_kode])

@section('content')
@php
    $pembayaranMenunggu = $pemesanan->pembayaran_menunggu;
    $refundInfo = $pemesanan->pengembalian->where('jenis', 'refund')->first();
    $rescheduleInfo = $pemesanan->pengembalian->where('jenis', 'pindah_jadwal')->first();
    $pengajuanBatal = $pemesanan->pengajuan_batal;
@endphp

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8" x-data="{ batalModal: false }">
    
    <!-- Top Nav & Status -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <a href="{{ route('pesanan-saya.index') }}" class="text-xs font-bold text-teal-700 hover:text-teal-800 flex items-center gap-1">
            &larr; Kembali ke Riwayat Pesanan
        </a>
        
        <div class="flex items-center gap-2">
            @if($pemesanan->status === 'dikonfirmasi')
                <span class="px-3.5 py-1.5 rounded-full text-xs font-bold uppercase bg-emerald-50 text-emerald-700 border border-emerald-200">
                    Dikonfirmasi (Tiket Aktif)
                </span>
            @elseif($pembayaranMenunggu)
                <span class="px-3.5 py-1.5 rounded-full text-xs font-bold uppercase bg-amber-50 text-amber-800 border border-amber-200 flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                    Menunggu Verifikasi Admin
                </span>
            @elseif($pemesanan->status === 'menunggu')
                <span class="px-3.5 py-1.5 rounded-full text-xs font-bold uppercase bg-amber-50 text-amber-700 border border-amber-200">
                    Menunggu Pembayaran
                </span>
            @else
                <span class="px-3.5 py-1.5 rounded-full text-xs font-bold uppercase bg-rose-50 text-rose-700 border border-rose-200">
                    Dibatalkan
                </span>
            @endif
        </div>
    </div>

    <!-- PEMBERITAHUAN REFUND JIKA PESANAN DI-REFUND -->
    @if($refundInfo)
        <div class="bg-amber-50/80 border-2 border-amber-300 rounded-3xl p-6 sm:p-8 space-y-5 shadow-xs">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-2xl bg-amber-500 text-white flex items-center justify-center shrink-0 text-xl font-bold shadow-xs">
                    ↩
                </div>
                <div class="flex-1">
                    <span class="px-3 py-1 rounded-full text-[11px] font-extrabold uppercase bg-amber-200 text-amber-900 tracking-wider">
                        Pengembalian Dana (Refund) Telah Diproses
                    </span>
                    <h3 class="text-xl font-extrabold text-slate-900 mt-2">Pemberitahuan Pengembalian Dana</h3>
                    <p class="text-xs text-slate-600 mt-1">
                        Pesanan ini telah diproses pengembalian dana oleh pihak biro perjalanan Jelajah Nusa.
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                <div class="p-4 bg-white rounded-2xl border border-amber-200">
                    <span class="text-slate-400 block font-medium">Nominal Refund:</span>
                    <span class="text-xl font-extrabold text-emerald-700 font-mono block mt-0.5">
                        {{ $refundInfo->nominal_format }}
                    </span>
                </div>
                <div class="p-4 bg-white rounded-2xl border border-amber-200">
                    <span class="text-slate-400 block font-medium">Tanggal Diproses:</span>
                    <strong class="text-slate-800 text-sm block mt-0.5">
                        {{ $refundInfo->tanggal_pengembalian->translatedFormat('d F Y') }}
                    </strong>
                </div>
                <div class="p-4 bg-white rounded-2xl border border-amber-200">
                    <span class="text-slate-400 block font-medium">Alasan Pengembalian:</span>
                    <p class="text-slate-800 font-semibold mt-0.5 leading-snug">{{ $refundInfo->alasan }}</p>
                </div>
            </div>

            <!-- Penjelasan Mekanisme Pengiriman Uang Refund -->
            <div class="p-4 sm:p-5 bg-white/90 rounded-2xl border border-amber-200/90 text-xs text-slate-700 space-y-2.5">
                <h4 class="font-extrabold text-slate-900 flex items-center gap-1.5 text-sm">
                    <svg class="w-4 h-4 text-amber-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                    Bagaimana Mekanisme Pengiriman Uang Refund?
                </h4>
                <ol class="list-decimal list-inside space-y-1.5 text-slate-600 pl-1 leading-relaxed">
                    <li><strong>Transfer Balik Manual:</strong> Dana refund ditransfer langsung oleh bendahara travel ke nomor rekening bank atau e-wallet yang Anda gunakan saat membayar dalam waktu maksimal <strong>1x24 jam kerja</strong>.</li>
                    <li><strong>Konfirmasi Rekening:</strong> Apabila nomor rekening Anda berbeda dari saat transfer, silakan kirimkan nomor rekening tujuan baru ke Admin/CS kami.</li>
                    <li><strong>Slip Bukti Transfer:</strong> Anda dapat meminta foto struk bukti pengembalian dana melalui kontak layanan pelanggan resmi di bawah ini.</li>
                </ol>
                <div class="pt-2 flex flex-wrap items-center gap-3">
                    <a href="https://wa.me/6281234567890?text=Halo%20Admin%20Jelajah%20Nusa,%20saya%20ingin%20konfirmasi%20pengiriman%20uang%20refund%20untuk%20pesanan%20{{ $pemesanan->display_kode }}" target="_blank" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs transition flex items-center gap-2 shadow-xs">
                        <span>💬 Hubungi WhatsApp CS Refund</span>
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- PEMBERITAHUAN PENGAJUAN PEMBATALAN MENUNGGU TINJAUAN ADMIN -->
    @if($pengajuanBatal && $pemesanan->status !== 'dibatalkan')
        <div class="bg-rose-50 border-2 border-rose-200 rounded-3xl p-6 sm:p-7 space-y-3">
            <div class="flex items-center justify-between">
                <span class="px-3 py-1 rounded-full text-xs font-extrabold uppercase bg-rose-200 text-rose-900 tracking-wider">
                    Permohonan Pembatalan Sedang Ditinjau Admin
                </span>
                <span class="text-xs text-slate-400">{{ $pengajuanBatal['tanggal'] ?? '' }}</span>
            </div>
            <h3 class="text-base font-extrabold text-slate-900">Pengajuan Pembatalan &amp; Refund Sedang Diproses</h3>
            <p class="text-xs text-slate-600">
                Anda telah mengajukan pembatalan untuk pesanan ini dengan alasan: <em>"{{ $pengajuanBatal['alasan'] ?? '—' }}"</em>. Admin kami sedang memverifikasi data dan menyiapkan pengembalian dana ke rekening tujuan Anda.
            </p>
            <div class="p-3.5 bg-white rounded-2xl border border-rose-100 text-xs text-slate-600 space-y-1">
                <p><strong>Rekening Tujuan Refund:</strong> {{ $pengajuanBatal['nama_bank'] ?? '' }} - {{ $pengajuanBatal['no_rekening'] ?? '' }} (a.n {{ $pengajuanBatal['atas_nama'] ?? '' }})</p>
                @if(!empty($pengajuanBatal['no_whatsapp']))
                    <p><strong>WhatsApp Konfirmasi:</strong> {{ $pengajuanBatal['no_whatsapp'] }}</p>
                @endif
            </div>
        </div>
    @endif

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
                @if($pemesanan->is_lunas)
                    <strong class="text-sm block mt-0.5 text-emerald-700 font-bold">Lunas Terverifikasi</strong>
                @elseif($pembayaranMenunggu)
                    <strong class="text-sm block mt-0.5 text-amber-700 font-bold">Menunggu Verifikasi Admin</strong>
                    <span class="text-[11px] text-slate-400 block mt-0.5 font-mono">Bukti: {{ $pembayaranMenunggu->nominal_format }}</span>
                @else
                    <strong class="text-sm block mt-0.5 text-amber-700">Sisa {{ $pemesanan->sisa_tagihan_format }}</strong>
                @endif
            </div>
        </div>

        <!-- Tombol Aksi Pembayaran & Pembatalan -->
        <div class="pt-2 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 border-t border-slate-100">
            <div>
                <!-- Opsi Ajukan Pembatalan oleh Wisatawan -->
                @if($pemesanan->status === 'dikonfirmasi' && !$pengajuanBatal)
                    <button type="button" @click="batalModal = true" class="px-4 py-2 bg-slate-100 hover:bg-rose-50 text-slate-600 hover:text-rose-700 font-bold text-xs rounded-xl border border-slate-200 transition">
                        Ajukan Pembatalan / Refund &rarr;
                    </button>
                @elseif($pemesanan->status === 'menunggu' && !$pembayaranMenunggu)
                    <form action="{{ route('pesanan-saya.batal', $pemesanan->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini? Kursi yang dipesan akan dilepas kembali.')">
                        @csrf
                        <button type="submit" class="px-4 py-2 text-rose-600 hover:bg-rose-50 font-bold text-xs rounded-xl transition">
                            Batalkan Pesanan
                        </button>
                    </form>
                @endif
            </div>

            <div class="flex items-center gap-2">
                @if($pembayaranMenunggu)
                    <a href="{{ route('pembayaran.show', $pemesanan->id) }}" class="px-5 py-2.5 bg-teal-50 text-teal-800 border border-teal-200 hover:bg-teal-100 font-bold text-xs rounded-xl transition flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-teal-600"></span>
                        Lihat Bukti Transfer &rarr;
                    </a>
                @elseif($pemesanan->status === 'menunggu')
                    <a href="{{ route('pembayaran.show', $pemesanan->id) }}" class="px-6 py-2.5 bg-orange-500 hover:bg-orange-600 text-white font-bold text-xs rounded-xl shadow-xs transition">
                        Selesaikan Pembayaran &rarr;
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Tiket Digital Section matching Figma Tiket Digital — Desktop.png -->
    @if($pemesanan->tiket->count() > 0 && $pemesanan->status !== 'dibatalkan')
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

    <!-- MODAL PENGAJUAN PEMBATALAN & REFUND OLEH WISATAWAN -->
    <div x-show="batalModal" 
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4"
         style="display: none;">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 space-y-5 shadow-2xl border border-slate-200"
             @click.away="batalModal = false">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-lg font-extrabold text-slate-900">Ajukan Pembatalan &amp; Refund</h3>
                    <p class="text-xs text-slate-400">Permohonan Anda akan ditinjau dan diverifikasi oleh Admin Travel</p>
                </div>
                <button type="button" @click="batalModal = false" class="text-slate-400 hover:text-slate-600 text-lg font-bold">
                    ✕
                </button>
            </div>

            <form action="{{ route('pesanan-saya.ajukan-batal', $pemesanan->id) }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Alasan Pembatalan</label>
                    <textarea name="alasan" required rows="3" placeholder="Contoh: Mendadak ada keperluan keluarga mendesak atau kondisi kesehatan tidak memungkinkan..." class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:bg-white"></textarea>
                </div>

                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 space-y-3">
                    <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Rekening Penerima Pengembalian Dana</h4>
                    
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-600 mb-1">Nama Bank / E-Wallet</label>
                        <input type="text" name="nama_bank" required placeholder="Contoh: BCA / Mandiri / BRI / GoPay / Dana" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs text-slate-800">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Nomor Rekening / No. HP</label>
                            <input type="text" name="no_rekening" required placeholder="Contoh: 1234567890" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs text-slate-800 font-mono">
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Atas Nama Pemilik Rekening</label>
                            <input type="text" name="atas_nama" required placeholder="Sesuai buku tabungan" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs text-slate-800">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold text-slate-600 mb-1">Nomor WhatsApp Aktif</label>
                        <input type="text" name="no_whatsapp" value="{{ auth()->user()->no_hp }}" placeholder="08xxxxxxxxxx" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl text-xs text-slate-800 font-mono">
                        <span class="text-[10px] text-slate-400 mt-0.5 block">Digunakan admin untuk mengonfirmasi slip transfer pengembalian dana</span>
                    </div>
                </div>

                <div class="p-3 bg-amber-50 rounded-xl border border-amber-200 text-[11px] text-amber-800 leading-relaxed">
                    <strong>Catatan:</strong> Setelah disetujui admin, dana refund akan ditransfer ke rekening di atas dalam 1x24 jam kerja sesuai kebijakan pembatalan biro travel Jelajah Nusa.
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" @click="batalModal = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl shadow-xs transition">
                        Kirim Permohonan Pembatalan
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
