@extends('layouts.wisatawan', ['title' => 'Form Pemesanan Paket — Jelajah Nusa'])

@section('content')
@php
    $maxSeats = $jadwal ? $jadwal->sisa_kuota : ($customTrip ? $customTrip->jumlah_peserta : 1);
    $unitPrice = $jadwal ? (float)$jadwal->paket->harga : 0;
    $customPrice = $customTrip ? (float)$customTrip->biaya_penawaran : 0;
    $paket = $jadwal ? $jadwal->paket : null;
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8" 
     x-data="bookingFormManager({{ $maxSeats }}, {{ $unitPrice }}, {{ $customPrice }}, {{ $customTrip ? 'true' : 'false' }})">
    
    <!-- Title & 4-Step Progress Header matching Figma -->
    <div class="space-y-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Pemesanan Paket</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Tentukan jadwal dan lengkapi data peserta untuk perjalanan Anda.</p>
        </div>

        <!-- 4 Step Stepper -->
        <div class="flex items-center gap-2 sm:gap-4 text-xs font-semibold overflow-x-auto pb-2">
            <!-- Step 1 -->
            <div class="flex items-center gap-2">
                <span class="w-6 h-6 rounded-full bg-teal-700 text-white flex items-center justify-center text-xs font-bold shrink-0">1</span>
                <span class="text-slate-900 font-bold whitespace-nowrap">Jadwal &amp; Jumlah</span>
            </div>
            <div class="w-8 sm:w-12 h-0.5 bg-teal-700"></div>

            <!-- Step 2 -->
            <div class="flex items-center gap-2">
                <span class="w-6 h-6 rounded-full bg-teal-700 text-white flex items-center justify-center text-xs font-bold shrink-0">2</span>
                <span class="text-slate-900 font-bold whitespace-nowrap">Data Peserta</span>
            </div>
            <div class="w-8 sm:w-12 h-0.5 bg-slate-200"></div>

            <!-- Step 3 -->
            <div class="flex items-center gap-2">
                <span class="w-6 h-6 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center text-xs font-bold shrink-0">3</span>
                <span class="text-slate-400 whitespace-nowrap">Ringkasan</span>
            </div>
            <div class="w-8 sm:w-12 h-0.5 bg-slate-200"></div>

            <!-- Step 4 -->
            <div class="flex items-center gap-2">
                <span class="w-6 h-6 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center text-xs font-bold shrink-0">4</span>
                <span class="text-slate-400 whitespace-nowrap">Konfirmasi</span>
            </div>
        </div>
    </div>

    <!-- 2 Column Layout: Left Form, Right Summary -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left: Booking Form (7 Cols) -->
        <div class="lg:col-span-7 space-y-6">
            
            <form id="booking-form" action="{{ route('pemesanan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @if($jadwal)
                    <input type="hidden" name="jadwal_id" value="{{ $jadwal->id }}">
                @elseif($customTrip)
                    <input type="hidden" name="custom_trip_id" value="{{ $customTrip->id }}">
                @endif

                <!-- Card 1: Jadwal Keberangkatan Terpilih -->
                <div class="bg-white p-6 rounded-3xl border border-slate-200/90 shadow-xs space-y-3">
                    <h3 class="text-sm font-extrabold text-slate-900">Jadwal Keberangkatan</h3>
                    <p class="text-xs text-slate-500">
                        {{ $paket ? $paket->nama_paket : ($customTrip ? 'Custom Trip: ' . $customTrip->tujuan : '') }}
                    </p>

                    @if($jadwal)
                        <div class="p-4 rounded-2xl border-2 border-teal-700 bg-teal-50/20 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-4 h-4 rounded-full border-4 border-teal-700 bg-white"></div>
                                <div>
                                    <span class="text-sm font-extrabold text-slate-900 block">
                                        {{ $jadwal->tanggal_berangkat->translatedFormat('d F Y') }}
                                    </span>
                                    <span class="text-xs text-slate-500">
                                        Pukul {{ substr($paket->jam_kumpul ?? '05:00', 0, 5) }} WIB &bull; {{ $paket->meeting_points[0] ?? 'Meeting Point Resmi' }}
                                    </span>
                                </div>
                            </div>
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700">
                                Sisa {{ $jadwal->sisa_kuota }} kursi
                            </span>
                        </div>
                    @elseif($customTrip)
                        <div class="p-4 rounded-2xl border-2 border-teal-700 bg-teal-50/20 flex items-center justify-between">
                            <div>
                                <span class="text-sm font-extrabold text-slate-900 block">
                                    {{ $customTrip->jadwal_penawaran ? $customTrip->jadwal_penawaran->translatedFormat('d F Y') : $customTrip->tanggal_diinginkan->translatedFormat('d F Y') }}
                                </span>
                                <span class="text-xs text-slate-500">
                                    Rombongan Custom Trip &bull; {{ $customTrip->jumlah_peserta }} Peserta
                                </span>
                            </div>
                            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700">
                                Disetujui
                            </span>
                        </div>
                    @endif
                </div>

                <!-- Card 2: Pemilihan Jumlah Peserta -->
                @if($jadwal)
                    <div class="bg-white p-6 rounded-3xl border border-slate-200/90 shadow-xs flex items-center justify-between gap-4">
                        <div>
                            <h3 class="text-sm font-extrabold text-slate-900">Jumlah Peserta</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Harga per orang {{ $jadwal->paket->harga_format }}</p>
                            <p class="text-[11px] text-slate-400">Termasuk peserta dewasa dan anak yang menggunakan kursi.</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <button type="button" @click="kurangiPeserta()" class="w-10 h-10 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 font-extrabold text-lg flex items-center justify-center transition">
                                -
                            </button>
                            <span class="text-lg font-extrabold text-slate-900 min-w-[28px] text-center" x-text="pesertaList.length"></span>
                            <button type="button" @click="tambahPeserta()" class="w-10 h-10 rounded-xl bg-teal-700 hover:bg-teal-800 text-white font-extrabold text-lg flex items-center justify-center transition">
                                +
                            </button>
                        </div>
                    </div>
                @endif

                <!-- Card 3: Status Open Trip Progress -->
                @if($jadwal && $jadwal->paket->jenis_layanan === 'open_trip')
                    @php
                        $aktif = $jadwal->peserta_aktif;
                        $min = $jadwal->min_peserta;
                        $max = $jadwal->kuota;
                        $layak = $aktif >= $min;
                        $pct = min(100, round(($aktif / max(1, $max)) * 100));
                    @endphp
                    <div class="bg-white p-6 rounded-3xl border border-slate-200/90 shadow-xs space-y-3">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-extrabold text-slate-900">Status Open Trip</h3>
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-bold {{ $layak ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                                {{ $layak ? 'Layak dijalankan' : 'Menunggu kuota' }}
                            </span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                            <div class="bg-teal-600 h-2 rounded-full transition-all" style="width: {{ $pct }}%"></div>
                        </div>
                        <p class="text-xs text-slate-500">
                            {{ $aktif }}/{{ $max }} peserta terdaftar · minimal {{ $min }}. 
                            {{ $layak ? 'Batas minimal sudah terpenuhi.' : 'Masih menunggu peserta lain.' }}
                        </p>
                    </div>
                @endif

                <!-- Formulir Data Peserta & Upload KTP matching Figma -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-base font-extrabold text-slate-900">Data dan Dokumen Identitas Peserta</h3>
                        <span class="text-xs text-slate-400">Diperlukan untuk manifes &amp; asuransi</span>
                    </div>

                    <template x-for="(peserta, index) in pesertaList" :key="index">
                        <div class="bg-white p-6 rounded-3xl border border-slate-200/90 shadow-xs space-y-4">
                            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                                <div class="flex items-center gap-2">
                                    <span class="w-6 h-6 rounded-full bg-teal-700 text-white flex items-center justify-center text-xs font-bold" x-text="index + 1"></span>
                                    <h4 class="text-sm font-extrabold text-slate-900" x-text="'Peserta ' + (index + 1) + (index === 0 ? ' (Pemesan Utama)' : '')"></h4>
                                </div>
                                <button type="button" @click="hapusPeserta(index)" x-show="pesertaList.length > 1 && !isCustom" class="text-rose-500 hover:text-rose-700 text-xs font-bold">
                                    Hapus
                                </button>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap (Sesuai KTP/Paspor)</label>
                                    <input type="text" :name="'peserta[' + index + '][nama_lengkap]'" x-model="peserta.nama_lengkap" required placeholder="Contoh: Rina Wulandari" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Jenis &amp; Nomor Identitas</label>
                                    <div class="flex gap-2">
                                        <select :name="'peserta[' + index + '][jenis_identitas]'" x-model="peserta.jenis_identitas" required class="w-24 px-2 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800">
                                            <option value="ktp">KTP</option>
                                            <option value="paspor">Paspor</option>
                                        </select>
                                        <input type="text" :name="'peserta[' + index + '][no_identitas]'" x-model="peserta.no_identitas" required placeholder="16 digit NIK / Paspor" class="flex-1 px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Nomor WhatsApp / HP</label>
                                    <input type="text" :name="'peserta[' + index + '][no_hp]'" x-model="peserta.no_hp" placeholder="08xxxxxxxxxx" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">Email Peserta (Opsional)</label>
                                    <input type="email" placeholder="nama@email.com" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                                </div>
                            </div>

                            <!-- Upload Foto KTP/Paspor Box matching Figma -->
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1.5">Unggah Foto KTP / Paspor</label>
                                <div class="border-2 border-dashed border-teal-500/60 bg-teal-50/30 rounded-2xl p-6 text-center hover:bg-teal-50/60 transition cursor-pointer relative">
                                    <input type="file" 
                                           :name="'peserta[' + index + '][file_identitas]'" 
                                           required 
                                           accept=".jpg,.jpeg,.png"
                                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                    <div class="space-y-1.5 pointer-events-none">
                                        <div class="w-10 h-10 rounded-full bg-teal-100 text-teal-800 flex items-center justify-center mx-auto">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                        </div>
                                        <p class="text-xs font-bold text-teal-900">
                                            Unggah foto KTP / Paspor — tarik &amp; lepas atau klik
                                        </p>
                                        <p class="text-[11px] text-slate-400">
                                            JPG / PNG &bull; maks. 5 MB &bull; disimpan privat &amp; terenkripsi
                                        </p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </template>
                </div>
            </form>

        </div>

        <!-- Right: Sticky Ringkasan Pesanan matching Figma (5 Cols) -->
        <div class="lg:col-span-5 space-y-6 lg:sticky lg:top-24">
            
            <div class="bg-white p-6 sm:p-7 rounded-3xl border border-slate-200/90 shadow-lg space-y-5">
                <h3 class="text-base font-extrabold text-slate-900">Ringkasan Pesanan</h3>

                <!-- Trip Banner -->
                @if($paket)
                    <div class="h-36 rounded-2xl overflow-hidden relative bg-slate-900">
                        <img src="{{ $paket->foto_url }}" alt="{{ $paket->nama_paket }}" class="w-full h-full object-cover opacity-90">
                        <span class="absolute top-3 left-3 px-2.5 py-1 rounded-md text-[10px] font-bold bg-white/90 text-slate-800 shadow-xs">
                            Paket Pilihan
                        </span>
                    </div>

                    <div>
                        <h4 class="text-base font-extrabold text-slate-900 leading-tight">{{ $paket->nama_paket }}</h4>
                        <p class="text-xs text-slate-500 mt-1">
                            Berangkat {{ $jadwal ? $jadwal->tanggal_berangkat->translatedFormat('d M Y') : '—' }} &bull; Pukul {{ substr($paket->jam_kumpul ?? '05:00', 0, 5) }} WIB
                        </p>
                        <span class="inline-block mt-2 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-sky-50 text-sky-700">
                            {{ $paket->jenis_layanan === 'open_trip' ? 'Open Trip' : 'Paket Reguler' }}
                        </span>
                    </div>
                @elseif($customTrip)
                    <div class="p-4 bg-teal-50 rounded-2xl border border-teal-100">
                        <h4 class="text-base font-extrabold text-slate-900">Custom Trip: {{ $customTrip->tujuan }}</h4>
                        <p class="text-xs text-slate-600 mt-1">Rombongan Khusus &bull; {{ $customTrip->jumlah_peserta }} Peserta</p>
                    </div>
                @endif

                <!-- Calculation Rows matching Figma -->
                <div class="pt-4 border-t border-slate-100 space-y-2.5 text-xs">
                    <div class="flex justify-between text-slate-500">
                        <span>Harga per peserta</span>
                        <span class="font-mono font-semibold text-slate-800" x-text="formatRupiah(unitPrice)"></span>
                    </div>
                    <div class="flex justify-between text-slate-500">
                        <span>Jumlah peserta</span>
                        <span class="font-semibold text-slate-800" x-text="pesertaList.length + ' orang'"></span>
                    </div>
                    <div class="flex justify-between text-slate-500">
                        <span>Subtotal biaya</span>
                        <span class="font-mono font-semibold text-slate-800" x-text="formatRupiah(totalHarga)"></span>
                    </div>
                    <div class="flex justify-between text-slate-500">
                        <span>Biaya layanan</span>
                        <span class="font-mono font-semibold text-slate-800">Rp 0</span>
                    </div>
                    <div class="flex justify-between text-slate-500">
                        <span>Diskon</span>
                        <span class="font-mono font-semibold text-slate-800">Rp 0</span>
                    </div>

                    <div class="pt-3 border-t border-slate-200/80 flex items-baseline justify-between">
                        <span class="text-xs font-bold text-slate-800">Total pembayaran</span>
                        <span class="text-xl sm:text-2xl font-extrabold text-teal-800 font-mono" x-text="formatRupiah(totalHarga)"></span>
                    </div>
                </div>

                <!-- Orange Continue Button matching Figma -->
                <div class="pt-2">
                    <button type="submit" 
                            form="booking-form"
                            class="w-full py-3.5 bg-orange-500 hover:bg-orange-600 text-white font-extrabold text-sm text-center rounded-xl shadow-md transition">
                        Lanjut ke Pembayaran &rarr;
                    </button>
                    <p class="text-[11px] text-center text-slate-400 mt-2">
                        Harga termasuk transportasi, hotel, tiket wisata, dan pemandu.
                    </p>
                </div>

            </div>

        </div>

    </div>
</div>

<script>
function bookingFormManager(maxSeats, unitPrice, customPrice, isCustom) {
    return {
        maxSeats: maxSeats,
        unitPrice: isCustom ? (customPrice / maxSeats) : unitPrice,
        isCustom: isCustom,
        pesertaList: [
            { nama_lengkap: '{{ auth()->user()->nama_lengkap ?? '' }}', jenis_identitas: 'ktp', no_identitas: '', no_hp: '{{ auth()->user()->no_hp ?? '' }}' }
        ],
        get totalHarga() {
            if (this.isCustom) return customPrice;
            return this.pesertaList.length * this.unitPrice;
        },
        tambahPeserta() {
            if (this.pesertaList.length >= this.maxSeats) {
                alert('Maksimal sisa kuota yang tersedia adalah ' + this.maxSeats + ' kursi.');
                return;
            }
            this.pesertaList.push({ nama_lengkap: '', jenis_identitas: 'ktp', no_identitas: '', no_hp: '' });
        },
        kurangiPeserta() {
            if (this.pesertaList.length > 1) {
                this.pesertaList.pop();
            }
        },
        hapusPeserta(index) {
            if (this.pesertaList.length > 1) {
                this.pesertaList.splice(index, 1);
            }
        },
        formatRupiah(angka) {
            return 'Rp ' + Math.round(angka).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }
    }
}
</script>
@endsection
