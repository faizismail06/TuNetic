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
        Schema::create('tracking_armada', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_jadwal_operasional')->constrained('jadwal_operasional');
            $table->timestamp('timestamp');
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, places: 7);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tracking_armada');
    }
};
