@extends('layouts.wisatawan', ['title' => 'Jelajah Nusa — Paket Wisata & Open Trip Indonesia'])

@section('content')
<!-- Hero Section matching Figma Desktop 1440 -->
<div class="bg-[#101d2d] text-white pt-12 pb-20 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
            
            <!-- Left Hero Content -->
            <div class="lg:col-span-7 space-y-5">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-400/20 text-amber-300 border border-amber-400/30">
                    Open Trip &amp; Custom Trip ke seluruh Indonesia
                </span>
                
                <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight leading-tight text-white">
                    Jelajahi Indonesia, dari Solo ke Bali dan lebih jauh
                </h1>
                
                <p class="text-sm sm:text-base text-slate-300 max-w-xl leading-relaxed">
                    Pesan open trip, paket reguler, atau rancang perjalananmu sendiri. Kuota real-time, pembayaran terverifikasi, tiket QR digital.
                </p>

                <!-- Search Bar Card in Hero -->
                <div class="bg-white rounded-2xl p-4 shadow-xl text-slate-800 mt-6">
                    <form action="{{ route('paket.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
                        <!-- Destinasi -->
                        <div class="sm:col-span-4 border-b sm:border-b-0 sm:border-r border-slate-200 pb-2 sm:pb-0 sm:pr-3">
                            <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Destinasi
                            </label>
                            <select name="destinasi" class="w-full bg-transparent text-xs sm:text-sm font-semibold text-slate-800 focus:outline-none border-0 p-0 cursor-pointer">
                                <option value="">Bali, Bromo, Raja Ampat...</option>
                                @foreach($destinasiList as $dst)
                                    <option value="{{ $dst }}" {{ $destinasi === $dst ? 'selected' : '' }}>{{ $dst }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Tanggal Berangkat -->
                        <div class="sm:col-span-3 border-b sm:border-b-0 sm:border-r border-slate-200 pb-2 sm:pb-0 sm:px-3">
                            <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                Tanggal Berangkat
                            </label>
                            <input type="date" name="tanggal" value="{{ $tanggal }}" min="{{ now()->toDateString() }}" class="w-full bg-transparent text-xs sm:text-sm font-semibold text-slate-800 focus:outline-none border-0 p-0 cursor-pointer">
                        </div>

                        <!-- Jenis Layanan -->
                        <div class="sm:col-span-3 pb-2 sm:pb-0 sm:px-3">
                            <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                                Jenis Layanan
                            </label>
                            <select name="layanan" class="w-full bg-transparent text-xs sm:text-sm font-semibold text-slate-800 focus:outline-none border-0 p-0 cursor-pointer">
                                <option value="">Semua Layanan</option>
                                <option value="open_trip" {{ $layanan === 'open_trip' ? 'selected' : '' }}>Open Trip</option>
                                <option value="paket_reguler" {{ $layanan === 'paket_reguler' ? 'selected' : '' }}>Paket Reguler</option>
                            </select>
                        </div>

                        <!-- Submit Button -->
                        <div class="sm:col-span-2">
                            <button type="submit" class="w-full py-3 bg-orange-500 hover:bg-orange-600 text-white font-bold text-xs sm:text-sm rounded-xl shadow-xs transition text-center">
                                Cari Paket
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right Hero Featured Card matching Figma -->
            <div class="lg:col-span-5">
                <div class="relative rounded-3xl overflow-hidden shadow-2xl h-[340px] sm:h-[380px] bg-slate-800 group">
                    <img src="https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&w=1000&q=80" 
                         alt="Destinasi Populer Bali" 
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-900/30 to-black/20"></div>

                    <!-- Top Glass Badges -->
                    <div class="absolute top-4 left-4 right-4 flex items-center justify-between">
                        <span class="px-3 py-1 rounded-full text-[11px] font-bold bg-white/20 backdrop-blur-md text-white border border-white/20">
                            Destinasi populer
                        </span>
                        <span class="px-3 py-1 rounded-full text-[11px] font-bold bg-white/20 backdrop-blur-md text-white border border-white/20">
                            3.500+ wisatawan aktif
                        </span>
                    </div>

                    <!-- Bottom Text & Action Buttons -->
                    <div class="absolute bottom-6 left-6 right-6 space-y-4">
                        <h3 class="text-xl sm:text-2xl font-extrabold text-white leading-snug">
                            Temukan paket wisata yang sesuai dengan rencana liburanmu.
                        </h3>
                        <div class="flex items-center gap-3">
                            <a href="#katalog-paket" class="px-5 py-2.5 bg-white hover:bg-slate-100 text-slate-900 font-bold text-xs rounded-full shadow transition">
                                Lihat Semua Paket
                            </a>
                            <a href="#open-trip-segera" class="px-4 py-2 text-white hover:text-slate-200 font-semibold text-xs transition">
                                Cek Jadwal &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Main Catalog Container -->
<div id="katalog-paket" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-16">
    
    <!-- Section: Paket Populer -->
    <div class="space-y-6">
        <div>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Paket Populer</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Destinasi favorit wisatawan — harga transparan, kuota diperbarui otomatis</p>
        </div>

        <!-- Package Grid (US-03) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($paketList as $pkt)
                @php
                    $jadwalTerdekat = $pkt->jadwal_mendatang;
                    $sisaKursi = $jadwalTerdekat ? $jadwalTerdekat->sisa_kuota : 0;
                @endphp
                <div class="bg-white rounded-2xl border border-slate-200/90 overflow-hidden shadow-xs hover:shadow-md transition-all flex flex-col group">
                    <!-- Photo Header -->
                    <div class="h-52 bg-slate-100 relative overflow-hidden">
                        <img src="{{ $pkt->foto_url }}" alt="{{ $pkt->nama_paket }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <span class="absolute top-3 left-3 px-3 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider backdrop-blur-md bg-white/90 text-slate-800 shadow-xs">
                            {{ $pkt->jenis_layanan === 'open_trip' ? 'Open Trip' : 'Paket Reguler' }}
                        </span>
                    </div>

                    <!-- Body -->
                    <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                        <div class="space-y-2">
                            <div class="flex items-center justify-between text-xs">
                                <span class="px-2 py-0.5 rounded-md text-[11px] font-semibold bg-sky-50 text-sky-700">
                                    {{ $pkt->jenis_layanan === 'open_trip' ? 'Open Trip' : 'Paket Reguler' }}
                                </span>
                                @if($sisaKursi > 0)
                                    <span class="font-bold text-xs {{ $sisaKursi <= 3 ? 'text-rose-600' : 'text-emerald-700' }}">
                                        Sisa {{ $sisaKursi }} kursi
                                    </span>
                                @else
                                    <span class="font-bold text-xs text-rose-600">Kuota penuh</span>
                                @endif
                            </div>

                            <h3 class="text-base font-extrabold text-slate-900 group-hover:text-teal-700 transition leading-snug">
                                {{ $pkt->nama_paket }}
                            </h3>

                            <div class="space-y-1 pt-1 text-xs text-slate-500">
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    <span>{{ $pkt->destinasi }}</span>
                                    @if(count($pkt->meeting_points) > 1)
                                        <span class="text-teal-700 font-bold bg-teal-50 px-2 py-0.5 rounded-md text-[10px]">&bull; {{ count($pkt->meeting_points) }} Titik Kumpul</span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>{{ $pkt->durasi_label }}</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span>Berangkat {{ $jadwalTerdekat ? $jadwalTerdekat->tanggal_berangkat->translatedFormat('d M Y') : 'Jadwal menyusul' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Footer Price & Detail Action -->
                        <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                            <div>
                                <span class="block text-[10px] text-slate-400 font-medium">Mulai dari</span>
                                <span class="block text-lg font-extrabold text-teal-800 font-mono">{{ $pkt->harga_format }}</span>
                            </div>
                            <a href="{{ route('paket.show', $pkt->id) }}" class="px-4 py-2 border border-teal-700 text-teal-700 hover:bg-teal-50 rounded-xl text-xs font-bold transition">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 bg-white rounded-3xl border border-slate-200 text-center space-y-3">
                    <p class="text-sm font-semibold text-slate-600">Tidak ada paket wisata yang cocok dengan pencarian Anda.</p>
                    <a href="{{ route('paket.index') }}" class="inline-block px-4 py-2 bg-teal-700 text-white rounded-xl text-xs font-bold">Reset Pencarian</a>
                </div>
            @endforelse
        </div>

        <div class="pt-4">
            {{ $paketList->links() }}
        </div>
    </div>

    <!-- Section: Open Trip Segera Berangkat matching Figma -->
    <div id="open-trip-segera" class="space-y-6">
        <div>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Open Trip Segera Berangkat</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Pantau jumlah peserta — keberangkatan dikonfirmasi saat batas minimal terpenuhi</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @php
                $openTrips = $paketList->where('jenis_layanan', 'open_trip')->take(2);
            @endphp
            @foreach($openTrips as $ot)
                @php
                    $jdw = $ot->jadwal_mendatang;
                    $pesertaAktif = $jdw ? $jdw->peserta_aktif : 12;
                    $minPeserta = $jdw ? $jdw->min_peserta : 10;
                    $kuota = $jdw ? $jdw->kuota : 20;
                    $layak = $pesertaAktif >= $minPeserta;
                    $persen = min(100, round(($pesertaAktif / max(1, $kuota)) * 100));
                @endphp
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs flex flex-col sm:flex-row">
                    <div class="sm:w-48 h-48 sm:h-auto bg-slate-100 shrink-0">
                        <img src="{{ $ot->foto_url }}" alt="{{ $ot->nama_paket }}" class="w-full h-full object-cover">
                    </div>
                    <div class="p-5 flex-1 flex flex-col justify-between space-y-3">
                        <div class="space-y-1">
                            <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $layak ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                                {{ $layak ? 'Layak dijalankan' : 'Belum memenuhi minimal' }}
                            </span>
                            <h3 class="text-base font-extrabold text-slate-900 leading-snug">{{ $ot->nama_paket }}</h3>
                            <p class="text-xs text-slate-500">
                                Berangkat {{ $jdw ? $jdw->tanggal_berangkat->translatedFormat('d M Y') : '15 Des 2026' }}
                            </p>
                        </div>

                        <!-- Quota Progress Bar -->
                        <div class="space-y-1.5">
                            <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                <div class="bg-teal-600 h-2 rounded-full transition-all" style="width: {{ $persen }}%"></div>
                            </div>
                            <span class="text-[11px] text-slate-500 block">
                                {{ $pesertaAktif }}/{{ $kuota }} peserta terdaftar · minimal {{ $minPeserta }}
                            </span>
                        </div>

                        <div>
                            <a href="{{ route('paket.show', $ot->id) }}" class="inline-block px-4 py-2 border border-teal-700 text-teal-700 hover:bg-teal-50 rounded-xl text-xs font-bold transition">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Section: Cara Pesan dalam 4 Langkah matching Figma -->
    <div class="space-y-6">
        <div>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Cara Pesan dalam 4 Langkah</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Dari memilih paket sampai tiket QR di tangan Anda</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <!-- Step 1 -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-3">
                <div class="w-8 h-8 rounded-full bg-amber-100 text-amber-900 flex items-center justify-center text-sm font-black">
                    1
                </div>
                <h3 class="text-base font-extrabold text-slate-900">Pilih Paket</h3>
                <p class="text-xs text-slate-500 leading-relaxed">Cari &amp; filter destinasi, jadwal, dan harga yang pas untuk liburan Anda.</p>
            </div>

            <!-- Step 2 -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-3">
                <div class="w-8 h-8 rounded-full bg-amber-100 text-amber-900 flex items-center justify-center text-sm font-black">
                    2
                </div>
                <h3 class="text-base font-extrabold text-slate-900">Isi Data Peserta</h3>
                <p class="text-xs text-slate-500 leading-relaxed">Lengkapi nama dan unggah KTP/Paspor dengan aman untuk manifes &amp; asuransi.</p>
            </div>

            <!-- Step 3 -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-3">
                <div class="w-8 h-8 rounded-full bg-amber-100 text-amber-900 flex items-center justify-center text-sm font-black">
                    3
                </div>
                <h3 class="text-base font-extrabold text-slate-900">Bayar &amp; Verifikasi</h3>
                <p class="text-xs text-slate-500 leading-relaxed">Unggah bukti transfer rekening resmi, admin memverifikasi dengan cepat.</p>
            </div>

            <!-- Step 4 -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-3">
                <div class="w-8 h-8 rounded-full bg-amber-100 text-amber-900 flex items-center justify-center text-sm font-black">
                    4
                </div>
                <h3 class="text-base font-extrabold text-slate-900">Terima Tiket QR</h3>
                <p class="text-xs text-slate-500 leading-relaxed">Tiket digital dengan QR code resmi terbit otomatis, siap dipakai saat berangkat.</p>
            </div>
        </div>
    </div>

    <!-- Section: Kenapa Jelajah Nusa matching Figma -->
    <div class="space-y-6">
        <div>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Kenapa Jelajah Nusa</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Sistem terpusat yang membuat perjalanan lebih tertib dan aman</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Feature 1 -->
            <div class="bg-[#fafaf9] p-6 rounded-2xl border border-stone-200/80 space-y-3">
                <div class="w-10 h-10 rounded-xl bg-teal-100 text-teal-800 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <h3 class="text-base font-extrabold text-slate-900">Kuota Real-time</h3>
                <p class="text-xs text-slate-600 leading-relaxed">Sisa kursi dan status keberangkatan selalu diperbarui otomatis tanpa risiko overbooking.</p>
            </div>

            <!-- Feature 2 -->
            <div class="bg-[#fafaf9] p-6 rounded-2xl border border-stone-200/80 space-y-3">
                <div class="w-10 h-10 rounded-xl bg-teal-100 text-teal-800 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <h3 class="text-base font-extrabold text-slate-900">Pembayaran Terverifikasi</h3>
                <p class="text-xs text-slate-600 leading-relaxed">Bukti transfer dicek admin secara cermat, status pembayaran jelas dan tercatat aman.</p>
            </div>

            <!-- Feature 3 -->
            <div class="bg-[#fafaf9] p-6 rounded-2xl border border-stone-200/80 space-y-3">
                <div class="w-10 h-10 rounded-xl bg-teal-100 text-teal-800 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                </div>
                <h3 class="text-base font-extrabold text-slate-900">Tiket Digital QR</h3>
                <p class="text-xs text-slate-600 leading-relaxed">Tiket unik dengan QR code yang mudah diverifikasi keasliannya oleh tim pemandu.</p>
            </div>
        </div>
    </div>

    <!-- Section: CTA Banner matching Figma -->
    <div class="bg-teal-800 rounded-3xl p-8 sm:p-12 text-white flex flex-col md:flex-row md:items-center md:justify-between gap-6 shadow-xl">
        <div class="space-y-2 max-w-xl">
            <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">
                Punya rencana perjalanan sendiri?
            </h2>
            <p class="text-sm text-teal-100 leading-relaxed">
                Ajukan custom trip — tentukan tujuan, waktu, dan kebutuhan, kami siapkan penawarannya.
            </p>
        </div>
        <div>
            <a href="{{ route('custom-trip.create') }}" class="inline-block px-7 py-3.5 bg-orange-500 hover:bg-orange-600 text-white font-bold text-sm rounded-xl shadow-lg transition">
                Ajukan Custom Trip
            </a>
        </div>
    </div>

</div>
@endsection
