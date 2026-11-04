@extends('layouts.admin', ['title' => 'Tambah Paket Wisata'])

@section('content')
<div class="py-6 space-y-6 max-w-5xl">
    
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Tambah Paket Wisata Baru</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Lengkapi informasi paket tour, itinerary harian, dan fasilitas</p>
        </div>
        <a href="{{ route('admin.paket.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
            &larr; Kembali
        </a>
    </div>

    <form action="{{ route('admin.paket.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- Bagian 1: Informasi Utama -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs space-y-4">
            <div class="flex items-center gap-3 pb-3 border-b border-slate-100">
                <span class="w-7 h-7 rounded-full bg-teal-700 text-white flex items-center justify-center text-xs font-extrabold">1</span>
                <h3 class="text-base font-extrabold text-slate-900">Informasi Utama Paket</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Paket Wisata</label>
                    <input type="text" name="nama_paket" value="{{ old('nama_paket') }}" required placeholder="Contoh: Bromo Midnight Sunrise &amp; Kawah Bromo" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 focus:bg-white transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Destinasi Utama</label>
                    <input type="text" name="destinasi" value="{{ old('destinasi') }}" required placeholder="Contoh: Bromo, Malang, Jawa Timur" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Jenis Layanan</label>
                    <select name="jenis_layanan" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800">
                        <option value="open_trip" {{ old('jenis_layanan') === 'open_trip' ? 'selected' : '' }}>Open Trip (Publik / Gabungan)</option>
                        <option value="paket_reguler" {{ old('jenis_layanan') === 'paket_reguler' ? 'selected' : '' }}>Paket Reguler (Private / Khusus)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Harga per Orang (Rp)</label>
                    <input type="number" name="harga" value="{{ old('harga') }}" min="1" required placeholder="Contoh: 1250000" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 font-mono">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Durasi Tour (Jumlah Hari)</label>
                    <input type="number" name="durasi_hari" value="{{ old('durasi_hari', 2) }}" min="1" max="30" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800">
                </div>
            </div>
        </div>

        <!-- Bagian 2: Deskripsi Paket -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs space-y-4">
            <div class="flex items-center gap-3 pb-3 border-b border-slate-100">
                <span class="w-7 h-7 rounded-full bg-teal-700 text-white flex items-center justify-center text-xs font-extrabold">2</span>
                <h3 class="text-base font-extrabold text-slate-900">Deskripsi &amp; Ringkasan Perjalanan</h3>
            </div>

            <div>
                <textarea name="deskripsi" rows="3" placeholder="Tuliskan daya tarik utama, spot foto terbaik, dan pengalaman yang didapatkan wisatawan..." class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800">{{ old('deskripsi') }}</textarea>
            </div>
        </div>

        <!-- Bagian 3: Itinerary Per Hari (Dynamic AlpineJS) -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs space-y-4" x-data="itineraryManager()">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <span class="w-7 h-7 rounded-full bg-teal-700 text-white flex items-center justify-center text-xs font-extrabold">3</span>
                    <h3 class="text-base font-extrabold text-slate-900">Rencana Perjalanan (Itinerary)</h3>
                </div>
                <button type="button" @click="tambahHari()" class="px-3 py-1.5 bg-teal-50 hover:bg-teal-100 text-teal-800 text-xs font-bold rounded-xl transition flex items-center gap-1.5">
                    + Tambah Hari
                </button>
            </div>

            <div class="space-y-4">
                <template x-for="(hari, index) in hariList" :key="index">
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200/80 space-y-3 relative">
                        <div class="flex items-center justify-between">
                            <span class="px-2.5 py-0.5 rounded-md bg-teal-700 text-white font-extrabold text-[11px] uppercase tracking-wider" x-text="'Hari ke-' + (index + 1)"></span>
                            <button type="button" @click="hapusHari(index)" x-show="hariList.length > 1" class="text-xs font-bold text-rose-500 hover:text-rose-700">
                                Hapus Hari Ini
                            </button>
                        </div>
                        <div>
                            <input type="text" :name="'itinerary[' + index + '][judul]'" x-model="hari.judul" required placeholder="Judul Hari (Contoh: Menuju Bromo &amp; Sunrise Penanjakan 1)" class="w-full px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-800">
                        </div>
                        <div>
                            <textarea :name="'itinerary[' + index + '][aktivitas]'" x-model="hari.aktivitas" required rows="2" placeholder="Detail aktivitas dan jadwal di hari ini..." class="w-full px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-xs text-slate-800"></textarea>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- Bagian 4: Fasilitas, Meeting Point & Jam Kumpul -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs space-y-4">
            <div class="flex items-center gap-3 pb-3 border-b border-slate-100">
                <span class="w-7 h-7 rounded-full bg-teal-700 text-white flex items-center justify-center text-xs font-extrabold">4</span>
                <h3 class="text-base font-extrabold text-slate-900">Fasilitas &amp; Titik Kumpul (Meeting Point)</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Fasilitas Termasuk</label>
                    <textarea name="fasilitas" rows="4" required placeholder="• Transportasi AC&#10;• Hotel Bintang 3&#10;• Tiket Masuk Objek Wisata&#10;• Makan sesuai jadwal" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800">{{ old('fasilitas') }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Fasilitas Tidak Termasuk (Opsional)</label>
                    <textarea name="fasilitas_tidak_termasuk" rows="4" placeholder="• Pengeluaran pribadi & laundry&#10;• Tipping driver & guide&#10;• Watersport pribadi" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800">{{ old('fasilitas_tidak_termasuk') }}</textarea>
                </div>

                <!-- Multipel Meeting Point (AlpineJS) -->
                <div class="md:col-span-2 p-5 bg-slate-50/80 rounded-2xl border border-slate-200/80 space-y-3"
                     x-data="{ points: {{ json_encode(old('meeting_points', [old('meeting_point', 'Stasiun Solo Balapan')])) }} }">
                    <div class="flex items-center justify-between">
                        <div>
                            <label class="block text-xs font-bold text-slate-800 uppercase tracking-wider">Lokasi Meeting Point (Titik Kumpul)</label>
                            <p class="text-[11px] text-slate-400">Admin bisa menambahkan beberapa titik kumpul yang nantinya ditampilkan kepada wisatawan.</p>
                        </div>
                        <button type="button" @click="points.push('')" class="px-3 py-1.5 bg-teal-50 hover:bg-teal-100 text-teal-800 text-xs font-bold rounded-xl transition flex items-center gap-1">
                            + Tambah Titik Kumpul
                        </button>
                    </div>

                    <div class="space-y-2.5">
                        <template x-for="(pt, index) in points" :key="index">
                            <div class="flex items-center gap-2">
                                <span class="w-7 h-7 rounded-xl bg-teal-100 text-teal-800 text-xs font-bold flex items-center justify-center shrink-0" x-text="index + 1"></span>
                                <input type="text" name="meeting_points[]" x-model="points[index]" required placeholder="Contoh: Stasiun Solo Balapan, Bandara Adi Soemarmo, Terminal Tirtonadi" class="flex-1 px-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-800 focus:border-teal-500">
                                <button type="button" @click="if (points.length > 1) points.splice(index, 1)" x-show="points.length > 1" class="p-2.5 text-rose-500 hover:bg-rose-50 rounded-xl text-xs font-bold transition">
                                    ✕
                                </button>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Jam Kumpul (WIB)</label>
                    <input type="time" name="jam_kumpul" value="{{ old('jam_kumpul', '16:00') }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800">
                </div>
            </div>
        </div>

        <!-- Bagian 5: Foto Paket Wisata -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs space-y-4"
             x-data="{ fotoTab: 'upload', previewUrl: null, urlInput: '{{ old('foto_url', '') }}' }">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-3 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <span class="w-7 h-7 rounded-full bg-teal-700 text-white flex items-center justify-center text-xs font-extrabold">5</span>
                    <h3 class="text-base font-extrabold text-slate-900">Foto Utama Paket Wisata</h3>
                </div>
                <div class="flex items-center gap-2 bg-slate-100 p-1 rounded-xl text-xs font-bold">
                    <button type="button" @click="fotoTab = 'upload'" :class="fotoTab === 'upload' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-500'" class="px-3 py-1 rounded-lg transition">Unggah Berkas</button>
                    <button type="button" @click="fotoTab = 'url'" :class="fotoTab === 'url' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-500'" class="px-3 py-1 rounded-lg transition">Tautan Gambar (URL)</button>
                </div>
            </div>

            <!-- Opsi 1: Upload Berkas -->
            <div x-show="fotoTab === 'upload'" class="border-2 border-dashed border-slate-200 rounded-2xl p-6 text-center hover:border-teal-500 transition bg-slate-50/50">
                <div x-show="!previewUrl">
                    <svg class="mx-auto h-12 w-12 text-slate-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <div class="mt-3 text-xs text-slate-600">
                        <label for="foto" class="relative cursor-pointer font-bold text-teal-700 hover:text-teal-800 focus-within:outline-none">
                            <span>Pilih berkas foto dari komputer / HP</span>
                            <input id="foto" name="foto" type="file" accept="image/png,image/jpeg,image/jpg" class="sr-only" @change="const file = $event.target.files[0]; if(file){ previewUrl = URL.createObjectURL(file); }">
                        </label>
                        <p class="text-slate-400 mt-1">Format JPG atau PNG (Maksimum 5 MB)</p>
                    </div>
                </div>
                <div x-show="previewUrl" class="space-y-3" style="display: none;">
                    <img :src="previewUrl" alt="Preview Foto" class="max-h-56 mx-auto rounded-xl object-cover shadow-md border border-slate-200">
                    <button type="button" @click="previewUrl = null; document.getElementById('foto').value = ''" class="text-xs font-bold text-rose-500 hover:underline">Hapus / Ganti Foto</button>
                </div>
            </div>

            <!-- Opsi 2: Input URL Gambar -->
            <div x-show="fotoTab === 'url'" class="space-y-3" style="display: none;">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">URL Gambar (Direct Link / Unsplash / CDN)</label>
                    <input type="url" name="foto_url" x-model="urlInput" placeholder="https://images.unsplash.com/photo-..." class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800">
                    <p class="text-[11px] text-slate-400 mt-1">Tempel link gambar langsung beresolusi tinggi dari internet</p>
                </div>
                <div x-show="urlInput" class="pt-2">
                    <p class="text-xs font-bold text-slate-700 mb-1">Pratinjau Gambar:</p>
                    <img :src="urlInput" alt="Preview URL" x-on:error="$el.style.display='none'" x-on:load="$el.style.display='block'" class="max-h-56 rounded-xl object-cover shadow-md border border-slate-200">
                </div>
            </div>
        </div>

        <!-- Submit Controls -->
        <div class="flex items-center justify-end gap-3 pt-4">
            <a href="{{ route('admin.paket.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-bold rounded-xl transition">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 bg-teal-700 hover:bg-teal-800 text-white text-sm font-bold rounded-xl shadow-xs transition">
                Simpan Paket Wisata
            </button>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
    function itineraryManager() {
        return {
            hariList: [
                { judul: 'Keberangkatan & Perjalanan', aktivitas: 'Kumpul di meeting point, briefing perjalanan malam menuju destinasi.' }
            ],
            tambahHari() {
                this.hariList.push({ judul: '', aktivitas: '' });
            },
            hapusHari(index) {
                if (this.hariList.length > 1) {
                    this.hariList.splice(index, 1);
                }
            }
        }
    }
</script>
@endpush
