@extends('layouts.admin', ['title' => 'Kelayakan Keberangkatan'])

@section('content')
<div class="space-y-6">
    
    <!-- Title & Subtitle matching Figma -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Kelayakan Keberangkatan</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Peserta terdaftar vs batas minimal vs kapasitas — garis hitam = batas minimal</p>
        </div>
    </div>

    <!-- Top Card: Status per Jadwal matching Figma -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h2 class="text-base font-extrabold text-slate-900">Status per Jadwal</h2>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-700">
                <span class="w-2.5 h-1 bg-black rounded-sm"></span>
                Garis hitam = batas minimal
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 border-b border-slate-100 text-slate-400 font-bold uppercase tracking-wider">
                    <tr>
                        <th class="py-3 px-5">Jadwal</th>
                        <th class="py-3 px-4 w-56">Peserta / Kapasitas</th>
                        <th class="py-3 px-4 text-center">Min &bull; Kap</th>
                        <th class="py-3 px-4">Estimasi vs Biaya</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($evaluasiJadwal as $ev)
                        @php
                            $j = $ev['jadwal'];
                            $isSelected = $selectedEvaluasi && $selectedEvaluasi['jadwal']->id === $j->id;
                            $isBelum = $ev['status'] === 'Belum memenuhi minimal';
                        @endphp
                        <tr class="hover:bg-slate-50/70 transition {{ $isSelected ? 'bg-teal-50/20' : ($isBelum ? 'bg-rose-50/20' : '') }}">
                            <!-- Jadwal -->
                            <td class="py-3.5 px-5">
                                <span class="block font-bold text-slate-900">{{ $j->paket->nama_paket ?? 'Trip' }}</span>
                                <span class="block text-[11px] text-slate-400 font-normal">{{ $j->tanggal_berangkat->translatedFormat('d M') }}</span>
                            </td>

                            <!-- Peserta / Kapasitas with Black Minimal Line matching Figma -->
                            <td class="py-3.5 px-4">
                                <div class="flex items-center justify-between text-[11px] mb-1">
                                    <span class="font-bold {{ $isBelum ? 'text-rose-600' : 'text-slate-900' }}">{{ $ev['peserta_aktif'] }} peserta</span>
                                    <span class="text-slate-400">dari {{ $ev['kuota'] }}</span>
                                </div>
                                <div class="w-full bg-slate-100 h-2.5 rounded-full relative overflow-visible">
                                    <div class="h-2.5 rounded-full transition-all {{ $isBelum ? 'bg-rose-600' : 'bg-teal-700' }}" 
                                         style="width: {{ $ev['persen_kapasitas'] }}%"></div>
                                    
                                    <!-- Black Minimal Vertical Marker Line -->
                                    @if($ev['persen_min'] > 0 && $ev['persen_min'] <= 100)
                                        <div class="absolute top-[-3px] bottom-[-3px] w-0.5 bg-black z-10" 
                                             style="left: {{ $ev['persen_min'] }}%" 
                                             title="Batas Minimal: {{ $ev['min_peserta'] }} peserta"></div>
                                    @endif
                                </div>
                            </td>

                            <!-- Min / Kap -->
                            <td class="py-3.5 px-4 text-center font-mono">
                                <span class="font-bold text-slate-900">{{ $ev['min_peserta'] }}</span>
                                <span class="text-slate-400">&bull;</span>
                                <span class="text-slate-500">{{ $ev['kuota'] }}</span>
                            </td>

                            <!-- Estimasi vs Biaya -->
                            <td class="py-3.5 px-4 font-mono text-[11px]">
                                <span class="font-bold {{ $ev['untung_rugi'] >= 0 ? 'text-emerald-700' : 'text-rose-700' }}">
                                    Rp {{ number_format($ev['estimasi_pendapatan'] / 1000000, 1) }} jt
                                </span>
                                <span class="text-slate-400">{{ $ev['untung_rugi'] >= 0 ? '>' : '<' }}</span>
                                <span class="text-slate-500">Rp {{ number_format($ev['biaya_operasional'] / 1000000, 1) }} jt</span>
                            </td>

                            <!-- Status Pill -->
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase {{ $ev['status'] === 'Penuh' ? 'bg-amber-100 text-amber-900' : ($ev['status'] === 'Layak dijalankan' ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-100 text-rose-800') }}">
                                    {{ $ev['status'] }}
                                </span>
                            </td>

                            <!-- Aksi -->
                            <td class="py-3.5 px-4 text-right">
                                <a href="{{ route('admin.kelayakan.index', ['selected' => $j->id]) }}" class="px-3 py-1.5 rounded-lg border border-slate-200 hover:border-teal-700 hover:text-teal-700 text-slate-700 text-xs font-bold transition">
                                    Tinjau
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">
                                Belum ada jadwal keberangkatan aktif.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bottom Row: Tindakan Jadwal & Highlights matching Figma -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-start">
        
        <!-- Left: Action Panel (7 Columns) -->
        <div class="lg:col-span-7 bg-white p-6 rounded-2xl border border-slate-200/90 shadow-xs space-y-5">
            @if($selectedEvaluasi)
                @php
                    $selJ = $selectedEvaluasi['jadwal'];
                    $isBelumSel = $selectedEvaluasi['status'] === 'Belum memenuhi minimal';
                @endphp

                <!-- Action Header Alert Box -->
                <div class="p-4 rounded-xl flex items-start gap-3 {{ $isBelumSel ? 'bg-rose-50 text-rose-900 border border-rose-100' : 'bg-emerald-50 text-emerald-900 border border-emerald-100' }}">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0 {{ $isBelumSel ? 'bg-rose-600 text-white' : 'bg-emerald-600 text-white' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-extrabold">
                            Tindakan — {{ $selJ->paket->nama_paket ?? 'Trip' }} &bull; {{ $selJ->tanggal_berangkat->translatedFormat('d M') }} ({{ $selectedEvaluasi['peserta_aktif'] }}/{{ $selectedEvaluasi['min_peserta'] }} peserta)
                        </h3>
                        <p class="text-xs mt-0.5 opacity-90">{{ $selectedEvaluasi['status'] }}</p>
                    </div>
                </div>

                <!-- Package Image Banner matching Figma -->
                @if($selJ->paket && $selJ->paket->foto_url)
                    <div class="h-36 rounded-xl overflow-hidden relative bg-slate-900">
                        <img src="{{ $selJ->paket->foto_url }}" alt="{{ $selJ->paket->nama_paket }}" class="w-full h-full object-cover opacity-80">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 to-transparent p-4 flex flex-col justify-end text-white">
                            <span class="px-2.5 py-0.5 rounded-md text-[10px] font-bold bg-white/20 backdrop-blur text-white w-fit mb-1">{{ $selJ->paket->nama_paket }}</span>
                            <span class="text-xs text-slate-300 font-mono">{{ $selJ->tanggal_berangkat->translatedFormat('d F Y') }}</span>
                        </div>
                    </div>
                @endif

                <!-- Form Keputusan Status -->
                <form action="{{ route('admin.kelayakan.keputusan', $selJ->id) }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-700">Pilih tindakan untuk jadwal ini:</label>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <button type="submit" 
                                    name="status_keberangkatan" 
                                    value="dikonfirmasi" 
                                    class="py-3 px-4 bg-teal-700 hover:bg-teal-800 text-white font-bold text-xs rounded-xl shadow-xs transition text-center">
                                Konfirmasi Keberangkatan
                            </button>

                            <button type="submit" 
                                    name="status_keberangkatan" 
                                    value="ditunda" 
                                    class="py-3 px-4 bg-white border border-teal-700 text-teal-700 hover:bg-teal-50 font-bold text-xs rounded-xl transition text-center">
                                Tunda
                            </button>

                            <button type="submit" 
                                    name="status_keberangkatan" 
                                    value="dibatalkan" 
                                    class="py-3 px-4 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-xl shadow-xs transition text-center">
                                Batalkan
                            </button>
                        </div>
                    </div>

                    <!-- Input Biaya Operasional Opsional -->
                    <div class="pt-2">
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Perbarui Biaya Operasional (Rp)</label>
                        <input type="number" name="biaya_operasional" value="{{ old('biaya_operasional', (int)$selJ->biaya_operasional) }}" min="0" class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono text-slate-800">
                    </div>

                    <!-- Yellow Info Alert matching Figma -->
                    <div class="p-3 bg-amber-50 border border-amber-200/80 rounded-xl text-xs text-amber-900 flex items-center gap-2">
                        <svg class="w-4 h-4 text-amber-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                        <span>Perubahan status akan diinformasikan ke seluruh peserta terdaftar.</span>
                    </div>
                </form>

            @else
                <div class="py-16 text-center text-slate-400 text-xs">
                    Pilih salah satu jadwal di atas untuk meninjau dan mengambil tindakan.
                </div>
            @endif
        </div>

        <!-- Right: Other Schedules Preview Cards (5 Columns) matching Figma -->
        <div class="lg:col-span-5 space-y-4">
            @foreach($evaluasiJadwal->take(2) as $preview)
                @php
                    $pj = $preview['jadwal'];
                @endphp
                <div class="rounded-2xl overflow-hidden relative p-4 flex flex-col justify-between text-white shadow-xs min-h-[110px] bg-slate-800 group">
                    <img src="{{ $pj->paket->foto_url ?? 'https://images.unsplash.com/photo-1588668214407-6ea9a6d8c272?auto=format&fit=crop&w=600&q=80' }}" 
                         alt="{{ $pj->paket->nama_paket ?? 'Trip' }}" 
                         class="absolute inset-0 w-full h-full object-cover opacity-60 group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-r from-black/80 to-transparent"></div>
                    
                    <div class="relative z-10 flex items-center justify-between">
                        <span class="text-sm font-extrabold text-white">{{ $pj->paket->nama_paket ?? 'Trip' }}</span>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $preview['status'] === 'Penuh' ? 'bg-amber-100 text-amber-900' : 'bg-emerald-100 text-emerald-900' }}">
                            {{ $preview['status'] }}
                        </span>
                    </div>
                    <div class="relative z-10 text-[11px] text-slate-200">
                        {{ $preview['peserta_aktif'] }} peserta &bull; {{ $pj->tanggal_berangkat->translatedFormat('d M') }}
                    </div>
                </div>
            @endforeach
        </div>

    </div>

</div>
@endsection
