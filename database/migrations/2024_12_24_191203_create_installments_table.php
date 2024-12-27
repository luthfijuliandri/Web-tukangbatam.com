<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('installments', function (Blueprint $table) {
            $table->unsignedBigInteger('installment_id', true); // Primary key menggunakan installment_id
            $table->unsignedBigInteger('order_id'); // Foreign key ke tabel orders
            $table->integer('installment_number'); // Nomor cicilan
            $table->decimal('amount', 15, 2); // Jumlah pembayaran per cicilan
            $table->enum('status', ['pending', 'paid'])->default('pending'); // Status cicilan
            $table->string('payment_proof')->nullable(); // Bukti pembayaran
            $table->timestamps();

            // Tambahkan foreign key constraint
            $table->foreign('order_id')->references('order_id')->on('orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('installments');
    }
};
