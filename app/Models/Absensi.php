<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $fillable = [
        'rapat_id',
        'user_id',
        'kehadiran',
        'foto_wajah',
        'foto_zoom',
        'tanda_tangan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rapat()
    {
        return $this->belongsTo(Rapat::class, 'rapat_id');
    }
}
