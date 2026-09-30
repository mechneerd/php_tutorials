<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('internals_topics', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 100)->unique();
            $table->string('title');
            $table->string('summary')->nullable();
            $table->longText('body_html')->nullable();
            $table->json('explain_back_prompts')->nullable();
            $table->string('related_lesson_code', 50)->nullable();
            $table->unsignedInteger('order_column')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('internals_topics');
    }
};
