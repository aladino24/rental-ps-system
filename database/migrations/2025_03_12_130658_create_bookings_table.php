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
   
        // Tabel Bookings (Menyimpan informasi pemesanan)
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); 
            $table->date('date'); 
            $table->string('service');
            $table->integer('session_duration');
            $table->integer('base_price'); 
            $table->integer('total_price'); 
            $table->string('status')->default('pending'); // pending, confirmed, canceled
            $table->integer('surcharge'); 
            $table->string('payment_status')->default('pending'); // pending, success, failed
            $table->timestamps();
        });

        // Tabel Pembayaran (Untuk Midtrans)
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->string('payment_gateway')->default('midtrans'); 
            $table->string('transaction_id')->unique(); 
            $table->string('payment_method')->nullable(); 
            $table->integer('amount'); 
            $table->string('status'); // pending, success, failed
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('bookings');
        Schema::dropIfExists('users');
    }
};
