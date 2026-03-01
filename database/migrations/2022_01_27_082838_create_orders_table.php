<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->date('order_date')->nullable();
            $table->string('invoice_id')->unique();
            $table->string('memo_number')->nullable();
            $table->integer('customer_id')->nullable();
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('customer_email')->nullable();
            $table->text('customer_address');
            $table->integer('courier_id')->nullable();
            $table->integer('courier_city_id')->nullable();
            $table->integer('courier_zone_id')->nullable();
            $table->integer('payment_method')->nullable();
            $table->tinyInteger('shipping_method')->nullable();
            $table->double('shipping_cost')->default(0)->nullable();
            $table->double('discount')->default(0)->nullable();
            $table->double('sub_total')->default(0)->nullable();
            $table->double('total')->default(0)->nullable();
            $table->tinyInteger('status')->default(0)->comment('0=pending/on hold, 1=delivered, 2=processing, 3=pending payment, 4=canceled');
            $table->string('order_note')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
