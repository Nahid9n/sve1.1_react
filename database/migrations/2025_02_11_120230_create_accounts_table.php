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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('account_type')->comment('1=Bank; 2=Bkash; 3=Nagad; 4=Rocket');
            $table->string('bkash_no')->nullable();
            $table->string('rocket_no')->nullable();
            $table->string('nagad_no')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('branch_name')->nullable();
            $table->string('routing_no')->nullable();
            $table->string('bank_account_no')->nullable();
            $table->string('bank_account_name')->nullable();
            $table->decimal('balance', 10, 4)->default(0);
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('is_default')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
