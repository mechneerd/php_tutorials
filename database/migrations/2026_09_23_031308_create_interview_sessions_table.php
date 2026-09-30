<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('interview_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('level', ['beginner', 'intermediate', 'senior', 'coding', 'debugging', 'system_design', 'explain'])->default('beginner');
            $table->enum('status', ['in_progress', 'completed', 'abandoned'])->default('in_progress');
            $table->json('questions')->nullable();
            $table->json('answers')->nullable();
            $table->json('evaluation')->nullable();
            $table->unsignedTinyInteger('score')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interview_sessions');
    }
};
