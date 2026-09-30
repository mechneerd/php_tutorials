<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CodeReviewDrill extends Model
{
    protected $fillable = [
        'slug',
        'title',
        'language',
        'context',
        'files',
        'planted_issues',
        'est_minutes',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'files' => 'array',
            'planted_issues' => 'array',
            'is_published' => 'boolean',
            'est_minutes' => 'integer',
        ];
    }

    /**
     * @return HasMany<ReviewAttempt, $this>
     */
    public function attempts(): HasMany
    {
        return $this->hasMany(ReviewAttempt::class);
    }

    /**
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }
}
