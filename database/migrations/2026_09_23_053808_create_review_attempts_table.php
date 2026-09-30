<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('review_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('code_review_drill_id')->constrained()->cascadeOnDelete();
            $table->json('found_issues')->nullable();
            $table->unsignedInteger('hits')->default(0);
            $table->unsignedInteger('misses')->default(0);
            $table->unsignedInteger('false_positives')->default(0);
            $table->unsignedInteger('score')->default(0);
            $table->boolean('is_complete')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('review_attempts');
    }
};
