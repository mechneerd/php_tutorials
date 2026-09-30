<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incident_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('incident_id')->constrained()->cascadeOnDelete();
            $table->json('hypotheses')->nullable();
            $table->string('root_cause_category', 20)->nullable();
            $table->json('fix_steps')->nullable();
            $table->unsignedInteger('score')->default(0);
            $table->boolean('is_complete')->default(false);
            $table->unsignedInteger('minutes_spent')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incident_attempts');
    }
};
