<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InterviewSimulation extends Model
{
    protected $fillable = [
        'slug',
        'title',
        'minutes_total',
        'segments',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'segments' => 'array',
            'minutes_total' => 'integer',
            'is_published' => 'boolean',
        ];
    }

    /**
     * @return HasMany<SimulationAttempt, $this>
     */
    public function attempts(): HasMany
    {
        return $this->hasMany(SimulationAttempt::class);
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
