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
        Schema::create('detail_periksa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('periksa_id')->constrained('periksa');
            $table->foreignId('obat_id')->constrained('obat');
            $table->string('dosis');
            $table->unsignedSmallInteger('jumlah')->default(1);
            $table->decimal('subtotal', 12, 2)->unsigned()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_periksa');
    }
};
