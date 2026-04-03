<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    protected $table = 'pendaftaran';

    protected $fillable = [
        'no_pendaftaran', 'id_siswa', 'id_jurusan',
        'status', 'catatan_admin', 'tanggal_verifikasi',
    ];

    protected $casts = [
        'tanggal_daftar'      => 'datetime',
        'tanggal_verifikasi'  => 'datetime',
    ];

    // ------- RELASI -------

    /** Pendaftaran milik satu calon siswa */
    public function calonSiswa()
    {
        return $this->belongsTo(CalonSiswa::class, 'id_siswa');
    }

    /** Pendaftaran memilih satu jurusan */
    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'id_jurusan');
    }

    /** Satu pendaftaran punya banyak berkas */
    public function berkas()
    {
        return $this->hasMany(Berkas::class, 'id_pendaftaran');
    }

    /** Satu pendaftaran punya satu data pembayaran */
    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'id_pendaftaran');
    }

    // ------- HELPER -------

    /** Label status dengan warna Tailwind */
    public function getStatusBadgeAttribute(): array
    {
        return match($this->status) {
            'pending'               => ['label' => 'Pending',               'color' => 'yellow'],
            'menunggu_verifikasi'   => ['label' => 'Menunggu Verifikasi',   'color' => 'blue'],
            'diterima'              => ['label' => 'Diterima',              'color' => 'green'],
            'ditolak'               => ['label' => 'Ditolak',               'color' => 'red'],
            default                 => ['label' => 'Tidak Diketahui',       'color' => 'gray'],
        };
    }

    /** Generate nomor pendaftaran otomatis */
    public static function generateNomor(): string
    {
        $tahun  = date('Y');                                    // Ambil tahun sekarang
        $urutan = static::whereYear('created_at', $tahun)->count() + 1; // Hitung urutan
        return sprintf('PPDB-%s-%04d', $tahun, $urutan);       // Format: PPDB-2025-0001
    }
}
