<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jurusan extends Model
{
    protected $table = 'jurusan';

    protected $fillable = [
        'kode_jurusan', 'nama_jurusan', 'deskripsi', 'kuota', 'is_aktif',
    ];

    protected $casts = [
        'is_aktif' => 'boolean',        // Cast ke boolean otomatis
        'kuota'    => 'integer',
    ];

    /** Jurusan punya banyak pendaftaran */
    public function pendaftaran()
    {
        return $this->hasMany(Pendaftaran::class, 'id_jurusan');
    }

    /** Hitung jumlah yang diterima di jurusan ini */
    public function getSiswaditerimaAttribute(): int
    {
        return $this->pendaftaran()->where('status', 'diterima')->count();
    }

    /** Sisa kuota yang tersedia */
    public function getSisaKuotaAttribute(): int
    {
        return max(0, $this->kuota - $this->siswa_diterima);
    }
}
