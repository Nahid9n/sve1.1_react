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
        Schema::create('abandoned_carts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('shipping_id')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();
            $table->text('customer_address')->nullable();
            $table->text('abandoned_item')->nullable();
            $table->decimal('shipping_cost', 10, 4)->default(0);
            $table->decimal('discount', 10, 4)->default(0);
            $table->decimal('subtotal', 10, 4)->default(0);
            $table->decimal('total', 10, 4)->default(0);
            $table->string('source')->nullable();
            $table->text('note')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('abandoned_carts');
    }
};
