<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';

    protected $fillable = [
        'id_pendaftaran', 'jumlah_tagihan', 'jumlah_bayar',
        'status_pembayaran', 'bukti_pembayaran',
        'metode_pembayaran', 'catatan_pembayaran',
        'tanggal_bayar', 'tanggal_verifikasi',
    ];

    protected $casts = [
        'jumlah_tagihan'     => 'decimal:2',
        'jumlah_bayar'       => 'decimal:2',
        'tanggal_bayar'      => 'datetime',
        'tanggal_verifikasi' => 'datetime',
    ];

    /** Relasi ke pendaftaran */
    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class, 'id_pendaftaran');
    }

    /** URL bukti pembayaran */
    public function getBuktiBayarUrlAttribute(): ?string
    {
        return $this->bukti_pembayaran
            ? Storage::url($this->bukti_pembayaran)
            : null;
    }

    /** Label status pembayaran */
    public function getStatusBadgeAttribute(): array
    {
        return match($this->status_pembayaran) {
            'belum_bayar' => ['label' => 'Belum Bayar', 'color' => 'red'],
            'dp'          => ['label' => 'DP',           'color' => 'yellow'],
            'lunas'       => ['label' => 'Lunas',        'color' => 'green'],
            default       => ['label' => '-',            'color' => 'gray'],
        };
    }
}
