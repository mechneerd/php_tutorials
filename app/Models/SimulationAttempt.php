<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SimulationAttempt extends Model
{
    protected $fillable = [
        'user_id',
        'interview_simulation_id',
        'started_at',
        'completed_at',
        'segment_answers',
        'self_scores',
        'rubric_results',
        'overall_score',
        'is_complete',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
            'segment_answers' => 'array',
            'self_scores' => 'array',
            'rubric_results' => 'array',
            'overall_score' => 'integer',
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
     * @return BelongsTo<InterviewSimulation, $this>
     */
    public function simulation(): BelongsTo
    {
        return $this->belongsTo(InterviewSimulation::class, 'interview_simulation_id');
    }
}
