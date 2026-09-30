<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IncidentAttempt extends Model
{
    protected $fillable = [
        'user_id',
        'incident_id',
        'hypotheses',
        'root_cause_category',
        'fix_steps',
        'score',
        'is_complete',
        'minutes_spent',
    ];

    protected function casts(): array
    {
        return [
            'hypotheses' => 'array',
            'fix_steps' => 'array',
            'score' => 'integer',
            'is_complete' => 'boolean',
            'minutes_spent' => 'integer',
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
     * @return BelongsTo<Incident, $this>
     */
    public function incident(): BelongsTo
    {
        return $this->belongsTo(Incident::class);
    }
}
