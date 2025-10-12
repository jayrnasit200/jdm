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
            $table->string('price');
            $table->string('barcode')->nullable();
            $table->enum('status', ['enable', 'disable'])->default('enable');
            $table->foreign('categories_id')->references('id')->on('Categories');
            $table->foreign('subcategories_id')->references('id')->on('subcategories');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
