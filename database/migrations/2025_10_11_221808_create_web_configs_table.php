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
            $table->text('option');
           $table->text('value');
        });
        $data = [
            ['option'=>'site_name','value'=>'JDM'],
            ['option'=>'from_email_address','value'=>'info@jdm-distributors.co.uk'],
            ['option'=>'copyright_text','value'=>'Copyright © JDM Distributors services. All rights reserved.'],
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
