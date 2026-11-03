@extends('layouts.wisatawan', ['title' => 'Katalog Paket Wisata & Open Trip — Jelajah Nusa'])

@section('content')
<!-- Header Banner Halaman Katalog -->
<div class="bg-[#101d2d] text-white py-12 px-4 sm:px-6 lg:px-8 border-b border-slate-800">
    <div class="max-w-7xl mx-auto space-y-4">
        <div class="flex items-center gap-2 text-xs text-teal-400 font-semibold">
            <a href="{{ route('beranda') }}" class="hover:underline">Beranda</a>
            <span>&rsaquo;</span>
            <span class="text-white">Katalog Paket Wisata</span>
        </div>
        <div class="max-w-3xl">
            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-white">
                Katalog Paket Wisata &amp; Open Trip
            </h1>
            <p class="text-sm sm:text-base text-slate-300 mt-2 leading-relaxed">
                Pilih destinasi impian Anda ke Bali, Bromo, Labuan Bajo, hingga Raja Ampat. Seluruh paket dilengkapi rincian itinerary harian, titik kumpul resmi, dan kuota yang terpantau real-time.
            </p>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">

    <!-- Filter & Pencarian Lengkap Card -->
    <div class="bg-white p-6 rounded-3xl border border-slate-200/90 shadow-xs">
        <form action="{{ route('paket.index') }}" method="GET" class="space-y-4">
            
            <!-- Baris 1: Search & Destinasi & Layanan -->
            <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                
                <!-- Kolom Pencarian -->
                <div class="sm:col-span-5">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Cari Paket / Kata Kunci</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </span>
                        <input type="text" 
                               name="cari" 
                               value="{{ $cari }}" 
                               placeholder="Contoh: Bromo, Sunrise, Snorkeling, Bali..." 
                               class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 text-slate-800">
                    </div>
                </div>

                <!-- Dropdown Destinasi -->
                <div class="sm:col-span-4">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Destinasi</label>
                    <select name="destinasi" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500">
                        <option value="">Semua Destinasi</option>
                        @foreach($destinasiList as $dst)
                            <option value="{{ $dst }}" {{ $destinasi === $dst ? 'selected' : '' }}>{{ $dst }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Jenis Layanan -->
                <div class="sm:col-span-3">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Jenis Layanan</label>
                    <select name="layanan" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500">
                        <option value="">Semua Layanan</option>
                        <option value="open_trip" {{ $layanan === 'open_trip' ? 'selected' : '' }}>Open Trip (Publik)</option>
                        <option value="paket_reguler" {{ $layanan === 'paket_reguler' ? 'selected' : '' }}>Paket Reguler (Private)</option>
                    </select>
                </div>

            </div>

            <!-- Baris 2: Tanggal, Harga Maksimal, Urutan & Tombol Aksi -->
            <div class="grid grid-cols-1 sm:grid-cols-12 gap-4 pt-2 border-t border-slate-100 items-end">
                
                <!-- Tanggal Keberangkatan -->
                <div class="sm:col-span-3">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Mulai Tanggal</label>
                    <input type="date" 
                           name="tanggal" 
                           value="{{ $tanggal }}" 
                           min="{{ now()->toDateString() }}" 
                           class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500">
                </div>

                <!-- Harga Maksimal -->
                <div class="sm:col-span-3">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Budget Maksimal (Rp)</label>
                    <input type="number" 
                           name="harga_max" 
                           value="{{ $hargaMax }}" 
                           placeholder="Contoh: 3000000" 
                           class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 font-mono">
                </div>

                <!-- Urutkan Berdasarkan -->
                <div class="sm:col-span-3">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Urutan Tampilan</label>
                    <select name="urutan" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500">
                        <option value="terbaru" {{ $urutan === 'terbaru' ? 'selected' : '' }}>Paket Terbaru</option>
                        <option value="termurah" {{ $urutan === 'termurah' ? 'selected' : '' }}>Harga Termurah</option>
                        <option value="termahal" {{ $urutan === 'termahal' ? 'selected' : '' }}>Harga Tertinggi</option>
                    </select>
                </div>

                <!-- Tombol Terapkan & Reset -->
                <div class="sm:col-span-3 flex items-center gap-2">
                    <button type="submit" class="flex-1 py-2.5 bg-teal-700 hover:bg-teal-800 text-white font-bold text-xs rounded-xl shadow-xs transition text-center">
                        Terapkan Filter
                    </button>
                    @if($cari || $destinasi || $layanan || $tanggal || $hargaMax || $urutan !== 'terbaru')
                        <a href="{{ route('paket.index') }}" title="Hapus Semua Filter" class="px-3 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-bold transition">
                            Reset
                        </a>
                    @endif
                </div>

            </div>

        </form>
    </div>

    <!-- Informasi Hasil & Grid Katalog -->
    <div class="space-y-6">
        
        <!-- Result Bar -->
        <div class="flex items-center justify-between pb-2 border-b border-slate-200/80">
            <div>
                <h2 class="text-lg font-extrabold text-slate-900">
                    Daftar Paket Wisata
                </h2>
                <p class="text-xs text-slate-500">
                    Menampilkan {{ $paketList->total() }} pilihan destinasi aktif
                </p>
            </div>
            
            <!-- Filter Pills Indicator -->
            <div class="hidden sm:flex items-center gap-1.5 text-xs">
                @if($layanan)
                    <span class="px-2.5 py-1 rounded-full bg-teal-50 text-teal-800 font-bold border border-teal-200">
                        {{ $layanan === 'open_trip' ? 'Open Trip' : 'Paket Reguler' }}
                    </span>
                @endif
                @if($destinasi)
                    <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-700 font-bold">
                        {{ $destinasi }}
                    </span>
                @endif
            </div>
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
                <div class="col-span-full py-16 bg-white rounded-3xl border border-slate-200 text-center space-y-4">
                    <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h4 class="text-base font-extrabold text-slate-800">Paket Wisata Tidak Ditemukan</h4>
                        <p class="text-xs text-slate-500 max-w-md mx-auto mt-1">Tidak ada paket yang sesuai dengan kriteria filter Anda. Silakan ubah filter pencarian atau ajukan rute sendiri.</p>
                    </div>
                    <div class="flex items-center justify-center gap-3">
                        <a href="{{ route('paket.index') }}" class="px-5 py-2 bg-slate-900 text-white rounded-xl text-xs font-bold transition">
                            Reset Filter
                        </a>
                        <a href="{{ route('custom-trip.create') }}" class="px-5 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-xl text-xs font-bold transition">
                            Ajukan Custom Trip
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="pt-4">
            {{ $paketList->links() }}
        </div>
    </div>

    <!-- Banner Bantuan Custom Trip di Bawah Katalog -->
    <div class="bg-gradient-to-r from-teal-800 to-[#101d2d] rounded-3xl p-8 text-white flex flex-col md:flex-row md:items-center md:justify-between gap-6 shadow-md">
        <div class="space-y-1.5 max-w-2xl">
            <span class="text-xs font-bold text-amber-300 uppercase tracking-wider">Perjalanan Khusus &amp; Rombongan</span>
            <h3 class="text-xl sm:text-2xl font-extrabold text-white">Tidak menemukan jadwal atau rute yang Anda inginkan?</h3>
            <p class="text-xs sm:text-sm text-teal-100 leading-relaxed">
                Buat perjalanan impian Anda sendiri bersama keluarga, komunitas, atau kantor lewat layanan Custom Trip. Kami sediakan armada resmi dan penawaran biaya transparan.
            </p>
        </div>
        <div>
            <a href="{{ route('custom-trip.create') }}" class="inline-block px-6 py-3 bg-orange-500 hover:bg-orange-600 text-white font-bold text-xs sm:text-sm rounded-xl shadow transition shrink-0">
                Ajukan Custom Trip &rarr;
            </a>
        </div>
    </div>

</div>
@endsection
