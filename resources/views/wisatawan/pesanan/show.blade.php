@extends('layouts.wisatawan', ['title' => 'Detail Pesanan ' . $pemesanan->display_kode])

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    
    <div class="flex items-center justify-between">
        <a href="{{ route('pesanan-saya.index') }}" class="text-xs font-bold text-sky-600 hover:text-sky-700 flex items-center gap-1">
            &larr; Kembali ke Riwayat Pesanan
        </a>
        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase {{ $pemesanan->status === 'dikonfirmasi' ? 'bg-emerald-50 text-emerald-700' : ($pemesanan->status === 'menunggu' ? 'bg-amber-50 text-amber-700' : 'bg-rose-50 text-rose-700') }}">
            {{ $pemesanan->status === 'dikonfirmasi' ? 'Dikonfirmasi (Tiket Aktif)' : ($pemesanan->status === 'menunggu' ? 'Menunggu Pembayaran' : 'Dibatalkan') }}
        </span>
    </div>

    <!-- Header Card -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 pb-4 border-b border-slate-100">
            <div>
                <span class="text-xs font-mono font-bold text-sky-600">{{ $pemesanan->display_kode }}</span>
                <h1 class="text-2xl font-extrabold text-slate-900 mt-0.5">{{ $pemesanan->judul_trip }}</h1>
                <p class="text-xs text-slate-500 mt-1">Dipesan pada: {{ $pemesanan->created_at->translatedFormat('d F Y H:i') }} WIB</p>
            </div>
            <div class="text-left sm:text-right">
                <span class="text-xs text-slate-400 block font-medium">Total Biaya</span>
                <span class="text-2xl font-extrabold text-slate-900 font-mono">{{ $pemesanan->total_biaya_format }}</span>
            </div>
        </div>

        <!-- Detail Perjalanan Ringkas -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2 text-xs">
            <div class="p-3.5 bg-slate-50 rounded-2xl">
                <span class="text-slate-400 block font-medium">Tanggal Berangkat</span>
                <strong class="text-slate-900 text-sm block mt-0.5">
                    {{ $pemesanan->tanggal_berangkat ? $pemesanan->tanggal_berangkat->translatedFormat('d F Y') : '—' }}
                </strong>
            </div>

            <div class="p-3.5 bg-slate-50 rounded-2xl">
                <span class="text-slate-400 block font-medium">Jumlah Wisatawan</span>
                <strong class="text-slate-900 text-sm block mt-0.5">{{ $pemesanan->jumlah_peserta }} Orang</strong>
            </div>

            <div class="p-3.5 bg-slate-50 rounded-2xl">
                <span class="text-slate-400 block font-medium">Status Tagihan</span>
                <strong class="text-sm block mt-0.5 {{ $pemesanan->is_lunas ? 'text-emerald-600' : 'text-amber-600' }}">
                    {{ $pemesanan->is_lunas ? 'Lunas' : 'Sisa ' . $pemesanan->sisa_tagihan_format }}
                </strong>
            </div>
        </div>
    </div>

    <!-- Tiket Digital Per Peserta (F10-04, Bagian 19) -->
    @if($pemesanan->tiket->count() > 0)
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">Tiket / Voucher Digital Anda</h3>
                    <p class="text-xs text-slate-400">Unduh e-tiket PDF atau tunjukkan QR Code pada pemandu saat keberangkatan</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($pemesanan->tiket as $tkt)
                    <div class="p-5 rounded-2xl bg-gradient-to-tr from-slate-900 to-indigo-950 text-white shadow-md relative overflow-hidden flex flex-col justify-between space-y-4">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase bg-white/20 text-sky-200">E-Tiket Resmi</span>
                                <h4 class="text-base font-extrabold mt-1 text-white">{{ $tkt->peserta->nama_lengkap ?? 'Peserta' }}</h4>
                                <p class="text-xs text-slate-400 font-mono">NIK: {{ $tkt->peserta->no_identitas ?? '—' }}</p>
                            </div>
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                                {{ $tkt->status }}
                            </span>
                        </div>

                        <div class="pt-3 border-t border-white/10 flex items-center justify-between">
                            <div>
                                <span class="text-[10px] text-slate-400 block font-medium">Kode Tiket:</span>
                                <span class="text-sm font-mono font-extrabold text-sky-300">{{ $tkt->kode_tiket }}</span>
                            </div>
                            <a href="{{ route('tiket.unduh', $tkt->id) }}" class="px-3.5 py-1.5 bg-sky-500 hover:bg-sky-400 text-white rounded-xl text-xs font-bold transition flex items-center gap-1 shadow-sm">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                Unduh PDF
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Manifest Peserta Terdaftar -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs space-y-4">
        <h3 class="text-base font-extrabold text-slate-900 pb-3 border-b border-slate-100">Daftar Manifest Peserta ({{ $pemesanan->peserta->count() }} Orang)</h3>
        
        <div class="space-y-3">
            @foreach($pemesanan->peserta as $pst)
                <div class="p-3.5 bg-slate-50 border border-slate-100 rounded-2xl flex items-center justify-between gap-3 text-xs">
                    <div>
                        <strong class="text-slate-900 text-sm block">{{ $pst->nama_lengkap }}</strong>
                        <span class="text-slate-500 font-mono">{{ strtoupper($pst->jenis_identitas) }}: {{ $pst->no_identitas }}</span>
                        @if($pst->no_hp)
                            <span class="text-slate-400 block mt-0.5">Kontak: {{ $pst->no_hp }}</span>
                        @endif
                    </div>
                    <a href="{{ route('dokumen.identitas', $pst->id) }}" target="_blank" class="px-3 py-1.5 bg-white border border-slate-200 hover:bg-sky-50 text-sky-600 rounded-xl font-bold text-xs transition">
                        KTP Tersimpan &check;
                    </a>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Riwayat Pembayaran & Bukti Transfer -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs space-y-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
            <div>
                <h3 class="text-base font-extrabold text-slate-900">Riwayat Pembayaran</h3>
                <p class="text-xs text-slate-400">Total terverifikasi: <strong class="text-emerald-600">{{ $pemesanan->total_terbayar_format }}</strong> dari {{ $pemesanan->total_biaya_format }}</p>
            </div>
            @if($pemesanan->status === 'menunggu' && $pemesanan->sisa_tagihan > 0)
                <a href="{{ route('pembayaran.show', $pemesanan->id) }}" class="px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white rounded-xl text-xs font-bold transition shadow-xs">
                    + Unggah Bukti Bayar
                </a>
            @endif
        </div>

        <div class="space-y-3">
            @forelse($pemesanan->pembayaran as $pby)
                <div class="p-4 bg-slate-50 border border-slate-100 rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-4 text-xs">
                    <div>
                        <span class="text-sm font-extrabold font-mono text-slate-900">{{ $pby->nominal_format }}</span>
                        <span class="text-slate-500 block mt-0.5">Tgl Bayar: {{ $pby->tanggal_bayar->format('d/m/Y') }} &bull; Diunggah: {{ $pby->created_at->format('d/m/Y H:i') }}</span>
                        @if($pby->status === 'ditolak' && $pby->catatan_admin)
                            <p class="text-rose-600 font-semibold mt-1">Alasan Penolakan: {{ $pby->catatan_admin }}</p>
                        @endif
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase {{ $pby->status === 'diverifikasi' ? 'bg-emerald-100 text-emerald-800' : ($pby->status === 'menunggu' ? 'bg-amber-100 text-amber-800' : 'bg-rose-100 text-rose-800') }}">
                            {{ $pby->status }}
                        </span>
                        <a href="{{ route('dokumen.bukti', $pby->id) }}" target="_blank" class="px-3 py-1.5 bg-white border border-slate-200 text-slate-700 hover:text-sky-600 rounded-xl font-bold">
                            Lihat Bukti
                        </a>
                    </div>
                </div>
            @empty
                <div class="p-6 bg-slate-50 rounded-2xl text-center text-xs text-slate-400">
                    Belum ada bukti pembayaran yang diunggah untuk pesanan ini.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Tombol Batalkan Pesanan Mandiri (F11-04, US-16, T-22) -->
    @if($pemesanan->status === 'menunggu')
        <div class="p-6 bg-white rounded-3xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <h4 class="text-sm font-bold text-slate-900">Batalkan Pemesanan Ini?</h4>
                <p class="text-xs text-slate-400">Kursi yang ditahan akan langsung dikembalikan ke kuota jadwal.</p>
            </div>
            <form action="{{ route('pesanan-saya.batal', $pemesanan->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini? Kuota akan dikembalikan.');">
                @csrf
                <button type="submit" class="px-4 py-2 bg-rose-50 hover:bg-rose-100 text-rose-600 text-xs font-bold rounded-xl transition border border-rose-200">
                    Batalkan Pesanan
                </button>
            </form>
        </div>
    @endif

</div>
@endsection
