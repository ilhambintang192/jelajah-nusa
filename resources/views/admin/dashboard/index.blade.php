@extends('layouts.admin', ['title' => 'Dashboard Operasional'])

@section('content')
<div class="space-y-6">
    
    <!-- Page Header & Action matching Figma Admin Dashboard -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Dashboard</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Ringkasan operasional — {{ now()->translatedFormat('F Y') }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.laporan.index') }}" class="px-5 py-2.5 bg-teal-700 hover:bg-teal-800 text-white font-bold text-xs rounded-xl shadow-xs transition">
                Buat Laporan
            </a>
        </div>
    </div>

    <!-- Highlights Banner Row matching Figma -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
        
        <!-- Wide Banner Card (8 Columns) -->
        <div class="lg:col-span-8 rounded-3xl overflow-hidden relative min-h-[220px] p-6 sm:p-8 flex flex-col justify-between text-white shadow-sm bg-gradient-to-r from-teal-900 via-teal-800 to-slate-900">
            <img src="https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1200&q=80" 
                 alt="Highlights Background" 
                 class="absolute inset-0 w-full h-full object-cover mix-blend-overlay opacity-40 pointer-events-none">
            
            <div class="relative z-10 flex items-center justify-between">
                <span class="px-3 py-1 rounded-full text-[11px] font-bold bg-white/20 backdrop-blur-md text-white border border-white/20">
                    Ringkasan harian
                </span>
                <span class="px-3 py-1 rounded-full text-[11px] font-bold bg-white/20 backdrop-blur-md text-white border border-white/20">
                    +12% dari bulan lalu
                </span>
            </div>

            <div class="relative z-10 space-y-2 mt-6">
                <h2 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight">
                    {{ $total_pemesanan }} pemesanan aktif
                </h2>
                <p class="text-xs sm:text-sm text-teal-100 max-w-xl leading-relaxed">
                    Momentum penjualan tetap stabil. Fokus verifikasi pembayaran dan keberangkatan mendatang untuk mempertahankan konversi.
                </p>
            </div>
        </div>

        <!-- 2 Mini Cards (4 Columns) matching Figma -->
        <div class="lg:col-span-4 flex flex-col gap-4">
            <!-- Mini Card 1: Bromo Sunrise -->
            <div class="flex-1 rounded-2xl overflow-hidden relative p-4 flex flex-col justify-between text-white shadow-xs min-h-[100px] bg-slate-800 group">
                <img src="https://images.unsplash.com/photo-1588668214407-6ea9a6d8c272?auto=format&fit=crop&w=600&q=80" 
                     alt="Bromo" 
                     class="absolute inset-0 w-full h-full object-cover opacity-60 group-hover:scale-105 transition-transform duration-500">
                <div class="absolute inset-0 bg-gradient-to-r from-black/80 to-transparent"></div>
                
                <div class="relative z-10 flex items-center justify-between">
                    <span class="text-sm font-extrabold text-white">Bromo Sunrise</span>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-white/90 text-slate-900">
                        Terlaris
                    </span>
                </div>
                <div class="relative z-10 text-[11px] text-slate-200">
                    74 peserta &bull; 2H1M
                </div>
            </div>

            <!-- Mini Card 2: Labuan Bajo -->
            <div class="flex-1 rounded-2xl overflow-hidden relative p-4 flex flex-col justify-between text-white shadow-xs min-h-[100px] bg-slate-800 group">
                <img src="https://images.unsplash.com/photo-1518548419970-58e3b4079ab2?auto=format&fit=crop&w=600&q=80" 
                     alt="Labuan Bajo" 
                     class="absolute inset-0 w-full h-full object-cover opacity-60 group-hover:scale-105 transition-transform duration-500">
                <div class="absolute inset-0 bg-gradient-to-r from-black/80 to-transparent"></div>
                
                <div class="relative z-10 flex items-center justify-between">
                    <span class="text-sm font-extrabold text-white">Labuan Bajo</span>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-900">
                        Trending
                    </span>
                </div>
                <div class="relative z-10 text-[11px] text-slate-200">
                    58 peserta &bull; 4H3M
                </div>
            </div>
        </div>

    </div>

    <!-- 4 Key Stat Cards matching Figma -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        
        <!-- Card 1: Total Pemesanan -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs space-y-3">
            <div class="w-9 h-9 rounded-xl bg-teal-50 text-teal-700 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-500 block">Total Pemesanan</span>
                <span class="text-2xl font-extrabold text-slate-900 font-mono block mt-0.5">{{ number_format($total_pemesanan, 0, ',', '.') }}</span>
            </div>
            <span class="text-[11px] font-bold text-emerald-600 block">
                {{ $persen_pemesanan !== null && $persen_pemesanan >= 0 ? '+' : '' }}{{ $persen_pemesanan ?? 12 }}% dari bulan lalu
            </span>
        </div>

        <!-- Card 2: Pendapatan Bulan Ini -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs space-y-3">
            <div class="w-9 h-9 rounded-xl bg-teal-50 text-teal-700 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-500 block">Pendapatan Bulan Ini</span>
                <span class="text-2xl font-extrabold text-slate-900 font-mono block mt-0.5">{{ $pendapatan_bulan_ini_format }}</span>
            </div>
            <span class="text-[11px] font-bold text-emerald-600 block">
                {{ $persen_pendapatan !== null && $persen_pendapatan >= 0 ? '+' : '' }}{{ $persen_pendapatan ?? 8 }}% dari bulan lalu
            </span>
        </div>

        <!-- Card 3: Menunggu Verifikasi -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs space-y-3">
            <div class="w-9 h-9 rounded-xl bg-teal-50 text-teal-700 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-500 block">Menunggu Verifikasi</span>
                <span class="text-2xl font-extrabold text-slate-900 font-mono block mt-0.5">{{ $jumlah_menunggu }}</span>
            </div>
            <span class="text-[11px] font-bold text-amber-600 block">
                Perlu tindakan hari ini
            </span>
        </div>

        <!-- Card 4: Keberangkatan Mendatang -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs space-y-3">
            <div class="w-9 h-9 rounded-xl bg-teal-50 text-teal-700 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-500 block">Keberangkatan Mendatang</span>
                <span class="text-2xl font-extrabold text-slate-900 font-mono block mt-0.5">{{ $keberangkatan_mendatang }}</span>
            </div>
            <span class="text-[11px] font-bold text-rose-600 block">
                {{ $keberangkatan_belum_min }} belum memenuhi minimal
            </span>
        </div>

    </div>

    <!-- Middle Row: Chart & Top Packages matching Figma -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
        
        <!-- Left: Pendapatan 6 Bulan Terakhir (7 Columns) -->
        <div class="lg:col-span-7 bg-white p-6 rounded-2xl border border-slate-200/90 shadow-xs space-y-4">
            <div>
                <h3 class="text-sm font-extrabold text-slate-900">Pendapatan 6 Bulan Terakhir</h3>
                <p class="text-[11px] text-slate-400">Dalam jutaan rupiah</p>
            </div>

            <!-- SVG Smooth Trend Chart matching Figma -->
            <div class="h-48 w-full pt-4">
                <svg viewBox="0 0 500 150" class="w-full h-full overflow-visible">
                    <defs>
                        <linearGradient id="chartGradient" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#0f766e" stop-opacity="0.25"/>
                            <stop offset="100%" stop-color="#0f766e" stop-opacity="0.0"/>
                        </linearGradient>
                    </defs>
                    <!-- Gradient fill under path -->
                    <path d="M 10,120 Q 100,105 180,115 T 320,85 T 420,95 T 480,40 L 480,140 L 10,140 Z" fill="url(#chartGradient)"/>
                    <!-- Smooth line -->
                    <path d="M 10,120 Q 100,105 180,115 T 320,85 T 420,95 T 480,40" fill="none" stroke="#0f766e" stroke-width="3" stroke-linecap="round"/>
                    <!-- Data points matching Figma -->
                    <circle cx="10" cy="120" r="4" fill="#ffffff" stroke="#0f766e" stroke-width="2"/>
                    <circle cx="100" cy="105" r="4" fill="#ffffff" stroke="#0f766e" stroke-width="2"/>
                    <circle cx="180" cy="115" r="4" fill="#ffffff" stroke="#0f766e" stroke-width="2"/>
                    <circle cx="320" cy="85" r="4" fill="#ffffff" stroke="#0f766e" stroke-width="2"/>
                    <circle cx="420" cy="95" r="4" fill="#ffffff" stroke="#0f766e" stroke-width="2"/>
                    <circle cx="480" cy="40" r="4" fill="#ffffff" stroke="#0f766e" stroke-width="2"/>
                </svg>
            </div>

            <!-- Month Labels -->
            <div class="flex justify-between text-[11px] text-slate-400 px-2 pt-2 border-t border-slate-100">
                <span>Mei</span>
                <span>Jun</span>
                <span>Jul</span>
                <span>Agu</span>
                <span>Sep</span>
                <span>Okt</span>
            </div>
        </div>

        <!-- Right: Paket Terlaris (5 Columns) matching Figma -->
        <div class="lg:col-span-5 bg-white p-6 rounded-2xl border border-slate-200/90 shadow-xs space-y-4">
            <div>
                <h3 class="text-sm font-extrabold text-slate-900">Paket Terlaris</h3>
            </div>

            <div class="space-y-4 pt-1">
                <!-- Item 1 -->
                <div class="space-y-1.5">
                    <div class="flex justify-between text-xs">
                        <span class="font-bold text-slate-800">Solo – Bali 4H3M</span>
                        <span class="text-slate-500 font-medium">92 peserta</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2">
                        <div class="bg-teal-700 h-2 rounded-full" style="width: 95%"></div>
                    </div>
                </div>

                <!-- Item 2 -->
                <div class="space-y-1.5">
                    <div class="flex justify-between text-xs">
                        <span class="font-bold text-slate-800">Bromo Sunrise 2H1M</span>
                        <span class="text-slate-500 font-medium">74 peserta</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2">
                        <div class="bg-teal-700 h-2 rounded-full" style="width: 78%"></div>
                    </div>
                </div>

                <!-- Item 3 -->
                <div class="space-y-1.5">
                    <div class="flex justify-between text-xs">
                        <span class="font-bold text-slate-800">Labuan Bajo 4H3M</span>
                        <span class="text-slate-500 font-medium">58 peserta</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2">
                        <div class="bg-teal-700 h-2 rounded-full" style="width: 62%"></div>
                    </div>
                </div>

                <!-- Item 4 -->
                <div class="space-y-1.5">
                    <div class="flex justify-between text-xs">
                        <span class="font-bold text-slate-800">Raja Ampat 5H4M</span>
                        <span class="text-slate-500 font-medium">41 peserta</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2">
                        <div class="bg-teal-700 h-2 rounded-full" style="width: 45%"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Bottom Row: Perlu Tindakan matching Figma -->
    <div class="bg-white p-6 rounded-2xl border border-slate-200/90 shadow-xs space-y-4">
        <h3 class="text-sm font-extrabold text-slate-900">Perlu Tindakan</h3>

        <div class="space-y-3">
            <!-- Row 1: Pembayaran -->
            <div class="flex items-center justify-between p-3.5 bg-slate-50 rounded-xl border border-slate-100 hover:bg-slate-100/70 transition">
                <div class="flex items-center gap-3">
                    <span class="px-2.5 py-1 rounded-md text-[10px] font-bold bg-amber-100 text-amber-900">
                        Pembayaran
                    </span>
                    <div>
                        <strong class="text-xs text-slate-900 block font-bold">{{ $jumlah_menunggu }} bukti transfer menunggu verifikasi</strong>
                        <span class="text-[11px] text-slate-400">Terlama {{ $umur_tertua_hari }} hari lalu</span>
                    </div>
                </div>
                <a href="{{ route('admin.pembayaran.index') }}" class="text-xs font-bold text-teal-700 hover:text-teal-800">
                    Buka antrean &rarr;
                </a>
            </div>

            <!-- Row 2: Custom Trip -->
            <div class="flex items-center justify-between p-3.5 bg-slate-50 rounded-xl border border-slate-100 hover:bg-slate-100/70 transition">
                <div class="flex items-center gap-3">
                    <span class="px-2.5 py-1 rounded-md text-[10px] font-bold bg-sky-100 text-sky-900">
                        Custom Trip
                    </span>
                    <div>
                        <strong class="text-xs text-slate-900 block font-bold">Pengajuan custom trip baru</strong>
                        <span class="text-[11px] text-slate-400">Raja Ampat, Dieng, Lombok</span>
                    </div>
                </div>
                <a href="{{ route('admin.custom-trip.index') }}" class="text-xs font-bold text-teal-700 hover:text-teal-800">
                    Lihat pengajuan &rarr;
                </a>
            </div>

            <!-- Row 3: Keberangkatan -->
            <div class="flex items-center justify-between p-3.5 bg-slate-50 rounded-xl border border-slate-100 hover:bg-slate-100/70 transition">
                <div class="flex items-center gap-3">
                    <span class="px-2.5 py-1 rounded-md text-[10px] font-bold bg-rose-100 text-rose-900">
                        Keberangkatan
                    </span>
                    <div>
                        <strong class="text-xs text-slate-900 block font-bold">{{ $keberangkatan_belum_min }} jadwal belum memenuhi batas minimal</strong>
                        <span class="text-[11px] text-slate-400">Tentukan konfirmasi, penundaan, atau pembatalan</span>
                    </div>
                </div>
                <a href="{{ route('admin.kelayakan.index') }}" class="text-xs font-bold text-teal-700 hover:text-teal-800">
                    Tinjau kelayakan &rarr;
                </a>
            </div>
        </div>
    </div>

</div>
@endsection
