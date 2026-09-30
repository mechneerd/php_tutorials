<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('code_review_drills', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 100)->unique();
            $table->string('title');
            $table->enum('language', ['php', 'blade', 'sql', 'yaml'])->default('php');
            $table->text('context')->nullable();
            $table->json('files');
            $table->json('planted_issues');
            $table->unsignedInteger('est_minutes')->default(20);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('code_review_drills');
    }
};
