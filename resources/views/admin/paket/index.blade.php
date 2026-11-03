@extends('layouts.admin', ['title' => 'Kelola Paket Wisata'])

@section('content')
<div class="space-y-6">
    
    <!-- Title & Subtitle matching Figma Admin Kelola Paket -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Kelola Paket Wisata</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Tambah, ubah, hapus, dan cari paket beserta jadwal keberangkatan</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.paket.create') }}" class="px-5 py-2.5 bg-teal-700 hover:bg-teal-800 text-white font-bold text-xs rounded-xl shadow-xs transition flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Paket
            </a>
        </div>
    </div>

    <!-- Card 1: Daftar Paket matching Figma -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <h2 class="text-base font-extrabold text-slate-900">Daftar Paket ({{ $paketList->count() }})</h2>
            
            <form action="{{ route('admin.paket.index') }}" method="GET" class="relative w-full sm:w-72">
                <input type="text" 
                       name="cari" 
                       value="{{ $search ?? request('cari', '') }}" 
                       placeholder="Cari nama paket atau destinasi..." 
                       class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-teal-500/20 text-slate-700">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </form>
        </div>

        <div class="divide-y divide-slate-100">
            @forelse($paketList as $p)
                @php
                    $isSelected = $selectedPaket && $selectedPaket->id === $p->id;
                @endphp
                <div class="p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-slate-50/80 transition {{ $isSelected ? 'bg-teal-50/20 ring-1 ring-inset ring-teal-600/30' : '' }}">
                    
                    <!-- Left: Thumbnail & Info -->
                    <div class="flex items-center gap-4 flex-1">
                        <a href="{{ route('admin.paket.index', ['selected' => $p->id, 'cari' => $search ?? request('cari', '')]) }}" class="shrink-0">
                            <img src="{{ $p->foto_url }}" alt="{{ $p->nama_paket }}" class="w-24 h-16 rounded-xl object-cover border border-slate-200 shadow-xs">
                        </a>

                        <div class="space-y-1">
                            <a href="{{ route('admin.paket.index', ['selected' => $p->id, 'cari' => $search ?? request('cari', '')]) }}" class="group">
                                <h3 class="text-sm font-extrabold text-slate-900 group-hover:text-teal-700 transition leading-snug">
                                    {{ $p->nama_paket }}
                                </h3>
                            </a>
                            <p class="text-xs text-slate-500">{{ $p->destinasi }}</p>
                            <div class="flex items-center gap-2 pt-0.5">
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase {{ $p->jenis_layanan === 'open_trip' ? 'bg-teal-50 text-teal-800' : 'bg-emerald-50 text-emerald-700' }}">
                                    {{ $p->jenis_layanan === 'open_trip' ? 'Open Trip' : 'Paket Reguler' }}
                                </span>
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 text-slate-600">
                                    {{ $p->durasi_label }}
                                </span>
                                @if(!$p->status_aktif)
                                    <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-rose-50 text-rose-600">Nonaktif</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Right: Price & Actions matching Figma -->
                    <div class="flex items-center justify-between sm:justify-end gap-6 pt-2 sm:pt-0 border-t sm:border-t-0 border-slate-100">
                        <div class="text-left sm:text-right">
                            <span class="block text-sm sm:text-base font-extrabold text-slate-900 font-mono">{{ $p->harga_format }}</span>
                            <span class="block text-[11px] text-slate-400">Mulai dari</span>
                        </div>

                        <div class="flex items-center gap-3 text-xs font-semibold">
                            <a href="{{ route('admin.paket.index', ['selected' => $p->id, 'cari' => $search ?? request('cari', '')]) }}" class="text-slate-600 hover:text-teal-700">
                                Jadwal ({{ $p->jadwal_count }})
                            </a>
                            <span class="text-slate-200">|</span>
                            <a href="{{ route('admin.paket.edit', $p->id) }}" class="text-teal-700 hover:text-teal-800 hover:underline">
                                Ubah
                            </a>
                            <span class="text-slate-200">|</span>
                            <form action="{{ route('admin.paket.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus data paket ini? Tindakan ini tidak dapat dibatalkan.');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-600 hover:text-rose-700 hover:underline">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            @empty
                <div class="p-8 text-center text-xs text-slate-400">
                    Tidak ada paket wisata ditemukan.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Card 2: Jadwal Keberangkatan matching Figma -->
    @if($selectedPaket)
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden" x-data="{ showAddJadwal: false }">
            <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <h2 class="text-base font-extrabold text-slate-900">
                        Jadwal Keberangkatan — {{ $selectedPaket->nama_paket }}
                    </h2>
                    <p class="text-xs text-slate-400 mt-0.5">Destinasi: {{ $selectedPaket->destinasi }} &bull; Meeting Point: {{ $selectedPaket->meeting_point }}</p>
                </div>
                <button @click="showAddJadwal = !showAddJadwal" class="px-4 py-2 bg-teal-700 hover:bg-teal-800 text-white font-bold text-xs rounded-xl shadow-xs transition flex items-center gap-1.5 shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Jadwal
                </button>
            </div>

            <!-- Form Tambah Jadwal Toggle -->
            <div x-show="showAddJadwal" x-transition class="p-5 bg-slate-50 border-b border-slate-200" style="display: none;">
                <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider mb-3">Tambah Jadwal Keberangkatan Baru</h4>
                <form action="{{ route('admin.jadwal.store', $selectedPaket->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 text-xs">
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Tanggal Berangkat</label>
                            <input type="date" name="tanggal_berangkat" required min="{{ now()->addDay()->toDateString() }}" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Kuota Kursi</label>
                            <input type="number" name="kuota" required min="1" placeholder="30" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Min. Peserta</label>
                            <input type="number" name="min_peserta" required min="1" placeholder="15" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl">
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Pilih Armada Kendaraan</label>
                            <select name="kendaraan_id" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl">
                                <option value="">— Belum Ditetapkan —</option>
                                @foreach(($kendaraanTersedia ?? \App\Models\Kendaraan::tersedia()->get()) as $k)
                                    <option value="{{ $k->id }}">{{ $k->jenis_kendaraan }} ({{ $k->kapasitas }} kursi)</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block font-bold text-slate-700 mb-1">Biaya Operasional (Rp)</label>
                            <input type="number" name="biaya_operasional" placeholder="0" class="w-full px-3 py-2 bg-white border border-slate-200 rounded-xl">
                        </div>
                    </div>
                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" @click="showAddJadwal = false" class="px-3 py-1.5 bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl">Batal</button>
                        <button type="submit" class="px-4 py-1.5 bg-teal-700 hover:bg-teal-800 text-white text-xs font-bold rounded-xl shadow-xs">Simpan Jadwal</button>
                    </div>
                </form>
            </div>

            <!-- Table Jadwal matching Figma -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 border-b border-slate-100 text-slate-400 font-bold uppercase tracking-wider">
                        <tr>
                            <th class="py-3 px-5">Tanggal</th>
                            <th class="py-3 px-4">Kuota</th>
                            <th class="py-3 px-4">Sisa Kuota</th>
                            <th class="py-3 px-4">Min. Peserta</th>
                            <th class="py-3 px-5">Kendaraan</th>
                            <th class="py-3 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @forelse($selectedPaket->jadwal as $j)
                            <tr class="hover:bg-slate-50/60">
                                <td class="py-3.5 px-5 font-bold text-slate-900">
                                    {{ $j->tanggal_berangkat->translatedFormat('d M Y') }}
                                </td>
                                <td class="py-3.5 px-4 font-mono">{{ $j->kuota }}</td>
                                <td class="py-3.5 px-4 font-mono">
                                    <span class="font-bold {{ $j->sisa_kuota <= 3 ? 'text-rose-600' : 'text-slate-800' }}">
                                        {{ $j->sisa_kuota }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 font-mono">{{ $j->min_peserta }}</td>
                                <td class="py-3.5 px-5 text-slate-600">
                                    {{ $j->kendaraan ? $j->kendaraan->jenis_kendaraan : 'Belum ditetapkan' }}
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <form action="{{ route('admin.jadwal.destroy', $j->id) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-600 hover:underline font-bold text-xs">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-slate-400">
                                    Belum ada jadwal keberangkatan untuk paket ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

</div>
@endsection
