<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('theme_id')->nullable();
            $table->string('sku')->unique();
            $table->integer('thumb')->nullable();
            $table->integer('image')->nullable();
            $table->string('gallery_images')->nullable();
            $table->string('name');
            $table->string('slug')->unique();
            $table->integer('stock')->default(0)->nullable();
            $table->longText('description')->nullable();
            $table->decimal('regular_price', 10, 4)->default(0)->nullable();
            $table->decimal('sale_price', 10, 4)->default(0)->nullable();
            $table->decimal('purchase_price', 10, 4)->default(0)->nullable();
            $table->tinyInteger('has_variant')->default(0);
            $table->tinyInteger('status')->default(0);
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
        Schema::dropIfExists('products');
    }
}
