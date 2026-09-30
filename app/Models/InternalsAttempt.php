<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InternalsAttempt extends Model
{
    protected $fillable = [
        'user_id',
        'internals_topic_id',
        'prompt_index',
        'answer',
        'score',
        'is_complete',
    ];

    protected function casts(): array
    {
        return [
            'prompt_index' => 'integer',
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
     * @return BelongsTo<InternalsTopic, $this>
     */
    public function topic(): BelongsTo
    {
        return $this->belongsTo(InternalsTopic::class, 'internals_topic_id');
    }
}
