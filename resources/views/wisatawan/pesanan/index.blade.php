@extends('layouts.wisatawan', ['title' => 'Pesanan Saya — Jelajah Nusa'])

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Pesanan Saya</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Pantau status konfirmasi tiket, tagihan, dan bukti pembayaran perjalanan Anda</p>
        </div>
        <a href="{{ route('paket.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-teal-700 hover:bg-teal-800 text-white text-xs font-bold rounded-xl transition shadow-xs">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Pesan Trip Lainnya
        </a>
    </div>

    <!-- Orders List -->
    <div class="space-y-4">
        @forelse($pesananList as $psn)
            @php
                $tglBerangkat = $psn->tanggal_berangkat;
            @endphp
            <div class="bg-white p-6 sm:p-7 rounded-3xl border border-slate-200/90 shadow-xs hover:border-teal-300 transition-all flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                
                <div class="space-y-2 flex-1 min-w-0">
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-mono font-bold text-teal-700 bg-teal-50 px-2.5 py-0.5 rounded-md">{{ $psn->display_kode }}</span>
                        <span class="text-[11px] text-slate-400 font-medium">Dipesan pada {{ $psn->created_at->format('d M Y') }}</span>
                    </div>

                    <h3 class="text-lg font-extrabold text-slate-900 leading-snug">
                        {{ $psn->judul_trip }}
                    </h3>

                    <div class="flex flex-wrap items-center gap-y-1 gap-x-4 text-xs text-slate-500">
                        <span>Keberangkatan: <strong class="text-slate-800">{{ $tglBerangkat ? $tglBerangkat->translatedFormat('d F Y') : '—' }}</strong></span>
                        <span>&bull;</span>
                        <span>Peserta: <strong class="text-slate-800">{{ $psn->jumlah_peserta }} Orang</strong></span>
                        <span>&bull;</span>
                        <span>Total: <strong class="text-slate-900 font-mono">{{ $psn->total_biaya_format }}</strong></span>
                    </div>
                </div>

                <div class="flex flex-col sm:items-end justify-between gap-3 flex-shrink-0">
                    <div class="flex items-center gap-2">
                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase {{ $psn->status === 'dikonfirmasi' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : ($psn->status === 'menunggu' ? 'bg-amber-50 text-amber-700 border border-amber-100' : 'bg-rose-50 text-rose-700 border border-rose-100') }}">
                            {{ $psn->status === 'dikonfirmasi' ? 'Tiket Aktif' : ($psn->status === 'menunggu' ? 'Menunggu Pembayaran' : 'Dibatalkan') }}
                        </span>
                    </div>

                    <div class="flex items-center gap-2">
                        @if($psn->status === 'menunggu')
                            <a href="{{ route('pembayaran.show', $psn->id) }}" class="px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white text-xs font-bold rounded-xl transition shadow-xs">
                                Bayar Sekarang
                            </a>
                        @endif
                        <a href="{{ route('pesanan-saya.show', $psn->id) }}" class="px-4 py-2 bg-teal-700 hover:bg-teal-800 text-white text-xs font-bold rounded-xl transition shadow-xs">
                            Detail &amp; Tiket &rarr;
                        </a>
                    </div>
                </div>

            </div>
        @empty
            <div class="p-12 bg-white rounded-3xl border border-slate-200 text-center space-y-3">
                <p class="text-sm font-semibold text-slate-600">Anda belum memiliki riwayat pemesanan.</p>
                <a href="{{ route('paket.index') }}" class="inline-block px-5 py-2.5 bg-teal-700 text-white text-xs font-bold rounded-xl">Eksplorasi Paket Wisata</a>
            </div>
        @endforelse
    </div>

    <div class="pt-2">
        {{ $pesananList->links() }}
    </div>

</div>
@endsection
