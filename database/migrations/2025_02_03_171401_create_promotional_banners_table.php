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
        Schema::create('promotional_banners', function (Blueprint $table) {
            $table->id();
            $table->string('promo_banner_1_url')->nullable();
            $table->string('promo_banner_file_1_url')->nullable();
            $table->tinyInteger('promo_banner_1_status')->nullable();
            $table->string('promo_banner_2_url')->nullable();
            $table->string('promo_banner_file_2_url')->nullable();
            $table->tinyInteger('promo_banner_2_status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotional_banners');
    }
};
