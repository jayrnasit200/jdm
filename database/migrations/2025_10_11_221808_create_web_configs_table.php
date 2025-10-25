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
        Schema::create('web_configs', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();   // e.g., 'site_name', 'default_currency'
            $table->text('value')->nullable(); // store the value
            $table->timestamps();
        });
        $data = [
            ['key'=>'site_name','value'=>'JDM'],
            ['key'=>'from_email_address','value'=>'info@jdm-distributors.co.uk'],
            ['key'=>'copyright_text','value'=>'Copyright © JDM Distributors services. All rights reserved.'],
        ];
        DB::table('web_configs')->insert($data);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('web_configs');
    }
};
