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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->enum('type', ['fixed', 'percentage'])->default('fixed');
            $table->decimal('amount', 10, 2);
            $table->decimal('max_discount', 10, 2)->nullable();
            $table->decimal('min_purchase', 10, 2)->default(0);
            $table->enum('apply_on', ['cart', 'product', 'shipping', 'payment', 'first_time'])->default('cart');
            $table->json('product_ids')->nullable(); // product-specific coupon
            $table->string('payment_method')->nullable(); // payment-specific
            $table->integer('usage_limit')->nullable();
            $table->integer('used_count')->default(0);
            $table->integer('per_user_limit')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
