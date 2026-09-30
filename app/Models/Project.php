<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
    protected $fillable = [
        'stage_id',
        'slug',
        'title',
        'description',
        'requirements',
        'milestones',
        'evaluation_criteria',
        'level',
        'order_column',
        'is_published',
    ];

    protected function casts(): array
    {
        return [
            'requirements' => 'array',
            'milestones' => 'array',
            'evaluation_criteria' => 'array',
            'is_published' => 'boolean',
        ];
    }

    /**
     * @return BelongsTo<Stage, $this>
     */
    public function stage(): BelongsTo
    {
        return $this->belongsTo(Stage::class);
    }
}
