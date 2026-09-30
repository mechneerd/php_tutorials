<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property array<string, mixed>|null $metadata
 */
class Lesson extends Model
{
    protected $fillable = [
        'stage_id',
        'slug',
        'code',
        'title',
        'summary',
        'body_html',
        'metadata',
        'order_column',
        'estimated_minutes',
        'difficulty',
        'is_published',
        'requires_checkpoint',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'is_published' => 'boolean',
            'requires_checkpoint' => 'boolean',
            'body_html' => 'string',
        ];
    }

    /**
     * @return BelongsTo<Stage, $this>
     */
    public function stage(): BelongsTo
    {
        return $this->belongsTo(Stage::class);
    }

    /**
     * @return HasMany<Exercise, $this>
     */
    public function exercises(): HasMany
    {
        return $this->hasMany(Exercise::class)->orderBy('order_column');
    }

    /**
     * @return HasMany<Checkpoint, $this>
     */
    public function checkpoints(): HasMany
    {
        return $this->hasMany(Checkpoint::class);
    }

    /**
     * @return BelongsToMany<Lesson, $this>
     */
    public function prerequisites(): BelongsToMany
    {
        return $this->belongsToMany(Lesson::class, 'lesson_prerequisites', 'lesson_id', 'prerequisite_lesson_id')
            ->withTimestamps();
    }

    /**
     * @return BelongsToMany<Lesson, $this>
     */
    public function dependentLessons(): BelongsToMany
    {
        return $this->belongsToMany(Lesson::class, 'lesson_prerequisites', 'prerequisite_lesson_id', 'lesson_id')
            ->withTimestamps();
    }

    /**
     * Lateral knowledge-graph links stored as lesson codes in metadata.related.
     *
     * @return list<Lesson>
     */
    public function relatedLessons(): array
    {
        $codes = array_values(array_diff(
            (array) ($this->metadata['related'] ?? []),
            [$this->code],
        ));

        if ($codes === []) {
            return [];
        }

        return array_values(Lesson::query()
            ->where('is_published', true)
            ->whereIn('code', $codes)
            ->get()
            ->all());
    }

    public function prevLesson(): ?Lesson
    {
        return $this->stage?->lessons()
            ->where('is_published', true)
            ->where(function ($q): void {
                $q->where('sort_order', '<', $this->sort_order)
                    ->orWhere(function ($q2): void {
                        $q2->where('sort_order', $this->sort_order)
                            ->where('order_column', '<', $this->order_column);
                    });
            })
            ->orderByDesc('sort_order')
            ->orderByDesc('order_column')
            ->first();
    }

    public function nextLesson(): ?Lesson
    {
        return $this->stage?->lessons()
            ->where('is_published', true)
            ->where(function ($q): void {
                $q->where('sort_order', '>', $this->sort_order)
                    ->orWhere(function ($q2): void {
                        $q2->where('sort_order', $this->sort_order)
                            ->where('order_column', '>', $this->order_column);
                    });
            })
            ->orderBy('sort_order')
            ->orderBy('order_column')
            ->first();
    }
}
