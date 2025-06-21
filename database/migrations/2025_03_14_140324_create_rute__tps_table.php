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
        Schema::create('rute_tps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_rute')->constrained('rute');
            $table->foreignId('id_lokasi_tps')->nullable()->constrained('lokasi_tps');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rute_tps');
    }
};
