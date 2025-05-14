<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('jadwal_operasional', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_armada')->constrained('armada');
            $table->foreignId('id_jadwal')->constrained('jadwal');
            $table->foreignId('id_rute')->constrained('rute');
            $table->date('tanggal');
            $table->tinyInteger('status')->default(0)->comment('0 = Belum Berjalan, 1 = Sedang Berjalan, 2 = Selesai');
            $table->time('jam_aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_operasional');
    }
};
