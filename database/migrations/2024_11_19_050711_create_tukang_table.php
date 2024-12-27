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
        Schema::create('tukang', function (Blueprint $table) {
            $table->id('id_tukang');
            $table->string('nama_tukang');
            $table->string('nomorhp_tukang');
            $table->enum('status_tukang', ['available', 'not available'])->default('available');
            $table->timestamps();
        });

        Schema::create('keahlian_tukang', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tukang_id');
            $table->string('keahlian');

            $table->foreign('tukang_id')->references('id_tukang')->on('tukang')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keahlian_tukang');
        Schema::dropIfExists('tukang');
    }
};
