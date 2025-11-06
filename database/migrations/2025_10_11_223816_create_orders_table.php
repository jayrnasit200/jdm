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
            $table->integer('sellar_id');
            $table->integer('shop_id');
            $table->string('invoice_number');
            $table->text('comments_about_your_order')->nullable();
            $table->text('invoice')->nullable();
            // $table->decimal('price', 10, 2);
            $table->decimal('discount', 10, 2)->nullable();
            $table->decimal('net_total', 10, 2)->nullable();
            $table->decimal('Vat', 10, 2)->nullable();
            $table->decimal('total', 10, 2)->nullable();
            $table->enum('payment_status',['padding','success'])->default('padding');
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
