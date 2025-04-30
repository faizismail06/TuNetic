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
        Schema::create('penugasan_petugas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_petugas')->constrained('petugas');
            $table->foreignId('id_jadwal_operasional')->constrained('jadwal_operasional')->onDelete('cascade');
            $table->integer('tugas')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penugasan_petugas');
    }
};
