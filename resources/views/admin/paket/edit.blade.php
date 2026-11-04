@extends('layouts.admin', ['title' => 'Ubah Paket Wisata'])

@section('content')
<div class="py-6 space-y-6 max-w-5xl">
    
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Ubah Paket Wisata</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Perbarui rincian paket: {{ $paket->nama_paket }}</p>
        </div>
        <a href="{{ route('admin.paket.index', ['selected' => $paket->id]) }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
            &larr; Batal &amp; Kembali
        </a>
    </div>

    <form action="{{ route('admin.paket.update', $paket->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Bagian 1: Informasi Utama -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs space-y-4">
            <div class="flex items-center gap-3 pb-3 border-b border-slate-100">
                <span class="w-7 h-7 rounded-full bg-teal-700 text-white flex items-center justify-center text-xs font-extrabold">1</span>
                <h3 class="text-base font-extrabold text-slate-900">Informasi Utama Paket</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Paket Wisata</label>
                    <input type="text" name="nama_paket" value="{{ old('nama_paket', $paket->nama_paket) }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Destinasi Utama</label>
                    <input type="text" name="destinasi" value="{{ old('destinasi', $paket->destinasi) }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Jenis Layanan</label>
                    <select name="jenis_layanan" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-800">
                        <option value="open_trip" {{ old('jenis_layanan', $paket->jenis_layanan) === 'open_trip' ? 'selected' : '' }}>Open Trip (Publik)</option>
                        <option value="paket_reguler" {{ old('jenis_layanan', $paket->jenis_layanan) === 'paket_reguler' ? 'selected' : '' }}>Paket Reguler (Private)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Harga per Orang (Rp)</label>
                    <input type="number" name="harga" value="{{ old('harga', (int)$paket->harga) }}" min="1" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800 font-mono">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Durasi Tour (Jumlah Hari)</label>
                    <input type="number" name="durasi_hari" value="{{ old('durasi_hari', $paket->durasi_hari) }}" min="1" max="30" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800">
                </div>
            </div>
        </div>

        <!-- Bagian 2: Deskripsi -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs space-y-4">
            <div class="flex items-center gap-3 pb-3 border-b border-slate-100">
                <span class="w-7 h-7 rounded-full bg-teal-700 text-white flex items-center justify-center text-xs font-extrabold">2</span>
                <h3 class="text-base font-extrabold text-slate-900">Deskripsi Paket</h3>
            </div>

            <div>
                <textarea name="deskripsi" rows="3" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800">{{ old('deskripsi', $paket->deskripsi) }}</textarea>
            </div>
        </div>

        <!-- Bagian 3: Itinerary Per Hari -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs space-y-4" x-data="itineraryEditManager({{ json_encode($paket->itinerary) }})">
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
                                Hapus
                            </button>
                        </div>
                        <div>
                            <input type="text" :name="'itinerary[' + index + '][judul]'" x-model="hari.judul" required class="w-full px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-800">
                        </div>
                        <div>
                            <textarea :name="'itinerary[' + index + '][aktivitas]'" x-model="hari.aktivitas" required rows="2" class="w-full px-3.5 py-2 bg-white border border-slate-200 rounded-xl text-xs text-slate-800"></textarea>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- Bagian 4: Fasilitas & Titik Kumpul (Meeting Point) -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs space-y-4">
            <div class="flex items-center gap-3 pb-3 border-b border-slate-100">
                <span class="w-7 h-7 rounded-full bg-teal-700 text-white flex items-center justify-center text-xs font-extrabold">4</span>
                <h3 class="text-base font-extrabold text-slate-900">Fasilitas &amp; Titik Kumpul (Meeting Point)</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Fasilitas Termasuk</label>
                    <textarea name="fasilitas" rows="4" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800">{{ old('fasilitas', $paket->fasilitas) }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Fasilitas Tidak Termasuk</label>
                    <textarea name="fasilitas_tidak_termasuk" rows="4" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800">{{ old('fasilitas_tidak_termasuk', $paket->fasilitas_tidak_termasuk) }}</textarea>
                </div>

                <!-- Multipel Meeting Point (AlpineJS) -->
                <div class="md:col-span-2 p-5 bg-slate-50/80 rounded-2xl border border-slate-200/80 space-y-3"
                     x-data="{ points: {{ json_encode(old('meeting_points', $paket->meeting_points)) }} }">
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
                    <input type="time" name="jam_kumpul" value="{{ old('jam_kumpul', substr($paket->jam_kumpul, 0, 5)) }}" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800">
                </div>
            </div>
        </div>

        <!-- Bagian 5: Foto Paket Wisata -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs space-y-4"
             x-data="{ fotoTab: 'upload', previewUrl: '{{ $paket->foto ? (str_starts_with($paket->foto, 'http') ? $paket->foto : asset('storage/' . $paket->foto)) : null }}', urlInput: '{{ old('foto_url', str_starts_with($paket->foto ?? '', 'http') ? $paket->foto : '') }}' }">
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

            <!-- Preview Foto Saat Ini / Terpilih -->
            <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-2xl border border-slate-200/80">
                <img :src="previewUrl || '{{ $paket->foto_url }}'" alt="{{ $paket->nama_paket }}" class="w-36 h-24 object-cover rounded-xl border border-slate-200 shadow-xs shrink-0">
                <div>
                    <h4 class="text-xs font-bold text-slate-800">Pratinjau Foto Paket Wisata</h4>
                    <p class="text-[11px] text-slate-500 mt-0.5">Foto ini akan menjadi sampul utama pada halaman katalog dan detail paket bagi wisatawan.</p>
                </div>
            </div>

            <!-- Opsi 1: Upload Berkas Baru -->
            <div x-show="fotoTab === 'upload'" class="border-2 border-dashed border-slate-200 rounded-2xl p-6 text-center hover:border-teal-500 transition bg-slate-50/50">
                <div class="text-xs text-slate-600">
                    <label for="foto" class="relative cursor-pointer font-bold text-teal-700 hover:text-teal-800">
                        <span>Pilih berkas foto baru (opsional)</span>
                        <input id="foto" name="foto" type="file" accept="image/png,image/jpeg,image/jpg" class="sr-only" @change="const file = $event.target.files[0]; if(file){ previewUrl = URL.createObjectURL(file); }">
                    </label>
                    <p class="text-slate-400 mt-1">Kosongkan jika tidak ingin mengubah foto (Maksimum 5 MB)</p>
                </div>
            </div>

            <!-- Opsi 2: Input URL Gambar -->
            <div x-show="fotoTab === 'url'" class="space-y-3" style="display: none;">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Ganti dengan URL Gambar (Direct Link / Unsplash / CDN)</label>
                    <input type="url" name="foto_url" x-model="urlInput" @input="if(urlInput){ previewUrl = urlInput; }" placeholder="https://images.unsplash.com/photo-..." class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-800">
                    <p class="text-[11px] text-slate-400 mt-1">Tempel link gambar langsung beresolusi tinggi dari internet</p>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-4">
            <a href="{{ route('admin.paket.index', ['selected' => $paket->id]) }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-bold rounded-xl transition">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 bg-teal-700 hover:bg-teal-800 text-white text-sm font-bold rounded-xl shadow-xs transition">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
    function itineraryEditManager(initial) {
        return {
            hariList: initial && initial.length > 0 ? initial : [{ judul: '', aktivitas: '' }],
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
