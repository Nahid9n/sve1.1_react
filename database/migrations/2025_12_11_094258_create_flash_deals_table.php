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
        Schema::create('flash_deals', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->timestamp('start_time')->nullable();
            $table->timestamp('end_time')->nullable();
            $table->decimal('discount', 10, 2)->default(0);
            $table->string('image')->nullable();
            $table->string('discount_type')->nullable();
            $table->boolean('status')->default(true); // active / inactive
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flash_deals');
    }
};
