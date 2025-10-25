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
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->text('shopname');
            $table->text('address');
            $table->text('city');
            $table->text('postcode');
            $table->text('email');
            $table->text('phone');
            $table->text('Vat')->nullable();
            $table->text('Name_staff')->nullable();
            $table->text('Staffnumber1')->nullable();
            $table->text('Staffnumber2')->nullable();
            
            $table->timestamps();
        });
    }
     
   
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shops');
    }
};
