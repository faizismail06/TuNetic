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
        Schema::table('jadwal_operasional', function (Blueprint $table) {
            //
            $table->dropForeign(['id_jadwal']);
        });

        Schema::table('jadwal_operasional', function (Blueprint $table) {
            // Tambahkan FK baru dengan cascading delete
            $table->foreign('id_jadwal')
                ->references('id')
                ->on('jadwal')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwal_operasional', function (Blueprint $table) {
            // Balik ke constraint FK tanpa cascade (optional)
            $table->dropForeign(['id_jadwal']);

            $table->foreign('id_jadwal')
                ->references('id')
                ->on('jadwal'); // default: restrict
        });
    }
};
