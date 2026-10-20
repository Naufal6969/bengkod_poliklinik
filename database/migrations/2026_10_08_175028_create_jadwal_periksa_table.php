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
        Schema::create('jadwal_periksa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dokter_id')->constrained('dokter');
            $table->string('hari', 10);
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->decimal('tarif_periksa', 12, 2)->unsigned()->default(0);
            $table->unique(['dokter_id', 'hari', 'jam_mulai']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_periksa');
    }
};
