<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Checkpoint extends Model
{
    protected $fillable = [
        'lesson_id',
        'stage_id',
        'title',
        'questions',
        'pass_score',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'questions' => 'array',
            'pass_score' => 'integer',
            'is_published' => 'boolean',
        ];
    }

    /**
     * @return BelongsTo<Lesson, $this>
     */
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    /**
     * @return BelongsTo<Stage, $this>
     */
    public function stage(): BelongsTo
    {
        return $this->belongsTo(Stage::class);
    }

    /**
     * @return HasMany<CheckpointAttempt, $this>
     */
    public function attempts(): HasMany
    {
        return $this->hasMany(CheckpointAttempt::class);
    }
}
