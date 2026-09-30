<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InternalsTopic extends Model
{
    protected $fillable = [
        'slug',
        'title',
        'summary',
        'body_html',
        'explain_back_prompts',
        'related_lesson_code',
        'order_column',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'explain_back_prompts' => 'array',
            'is_published' => 'boolean',
            'order_column' => 'integer',
        ];
    }

    /**
     * @return HasMany<InternalsAttempt, $this>
     */
    public function attempts(): HasMany
    {
        return $this->hasMany(InternalsAttempt::class);
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
