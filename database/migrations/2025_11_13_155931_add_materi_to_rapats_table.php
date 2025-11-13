<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rapats', function (Blueprint $table) {
            $table->string('materi')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('rapats', function (Blueprint $table) {
            $table->dropColumn('materi');
        });
    }
};
