<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stage_id')->constrained()->cascadeOnDelete();
            $table->string('slug', 100)->unique();
            $table->string('code', 50)->unique();
            $table->string('title');
            $table->string('summary')->nullable();
            $table->longText('body_html')->nullable();
            $table->json('metadata')->nullable();
            $table->unsignedInteger('order_column')->default(0);
            $table->unsignedInteger('estimated_minutes')->default(15);
            $table->enum('difficulty', ['beginner', 'intermediate', 'advanced', 'senior'])->default('beginner');
            $table->boolean('is_published')->default(true);
            $table->boolean('requires_checkpoint')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
