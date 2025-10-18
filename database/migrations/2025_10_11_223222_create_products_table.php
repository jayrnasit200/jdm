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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('model_number')->unique();
            $table->string('name');
            $table->integer('categories_id');
            $table->integer('subcategories_id');
            $table->text('description')->nullable();
            $table->text('image');
            $table->text('backimage')->nullable();;
            $table->text('nutritionimage')->nullable();;
            // $table->string('price');
            $table->decimal('price', 10, 2); // 10 digits total, 2 decimals

            $table->string('barcode')->nullable();
            $table->enum('vat', ['yes', 'no'])->default('yes');
            $table->enum('status', ['enable', 'disable'])->default('enable');
            $table->enum('special_offer', ['yes', 'no'])->default('no');
            
            // $table->foreign('categories_id')->references('id')->on('Categories');
            // $table->foreign('subcategories_id')->references('id')->on('subcategories');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
