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
        Schema::create('rekap_gajis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('bulan');
            $table->integer('total_hari_kerja');
            $table->integer('total_lembur');
            $table->integer('total_potongan');
            $table->integer('gaji_pokok');
            $table->integer('gaji_lembur');
            $table->integer('gaji_bersih');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekap_gajis');
    }
};
