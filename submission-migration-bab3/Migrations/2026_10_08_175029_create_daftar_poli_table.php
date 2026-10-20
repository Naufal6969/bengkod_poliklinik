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
        Schema::create('daftar_poli', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pasien_id')->constrained('pasien');
            $table->foreignId('jadwal_periksa_id')->constrained('jadwal_periksa');
            $table->date('tanggal_daftar');
            $table->unsignedSmallInteger('no_antrian');
            $table->text('keluhan');
            $table->string('status', 20)->default('menunggu');
            $table->unique(['jadwal_periksa_id', 'tanggal_daftar', 'no_antrian']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daftar_poli');
    }
};
