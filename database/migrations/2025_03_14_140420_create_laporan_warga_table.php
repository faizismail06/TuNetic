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
        Schema::create('laporan_warga', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade');
            $table->foreignId('id_petugas')->nullable()->constrained('petugas')->onDelete('set null');
            $table->string('judul')->nullable();
            // $table->decimal('latitude', 10, 7);
            // $table->decimal('longitude', 10, places: 7);
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->string('gambar')->nullable();
            $table->text('deskripsi');
            $table->integer('status')->default(1);
            $table->string('alasan_ditolak')->nullable();
            $table->enum('kategori', ['Tumpukan sampah', 'TPS Penuh', 'Bau Menyengat', 'Pembuangan Liar', 'Lainnya'])->default('Tumpukan sampah');
            $table->timestamp('waktu_diambil')->nullable();
            $table->timestamp('waktu_selesai')->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_warga');
    }
};
