<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->text('penawaran_harga')->nullable();  // Kolom untuk menyimpan rincian penawaran harga
            $table->enum('status_penawaran', ['Menunggu Persetujuan', 'Setuju', 'Tidak Setuju'])
                  ->default('Menunggu Persetujuan');  // Kolom status persetujuan penawaran
        });
    }
    
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['penawaran_harga', 'status_penawaran']);  // Menghapus kolom ketika rollback
        });
    }
    
};
