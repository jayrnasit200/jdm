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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            // $table->integer('user_id');
            // $table->string('invoice_number');
            // $table->integer('user_addresses_id');
            // $table->integer('payment_id');
            // $table->text('comments_about_your_order')->nullable();
            // $table->enum('status',['padding','success'])->default('padding');
            // $table->timestamps();
            // $table->foreign('user_id')->references('id')->on('users');
            // $table->foreign('user_addresses_id')->references('id')->on('user_addresses');
            // $table->foreign('payment_id')->references('id')->on('payments');
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
