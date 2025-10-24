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
            $table->string('nip')->nullable()->after('id');
            $table->string('unit_kerja')->nullable()->after('name');
            $table->string('jabatan')->nullable()->after('unit_kerja');
            $table->enum('peran', ['admin', 'pegawai'])->default('pegawai')->after('jabatan');
            $table->string('no_hp')->nullable()->after('peran');
            $table->enum('jenis_kelamin', ['Laki-Laki', 'Perempuan'])->nullable()->after('no_hp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nip', 'unit_kerja', 'jabatan', 'peran', 'no_hp', 'jenis_kelamin']);
        });
    }
};
