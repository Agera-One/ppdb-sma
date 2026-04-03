<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrangTua extends Model
{
    protected $table = 'orang_tua';

    protected $fillable = [
        'id_siswa', 'nama_ayah', 'pekerjaan_ayah', 'pendidikan_ayah', 'no_hp_ayah',
        'nama_ibu', 'pekerjaan_ibu', 'pendidikan_ibu', 'no_hp_ibu',
        'nama_wali', 'hubungan_wali', 'no_hp_wali',
        'penghasilan_ortu', 'alamat_ortu',
    ];

    protected $casts = [
        'penghasilan_ortu' => 'decimal:2',
    ];

    /** Relasi ke calon siswa */
    public function calonSiswa()
    {
        return $this->belongsTo(CalonSiswa::class, 'id_siswa');
    }
}