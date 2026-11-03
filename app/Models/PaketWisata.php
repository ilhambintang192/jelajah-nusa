<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class PaketWisata extends Model
{
    use HasFactory;

    protected $table = 'paket_wisata';

    protected $fillable = [
        'nama_paket',
        'destinasi',
        'jenis_layanan',
        'harga',
        'durasi_hari',
        'deskripsi',
        'itinerary',
        'fasilitas',
        'fasilitas_tidak_termasuk',
        'meeting_point',
        'jam_kumpul',
        'foto',
        'status_aktif',
    ];

    protected function casts(): array
    {
        return [
            'harga' => 'decimal:2',
            'durasi_hari' => 'integer',
            'itinerary' => 'array',
            'status_aktif' => 'boolean',
        ];
    }

    public function jadwal(): HasMany
    {
        return $this->hasMany(JadwalKeberangkatan::class, 'paket_wisata_id');
    }

    public function pemesanan(): HasManyThrough
    {
        return $this->hasManyThrough(Pemesanan::class, JadwalKeberangkatan::class, 'paket_wisata_id', 'jadwal_id');
    }

    public function scopeAktif($query)
    {
        return $query->where('status_aktif', true);
    }

    public function getHargaFormatAttribute(): string
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }

    public function getDurasiLabelAttribute(): string
    {
        $malam = max(0, $this->durasi_hari - 1);
        return "{$this->durasi_hari}H{$malam}M";
    }

    public function getJadwalMendatangAttribute()
    {
        return $this->jadwal()
            ->where('tanggal_berangkat', '>=', now()->toDateString())
            ->whereIn('status_keberangkatan', ['dijadwalkan', 'dikonfirmasi'])
            ->orderBy('tanggal_berangkat', 'asc')
            ->first();
    }

    /**
     * URL Foto Paket Wisata (Upload Lokal atau Kurasi Resolusi Tinggi).
     */
    public function getFotoUrlAttribute(): string
    {
        if (!empty($this->foto)) {
            if (str_starts_with($this->foto, 'http://') || str_starts_with($this->foto, 'https://')) {
                return $this->foto;
            }
            return asset('storage/' . $this->foto);
        }

        // Gambar kurasi resolusi tinggi sesuai destinasi
        $text = strtolower(($this->destinasi ?? '') . ' ' . ($this->nama_paket ?? ''));
        if (str_contains($text, 'bromo') || str_contains($text, 'semeru')) {
            return 'https://images.unsplash.com/photo-1588668214407-6ea9a6d8c272?auto=format&fit=crop&w=1200&q=80';
        } elseif (str_contains($text, 'bali') || str_contains($text, 'penida')) {
            return 'https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&w=1200&q=80';
        } elseif (str_contains($text, 'bajo') || str_contains($text, 'komodo')) {
            return 'https://images.unsplash.com/photo-1516690561799-46d8f74f9abf?auto=format&fit=crop&w=1200&q=80';
        } elseif (str_contains($text, 'raja ampat') || str_contains($text, 'papua')) {
            return 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1200&q=80';
        } elseif (str_contains($text, 'jogja') || str_contains($text, 'borobudur')) {
            return 'https://images.unsplash.com/photo-1596402184320-417e7178b2cd?auto=format&fit=crop&w=1200&q=80';
        }

        return 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?auto=format&fit=crop&w=1200&q=80';
    }

    /**
     * Daftar Titik Kumpul / Meeting Points (Dukungan Multipel Meeting Point).
     *
     * @return array<int, string>
     */
    public function getMeetingPointsAttribute(): array
    {
        if (empty($this->meeting_point)) {
            return [];
        }

        // Jika tersimpan sebagai format JSON array
        $decoded = json_decode($this->meeting_point, true);
        if (is_array($decoded)) {
            return array_values(array_filter(array_map('trim', $decoded)));
        }

        // Jika dipisahkan oleh baris baru (\n)
        if (str_contains($this->meeting_point, "\n")) {
            return array_values(array_filter(array_map('trim', explode("\n", $this->meeting_point))));
        }

        // Jika dipisahkan oleh karakter pipe (|)
        if (str_contains($this->meeting_point, '|')) {
            return array_values(array_filter(array_map('trim', explode('|', $this->meeting_point))));
        }

        return [trim($this->meeting_point)];
    }

    /**
     * Titik Kumpul Utama (Titik Pertama).
     */
    public function getPrimaryMeetingPointAttribute(): string
    {
        $points = $this->meeting_points;
        return $points[0] ?? ($this->meeting_point ?? 'Dikonfirmasi Tour Leader');
    }
}
