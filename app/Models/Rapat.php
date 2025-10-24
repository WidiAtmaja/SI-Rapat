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

    public function pic()
    {
        return $this->belongsTo(User::class, 'pic_id');
    }

    public function absensis()
    {
        return $this->hasMany(Absensi::class);
    }

    public function notulen()
    {
        return $this->hasOne(Notulen::class);
    }
}
