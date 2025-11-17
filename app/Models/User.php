<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'nip',
        'unit_kerja',
        'jabatan',
        'peran',
        'no_hp',
        'jenis_kelamin',
        'perangkat_daerah_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Relasi: User (PIC) memiliki banyak Rapat.
     * Ini digunakan di UserController@destroy untuk cek relasi.
     */
    public function rapatsAsPic()
    {
        return $this->hasMany(Rapat::class, 'pic_id');
    }

    /**
     * Relasi: User (Pegawai) memiliki banyak Absensi.
     */
    public function absensis()
    {
        return $this->hasMany(Absensi::class, 'user_id');
    }

    public function perangkatDaerah()
    {
        return $this->belongsTo(PerangkatDaerah::class, 'perangkat_daerah_id');
    }
}
