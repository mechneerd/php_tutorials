<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exercise extends Model
{
    protected $fillable = [
        'lesson_id',
        'title',
        'prompt',
        'level',
        'type',
        'starter_code',
        'solution',
        'expected_output',
        'hints',
        'order_column',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'level' => 'integer',
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
     * @return HasMany<ExerciseAttempt, $this>
     */
    public function attempts(): HasMany
    {
        return $this->hasMany(ExerciseAttempt::class);
    }
}
