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
        // 1. Tabel untuk menampung data Transaksi Tiket
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');       // ID User dari Service Valdo
            $table->integer('event_id');      // ID Event dari Service Rafli
            $table->integer('quantity');      // Jumlah tiket yang dibeli
            $table->decimal('total_price', 10, 2);
            $table->enum('status', ['PENDING', 'PAID', 'FAILED'])->default('PENDING');
            $table->timestamps();
        });

        // 2. Tabel untuk menampung data Bukti/Metode Pembayaran
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->string('payment_method'); // Misal: GOPAY, VA_BCA
            $table->string('transaction_id')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Hapus tabel payments duluan karena dia punya foreign key ke tabel orders
        Schema::dropIfExists('payments');
        Schema::dropIfExists('orders');
    }
};