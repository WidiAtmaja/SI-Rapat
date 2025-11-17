<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Nama tabel pivot: urutan alfabet dari dua model
        Schema::create('perangkat_daerah_rapat', function (Blueprint $table) {
            $table->id();

            // Foreign key ke tabel 'rapats' kamu
            $table->foreignId('rapat_id')->constrained('rapats')->onDelete('cascade');

            // Foreign key ke tabel 'perangkat_daerahs' yang baru
            $table->foreignId('perangkat_daerah_id')->constrained('perangkat_daerahs')->onDelete('cascade');

            $table->timestamps();

            // Mencegah duplikat undangan di rapat yang sama
            $table->unique(['rapat_id', 'perangkat_daerah_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perangkat_daerah_rapat');
    }
};
