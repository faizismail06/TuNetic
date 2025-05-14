<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('jadwal_templates', function (Blueprint $table) {
            $table->id();
            $table->string('hari'); // 'Senin', 'Selasa', dst
            $table->foreignId('id_armada')->constrained('armada')->onDelete('cascade');
            $table->foreignId('id_rute')->constrained('rute')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_templates');
    }
};
