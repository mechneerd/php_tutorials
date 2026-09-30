<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stage_id')->nullable()->constrained()->nullOnDelete();
            $table->string('slug', 100)->unique();
            $table->string('title');
            $table->text('description');
            $table->json('requirements')->nullable();
            $table->json('milestones')->nullable();
            $table->json('evaluation_criteria')->nullable();
            $table->enum('level', ['beginner', 'intermediate', 'advanced', 'senior'])->default('beginner');
            $table->unsignedInteger('order_column')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
