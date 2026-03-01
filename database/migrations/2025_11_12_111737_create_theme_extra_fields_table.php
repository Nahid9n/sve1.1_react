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
        Schema::create('theme_extra_fields', function (Blueprint $table) {
            $table->id();
            $table->string('theme_path');
            $table->integer('theme_id');
            $table->string('model_type');
            $table->string('field_name');
            $table->string('field_label')->nullable();
            $table->string('field_type')->default('text');
            $table->json('options')->nullable();
            $table->boolean('is_required')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('theme_extra_fields');
    }
};
