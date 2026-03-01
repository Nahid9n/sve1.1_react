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
        Schema::create('combo_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('combo_product_id');
            $table->unsignedBigInteger('product_id');
            $table->decimal('purchase_price', 10, 4)->default(0);
            $table->decimal('regular_price', 10, 4)->default(0);
            $table->decimal('sale_price', 10, 4)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('combo_products');
    }
};
