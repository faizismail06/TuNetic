<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lokasi_tps', function (Blueprint $table) {
            $table->string('tipe', 10)->nullable()->default('TPS')->after('nama_lokasi');
        });
    }

    public function down(): void
    {
        Schema::table('lokasi_tps', function (Blueprint $table) {
            $table->dropColumn('tipe');
        });
    }
};