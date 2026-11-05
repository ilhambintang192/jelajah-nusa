<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Services\BookingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PesananSayaController extends Controller
{
    public function __construct(
        protected BookingService $bookingService
    ) {}

    /**
     * Daftar Riwayat Pesanan Wisatawan (F10-02, F10-03, W-10).
     */
    public function index(Request $request): View
    {
        $pesananList = Pemesanan::with(['jadwal.paket', 'customTrip', 'peserta', 'pembayaran'])
            ->where('user_id', $request->user()->id)
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('wisatawan.pesanan.index', compact('pesananList'));
    }

    /**
     * Detail Pesanan, Tiket, & Status Pembayaran (F10-02, F10-04, W-11).
     */
    public function show(Request $request, int $id): View
    {
        $pemesanan = Pemesanan::with([
            'jadwal.paket',
            'jadwal.kendaraan',
            'customTrip.kendaraan',
            'peserta.tiket',
            'pembayaran' => fn($q) => $q->orderBy('id', 'desc'),
            'tiket',
            'pengembalian.jadwalBaru.paket',
        ])
        ->where('user_id', $request->user()->id)
        ->findOrFail($id);

        return view('wisatawan.pesanan.show', compact('pemesanan'));
    }

    /**
     * Wisatawan Membatalkan Pesanan Sendiri (F11-04, US-16, T-22).
     */
    public function batal(Request $request, int $id): RedirectResponse
    {
        $pemesanan = Pemesanan::where('user_id', $request->user()->id)->findOrFail($id);

        if ($pemesanan->status !== 'menunggu') {
            return back()->with('error', 'Pesanan yang telah dikonfirmasi atau dibatalkan tidak dapat dibatalkan mandiri.');
        }

        $this->bookingService->cancelBooking($pemesanan, 'Dibatalkan oleh wisatawan.');

        return back()->with('sukses', 'Pesanan Anda telah berhasil dibatalkan. Kuota telah dikembalikan.');
    }

    /**
     * Wisatawan Mengajukan Pembatalan & Refund untuk Ditinjau/Diverifikasi Admin.
     */
    public function ajukanBatal(Request $request, int $id): RedirectResponse
    {
        $pemesanan = Pemesanan::where('user_id', $request->user()->id)->findOrFail($id);

        if ($pemesanan->status === 'dibatalkan') {
            return back()->with('error', 'Pesanan ini sudah dibatalkan sebelumnya.');
        }

        $validated = $request->validate([
            'alasan' => 'required|string|min:5|max:500',
            'nama_bank' => 'required|string|max:100',
            'no_rekening' => 'required|string|max:50',
            'atas_nama' => 'required|string|max:100',
            'no_whatsapp' => 'nullable|string|max:20',
        ], [
            'alasan.required' => 'Alasan pembatalan wajib diisi.',
            'alasan.min' => 'Alasan pembatalan minimal 5 karakter.',
            'nama_bank.required' => 'Nama bank atau e-wallet tujuan refund wajib diisi.',
            'no_rekening.required' => 'Nomor rekening atau e-wallet wajib diisi.',
            'atas_nama.required' => 'Nama pemilik rekening wajib diisi.',
        ]);

        $payload = [
            'alasan' => $validated['alasan'],
            'nama_bank' => $validated['nama_bank'],
            'no_rekening' => $validated['no_rekening'],
            'atas_nama' => $validated['atas_nama'],
            'no_whatsapp' => $validated['no_whatsapp'] ?? $request->user()->no_hp,
            'tanggal' => now()->translatedFormat('d F Y H:i'),
        ];

        \App\Models\LogAktivitas::catat(
            aksi: 'pengajuan_pembatalan',
            subjekTipe: 'Pemesanan',
            subjekId: $pemesanan->id,
            keterangan: json_encode($payload),
            userId: $request->user()->id
        );

        return back()->with('sukses', 'Permohonan pembatalan & pengembalian dana telah berhasil diajukan ke Admin. Tim kami akan segera meninjau dan memproses refund Anda.');
    }
}
