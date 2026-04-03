<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Berkas extends Model
{
    protected $table = 'berkas';

    protected $fillable = [
        'id_pendaftaran', 'jenis_berkas', 'nama_file',
        'path_file', 'status_berkas', 'catatan_berkas',
    ];

    /** Relasi ke pendaftaran */
    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class, 'id_pendaftaran');
    }

    /** URL publik file yang tersimpan di storage */
    public function getUrlFileAttribute(): string
    {
        return Storage::url($this->path_file);  // Ambil URL dari Laravel storage
    }

    /** Label jenis berkas yang readable */
    public static function labelJenis(): array
    {
        return [
            'ijazah'       => 'Ijazah / SKL',
            'kk'           => 'Kartu Keluarga',
            'akta'         => 'Akta Kelahiran',
            'raport'       => 'Raport SMP',
            'foto'         => 'Pas Foto 3x4',
            'surat_ket'    => 'Surat Keterangan Lainnya',
        ];
    }
}
