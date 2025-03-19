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
        Schema::create('sampah', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_lokasi')->constrained('lokasi_tps');
            $table->enum('jenis_sampah', ['Organik', 'Anorganik', 'B3', 'Plastik', 'Kertas', 'Elektronik']);
            $table->float('berat');
            $table->date('tanggal_pengangkutan');
            $table->enum('status', ['Unverified','Belum Diangkut', 'Diangkut']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sampah');
    }
};
