<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('star_stories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('prompt_slug', 100)->nullable();
            $table->string('title');
            $table->text('situation')->nullable();
            $table->text('task')->nullable();
            $table->text('action')->nullable();
            $table->text('result')->nullable();
            $table->json('metrics')->nullable();
            $table->text('lessons_learned')->nullable();
            $table->json('tags')->nullable();
            $table->enum('status', ['draft', 'rehearsed', 'interview_ready'])->default('draft');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('star_stories');
    }
};
