<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Notulen extends Model
{
    use HasFactory;

    protected $fillable = [
        'rapat_id',
        'user_id',
        'ringkasan',
        'lampiran_file',
    ];

    protected static function booted(): void
    {
        static::deleting(function (Notulen $notulen) {
            if ($notulen->lampiran_file) {
                Storage::disk('public')->delete($notulen->lampiran_file);
            }
        });
    }

    public function rapat()
    {
        return $this->belongsTo(Rapat::class, 'rapat_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
