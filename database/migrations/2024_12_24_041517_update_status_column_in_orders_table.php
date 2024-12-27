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
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('status', [
                'in progress', 
                'Proses Survei Tukang', 
                'Proses Pengerjaan', 
                'Status Pembayaran', 
                'Pembayaran Berhasil', 
                'Order Selesai',
                'Penawaran Harga'
            ])->default('in progress')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('status', [
                'in progress', 
                'Proses Survei Tukang', 
                'Proses Pengerjaan', 
                'Menunggu Pembayaran', 
                'Pembayaran Berhasil', 
                'Order Selesai',
                'Penawaran Harga'
            ])->default('in progress')->change();
        });
    }
};
