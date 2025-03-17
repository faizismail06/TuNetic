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
        Schema::create('armada', function (Blueprint $table) {
            $table->id();
            $table->string('kode_armada');
            $table->string('jenis_kendaraan');
            $table->string('kapasitas');
            $table->enum('status', ['Aktif', 'Nonaktif']);
            $table->string('jam_aktif');
            $table->string('user_id'); //FK User(Petugas)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('armada');
    }
};
