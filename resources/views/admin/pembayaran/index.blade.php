@extends('layouts.admin', ['title' => 'Verifikasi Pembayaran'])

@section('content')
<div class="space-y-6" x-data="{ tolakModal: false, tolakId: null }">
    
    <!-- Title & Top Action Buttons matching Figma -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Verifikasi Pembayaran</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Periksa bukti transfer dan kesesuaian nominal</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="flex items-center gap-1.5 bg-white border border-slate-200 rounded-xl p-1 text-xs font-semibold">
                <a href="{{ route('admin.pembayaran.index', ['status' => 'menunggu']) }}" class="px-3 py-1.5 rounded-lg transition {{ $status === 'menunggu' ? 'bg-teal-700 text-white font-bold' : 'text-slate-600 hover:text-slate-900' }}">
                    Menunggu ({{ \App\Models\Pembayaran::where('status', 'menunggu')->count() }})
                </a>
                <a href="{{ route('admin.pembayaran.index', ['status' => 'diverifikasi']) }}" class="px-3 py-1.5 rounded-lg transition {{ $status === 'diverifikasi' ? 'bg-teal-700 text-white font-bold' : 'text-slate-600 hover:text-slate-900' }}">
                    Diverifikasi
                </a>
                <a href="{{ route('admin.pembayaran.index', ['status' => 'ditolak']) }}" class="px-3 py-1.5 rounded-lg transition {{ $status === 'ditolak' ? 'bg-teal-700 text-white font-bold' : 'text-slate-600 hover:text-slate-900' }}">
                    Ditolak
                </a>
            </div>
            <a href="{{ route('admin.laporan.ekspor-excel') }}" class="px-4 py-2 bg-teal-700 hover:bg-teal-800 text-white font-bold text-xs rounded-xl shadow-xs transition">
                Export Laporan
            </a>
        </div>
    </div>

    <!-- 3-Column Verification Layout matching Figma Admin Verifikasi Pembayaran -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-start">
        
        <!-- Column 1 (Left): Antrian (4 Cols) -->
        <div class="lg:col-span-4 bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-sm font-extrabold text-slate-900">Antrian ({{ $antrean->count() }})</h3>
            </div>

            <div class="space-y-3 max-h-[72vh] overflow-y-auto pr-1">
                @forelse($antrean as $bayar)
                    @php
                        $isSelected = $selectedPembayaran && $selectedPembayaran->id === $bayar->id;
                    @endphp
                    <a href="{{ route('admin.pembayaran.index', ['selected' => $bayar->id, 'status' => $status]) }}" 
                       class="block p-4 rounded-xl border transition-all {{ $isSelected ? 'border-2 border-teal-700 bg-teal-50/20 ring-1 ring-teal-700/20' : 'border-slate-200 bg-white hover:border-slate-300' }}">
                        <div class="flex items-start justify-between gap-2 mb-1">
                            <span class="text-xs font-extrabold text-slate-900 truncate">{{ $bayar->pemesanan->user->nama_lengkap ?? 'Wisatawan' }}</span>
                            <span class="text-xs font-extrabold text-teal-800 font-mono">{{ $bayar->nominal_format }}</span>
                        </div>
                        <p class="text-[11px] text-slate-500 truncate mb-2">{{ $bayar->pemesanan->judul_trip }}</p>
                        <div class="flex items-center justify-between text-[11px] text-slate-400">
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $bayar->tanggal_bayar->translatedFormat('d M Y') }}
                            </span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $bayar->status === 'diverifikasi' ? 'bg-emerald-50 text-emerald-700' : ($bayar->status === 'menunggu' ? 'bg-amber-50 text-amber-700' : 'bg-rose-50 text-rose-700') }}">
                                {{ $bayar->status }}
                            </span>
                        </div>
                    </a>
                @empty
                    <div class="py-12 text-center text-slate-400 text-xs">
                        Tidak ada antrean pembayaran dalam status ini.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Column 2 (Center): Bukti Transfer (4 Cols) matching Figma -->
        <div class="lg:col-span-4 bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs space-y-4">
            <div class="pb-3 border-b border-slate-100">
                <h3 class="text-sm font-extrabold text-slate-900">Bukti Transfer</h3>
            </div>

            @if($selectedPembayaran)
                <!-- Package Thumbnail Card Header -->
                @php
                    $pktFoto = $selectedPembayaran->pemesanan->jadwal ? $selectedPembayaran->pemesanan->jadwal->paket->foto_url : null;
                @endphp
                @if($pktFoto)
                    <div class="h-28 rounded-xl overflow-hidden relative bg-slate-900">
                        <img src="{{ $pktFoto }}" alt="Destinasi" class="w-full h-full object-cover opacity-80">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 to-transparent p-3 flex flex-col justify-end text-white">
                            <span class="text-xs font-extrabold text-white leading-tight">{{ $selectedPembayaran->pemesanan->judul_trip }}</span>
                            <span class="text-[10px] text-slate-300">Destinasi Tujuan</span>
                        </div>
                    </div>
                @endif

                <!-- Uploaded Proof Container -->
                <div class="bg-slate-50 rounded-xl border border-slate-200/90 p-4 space-y-3">
                    <div class="bg-white rounded-lg p-2 border border-slate-200 flex items-center justify-center min-h-[220px]">
                        <img src="{{ route('dokumen.bukti', $selectedPembayaran->id) }}" 
                             alt="Bukti Transfer" 
                             class="max-h-[300px] w-auto object-contain rounded">
                    </div>
                    
                    <div class="text-center space-y-1">
                        <div class="flex items-center justify-center gap-1.5 text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            <span class="text-xs font-mono font-medium text-slate-600 truncate max-w-[200px]">{{ basename($selectedPembayaran->bukti_transfer) }}</span>
                        </div>
                        <a href="{{ route('dokumen.bukti', $selectedPembayaran->id) }}" target="_blank" class="text-[11px] text-teal-700 hover:underline font-semibold block">
                            Pratinjau gambar bukti transfer &rarr;
                        </a>
                    </div>
                </div>
            @else
                <div class="py-24 text-center text-slate-400 text-xs">
                    Pilih salah satu antrean untuk memeriksa bukti transfer.
                </div>
            @endif
        </div>

        <!-- Column 3 (Right): Detail Pesanan & Action (4 Cols) matching Figma -->
        <div class="lg:col-span-4 bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs space-y-5">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-sm font-extrabold text-slate-900">Detail Pesanan</h3>
                @if($selectedPembayaran)
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $selectedPembayaran->status === 'diverifikasi' ? 'bg-emerald-50 text-emerald-700' : ($selectedPembayaran->status === 'menunggu' ? 'bg-amber-50 text-amber-700' : 'bg-rose-50 text-rose-700') }}">
                        {{ $selectedPembayaran->status === 'menunggu' ? 'Menunggu verifikasi' : $selectedPembayaran->status }}
                    </span>
                @endif
            </div>

            @if($selectedPembayaran)
                <!-- Key-Value Table matching Figma -->
                <div class="divide-y divide-slate-100 text-xs">
                    <div class="py-2.5 flex justify-between">
                        <span class="text-slate-400">Kode pesanan</span>
                        <strong class="font-mono text-slate-800">{{ $selectedPembayaran->pemesanan->display_kode }}</strong>
                    </div>
                    <div class="py-2.5 flex justify-between">
                        <span class="text-slate-400">Paket</span>
                        <strong class="text-slate-800 truncate max-w-[180px]">{{ $selectedPembayaran->pemesanan->judul_trip }}</strong>
                    </div>
                    <div class="py-2.5 flex justify-between">
                        <span class="text-slate-400">Jadwal</span>
                        <strong class="text-slate-800">{{ $selectedPembayaran->pemesanan->tanggal_berangkat ? $selectedPembayaran->pemesanan->tanggal_berangkat->translatedFormat('d M Y') : '—' }}</strong>
                    </div>
                    <div class="py-2.5 flex justify-between">
                        <span class="text-slate-400">Peserta</span>
                        <strong class="text-slate-800">{{ $selectedPembayaran->pemesanan->jumlah_peserta }} orang</strong>
                    </div>
                    <div class="py-2.5 flex justify-between">
                        <span class="text-slate-400">Tanggal unggah</span>
                        <strong class="text-slate-800">{{ $selectedPembayaran->created_at->translatedFormat('d M Y') }}</strong>
                    </div>
                </div>

                <!-- Perbandingan Nominal Box matching Figma -->
                @php
                    $isSesuai = (float)$selectedPembayaran->nominal >= (float)$selectedPembayaran->pemesanan->total_biaya;
                @endphp
                <div class="p-4 rounded-xl bg-emerald-50/70 border border-emerald-100 space-y-2.5 text-xs">
                    <span class="font-bold text-emerald-950 block">Perbandingan nominal</span>
                    <div class="flex justify-between text-slate-600">
                        <span>Tagihan</span>
                        <strong class="font-mono text-slate-900">{{ $selectedPembayaran->pemesanan->total_biaya_format }}</strong>
                    </div>
                    <div class="flex justify-between text-slate-600">
                        <span>Transfer</span>
                        <strong class="font-mono text-slate-900">{{ $selectedPembayaran->nominal_format }}</strong>
                    </div>
                    <div class="pt-2 border-t border-emerald-200/60 flex items-center gap-1.5 text-emerald-700 font-bold">
                        <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        <span>{{ $isSesuai ? 'Nominal sesuai' : 'Ada selisih nominal' }}</span>
                    </div>
                </div>

                <!-- Action Buttons: Tolak (White) and Verifikasi (Teal) matching Figma -->
                @if($selectedPembayaran->status === 'menunggu')
                    <div class="pt-2 flex items-center justify-end gap-3">
                        <button type="button" 
                                @click="tolakModal = true; tolakId = {{ $selectedPembayaran->id }}" 
                                class="px-5 py-2.5 bg-white border border-teal-700 text-teal-700 hover:bg-slate-50 text-xs font-bold rounded-xl transition">
                            Tolak
                        </button>

                        <form action="{{ route('admin.pembayaran.verifikasi', $selectedPembayaran->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="px-6 py-2.5 bg-teal-700 hover:bg-teal-800 text-white text-xs font-bold rounded-xl shadow-xs transition">
                                Verifikasi
                            </button>
                        </form>
                    </div>
                @endif
            @else
                <div class="py-24 text-center text-slate-400 text-xs">
                    Pilih salah satu antrean untuk melihat rincian pembayaran.
                </div>
            @endif
        </div>

    </div>

    <!-- Modal Tolak Pembayaran (Frame 107:1096, BR-06, US-09, T-09) -->
    <div x-show="tolakModal" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60" style="display: none;">
        <div @click.away="tolakModal = false" class="bg-white max-w-md w-full p-6 sm:p-8 rounded-3xl shadow-2xl space-y-5">
            <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center mx-auto">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            
            <div class="text-center">
                <h3 class="text-lg font-extrabold text-slate-900">Tolak Pembayaran Ini?</h3>
                <p class="text-xs text-slate-500 mt-1">Wisatawan akan menerima pemberitahuan dan alasan penolakan ini, serta diberikan kesempatan mengunggah bukti baru.</p>
            </div>

            <form :action="'{{ url('admin/pembayaran') }}/' + tolakId + '/tolak'" method="POST" class="space-y-4">
                @csrf
                @method('PATCH')

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Alasan Penolakan (Wajib Diisi)</label>
                    <textarea name="catatan_admin" required rows="3" placeholder="Contoh: Foto bukti transfer buram / nominal tidak sesuai dengan mutasi rekening..." class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500"></textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" @click="tolakModal = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl shadow-md transition">
                        Konfirmasi Tolak Pembayaran
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
