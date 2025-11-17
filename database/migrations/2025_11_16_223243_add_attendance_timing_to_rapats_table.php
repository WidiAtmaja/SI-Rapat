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
        Schema::table('rapats', function (Blueprint $table) {
            $table->dateTime('datetime_absen_buka')->nullable()->after('status');
            $table->dateTime('datetime_absen_tutup')->nullable()->after('datetime_absen_buka');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rapats', function (Blueprint $table) {
            $table->dropColumn(['datetime_absen_buka', 'datetime_absen_tutup']);
        });
    }
};
