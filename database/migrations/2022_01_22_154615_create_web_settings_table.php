<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('web_settings', function (Blueprint $table) {
            $table->id();
            $table->string('website_address')->nullable();
            $table->string('website_phone')->nullable();
            $table->string('website_phone2')->nullable();
            $table->string('website_phone3')->nullable();
            $table->string('website_email')->nullable();
            $table->string('website_email2')->nullable();
            $table->string('website_facebook')->nullable();
            $table->string('website_twitter')->nullable();
            $table->string('website_youtube')->nullable();
            $table->string('website_instagram')->nullable();
            $table->integer('website_header_logo')->nullable();
            $table->integer('website_favicon')->nullable();
            $table->text('website_copyright_text')->nullable();
            $table->string('currency_sign')->nullable();
            $table->string('bkash_merchant_numb')->nullable();
            $table->text('fb_pixel')->nullable();
            $table->timestamps();
        });

        DB::table('web_settings')->insert([
            'website_address' => 'Uttara, Dhaka',
            'website_phone' => '+8801681636068',
            'website_email' => 'info@website.com',
            'website_facebook' => 'https://www.facebook.com',
            'website_twitter' => 'https://www.twitter.com',
            'website_youtube' => 'https://www.youtube.com',
            'website_instagram' => 'https://www.instagram.com',
            'website_copyright_text' => '<i class="fa fa-copyright"></i> 2022 <a href="https://website.com/" target="_blank">Ecommerce</a> All Right Reserved.',
            'currency_sign' => '৳',
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('web_settings');
    }
}
