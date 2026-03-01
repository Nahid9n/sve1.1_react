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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('supplier_id');
            $table->unsignedBigInteger('payment_method_id');
            $table->string('memo_no');
            $table->string('remarks');
            $table->decimal('subtotal', 10, 4)->default(0);
            $table->decimal('discount', 10, 4)->default(0);
            $table->decimal('total', 10, 4)->default(0);
            $table->decimal('paid_amount', 10, 4)->default(0);
            $table->decimal('previous_due', 10, 4)->default(0);
            $table->decimal('due_amount', 10, 4)->default(0);
            $table->string('purchase_date');
            $table->text('note')->nullable();
            $table->tinyInteger('status')->default(0)->comment('0=pending; 1=ordered; 2=received');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
