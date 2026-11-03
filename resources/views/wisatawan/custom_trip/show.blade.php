@extends('layouts.wisatawan', ['title' => 'Detail Custom Trip: ' . $customTrip->tujuan])

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    
    <div class="flex items-center justify-between">
        <a href="{{ route('custom-trip.index') }}" class="text-xs font-bold text-teal-700 hover:text-teal-800 flex items-center gap-1">
            &larr; Kembali ke Daftar Custom Trip
        </a>
        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase {{ $customTrip->status === 'disetujui' ? 'bg-emerald-50 text-emerald-700' : ($customTrip->status === 'ditawarkan' ? 'bg-amber-50 text-amber-700' : ($customTrip->status === 'diajukan' ? 'bg-teal-50 text-teal-800' : 'bg-rose-50 text-rose-700')) }}">
            Status: {{ $customTrip->status }}
        </span>
    </div>

    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/90 shadow-xs space-y-6">
        
        <div>
            <span class="text-xs font-mono font-bold text-teal-800">Pengajuan #{{ $customTrip->id }}</span>
            <h1 class="text-2xl font-extrabold text-slate-900 mt-1">{{ $customTrip->tujuan }}</h1>
            <p class="text-xs text-slate-500 mt-1">Diajukan pada {{ $customTrip->created_at->translatedFormat('d F Y') }} &bull; Untuk {{ $customTrip->jumlah_peserta }} Peserta &bull; Durasi {{ $customTrip->durasi_hari }} Hari</p>
        </div>

        <!-- Rincian Permintaan Awal -->
        <div class="p-5 bg-slate-50 rounded-2xl border border-slate-100 space-y-2 text-xs">
            <h4 class="font-bold text-slate-800 uppercase tracking-wider text-[11px]">Rincian Kebutuhan yang Diajukan:</h4>
            <p class="text-slate-600 leading-relaxed whitespace-pre-line">{{ $customTrip->kebutuhan }}</p>
        </div>

        <!-- Penawaran dari Admin Travel (F3-07, BR-11, US-13) -->
        @if($customTrip->status === 'ditawarkan' || $customTrip->status === 'disetujui')
            <div class="p-6 rounded-3xl bg-teal-50/50 border border-teal-200 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-teal-200/60">
                    <div>
                        <span class="px-2.5 py-0.5 rounded-md text-[10px] font-bold uppercase bg-teal-700 text-white">Penawaran Resmi Travel</span>
                        <h3 class="text-base font-extrabold text-slate-900 mt-1">Proposal Biaya &amp; Fasilitas Terpilih</h3>
                    </div>
                    <div class="text-right">
                        <span class="text-[11px] text-slate-400 block font-medium">Total Biaya Penawaran</span>
                        <span class="text-2xl font-extrabold text-teal-800 font-mono">{{ $customTrip->biaya_penawaran_format }}</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div>
                        <span class="text-slate-500 block font-medium">Armada Kendaraan Ditetapkan:</span>
                        <strong class="text-slate-900 text-sm block mt-0.5">
                            {{ $customTrip->kendaraan ? $customTrip->kendaraan->jenis_kendaraan : 'Armada Pariwisata Eksekutif' }}
                        </strong>
                    </div>

                    <div>
                        <span class="text-slate-500 block font-medium">Tanggal Keberangkatan Penawaran:</span>
                        <strong class="text-slate-900 text-sm block mt-0.5">
                            {{ $customTrip->jadwal_penawaran ? $customTrip->jadwal_penawaran->translatedFormat('l, d F Y') : '—' }}
                        </strong>
                    </div>

                    <div class="sm:col-span-2 pt-2 border-t border-teal-100">
                        <span class="text-slate-500 block font-medium mb-1">Rincian Fasilitas Termasuk:</span>
                        <div class="p-4 bg-white/90 rounded-2xl border border-teal-100 text-slate-700 leading-relaxed whitespace-pre-line">
                            {{ $customTrip->fasilitas_penawaran }}
                        </div>
                    </div>
                </div>

                <!-- Action: Setujui atau Tolak Penawaran (US-13, T-16) -->
                @if($customTrip->status === 'ditawarkan')
                    <div class="pt-4 border-t border-teal-200/60 flex flex-col sm:flex-row items-center justify-end gap-3">
                        <form action="{{ route('custom-trip.tolak', $customTrip->id) }}" method="POST">
                            @csrf
                            <button type="submit" onclick="return confirm('Tolak penawaran custom trip ini?');" class="px-5 py-2.5 bg-white border border-rose-200 text-rose-600 text-xs font-bold rounded-xl transition hover:bg-rose-50">
                                Tolak Penawaran
                            </button>
                        </form>
                        <form action="{{ route('custom-trip.setujui', $customTrip->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-6 py-2.5 bg-teal-700 hover:bg-teal-800 text-white text-xs sm:text-sm font-bold rounded-xl shadow-xs transition">
                                Setujui Penawaran &amp; Lanjut Pemesanan &rarr;
                            </button>
                        </form>
                    </div>
                @endif

                @if($customTrip->status === 'disetujui')
                    <div class="pt-2">
                        @if($customTrip->pemesanan)
                            <a href="{{ route('pesanan-saya.show', $customTrip->pemesanan->id) }}" class="inline-block px-5 py-2.5 bg-teal-700 hover:bg-teal-800 text-white rounded-xl text-xs font-bold shadow-xs">
                                Buka Pesanan &amp; Pembayaran #{{ $customTrip->pemesanan->display_kode }} &rarr;
                            </a>
                        @else
                            <a href="{{ route('pemesanan.create', ['custom_trip_id' => $customTrip->id]) }}" class="inline-block px-5 py-2.5 bg-orange-500 hover:bg-orange-600 text-white rounded-xl text-xs font-bold shadow-xs">
                                Isi Data Peserta untuk Menyelesaikan Pesanan &rarr;
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        @else
            <div class="p-8 bg-slate-50 rounded-3xl border border-slate-200 text-center space-y-2">
                <span class="w-10 h-10 rounded-full bg-teal-50 text-teal-700 flex items-center justify-center mx-auto text-lg font-bold">
                    &hellip;
                </span>
                <h4 class="text-sm font-extrabold text-slate-900">Menunggu Penawaran Resmi</h4>
                <p class="text-xs text-slate-500 max-w-md mx-auto">Tim Jelajah Nusa sedang menghitung ketersediaan armada dan biaya operasional untuk perjalanan Anda. Penawaran akan muncul di halaman ini.</p>
            </div>
        @endif

    </div>
</div>
@endsection
