<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalonSiswa extends Model
{
    use HasFactory;

    protected $table = 'calon_siswa';   // Nama tabel eksplisit

    protected $fillable = [
        'nik', 'nisn', 'nama_lengkap', 'jenis_kelamin',
        'tanggal_lahir', 'tempat_lahir', 'agama',
        'alamat', 'kelurahan', 'kecamatan', 'kota', 'provinsi', 'kode_pos',
        'no_telepon', 'asal_sekolah', 'tahun_lulus', 'nilai_rata_rata', 'foto',
    ];

    protected $casts = [
        'tanggal_lahir'  => 'date',         // Otomatis cast ke Carbon
        'nilai_rata_rata' => 'decimal:2',
    ];

    // ------- RELASI -------

    /** Setiap calon siswa punya satu user akun */
    public function user()
    {
        return $this->hasOne(User::class, 'id_siswa');
    }

    /** Setiap calon siswa punya satu data orang tua */
    public function orangTua()
    {
        return $this->hasOne(OrangTua::class, 'id_siswa');
    }

    /** Satu siswa bisa punya banyak pendaftaran (meski biasanya 1) */
    public function pendaftaran()
    {
        return $this->hasMany(Pendaftaran::class, 'id_siswa');
    }

    /** Ambil pendaftaran aktif/terbaru */
    public function pendaftaranAktif()
    {
        return $this->hasOne(Pendaftaran::class, 'id_siswa')->latest();
    }

    // ------- HELPER -------

    /** Mendapatkan URL foto siswa */
    public function getFotoUrlAttribute(): string
    {
        return $this->foto
            ? asset('storage/' . $this->foto)
            : asset('images/default-avatar.png');
    }
}
