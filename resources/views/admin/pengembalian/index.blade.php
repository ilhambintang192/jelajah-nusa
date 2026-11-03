@extends('layouts.admin', ['title' => 'Refund & Pindah Jadwal'])

@section('content')
<div class="py-6 space-y-6" x-data="refundManager(@js($pemesananTerdampak), {{ $selectedPemesananId ? (int)$selectedPemesananId : 'null' }})">
    
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Pencatatan Refund &amp; Pemindahan Jadwal</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Kelola pengembalian dana dan pemindahan tanggal bagi jadwal yang ditunda atau dibatalkan</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="px-3 py-1.5 rounded-xl bg-amber-50 border border-amber-200/80 text-amber-800 text-xs font-bold flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                <span>{{ $pemesananTerdampak->count() }} Pemesanan Memerlukan Penanganan</span>
            </span>
        </div>
    </div>

    <!-- 1. Section: Daftar Pemesanan Terdampak yang Bisa Dipilih -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-3 border-b border-slate-100">
            <div>
                <h3 class="text-base font-extrabold text-slate-900">Daftar Pemesanan Terdampak (Siap Diproses)</h3>
                <p class="text-xs text-slate-400">Pilih salah satu pemesanan di bawah ini untuk mengisi formulir refund atau pindah jadwal secara otomatis</p>
            </div>
            <!-- Filter Kategori -->
            <div class="flex items-center gap-1.5 bg-slate-100 p-1 rounded-xl text-xs font-bold">
                <button type="button" @click="filterKategori = 'semua'" :class="filterKategori === 'semua' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-500'" class="px-3 py-1 rounded-lg transition">Semua ({{ $pemesananTerdampak->count() }})</button>
                <button type="button" @click="filterKategori = 'jadwal'" :class="filterKategori === 'jadwal' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-500'" class="px-3 py-1 rounded-lg transition">Jadwal Ditunda/Batal</button>
                <button type="button" @click="filterKategori = 'pesanan'" :class="filterKategori === 'pesanan' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-500'" class="px-3 py-1 rounded-lg transition">Pesanan Batal</button>
            </div>
        </div>

        @if($pemesananTerdampak->isEmpty())
            <div class="py-10 text-center text-slate-400 text-xs">
                <div class="w-12 h-12 rounded-2xl bg-slate-50 text-slate-300 flex items-center justify-center mx-auto mb-2 text-xl font-bold">✓</div>
                Tidak ada pemesanan terdampak saat ini. Semua jadwal berjalan sesuai rencana.
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($pemesananTerdampak as $psn)
                    @php
                        $isJadwalBermasalah = $psn->jadwal && in_array($psn->jadwal->status_keberangkatan, ['ditunda', 'dibatalkan']);
                        $isPesananBatal = $psn->status === 'dibatalkan';
                    @endphp
                    <div class="p-4 rounded-2xl border transition-all flex flex-col justify-between cursor-pointer"
                         :class="selectedId === {{ $psn->id }} ? 'bg-teal-50/70 border-teal-600 ring-2 ring-teal-600/20 shadow-md' : 'bg-slate-50/50 hover:bg-slate-50 border-slate-200/80 hover:border-slate-300'"
                         @click="pilihPemesanan({{ $psn->id }})"
                         x-show="filterKategori === 'semua' || (filterKategori === 'jadwal' && {{ $isJadwalBermasalah ? 'true' : 'false' }}) || (filterKategori === 'pesanan' && {{ $isPesananBatal ? 'true' : 'false' }})">
                        
                        <div class="space-y-2.5">
                            <div class="flex items-center justify-between">
                                <span class="font-mono font-bold text-xs text-teal-800 bg-teal-100/80 px-2.5 py-0.5 rounded-lg">
                                    {{ $psn->display_kode }}
                                </span>
                                @if($isJadwalBermasalah)
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase {{ $psn->jadwal->status_keberangkatan === 'dibatalkan' ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-800' }}">
                                        Trip {{ ucfirst($psn->jadwal->status_keberangkatan) }}
                                    </span>
                                @elseif($isPesananBatal)
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-slate-200 text-slate-700">
                                        Pesanan Batal
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-emerald-100 text-emerald-800">
                                        Terkonfirmasi
                                    </span>
                                @endif
                            </div>

                            <div>
                                <h4 class="text-sm font-extrabold text-slate-900 leading-snug">{{ $psn->judul_trip }}</h4>
                                <p class="text-xs text-slate-600 mt-0.5 font-medium">
                                    {{ $psn->user->nama_lengkap ?? 'Wisatawan' }} &bull; {{ $psn->jumlah_peserta }} Peserta
                                </p>
                                <p class="text-[11px] text-slate-400">
                                    Jadwal: {{ $psn->jadwal ? $psn->jadwal->tanggal_berangkat->translatedFormat('d M Y') : 'Custom Trip' }}
                                </p>
                            </div>
                        </div>

                        <div class="pt-3 mt-3 border-t border-slate-200/60 flex items-center justify-between">
                            <div>
                                <span class="text-[10px] text-slate-400 block font-medium">Dana Terbayar:</span>
                                <span class="text-xs font-mono font-extrabold text-emerald-600">{{ $psn->total_terbayar_format }}</span>
                            </div>

                            <button type="button" class="px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1"
                                    :class="selectedId === {{ $psn->id }} ? 'bg-teal-700 text-white' : 'bg-white text-slate-700 hover:bg-teal-50 hover:text-teal-800 border border-slate-200'">
                                <span x-text="selectedId === {{ $psn->id }} ? '✓ Dipilih' : 'Pilih &rarr;'"></span>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- 2. Form & Riwayat -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start" id="formSection">
        
        <!-- Left: Form Catat Pengembalian (5 Cols) -->
        <div class="lg:col-span-5 bg-white p-6 sm:p-7 rounded-3xl border border-slate-200/80 shadow-xs space-y-4">
            <div class="pb-3 border-b border-slate-100">
                <h3 class="text-base font-extrabold text-slate-900">Formulir Proses Refund / Reschedule</h3>
                <p class="text-xs text-slate-400">Pilih pemesanan dan isi rincian pengembalian</p>
            </div>

            <!-- Kartu Pemesanan yang Sedang Terpilih -->
            <div x-show="selectedOrder" class="p-4 bg-teal-50 border border-teal-200 rounded-2xl space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-teal-800">Pemesanan Terpilih</span>
                    <button type="button" @click="resetPemesanan()" class="text-[11px] font-bold text-teal-700 hover:underline">Ganti</button>
                </div>
                <div>
                    <h4 class="text-sm font-extrabold text-slate-900" x-text="selectedOrder ? selectedOrder.judul_trip : ''"></h4>
                    <p class="text-xs text-slate-600 mt-0.5">
                        <span class="font-bold text-slate-800" x-text="selectedOrder ? selectedOrder.user_nama : ''"></span> &bull; 
                        <span x-text="selectedOrder ? selectedOrder.display_kode : ''"></span>
                    </p>
                    <div class="flex items-center justify-between pt-2 mt-2 border-t border-teal-100 text-xs">
                        <span class="text-slate-500">Maks. Dana Terbayar:</span>
                        <strong class="text-emerald-700 font-mono" x-text="selectedOrder ? selectedOrder.total_terbayar_format : 'Rp 0'"></strong>
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.pengembalian.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Pilih Pemesanan Terdampak</label>
                    <select name="pemesanan_id" x-model="selectedId" @change="onSelectChange($event.target.value)" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800">
                        <option value="">— Pilih Pemesanan Terdampak —</option>
                        @foreach($pemesananTerdampak as $psn)
                            <option value="{{ $psn->id }}">
                                {{ $psn->display_kode }} &bull; {{ $psn->user->nama_lengkap ?? 'Wisatawan' }} ({{ $psn->judul_trip }}) - Terbayar: {{ $psn->total_terbayar_format }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Jenis Tindakan</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="flex items-center gap-2 p-3 rounded-xl border border-slate-200 cursor-pointer text-xs font-bold" :class="jenisPengembalian === 'refund' ? 'bg-teal-50 border-teal-600 text-teal-800' : 'bg-slate-50 text-slate-700'">
                            <input type="radio" name="jenis" value="refund" x-model="jenisPengembalian" class="text-teal-700">
                            Refund Dana
                        </label>
                        <label class="flex items-center gap-2 p-3 rounded-xl border border-slate-200 cursor-pointer text-xs font-bold" :class="jenisPengembalian === 'pindah_jadwal' ? 'bg-teal-50 border-teal-600 text-teal-800' : 'bg-slate-50 text-slate-700'">
                            <input type="radio" name="jenis" value="pindah_jadwal" x-model="jenisPengembalian" class="text-teal-700">
                            Pindah Jadwal
                        </label>
                    </div>
                </div>

                <!-- Input Nominal jika Refund -->
                <div x-show="jenisPengembalian === 'refund'" class="space-y-1.5">
                    <div class="flex items-center justify-between">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Nominal Refund (Rp)</label>
                        <button type="button" x-show="selectedOrder" @click="nominalInput = selectedOrder.total_terbayar" class="text-[11px] font-bold text-teal-700 hover:underline">
                            Gunakan Refund Penuh
                        </button>
                    </div>
                    <input type="number" name="nominal" x-model="nominalInput" min="1" :max="selectedOrder ? selectedOrder.total_terbayar : null" placeholder="Maks. total terbayar pemesan" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 font-mono">
                    <p class="text-[11px] text-slate-400">Nominal pengembalian dana tidak boleh melebihi total yang telah dibayar wisatawan.</p>
                </div>

                <!-- Input Jadwal Baru jika Pindah Jadwal -->
                <div x-show="jenisPengembalian === 'pindah_jadwal'" style="display: none;">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Pilih Jadwal Baru yang Tersedia</label>
                    <select name="jadwal_baru_id" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800">
                        <option value="">— Pilih Jadwal Pengganti —</option>
                        @foreach($jadwalTersedia as $jb)
                            <option value="{{ $jb->id }}">
                                {{ $jb->paket->nama_paket ?? 'Trip' }} &bull; {{ $jb->tanggal_berangkat->format('d/m/Y') }} (Sisa {{ $jb->sisa_kuota }} kursi)
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Alasan Pengembalian / Reschedule</label>
                    <textarea name="alasan" required rows="3" placeholder="Contoh: Jadwal ditunda akibat cuaca buruk di laut perairan Komodo atau permintaan pembatalan dari pemesan..." class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800"></textarea>
                </div>

                <button type="submit" class="w-full py-2.5 bg-teal-700 hover:bg-teal-800 text-white text-xs font-bold rounded-xl shadow-xs transition">
                    Simpan &amp; Proses Pengembalian
                </button>
            </form>
        </div>

        <!-- Right: Riwayat Pengembalian (7 Cols) -->
        <div class="lg:col-span-7 bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs space-y-4">
            <div class="pb-3 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">Riwayat Pengembalian &amp; Reschedule</h3>
                    <p class="text-xs text-slate-400">Daftar transaksi pengembalian dana dan jadwal baru yang telah diterbitkan</p>
                </div>
                <span class="text-xs font-bold text-slate-600 bg-slate-100 px-2.5 py-1 rounded-lg">{{ $riwayat->total() }} Data Tercatat</span>
            </div>

            <div class="overflow-x-auto border border-slate-100 rounded-2xl">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 border-b border-slate-100 text-slate-500 font-bold uppercase tracking-wider">
                        <tr>
                            <th class="py-3 px-3">Tanggal</th>
                            <th class="py-3 px-3">Kode Pesanan</th>
                            <th class="py-3 px-3">Pemesan</th>
                            <th class="py-3 px-2">Jenis</th>
                            <th class="py-3 px-3">Nominal / Jadwal</th>
                            <th class="py-3 px-3">Alasan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium">
                        @forelse($riwayat as $rw)
                            <tr class="hover:bg-slate-50/50">
                                <td class="py-3 px-3 text-slate-400 whitespace-nowrap">
                                    {{ $rw->tanggal_pengembalian->format('d/m/Y') }}
                                </td>
                                <td class="py-3 px-3 font-mono font-bold text-teal-800 whitespace-nowrap">
                                    {{ $rw->pemesanan ? $rw->pemesanan->display_kode : '—' }}
                                </td>
                                <td class="py-3 px-3 text-slate-800 font-bold">
                                    {{ $rw->pemesanan->user->nama_lengkap ?? '—' }}
                                </td>
                                <td class="py-3 px-2 whitespace-nowrap">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $rw->jenis === 'refund' ? 'bg-amber-100 text-amber-800' : 'bg-teal-100 text-teal-800' }}">
                                        {{ $rw->jenis === 'refund' ? 'Refund' : 'Reschedule' }}
                                    </span>
                                </td>
                                <td class="py-3 px-3 whitespace-nowrap">
                                    @if($rw->jenis === 'refund')
                                        <span class="font-bold text-emerald-600 font-mono">{{ $rw->nominal_format }}</span>
                                    @else
                                        <span class="text-slate-700">Tgl: {{ $rw->jadwalBaru ? $rw->jadwalBaru->tanggal_berangkat->format('d/m/Y') : '—' }}</span>
                                    @endif
                                </td>
                                <td class="py-3 px-3 text-slate-500 max-w-[150px] truncate" title="{{ $rw->alasan }}">
                                    {{ $rw->alasan }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-slate-400">
                                    Belum ada catatan refund atau pemindahan jadwal.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pt-2">
                {{ $riwayat->links() }}
            </div>
        </div>
    </div>
</div>

<script>
    function refundManager(rawList, initialId) {
        const orderList = rawList.map(o => ({
            id: o.id,
            display_kode: o.display_kode || ('PSN-' + o.id),
            user_nama: o.user ? o.user.nama_lengkap : 'Wisatawan',
            judul_trip: o.judul_trip || 'Trip',
            total_terbayar: parseFloat(o.total_terbayar || 0),
            total_terbayar_format: o.total_terbayar_format || ('Rp ' + parseFloat(o.total_terbayar || 0).toLocaleString('id-ID'))
        }));

        let initOrder = null;
        let initNominal = '';
        if (initialId) {
            initOrder = orderList.find(o => o.id === initialId) || null;
            if (initOrder) {
                initNominal = initOrder.total_terbayar;
            }
        }

        return {
            orderList: orderList,
            selectedId: initialId || '',
            selectedOrder: initOrder,
            jenisPengembalian: 'refund',
            nominalInput: initNominal,
            filterKategori: 'semua',

            pilihPemesanan(id) {
                this.selectedId = id;
                this.selectedOrder = this.orderList.find(o => o.id === id) || null;
                if (this.selectedOrder) {
                    this.nominalInput = this.selectedOrder.total_terbayar;
                }
                const formEl = document.getElementById('formSection');
                if (formEl) {
                    formEl.scrollIntoView({ behavior: 'smooth' });
                }
            },

            onSelectChange(id) {
                const numericId = parseInt(id);
                this.selectedId = numericId || '';
                this.selectedOrder = this.orderList.find(o => o.id === numericId) || null;
                if (this.selectedOrder) {
                    this.nominalInput = this.selectedOrder.total_terbayar;
                } else {
                    this.nominalInput = '';
                }
            },

            resetPemesanan() {
                this.selectedId = '';
                this.selectedOrder = null;
                this.nominalInput = '';
            }
        };
    }
</script>
@endsection
