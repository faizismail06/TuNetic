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
        Schema::create('laporan_tps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_jadwal_operasional')->constrained('jadwal_operasional');
            $table->float('total_sampah');
            $table->text('deskripsi');
            $table->date('tanggal_pengangkutan')->nullable(); // Tambahkan kolom tanggal_pengangkutan
            // $table->integer('status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan');
    }
};
