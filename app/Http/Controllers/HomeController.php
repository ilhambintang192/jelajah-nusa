<?php

namespace App\Http\Controllers;

use App\Models\PaketWisata;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Beranda Utama Wisatawan (Landing Page Jelajah Nusa).
     * Memperkenalkan layanan travel, paket unggulan, open trip terdekat,
     * alur pemesanan, dan keunggulan bisnis agensi.
     */
    public function index(): View
    {
        // Daftar destinasi unik untuk dropdown quick search
        $destinasiList = PaketWisata::aktif()->distinct()->pluck('destinasi');

        // Paket Unggulan Terpilih untuk Showcase di Beranda (Maks. 6 Paket)
        $paketUnggulan = PaketWisata::aktif()
            ->with(['jadwal' => function ($q) {
                $q->where('tanggal_berangkat', '>=', now()->toDateString())
                  ->whereIn('status_keberangkatan', ['dijadwalkan', 'dikonfirmasi'])
                  ->orderBy('tanggal_berangkat', 'asc');
            }])
            ->latest('id')
            ->take(6)
            ->get();

        // Paket Open Trip yang Segera Berangkat dengan Kuota Real-time
        $openTrips = PaketWisata::aktif()
            ->where('jenis_layanan', 'open_trip')
            ->with(['jadwal' => function ($q) {
                $q->where('tanggal_berangkat', '>=', now()->toDateString())
                  ->whereIn('status_keberangkatan', ['dijadwalkan', 'dikonfirmasi'])
                  ->orderBy('tanggal_berangkat', 'asc');
            }])
            ->take(4)
            ->get();

        return view('wisatawan.beranda', compact('destinasiList', 'paketUnggulan', 'openTrips'));
    }
}
