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
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('name');
            $table->string('username');
            $table->string('password');
            $table->string('alamat')->nullable();
            $table->enum('role', ['Petugas'])->nullable();
            $table->bigInteger('nomor')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('sim_image')->nullable(); // Kolom untuk upload gambar SIM
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};
