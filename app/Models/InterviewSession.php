<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InterviewSession extends Model
{
    protected $fillable = [
        'user_id',
        'level',
        'status',
        'questions',
        'answers',
        'evaluation',
        'score',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'questions' => 'array',
            'answers' => 'array',
            'evaluation' => 'array',
            'score' => 'integer',
            'completed_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
