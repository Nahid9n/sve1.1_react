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
        Schema::create('purchase_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('purchase_id')->nullable();
            $table->foreign('purchase_id')->references('id')->on('purchases')->onDelete('cascade');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('sku');
            $table->integer('product_quantity')->default(0);
            $table->decimal('purchase_cost', 10, 4)->default(0);
            $table->decimal('sell_price', 10, 4)->default(0);
            $table->decimal('total', 10, 4)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_items');
    }
};
