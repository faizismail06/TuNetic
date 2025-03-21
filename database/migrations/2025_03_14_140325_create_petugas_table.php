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
        Schema::create('petugas', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('name');
            $table->string('username');
            $table->string('password');
            $table->bigInteger('nomor')->nullable();
            $table->date('tanggal_lahir')->nullable(); // Tambahkan kolom tanggal_lahir
            $table->string('alamat')->nullable();
            $table->string('sim_image')->nullable(); // Kolom untuk upload gambar SIM
            $table->string('alasan bergabung');
            $table->enum('role', ['Petugas'])->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('petugas');
    }
};
