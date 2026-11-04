<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JadwalKeberangkatan;
use App\Models\Pemesanan;
use App\Models\Pengembalian;
use App\Services\RefundService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PengembalianController extends Controller
{
    public function __construct(
        protected RefundService $refundService
    ) {}

    /**
     * Daftar Riwayat Refund & Pemindahan Jadwal (F4-05, US-12).
     */
    public function index(Request $request): View
    {
        $riwayat = Pengembalian::with(['pemesanan.user', 'pemesanan.jadwal.paket', 'jadwalBaru.paket'])
            ->orderBy('id', 'desc')
            ->paginate(15);

        // Ambil pesanan yang terdampak penundaan/pembatalan jadwal atau pembatalan pesanan yang telah memiliki dana terbayar
        $queryTerdampak = Pemesanan::with(['user', 'jadwal.paket', 'pembayaran'])
            ->where(function ($q) {
                // Terdampak status keberangkatan jadwal ditunda / dibatalkan
                $q->whereHas('jadwal', function ($sub) {
                    $sub->whereIn('status_keberangkatan', ['ditunda', 'dibatalkan']);
                })
                // Atau pemesanan berstatus dibatalkan / dikonfirmasi
                ->orWhereIn('status', ['dibatalkan', 'dikonfirmasi']);
            })
            // Hanya pemesanan yang telah melakukan pembayaran dan diverifikasi
            ->whereHas('pembayaran', function ($q) {
                $q->where('status', 'diverifikasi');
            });

        // Filter jadwal jika dipilih
        if ($request->filled('jadwal_id')) {
            $queryTerdampak->where('jadwal_id', $request->jadwal_id);
        }

        $pemesananTerdampak = $queryTerdampak->orderBy('id', 'desc')->get();

        $jadwalTersedia = JadwalKeberangkatan::with('paket')
            ->where('tanggal_berangkat', '>=', now()->toDateString())
            ->whereIn('status_keberangkatan', ['dijadwalkan', 'dikonfirmasi'])
            ->get();

        $selectedPemesananId = $request->query('pemesanan_id');
        $selectedPemesanan = $selectedPemesananId ? $pemesananTerdampak->firstWhere('id', (int)$selectedPemesananId) : null;

        $pemesananTerdampakData = $pemesananTerdampak->map(function ($p) {
            return [
                'id' => $p->id,
                'display_kode' => $p->display_kode,
                'user_nama' => $p->user?->nama_lengkap ?? 'Wisatawan',
                'judul_trip' => $p->judul_trip,
                'total_terbayar' => (float) $p->total_terbayar,
                'total_terbayar_format' => $p->total_terbayar_format,
            ];
        })->values();

        return view('admin.pengembalian.index', compact(
            'riwayat',
            'pemesananTerdampak',
            'pemesananTerdampakData',
            'jadwalTersedia',
            'selectedPemesananId',
            'selectedPemesanan'
        ));
    }

    /**
     * Proses Refund atau Pindah Jadwal (BR-10, US-12, T-19).
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'pemesanan_id' => 'required|exists:pemesanan,id',
            'jenis' => 'required|in:refund,pindah_jadwal',
            'nominal' => 'nullable|required_if:jenis,refund|numeric|min:1',
            'jadwal_baru_id' => 'nullable|required_if:jenis,pindah_jadwal|exists:jadwal_keberangkatan,id',
            'alasan' => 'required|string|max:500',
        ], [
            'pemesanan_id.required' => 'Pemesanan wajib dipilih.',
            'jenis.required' => 'Jenis pengembalian wajib dipilih.',
            'nominal.required_if' => 'Nominal pengembalian dana wajib diisi untuk jenis refund.',
            'jadwal_baru_id.required_if' => 'Jadwal baru wajib dipilih untuk jenis pindah jadwal.',
            'alasan.required' => 'Alasan wajib diisi.',
        ]);

        $pemesanan = Pemesanan::findOrFail($validated['pemesanan_id']);

        if ($validated['jenis'] === 'refund') {
            $this->refundService->processRefund(
                $pemesanan,
                (float) $validated['nominal'],
                $validated['alasan'],
                $request->user()
            );
            return back()->with('sukses', 'Pengembalian dana (refund) berhasil dicatat.');
        } else {
            $this->refundService->processReschedule(
                $pemesanan,
                (int) $validated['jadwal_baru_id'],
                $validated['alasan'],
                $request->user()
            );
            return back()->with('sukses', 'Pemindahan jadwal berhasil diproses. Tiket baru telah diterbitkan.');
        }
    }
}
