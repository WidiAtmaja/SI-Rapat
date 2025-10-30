<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rapat extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'nama_perangkat_daerah',
        'pic_id',
        'tanggal',
        'link_zoom',
        'lokasi',
        'waktu_mulai',
        'waktu_selesai',
        'status',
    ];

    protected static function booted(): void
    {
        static::deleting(function (Rapat $rapat) {
            $rapat->notulensi()->each(function ($notulen) {
                $notulen->delete();
            });
            $rapat->absensis()->delete();
        });
    }

    public function pic()
    {
        return $this->belongsTo(User::class, 'pic_id');
    }

    public function notulensi()
    {
        return $this->hasMany(Notulen::class, 'rapat_id');
    }

    public function absensis()
    {
        return $this->hasMany(Absensi::class, 'rapat_id');
    }
}
