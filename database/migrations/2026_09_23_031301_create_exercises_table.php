<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exercises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('prompt');
            $table->enum('level', [1, 2, 3, 4, 5, 6])->default(1);
            $table->enum('type', ['code', 'debug', 'design', 'explain', 'production', 'senior'])->default('code');
            $table->longText('starter_code')->nullable();
            $table->longText('solution')->nullable();
            $table->text('expected_output')->nullable();
            $table->text('hints')->nullable();
            $table->unsignedInteger('order_column')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exercises');
    }
};
