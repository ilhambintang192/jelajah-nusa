@extends('layouts.wisatawan', ['title' => $paket->nama_paket . ' — Jelajah Nusa'])

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8" x-data="{ activeTab: 'itinerary', selectedJadwalId: '{{ $paket->jadwal->where('sisa_kuota', '>', 0)->first()->id ?? ($paket->jadwal->first()->id ?? '') }}' }">
    
    <!-- Top Hero Photo matching Figma Detail Paket -->
    <div class="h-80 sm:h-[420px] rounded-3xl overflow-hidden bg-slate-900 relative shadow-md">
        <img src="{{ $paket->foto_url }}" alt="{{ $paket->nama_paket }}" class="w-full h-full object-cover">
        <div class="absolute top-5 left-5">
            <span class="px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider backdrop-blur-md text-white bg-slate-900/60 border border-white/20">
                {{ $paket->jenis_layanan === 'open_trip' ? 'Open Trip' : 'Paket Reguler' }}
            </span>
        </div>
    </div>

    <!-- Main Content 2-Column Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left: Details & Tabs (7 Columns) -->
        <div class="lg:col-span-7 space-y-6">
            
            <!-- Title & Quick Meta -->
            <div class="space-y-3">
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                    {{ $paket->nama_paket }}
                </h1>
                
                <div class="flex flex-wrap items-center gap-4 text-xs font-medium text-slate-500">
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        {{ $paket->destinasi }}
                    </span>
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $paket->durasi_label }}
                    </span>
                    <span class="flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                        {{ $paket->jadwal->first()->kendaraan->jenis_kendaraan ?? 'Hiace Premio · 15 kursi' }}
                    </span>
                </div>
            </div>

            <!-- Tab Navigation matching Figma -->
            <div class="border-b border-slate-200">
                <nav class="flex gap-8 text-sm font-bold">
                    <button type="button" 
                            @click="activeTab = 'itinerary'" 
                            :class="activeTab === 'itinerary' ? 'border-b-2 border-teal-700 text-teal-800 pb-3' : 'text-slate-400 hover:text-slate-700 pb-3'">
                        Itinerary
                    </button>
                    <button type="button" 
                            @click="activeTab = 'fasilitas'" 
                            :class="activeTab === 'fasilitas' ? 'border-b-2 border-teal-700 text-teal-800 pb-3' : 'text-slate-400 hover:text-slate-700 pb-3'">
                        Fasilitas
                    </button>
                    <button type="button" 
                            @click="activeTab = 'meeting_point'" 
                            :class="activeTab === 'meeting_point' ? 'border-b-2 border-teal-700 text-teal-800 pb-3' : 'text-slate-400 hover:text-slate-700 pb-3'">
                        Meeting Point
                    </button>
                    <button type="button" 
                            @click="activeTab = 'deskripsi'" 
                            :class="activeTab === 'deskripsi' ? 'border-b-2 border-teal-700 text-teal-800 pb-3' : 'text-slate-400 hover:text-slate-700 pb-3'">
                        Tentang Trip
                    </button>
                </nav>
            </div>

            <!-- Tab Content 1: Itinerary matching Figma timeline pills -->
            <div x-show="activeTab === 'itinerary'" class="space-y-4">
                @if(is_array($paket->itinerary) && count($paket->itinerary) > 0)
                    @foreach($paket->itinerary as $index => $it)
                        <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-start gap-4">
                            <span class="px-3 py-1 rounded-full bg-amber-50 text-amber-900 border border-amber-200 font-bold text-xs shrink-0 mt-0.5">
                                Hari {{ $it['hari'] ?? ($index + 1) }}
                            </span>
                            <div class="space-y-1">
                                <h4 class="font-extrabold text-slate-900 text-sm leading-tight">{{ $it['judul'] ?? 'Agenda Perjalanan' }}</h4>
                                <p class="text-xs text-slate-500 leading-relaxed">{{ $it['aktivitas'] ?? '' }}</p>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="p-5 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex items-start gap-4">
                        <span class="px-3 py-1 rounded-full bg-amber-50 text-amber-900 border border-amber-200 font-bold text-xs shrink-0 mt-0.5">
                            Hari 1
                        </span>
                        <div class="space-y-1">
                            <h4 class="font-extrabold text-slate-900 text-sm">Penjemputan &amp; Eksplorasi Destinasi</h4>
                            <p class="text-xs text-slate-500 leading-relaxed">Berkumpul di meeting point sesuai jadwal, perjalanan wisata terpandu, dan istirahat di hotel pilihan.</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Tab Content 2: Fasilitas -->
            <div x-show="activeTab === 'fasilitas'" class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs space-y-6" style="display: none;">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs">
                    <div class="space-y-2">
                        <span class="font-bold text-emerald-700 uppercase tracking-wider block text-[11px] pb-1 border-b border-emerald-100">
                            &check; Fasilitas Termasuk (Included):
                        </span>
                        <div class="text-slate-700 leading-relaxed whitespace-pre-line pl-1">{{ $paket->fasilitas }}</div>
                    </div>

                    @if($paket->fasilitas_tidak_termasuk)
                        <div class="space-y-2">
                            <span class="font-bold text-rose-700 uppercase tracking-wider block text-[11px] pb-1 border-b border-rose-100">
                                &times; Tidak Termasuk (Excluded):
                            </span>
                            <div class="text-slate-700 leading-relaxed whitespace-pre-line pl-1">{{ $paket->fasilitas_tidak_termasuk }}</div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Tab Content 3: Meeting Point -->
            <div x-show="activeTab === 'meeting_point'" class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs space-y-4" style="display: none;">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="text-sm font-extrabold text-slate-900">Titik Kumpul Penjemputan (Meeting Point)</h3>
                    <span class="text-xs font-bold text-teal-800 bg-teal-50 px-3 py-1 rounded-full">
                        Pukul {{ substr($paket->jam_kumpul, 0, 5) }} WIB
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($paket->meeting_points as $index => $mp)
                        <div class="flex items-start gap-3 p-3.5 bg-slate-50 border border-slate-200/80 rounded-2xl">
                            <span class="w-6 h-6 rounded-full bg-teal-700 text-white flex items-center justify-center text-xs font-bold shrink-0 mt-0.5">
                                {{ $index + 1 }}
                            </span>
                            <div>
                                <strong class="text-xs text-slate-900 block font-bold leading-tight">{{ $mp }}</strong>
                                <span class="text-[10px] text-slate-400">Titik Kumpul Resmi Agen Jelajah Nusa</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Tab Content 4: Tentang Trip -->
            <div x-show="activeTab === 'deskripsi'" class="bg-white p-6 rounded-3xl border border-slate-200/80 shadow-xs space-y-4" style="display: none;">
                <h3 class="text-base font-extrabold text-slate-900">Tentang Paket Wisata Ini</h3>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed whitespace-pre-line">{{ $paket->deskripsi ?: 'Nikmati perjalanan mengagumkan dengan paket wisata eksklusif yang dirancang untuk kenyamanan dan pengalaman liburan terbaik Anda.' }}</p>
            </div>

        </div>

        <!-- Right: Sticky Booking Card matching Figma Detail Paket (5 Columns) -->
        <div class="lg:col-span-5 space-y-6 lg:sticky lg:top-24">
            
            <div class="bg-white p-6 sm:p-7 rounded-3xl border border-slate-200/90 shadow-lg space-y-6">
                
                <!-- Price Row & Status Pill -->
                <div class="flex items-start justify-between">
                    <div>
                        <span class="text-xs text-slate-400 font-medium block">Mulai dari</span>
                        <div class="flex items-baseline gap-1 mt-0.5">
                            <span class="text-2xl sm:text-3xl font-extrabold text-teal-800 font-mono">{{ $paket->harga_format }}</span>
                        </div>
                        <span class="text-[11px] text-slate-400">per peserta</span>
                    </div>

                    @php
                        $firstJdw = $paket->jadwal->where('sisa_kuota', '>', 0)->first();
                        $isLayak = $firstJdw ? ($firstJdw->peserta_aktif >= $firstJdw->min_peserta) : true;
                    @endphp
                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $isLayak ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-amber-50 text-amber-700 border border-amber-100' }}">
                        {{ $isLayak ? 'Layak dijalankan' : 'Menunggu kuota' }}
                    </span>
                </div>

                <!-- Schedule Selector -->
                <div class="space-y-3 pt-2">
                    <label class="block text-xs font-bold text-slate-800">Pilih jadwal keberangkatan</label>

                    <div class="space-y-2.5">
                        @forelse($paket->jadwal as $jdw)
                            <label class="block cursor-pointer">
                                <div class="p-3.5 rounded-2xl border transition-all flex items-center justify-between"
                                     :class="selectedJadwalId == '{{ $jdw->id }}' ? 'border-teal-700 bg-teal-50/20 ring-1 ring-teal-700' : 'border-slate-200 hover:border-slate-300 bg-white'">
                                    <div class="flex items-center gap-3">
                                        <input type="radio" 
                                               name="jadwal_select" 
                                               value="{{ $jdw->id }}" 
                                               x-model="selectedJadwalId" 
                                               class="w-4 h-4 text-teal-700 focus:ring-teal-500 border-slate-300">
                                        <div>
                                            <span class="text-xs font-bold text-slate-900 block">
                                                {{ $jdw->tanggal_berangkat->translatedFormat('d M Y') }}
                                            </span>
                                            <span class="text-[10px] text-slate-400">
                                                {{ $jdw->kendaraan ? $jdw->kendaraan->jenis_kendaraan : 'Armada Pariwisata' }}
                                            </span>
                                        </div>
                                    </div>
                                    <span class="text-xs font-semibold {{ $jdw->sisa_kuota <= 3 ? 'text-rose-600' : 'text-slate-600' }}">
                                        {{ $jdw->sisa_kuota <= 0 ? 'Penuh' : "Sisa {$jdw->sisa_kuota} kursi" }}
                                    </span>
                                </div>
                            </label>
                        @empty
                            <div class="p-4 bg-slate-50 rounded-2xl text-center text-xs text-slate-400">
                                Belum ada jadwal aktif saat ini.
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Open Trip Progress Card matching Figma -->
                @if($firstJdw)
                    @php
                        $aktif = $firstJdw->peserta_aktif;
                        $min = $firstJdw->min_peserta;
                        $max = $firstJdw->kuota;
                        $pct = min(100, round(($aktif / max(1, $max)) * 100));
                    @endphp
                    <div class="p-4 rounded-2xl bg-emerald-50/80 border border-emerald-100 space-y-2">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-bold text-emerald-800">Layak dijalankan</span>
                            <span class="text-[11px] text-slate-600 font-medium">{{ $aktif }}/{{ $max }} peserta terdaftar · minimal {{ $min }}</span>
                        </div>
                        <div class="w-full bg-white rounded-full h-2 overflow-hidden border border-emerald-200/60">
                            <div class="bg-teal-600 h-2 rounded-full transition-all" style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                @endif

                <!-- Big Orange Action Button matching Figma -->
                <div class="space-y-2 pt-2">
                    <template x-if="selectedJadwalId">
                        <a :href="'{{ url('pemesanan') }}?jadwal_id=' + selectedJadwalId" 
                           class="w-full block py-3.5 bg-orange-500 hover:bg-orange-600 text-white font-extrabold text-sm text-center rounded-xl shadow-md transition">
                            Pesan Sekarang
                        </a>
                    </template>
                    <template x-if="!selectedJadwalId">
                        <a href="{{ route('custom-trip.create') }}" 
                           class="w-full block py-3.5 bg-orange-500 hover:bg-orange-600 text-white font-extrabold text-sm text-center rounded-xl shadow-md transition">
                            Ajukan Custom Trip
                        </a>
                    </template>
                    <p class="text-[11px] text-center text-slate-400">
                        Ringkasan &amp; total biaya ditampilkan sebelum konfirmasi
                    </p>
                </div>

            </div>

        </div>

    </div>
</div>
@endsection
