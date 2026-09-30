<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DesignCase extends Model
{
    protected $fillable = [
        'slug',
        'title',
        'level',
        'track',
        'prompt',
        'constraints',
        'rubric',
        'model_answer_html',
        'follow_ups',
        'est_minutes',
        'order_column',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'constraints' => 'array',
            'rubric' => 'array',
            'follow_ups' => 'array',
            'is_published' => 'boolean',
            'est_minutes' => 'integer',
            'order_column' => 'integer',
        ];
    }

    /**
     * @return HasMany<DesignAttempt, $this>
     */
    public function attempts(): HasMany
    {
        return $this->hasMany(DesignAttempt::class);
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
