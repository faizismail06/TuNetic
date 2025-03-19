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
            $table->foreignId('id_penugasan')->constrained('penugasan_armada');
            $table->foreignId('id_jadwal')->constrained('jadwal');
            $table->foreignId('id_rute')->constrained('rute');
            $table->date('tanggal');
            $table->time('jam_aktif');
            $table->enum('status', ['Belum Berjalan', 'Sedang Berjalan', 'Selesai']);
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
