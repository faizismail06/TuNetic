<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('laporan_tps', function (Blueprint $table) {
            $table->dropForeign(['id_jadwal_operasional']);

            $table->foreign('id_jadwal_operasional')
                  ->references('id')->on('jadwal_operasional')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('laporan_tps', function (Blueprint $table) {
            $table->dropForeign(['id_jadwal_operasional']);

            $table->foreign('id_jadwal_operasional')
                  ->references('id')->on('jadwal_operasional');
        });
    }
};