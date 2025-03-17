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
        Schema::create('master_data', function (Blueprint $table) {
            $table->id('id_master');
            $table->foreignId('id_user')->constrained('users');
            $table->foreignId('id_sampah')->constrained('sampah');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_data');
    }
};
