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
        Schema::table('rute', function (Blueprint $table) {
            $table->dropColumn(['map', 'alamat_laporan']);
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rute', function (Blueprint $table) {
            $table->json('map')->nullable();
            $table->string('alamat_laporan')->nullable();
            $table->dropColumn(['latitude', 'longitude']);
        });
    }
};