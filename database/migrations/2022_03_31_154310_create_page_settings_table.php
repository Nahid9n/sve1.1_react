<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePageSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('page_settings', function (Blueprint $table) {
            $table->id();
            $table->longText('about_us')->nullable();
            $table->longText('delivery_policy')->nullable();
            $table->longText('return_policy')->nullable();
            $table->longText('how_to_order')->nullable();
            $table->longText('privacy_policy')->nullable();
            $table->longText('terms_condition')->nullable();
            $table->longText('why_us')->nullable();
            $table->longText('contact_us')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('page_settings');
    }
}
