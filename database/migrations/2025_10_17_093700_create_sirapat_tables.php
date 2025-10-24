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
        Schema::create('rapats', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('nama_perangkat_daerah');
            $table->foreignId('pic_id')->constrained('users')->onDelete('cascade');
            $table->date('tanggal');
            $table->time('waktu_mulai');
            $table->time('waktu_selesai');
            $table->string('link_zoom');
            $table->string('lokasi');
            $table->enum('status', ['terjadwal', 'sedang berlangsung', 'selesai', 'dibatalkan'])->default('terjadwal');
            $table->timestamps();
        });

        Schema::create('notulens', function (Blueprint $table) {
            $table->id();
            $table->longText('ringkasan');
            $table->string('lampiran_file')->nullable();
            $table->foreignId('rapat_id')->constrained('rapats')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('absensis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rapat_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('kehadiran', ['hadir', 'izin', 'tidak hadir']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensis');
        Schema::dropIfExists('notulens');
        Schema::dropIfExists('rapats');
    }
};
