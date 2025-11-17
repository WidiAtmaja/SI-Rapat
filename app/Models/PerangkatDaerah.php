<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerangkatDaerah extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_perangkat_daerah',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'perangkat_daerah_id');
    }

    /**
     * Relasi Many-to-Many ke Rapat
     * Satu perangkat daerah bisa diundang ke banyak rapat.
     */
    public function rapats()
    {
        return $this->belongsToMany(Rapat::class, 'perangkat_daerah_rapat', 'perangkat_daerah_id', 'rapat_id');
    }
}
