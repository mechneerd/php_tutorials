<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incidents', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 100)->unique();
            $table->string('title');
            $table->enum('severity', ['sev1', 'sev2', 'sev3'])->default('sev2');
            $table->text('symptom');
            $table->longText('logs')->nullable();
            $table->json('metrics')->nullable();
            $table->longText('traces')->nullable();
            $table->json('artifacts')->nullable();
            $table->json('wrong_paths')->nullable();
            $table->text('correct_diagnosis');
            $table->json('fix_steps')->nullable();
            $table->enum('root_cause_category', ['code', 'schema', 'config', 'infra', 'dependency', 'race'])->default('code');
            $table->text('blast_radius')->nullable();
            $table->unsignedInteger('est_minutes')->default(30);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incidents');
    }
};
