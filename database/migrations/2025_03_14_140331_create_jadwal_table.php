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
        Schema::create('jadwal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_rute')->constrained('rute');
            $table->foreignId('id_armada')->constrained('armada');
            $table->date('tanggal');
            $table->string('hari');
            $table->enum('status', ['Belum Berjalan', 'Sedang Berjalan', 'Selesai']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal');
    }
};
