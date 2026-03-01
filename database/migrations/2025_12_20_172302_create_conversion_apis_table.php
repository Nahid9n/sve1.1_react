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
        Schema::create('conversion_apis', function (Blueprint $table) {
            $table->id();
            $table->longText('facebook_pixel')->nullable();
            $table->longText('gtm_head_script')->nullable();
            $table->longText('gtm_body_script')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversion_apis');
    }
};
