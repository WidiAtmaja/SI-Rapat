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
        Schema::table('absensis', function (Blueprint $table) {
            $table->string('foto_wajah')->nullable()->after('kehadiran');
            $table->string('foto_zoom')->nullable()->after('foto_wajah');
            $table->longText('tanda_tangan')->nullable()->after('foto_zoom');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('absensis', function (Blueprint $table) {
            $table->dropColumn(['foto_wajah', 'foto_zoom', 'tanda_tangan']);
        });
    }
};
