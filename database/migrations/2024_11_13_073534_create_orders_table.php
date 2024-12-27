<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use League\CommonMark\Reference\Reference;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->nullable();
            
            $table->longText('lokasi')->nullable();
            $table->string('no_handphone')->nullable();
            $table->longText('info_tambahan')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->date('order_date')->nullable();
            
            
            $table->enum('status', [
                'in progress', 
                'Proses Survei Tukang', 
                'Proses Pengerjaan', 
                'Menunggu Pembayaran', 
                'Pembayaran Berhasil', 
                'Order Selesai'
            ])->default('in progress');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
