<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->integer('no_telepon')->nullable();
            $table->string('gambar')->nullable();
            $table->char('province_id', 2)->nullable(); // Sesuai dengan tipe data di reg_provinces
            $table->char('regency_id', 4)->nullable(); // Sesuai dengan tipe data di reg_regencies
            $table->char('district_id', 7)->nullable(); // Sesuai dengan tipe data di reg_districts
            $table->char('village_id', 10)->nullable(); // Sesuai dengan tipe data di reg_villages

            $table->string('alamat')->nullable();
            // $table->integer('role')->default(0);
            $table->integer('level')->default(0);
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('province_id')->references('id')->on('reg_provinces')->onDelete('cascade');
            $table->foreign('regency_id')->references('id')->on('reg_regencies')->onDelete('cascade');
            $table->foreign('district_id')->references('id')->on('reg_districts')->onDelete('cascade');
            $table->foreign('village_id')->references('id')->on('reg_villages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
