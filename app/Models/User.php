<?php
// ============================================================
// MODEL: User.php  (app/Models/User.php)
// ============================================================
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /** Kolom yang boleh diisi massal */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'id_siswa',
    ];

    /** Kolom yang disembunyikan dari serialisasi JSON */
    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',        // Auto-hash saat set password
        ];
    }

    // ------- RELASI -------

    /** User bisa punya data calon siswa */
    public function calonSiswa()
    {
        return $this->belongsTo(CalonSiswa::class, 'id_siswa');
    }

    // ------- HELPER -------

    /** Cek apakah user adalah admin */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /** Cek apakah user adalah siswa */
    public function isSiswa(): bool
    {
        return $this->role === 'siswa';
    }
}
