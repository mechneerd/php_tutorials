<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StarStory extends Model
{
    protected $fillable = [
        'user_id',
        'prompt_slug',
        'title',
        'situation',
        'task',
        'action',
        'result',
        'metrics',
        'lessons_learned',
        'tags',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'metrics' => 'array',
            'tags' => 'array',
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
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }
}
