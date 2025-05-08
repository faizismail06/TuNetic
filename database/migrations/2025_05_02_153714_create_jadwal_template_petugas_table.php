<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('jadwal_template_petugas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_template_id')->constrained('jadwal_templates')->onDelete('cascade');
            $table->foreignId('id_petugas')->constrained('petugas')->onDelete('cascade');
            $table->enum('tugas', [1, 2]); // 1 = Driver, 2 = Picker
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_template_petugas');
    }
};
