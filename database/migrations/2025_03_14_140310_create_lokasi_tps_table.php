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
        Schema::create('lokasi_tps', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lokasi');

            $table->char('province_id', 2); // Sesuai dengan tipe data di reg_provinces
            $table->char('regency_id', 4); // Sesuai dengan tipe data di reg_regencies
            $table->char('district_id', 7); // Sesuai dengan tipe data di reg_districts
            $table->char('village_id', 10); // Sesuai dengan tipe data di reg_villages
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            // $table->integer('level')->default(0);
            $table->timestamps();

            $table->foreign('province_id')->references('id')->on('reg_provinces')->onDelete('cascade');
            $table->foreign('regency_id')->references('id')->on('reg_regencies')->onDelete('cascade');
            $table->foreign('district_id')->references('id')->on('reg_districts')->onDelete('cascade');
            $table->foreign('village_id')->references('id')->on('reg_villages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lokasi_tps');
    }
};
