<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kendaraan;
use App\Models\LogAktivitas;
use App\Models\PaketWisata;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PaketWisataController extends Controller
{
    /**
     * Daftar Paket Wisata & Detail Tab (F2, Frame 107:2244, US-05, US-06).
     */
    public function index(Request $request): View
    {
        $query = PaketWisata::withCount(['jadwal', 'pemesanan']);

        // Filter Layanan
        if ($request->filled('layanan') && $request->layanan !== 'semua') {
            $query->where('jenis_layanan', $request->layanan);
        }

        // Filter Destinasi
        if ($request->filled('destinasi')) {
            $query->where('destinasi', $request->destinasi);
        }

        // Pencarian Nama Paket
        if ($request->filled('q')) {
            $query->where('nama_paket', 'like', '%' . $request->q . '%');
        }

        $paketList = $query->orderBy('id', 'desc')->get();

        // Ambil paket yang terpilih untuk panel detail kanan
        $selectedId = $request->query('selected', $paketList->first()?->id);
        $selectedPaket = $selectedId ? PaketWisata::with(['jadwal.kendaraan', 'jadwal.pemesanan.peserta'])->find($selectedId) : $paketList->first();

        // Daftar destinasi unik untuk filter
        $destinasiList = PaketWisata::distinct()->pluck('destinasi');

        return view('admin.paket.index', compact('paketList', 'selectedPaket', 'destinasiList'));
    }

    /**
     * Form Tambah Paket Wisata (Frame 107:2310, US-05).
     */
    public function create(): View
    {
        $kendaraanList = Kendaraan::tersedia()->get();
        return view('admin.paket.create', compact('kendaraanList'));
    }

    /**
     * Simpan Paket Wisata Baru.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_paket' => 'required|string|max:150',
            'destinasi' => 'required|string|max:100',
            'jenis_layanan' => 'required|in:open_trip,paket_reguler',
            'harga' => 'required|numeric|min:1',
            'durasi_hari' => 'required|integer|min:1|max:30',
            'deskripsi' => 'nullable|string',
            'itinerary' => 'required|array|min:1',
            'itinerary.*.judul' => 'required|string',
            'itinerary.*.aktivitas' => 'required|string',
            'fasilitas' => 'required|string',
            'fasilitas_tidak_termasuk' => 'nullable|string',
            'meeting_point' => 'nullable|string',
            'meeting_points' => 'nullable|array',
            'meeting_points.*' => 'nullable|string',
            'jam_kumpul' => 'required',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png|max:5120',
            'foto_url' => 'nullable|string|max:500',
        ], [
            'nama_paket.required' => 'Nama paket wajib diisi.',
            'destinasi.required' => 'Destinasi wajib diisi.',
            'harga.required' => 'Harga paket wajib diisi.',
            'durasi_hari.required' => 'Durasi hari wajib diisi.',
            'itinerary.required' => 'Itinerary per hari wajib diisi minimal 1 hari.',
            'fasilitas.required' => 'Fasilitas termasuk wajib diisi.',
            'jam_kumpul.required' => 'Jam kumpul wajib diisi.',
            'foto.max' => 'Ukuran file foto melebihi 5 MB.',
        ]);

        // Resolusi meeting point: bisa array multipel meeting point atau input tunggal
        $points = [];
        if ($request->has('meeting_points') && is_array($request->meeting_points)) {
            $points = array_values(array_filter(array_map('trim', $request->meeting_points)));
        }
        if (empty($points) && $request->filled('meeting_point')) {
            $points = [trim($request->meeting_point)];
        }
        if (empty($points)) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'meeting_point' => 'Titik kumpul (meeting point) wajib diisi minimal 1 lokasi.',
            ]);
        }
        $meetingPointValue = count($points) > 1 ? json_encode($points) : $points[0];

        // Resolusi foto: upload berkas atau URL gambar
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('paket_foto', 'public');
        } elseif ($request->filled('foto_url')) {
            $fotoPath = $request->input('foto_url');
        }

        // Format itinerary array index {hari, judul, aktivitas}
        $formattedItinerary = [];
        $day = 1;
        foreach ($validated['itinerary'] as $item) {
            $formattedItinerary[] = [
                'hari' => $day++,
                'judul' => $item['judul'],
                'aktivitas' => $item['aktivitas'],
            ];
        }

        $paket = PaketWisata::create([
            'nama_paket' => $validated['nama_paket'],
            'destinasi' => $validated['destinasi'],
            'jenis_layanan' => $validated['jenis_layanan'],
            'harga' => $validated['harga'],
            'durasi_hari' => $validated['durasi_hari'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'itinerary' => $formattedItinerary,
            'fasilitas' => $validated['fasilitas'],
            'fasilitas_tidak_termasuk' => $validated['fasilitas_tidak_termasuk'] ?? null,
            'meeting_point' => $meetingPointValue,
            'jam_kumpul' => $validated['jam_kumpul'],
            'foto' => $fotoPath,
            'status_aktif' => true,
        ]);

        LogAktivitas::catat(
            aksi: 'tambah_paket',
            subjekTipe: 'PaketWisata',
            subjekId: $paket->id,
            keterangan: "Paket wisata '{$paket->nama_paket}' ditambahkan."
        );

        return redirect()->route('admin.paket.index', ['selected' => $paket->id])
            ->with('sukses', 'Paket wisata berhasil ditambahkan.');
    }

    /**
     * Form Ubah Paket Wisata.
     */
    public function edit(int $id): View
    {
        $paket = PaketWisata::findOrFail($id);
        $kendaraanList = Kendaraan::tersedia()->get();
        return view('admin.paket.edit', compact('paket', 'kendaraanList'));
    }

    /**
     * Simpan Perubahan Paket Wisata.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $paket = PaketWisata::findOrFail($id);

        $validated = $request->validate([
            'nama_paket' => 'required|string|max:150',
            'destinasi' => 'required|string|max:100',
            'jenis_layanan' => 'required|in:open_trip,paket_reguler',
            'harga' => 'required|numeric|min:1',
            'durasi_hari' => 'required|integer|min:1|max:30',
            'deskripsi' => 'nullable|string',
            'itinerary' => 'required|array|min:1',
            'itinerary.*.judul' => 'required|string',
            'itinerary.*.aktivitas' => 'required|string',
            'fasilitas' => 'required|string',
            'fasilitas_tidak_termasuk' => 'nullable|string',
            'meeting_point' => 'nullable|string',
            'meeting_points' => 'nullable|array',
            'meeting_points.*' => 'nullable|string',
            'jam_kumpul' => 'required',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png|max:5120',
            'foto_url' => 'nullable|string|max:500',
        ]);

        // Resolusi meeting point: bisa array multipel meeting point atau input tunggal
        $points = [];
        if ($request->has('meeting_points') && is_array($request->meeting_points)) {
            $points = array_values(array_filter(array_map('trim', $request->meeting_points)));
        }
        if (empty($points) && $request->filled('meeting_point')) {
            $points = [trim($request->meeting_point)];
        }
        $meetingPointValue = !empty($points) ? (count($points) > 1 ? json_encode($points) : $points[0]) : $paket->meeting_point;

        // Resolusi foto
        $fotoPath = $paket->foto;
        if ($request->hasFile('foto')) {
            if ($paket->foto && !str_starts_with($paket->foto, 'http')) {
                Storage::disk('public')->delete($paket->foto);
            }
            $fotoPath = $request->file('foto')->store('paket_foto', 'public');
        } elseif ($request->filled('foto_url')) {
            $fotoPath = $request->input('foto_url');
        }

        $formattedItinerary = [];
        $day = 1;
        foreach ($validated['itinerary'] as $item) {
            $formattedItinerary[] = [
                'hari' => $day++,
                'judul' => $item['judul'],
                'aktivitas' => $item['aktivitas'],
            ];
        }

        $paket->update([
            'nama_paket' => $validated['nama_paket'],
            'destinasi' => $validated['destinasi'],
            'jenis_layanan' => $validated['jenis_layanan'],
            'harga' => $validated['harga'],
            'durasi_hari' => $validated['durasi_hari'],
            'deskripsi' => $validated['deskripsi'] ?? null,
            'itinerary' => $formattedItinerary,
            'fasilitas' => $validated['fasilitas'],
            'fasilitas_tidak_termasuk' => $validated['fasilitas_tidak_termasuk'] ?? null,
            'meeting_point' => $meetingPointValue,
            'jam_kumpul' => $validated['jam_kumpul'],
            'foto' => $fotoPath,
        ]);

        LogAktivitas::catat(
            aksi: 'ubah_paket',
            subjekTipe: 'PaketWisata',
            subjekId: $paket->id,
            keterangan: "Paket wisata '{$paket->nama_paket}' diperbarui."
        );

        return redirect()->route('admin.paket.index', ['selected' => $paket->id])
            ->with('sukses', 'Perubahan berhasil disimpan.');
    }

    /**
     * Hapus Paket Wisata (BR-12, US-17, T-14).
     */
    public function destroy(int $id): RedirectResponse
    {
        $paket = PaketWisata::findOrFail($id);

        // Periksa apakah ada jadwal dengan pemesanan aktif
        $totalPemesananAktif = $paket->jadwal()
            ->whereHas('pemesanan', function ($q) {
                $q->whereIn('status', ['menunggu', 'dikonfirmasi']);
            })
            ->count();

        if ($totalPemesananAktif > 0) {
            return back()->with('error', 'Data ini sudah dipakai pemesanan dan tidak bisa dihapus. Nonaktifkan saja.');
        }

        // Hapus foto jika ada
        if ($paket->foto && !str_starts_with($paket->foto, 'http')) {
            Storage::disk('public')->delete($paket->foto);
        }

        // Hapus jadwal kosong yang terkait
        $paket->jadwal()->delete();
        $namaPaket = $paket->nama_paket;
        $paket->delete();

        LogAktivitas::catat(
            aksi: 'hapus_paket',
            subjekTipe: 'PaketWisata',
            subjekId: $id,
            keterangan: "Paket wisata '{$namaPaket}' dihapus."
        );

        return redirect()->route('admin.paket.index')
            ->with('sukses', "Paket wisata '{$namaPaket}' berhasil dihapus.");
    }
}
