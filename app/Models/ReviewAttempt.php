<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReviewAttempt extends Model
{
    protected $fillable = [
        'user_id',
        'code_review_drill_id',
        'found_issues',
        'hits',
        'misses',
        'false_positives',
        'score',
        'is_complete',
    ];

    protected function casts(): array
    {
        return [
            'found_issues' => 'array',
            'hits' => 'integer',
            'misses' => 'integer',
            'false_positives' => 'integer',
            'score' => 'integer',
            'is_complete' => 'boolean',
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
     * @return BelongsTo<CodeReviewDrill, $this>
     */
    public function drill(): BelongsTo
    {
        return $this->belongsTo(CodeReviewDrill::class, 'code_review_drill_id');
    }
}
