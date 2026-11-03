@extends('layouts.wisatawan', ['title' => 'Jelajah Nusa — Agen Wisata & Open Trip Terpercaya'])

@section('content')
<!-- Hero Section (Dark Navy #101d2d matching Figma) -->
<div class="bg-[#101d2d] text-white pt-12 pb-20 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
            
            <!-- Left Hero Content -->
            <div class="lg:col-span-7 space-y-5">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-400/20 text-amber-300 border border-amber-400/30">
                    <svg class="w-3.5 h-3.5 text-amber-300" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    Open Trip &amp; Custom Trip ke Seluruh Indonesia
                </span>
                
                <h1 class="text-3xl sm:text-5xl font-extrabold tracking-tight leading-tight text-white">
                    Jelajahi Keindahan Nusantara, dari Solo ke Penjuru Indonesia
                </h1>
                
                <p class="text-sm sm:text-base text-slate-300 max-w-xl leading-relaxed">
                    Pesan open trip hemat, paket wisata reguler, atau rancang liburan privat keluarga dan instansi. Kuota real-time terjamin, verifikasi cepat, dan tiket QR digital resmi.
                </p>

                <!-- Quick Search Bar Card in Hero -->
                <div class="bg-white rounded-2xl p-4 shadow-xl text-slate-800 mt-6">
                    <form action="{{ route('paket.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
                        <!-- Destinasi -->
                        <div class="sm:col-span-4 border-b sm:border-b-0 sm:border-r border-slate-200 pb-2 sm:pb-0 sm:pr-3">
                            <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-teal-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Destinasi Tujuan
                            </label>
                            <select name="destinasi" class="w-full bg-transparent text-xs sm:text-sm font-semibold text-slate-800 focus:outline-none border-0 p-0 cursor-pointer">
                                <option value="">Semua Destinasi...</option>
                                @foreach($destinasiList as $dst)
                                    <option value="{{ $dst }}">{{ $dst }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Tanggal Berangkat -->
                        <div class="sm:col-span-3 border-b sm:border-b-0 sm:border-r border-slate-200 pb-2 sm:pb-0 sm:px-3">
                            <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-teal-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                Keberangkatan
                            </label>
                            <input type="date" name="tanggal" min="{{ now()->toDateString() }}" class="w-full bg-transparent text-xs sm:text-sm font-semibold text-slate-800 focus:outline-none border-0 p-0 cursor-pointer">
                        </div>

                        <!-- Jenis Layanan -->
                        <div class="sm:col-span-3 pb-2 sm:pb-0 sm:px-3">
                            <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-teal-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                                Tipe Layanan
                            </label>
                            <select name="layanan" class="w-full bg-transparent text-xs sm:text-sm font-semibold text-slate-800 focus:outline-none border-0 p-0 cursor-pointer">
                                <option value="">Semua Layanan</option>
                                <option value="open_trip">Open Trip</option>
                                <option value="paket_reguler">Paket Reguler</option>
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

            <!-- Right Hero Card -->
            <div class="lg:col-span-5">
                <div class="relative rounded-3xl overflow-hidden shadow-2xl h-[340px] sm:h-[400px] bg-slate-800 group border border-slate-700/50">
                    <img src="https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&w=1000&q=80" 
                         alt="Keindahan Bali Nusa Penida" 
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/95 via-slate-900/40 to-black/20"></div>

                    <!-- Glass Badges -->
                    <div class="absolute top-4 left-4 right-4 flex items-center justify-between">
                        <span class="px-3 py-1 rounded-full text-[11px] font-bold bg-white/20 backdrop-blur-md text-white border border-white/20">
                            Destinasi Terfavorit
                        </span>
                        <span class="px-3 py-1 rounded-full text-[11px] font-bold bg-teal-500/80 backdrop-blur-md text-white border border-teal-300/30">
                            100% Berangkat Resmi
                        </span>
                    </div>

                    <!-- Bottom Text & CTA -->
                    <div class="absolute bottom-6 left-6 right-6 space-y-4">
                        <div>
                            <span class="text-xs font-semibold text-amber-300 uppercase tracking-wider">Pilihan Wisatawan</span>
                            <h3 class="text-xl sm:text-2xl font-extrabold text-white leading-snug mt-0.5">
                                Jelajahi Eksotisme Bali, Bromo, hingga Raja Ampat
                            </h3>
                        </div>
                        <div class="flex items-center gap-3">
                            <a href="{{ route('paket.index') }}" class="px-5 py-2.5 bg-white hover:bg-slate-100 text-slate-900 font-bold text-xs rounded-xl shadow transition">
                                Buka Katalog Paket &rarr;
                            </a>
                            <a href="{{ route('custom-trip.create') }}" class="px-4 py-2.5 bg-white/10 hover:bg-white/20 text-white font-semibold text-xs rounded-xl backdrop-blur-sm border border-white/20 transition">
                                Request Rute Sendiri
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Main Body Container -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 space-y-16">
    
    <!-- Section: 2 Model Layanan Perjalanan (Open Trip vs Custom Trip) -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Open Trip Card -->
        <div class="p-8 rounded-3xl bg-white border border-slate-200/90 shadow-xs hover:shadow-md transition flex flex-col justify-between space-y-5">
            <div class="space-y-3">
                <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-700 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
                <div class="space-y-1">
                    <span class="text-xs font-bold uppercase tracking-wider text-teal-700">Layanan Reguler &amp; Publik</span>
                    <h3 class="text-xl font-extrabold text-slate-900">Open Trip Nusantara</h3>
                </div>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                    Berangkat bersama wisatawan lain dengan jadwal terencana dan biaya jauh lebih hemat. Kuota kursi terpantau langsung secara real-time dengan status kelayakan armada yang transparan.
                </p>
            </div>
            <div>
                <a href="{{ route('paket.index', ['layanan' => 'open_trip']) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-teal-700 hover:bg-teal-800 text-white font-bold text-xs rounded-xl shadow-xs transition">
                    Lihat Pilihan Open Trip &rarr;
                </a>
            </div>
        </div>

        <!-- Custom Trip Card -->
        <div class="p-8 rounded-3xl bg-white border border-slate-200/90 shadow-xs hover:shadow-md transition flex flex-col justify-between space-y-5">
            <div class="space-y-3">
                <div class="w-12 h-12 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                </div>
                <div class="space-y-1">
                    <span class="text-xs font-bold uppercase tracking-wider text-orange-600">Layanan Privat &amp; Rombongan</span>
                    <h3 class="text-xl font-extrabold text-slate-900">Custom Trip Eksklusif</h3>
                </div>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                    Tentukan sendiri destinasi tujuan, tanggal liburan, durasi hari, dan kebutuhan fasilitas khusus Anda. Tim kami akan menyiapkan armada kendaraan resmi beserta penawaran biaya yang transparan.
                </p>
            </div>
            <div>
                <a href="{{ route('custom-trip.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-orange-500 hover:bg-orange-600 text-white font-bold text-xs rounded-xl shadow-xs transition">
                    Ajukan Custom Trip &rarr;
                </a>
            </div>
        </div>
    </div>

    <!-- Section: Paket Pilihan Unggulan (Top Curated Packages) -->
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
            <div>
                <span class="text-xs font-bold text-teal-700 uppercase tracking-wider">Koleksi Terpopuler</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight mt-0.5">Paket Wisata Pilihan</h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Destinasi favorit wisatawan dengan fasilitas lengkap dan jaminan kenyamanan</p>
            </div>
            <a href="{{ route('paket.index') }}" class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-bold text-teal-700 hover:text-teal-800 hover:underline">
                Lihat Semua Paket (Katalog Lengkap) &rarr;
            </a>
        </div>

        <!-- Package Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($paketUnggulan as $pkt)
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
                                <span class="px-2 py-0.5 rounded-md text-[11px] font-semibold bg-teal-50 text-teal-800">
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

                        <!-- Price & Action -->
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
                <div class="col-span-full py-12 bg-white rounded-3xl border border-slate-200 text-center text-slate-400 text-sm">
                    Belum ada paket wisata aktif saat ini.
                </div>
            @endforelse
        </div>

        <div class="text-center pt-2">
            <a href="{{ route('paket.index') }}" class="inline-block px-7 py-3 bg-teal-700 hover:bg-teal-800 text-white font-bold text-xs sm:text-sm rounded-xl shadow-xs transition">
                Buka Katalog Lengkap Paket Wisata &rarr;
            </a>
        </div>
    </div>

    <!-- Section: Open Trip Segera Berangkat -->
    @if(count($openTrips) > 0)
        <div class="space-y-6">
            <div>
                <span class="text-xs font-bold text-teal-700 uppercase tracking-wider">Keberangkatan Terdekat</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight mt-0.5">Open Trip Siap Berangkat</h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Pantau jumlah kuota kursi dan status kelayakan armada secara langsung</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($openTrips as $ot)
                    @php
                        $jdw = $ot->jadwal_mendatang;
                        $pesertaAktif = $jdw ? $jdw->peserta_aktif : 0;
                        $minPeserta = $jdw ? $jdw->min_peserta : 10;
                        $kuota = $jdw ? $jdw->kuota : 20;
                        $layak = $pesertaAktif >= $minPeserta;
                        $persen = min(100, round(($pesertaAktif / max(1, $kuota)) * 100));
                    @endphp
                    <div class="bg-white rounded-2xl border border-slate-200/90 overflow-hidden shadow-xs flex flex-col sm:flex-row">
                        <div class="sm:w-48 h-48 sm:h-auto bg-slate-100 shrink-0">
                            <img src="{{ $ot->foto_url }}" alt="{{ $ot->nama_paket }}" class="w-full h-full object-cover">
                        </div>
                        <div class="p-5 flex-1 flex flex-col justify-between space-y-3">
                            <div class="space-y-1">
                                <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $layak ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                                    {{ $layak ? 'Layak dijalankan' : 'Menunggu kuota minimal' }}
                                </span>
                                <h3 class="text-base font-extrabold text-slate-900 leading-snug">{{ $ot->nama_paket }}</h3>
                                <p class="text-xs text-slate-500">
                                    Berangkat: {{ $jdw ? $jdw->tanggal_berangkat->translatedFormat('d M Y') : 'Jadwal Segera' }} &bull; {{ $ot->destinasi }}
                                </p>
                            </div>

                            <!-- Quota Progress Bar -->
                            <div class="space-y-1.5">
                                <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                    <div class="bg-teal-600 h-2 rounded-full transition-all" style="width: {{ $persen }}%"></div>
                                </div>
                                <span class="text-[11px] text-slate-500 block">
                                    {{ $pesertaAktif }}/{{ $kuota }} kursi terisi &bull; min. {{ $minPeserta }} peserta
                                </span>
                            </div>

                            <div>
                                <a href="{{ route('paket.show', $ot->id) }}" class="inline-block px-4 py-2 border border-teal-700 text-teal-700 hover:bg-teal-50 rounded-xl text-xs font-bold transition">
                                    Lihat Detail &amp; Pesan
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Section: Cara Pesan dalam 4 Langkah -->
    <div class="space-y-6">
        <div>
            <span class="text-xs font-bold text-teal-700 uppercase tracking-wider">Panduan Pemesanan</span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight mt-0.5">Cara Pesan dalam 4 Langkah Mudah</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Alur pemesanan digital dari pemilihan jadwal hingga tiket boarding pass QR</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <!-- Step 1 -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-3">
                <div class="w-8 h-8 rounded-full bg-teal-100 text-teal-800 flex items-center justify-center text-sm font-black">
                    1
                </div>
                <h3 class="text-base font-extrabold text-slate-900">Pilih Paket Wisata</h3>
                <p class="text-xs text-slate-500 leading-relaxed">Cari destinasi dan tanggal keberangkatan yang sesuai dengan rencana liburan Anda.</p>
            </div>

            <!-- Step 2 -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-3">
                <div class="w-8 h-8 rounded-full bg-teal-100 text-teal-800 flex items-center justify-center text-sm font-black">
                    2
                </div>
                <h3 class="text-base font-extrabold text-slate-900">Isi Data Peserta</h3>
                <p class="text-xs text-slate-500 leading-relaxed">Lengkapi identitas KTP/Paspor untuk kepentingan manifes resmi dan asuransi trip.</p>
            </div>

            <!-- Step 3 -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-3">
                <div class="w-8 h-8 rounded-full bg-teal-100 text-teal-800 flex items-center justify-center text-sm font-black">
                    3
                </div>
                <h3 class="text-base font-extrabold text-slate-900">Bayar &amp; Verifikasi</h3>
                <p class="text-xs text-slate-500 leading-relaxed">Transfer ke rekening resmi agen dan unggah bukti transfer untuk diverifikasi admin.</p>
            </div>

            <!-- Step 4 -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-3">
                <div class="w-8 h-8 rounded-full bg-teal-100 text-teal-800 flex items-center justify-center text-sm font-black">
                    4
                </div>
                <h3 class="text-base font-extrabold text-slate-900">Terima Tiket QR</h3>
                <p class="text-xs text-slate-500 leading-relaxed">Tiket digital boarding pass otomatis terbit lengkap dengan QR code keaslian.</p>
            </div>
        </div>
    </div>

    <!-- Section: Kenapa Jelajah Nusa (Keunggulan Bisnis) -->
    <div class="space-y-6">
        <div>
            <span class="text-xs font-bold text-teal-700 uppercase tracking-wider">Keunggulan Layanan</span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight mt-0.5">Kenapa Memilih Jelajah Nusa?</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Standar operasional terpercaya untuk keamanan dan kenyamanan liburan Anda</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Feature 1 -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-3">
                <div class="w-10 h-10 rounded-xl bg-teal-100 text-teal-800 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <h3 class="text-base font-extrabold text-slate-900">Kuota Real-time</h3>
                <p class="text-xs text-slate-600 leading-relaxed">Jumlah sisa kursi otomatis terkunci saat reservasi, mencegah risiko overbooking armada.</p>
            </div>

            <!-- Feature 2 -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-3">
                <div class="w-10 h-10 rounded-xl bg-teal-100 text-teal-800 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <h3 class="text-base font-extrabold text-slate-900">Pembayaran Terverifikasi</h3>
                <p class="text-xs text-slate-600 leading-relaxed">Pemeriksaan bukti transfer dilakukan cermat oleh admin, rekening agen resmi berbadan hukum.</p>
            </div>

            <!-- Feature 3 -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs space-y-3">
                <div class="w-10 h-10 rounded-xl bg-teal-100 text-teal-800 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                </div>
                <h3 class="text-base font-extrabold text-slate-900">Tiket Digital QR Valid</h3>
                <p class="text-xs text-slate-600 leading-relaxed">Dapat dicetak atau disimpan di HP, dilengkapi kode unik yang dapat diverifikasi oleh siapa pun.</p>
            </div>
        </div>
    </div>

    <!-- Section: CTA Banner Custom Trip -->
    <div class="bg-teal-800 rounded-3xl p-8 sm:p-12 text-white flex flex-col md:flex-row md:items-center md:justify-between gap-6 shadow-xl">
        <div class="space-y-2 max-w-xl">
            <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">
                Punya rencana perjalanan khusus?
            </h2>
            <p class="text-sm text-teal-100 leading-relaxed">
                Ajukan Custom Trip — tentukan destinasi, tanggal liburan, dan jumlah rombongan, tim kami segera menyiapkan penawaran armada dan fasilitas terbaik.
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
