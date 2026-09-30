<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Incident extends Model
{
    protected $fillable = [
        'slug',
        'title',
        'severity',
        'symptom',
        'logs',
        'metrics',
        'traces',
        'artifacts',
        'wrong_paths',
        'correct_diagnosis',
        'fix_steps',
        'root_cause_category',
        'blast_radius',
        'est_minutes',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'metrics' => 'array',
            'artifacts' => 'array',
            'wrong_paths' => 'array',
            'fix_steps' => 'array',
            'is_published' => 'boolean',
            'est_minutes' => 'integer',
        ];
    }

    /**
     * @return HasMany<IncidentAttempt, $this>
     */
    public function attempts(): HasMany
    {
        return $this->hasMany(IncidentAttempt::class);
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
