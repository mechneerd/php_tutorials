<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DesignAttempt extends Model
{
    protected $fillable = [
        'user_id',
        'design_case_id',
        'sections',
        'self_scores',
        'score',
        'is_complete',
    ];

    protected function casts(): array
    {
        return [
            'sections' => 'array',
            'self_scores' => 'array',
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
     * @return BelongsTo<DesignCase, $this>
     */
    public function designCase(): BelongsTo
    {
        return $this->belongsTo(DesignCase::class);
    }
}
