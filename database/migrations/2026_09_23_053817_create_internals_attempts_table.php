<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('internals_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('internals_topic_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('prompt_index')->default(0);
            $table->text('answer')->nullable();
            $table->unsignedInteger('score')->default(0);
            $table->boolean('is_complete')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('internals_attempts');
    }
};
