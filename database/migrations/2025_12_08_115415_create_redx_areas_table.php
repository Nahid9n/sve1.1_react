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
        Schema::create('redx_areas', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->integer('parent_id')->nullable();
            $table->integer('zone_id')->nullable();
            $table->integer('postal_code')->nullable();
            $table->string('district')->nullable();
            $table->string('division')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('redx_areas');
    }
};
