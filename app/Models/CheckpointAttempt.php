<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CheckpointAttempt extends Model
{
    protected $fillable = [
        'user_id',
        'checkpoint_id',
        'answers',
        'score',
        'passed',
        'feedback',
    ];

    protected function casts(): array
    {
        return [
            'answers' => 'array',
            'feedback' => 'array',
            'passed' => 'boolean',
            'score' => 'integer',
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<Checkpoint, $this>
     */
    public function checkpoint(): BelongsTo
    {
        return $this->belongsTo(Checkpoint::class);
    }
}
