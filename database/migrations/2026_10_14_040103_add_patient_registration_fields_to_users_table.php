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
        Schema::table('users', function (Blueprint $table) {
            $table->text('alamat')->nullable();
            $table->string('no_ktp', 16)->nullable()->unique();
            $table->string('no_hp', 20)->nullable();
            $table->string('no_rm')->nullable()->unique();
            $table->foreignId('id_poli')->nullable()->constrained('poli');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('id_poli');
            $table->dropUnique(['no_ktp']);
            $table->dropUnique(['no_rm']);
            $table->dropColumn(['alamat', 'no_ktp', 'no_hp', 'no_rm']);
        });
    }
};
