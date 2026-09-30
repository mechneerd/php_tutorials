<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('design_cases', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 100)->unique();
            $table->string('title');
            $table->enum('level', ['mid', 'senior'])->default('senior');
            $table->enum('track', ['product', 'infra', 'data'])->default('product');
            $table->text('prompt');
            $table->json('constraints')->nullable();
            $table->json('rubric')->nullable();
            $table->longText('model_answer_html')->nullable();
            $table->json('follow_ups')->nullable();
            $table->unsignedInteger('est_minutes')->default(45);
            $table->unsignedInteger('order_column')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('design_cases');
    }
};
