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
        Schema::table('users', function (Blueprint $table) {
            // Menambahkan kolom foreign key setelah kolom 'jabatan'
            $table->foreignId('perangkat_daerah_id')
                ->nullable() // 'admin' mungkin tidak punya PD
                ->after('jabatan') // Sesuai struktur tabel 'users' kamu
                ->constrained('perangkat_daerahs') // Ke tabel master
                ->onDelete('set null'); // Jika PD dihapus, user-nya aman
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['perangkat_daerah_id']);
            $table->dropColumn('perangkat_daerah_id');
        });
    }
};
