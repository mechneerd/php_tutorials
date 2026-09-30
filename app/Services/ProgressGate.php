<?php

namespace App\Services;

use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\Stage;
use App\Models\User;
use Illuminate\Support\Collection;

class ProgressGate
{
    /**
     * Determine whether the user may access the given lesson.
     *
     * @return array{allowed: bool, reasons: list<string>, missing_prerequisites: Collection<int, Lesson>}
     */
    public function canAccess(User $user, Lesson $lesson): array
    {
        $completedIds = LessonProgress::query()
            ->where('user_id', $user->id)
            ->where('status', 'completed')
            ->pluck('lesson_id');

        $missing = $lesson->prerequisites()
            ->whereNotIn('lessons.id', $completedIds)
            ->get();

        $reasons = [];

        if ($missing->isNotEmpty()) {
            foreach ($missing as $prerequisite) {
                $reasons[] = "Complete prerequisite: {$prerequisite->title}";
            }
        }

        $previousInStage = Lesson::where('stage_id', $lesson->stage_id)
            ->where('is_published', true)
            ->where(function ($query) use ($lesson): void {
                $query->where('sort_order', '<', $lesson->sort_order)
                    ->orWhere(function ($q) use ($lesson): void {
                        $q->where('sort_order', $lesson->sort_order)
                            ->where('order_column', '<', $lesson->order_column);
                    });
            })
            ->where('requires_checkpoint', true)
            ->get()
            ->filter(function (Lesson $prior) use ($user): bool {
                return ! $this->isCompleted($user, $prior);
            });

        foreach ($previousInStage as $prior) {
            $reasons[] = "Pass checkpoint for: {$prior->title}";
        }

        return [
            'allowed' => $reasons === [],
            'reasons' => $reasons,
            'missing_prerequisites' => $missing,
        ];
    }

    public function isCompleted(User $user, Lesson $lesson): bool
    {
        return LessonProgress::query()
            ->where('user_id', $user->id)
            ->where('lesson_id', $lesson->id)
            ->where('status', 'completed')
            ->exists();
    }

    public function markInProgress(User $user, Lesson $lesson): LessonProgress
    {
        return LessonProgress::query()->firstOrCreate(
            ['user_id' => $user->id, 'lesson_id' => $lesson->id],
            ['status' => 'in_progress'],
        );
    }

    public function markCompleted(User $user, Lesson $lesson, ?int $score = null): LessonProgress
    {
        return LessonProgress::query()->updateOrCreate(
            ['user_id' => $user->id, 'lesson_id' => $lesson->id],
            [
                'status' => 'completed',
                'score' => $score,
                'completed_at' => now(),
            ],
        );
    }

    /**
     * @return array{
     *     completed: int<0, max>,
     *     total: int<0, max>,
     *     percent: float,
     *     stages: Collection<int, array{
     *         stage: Stage|null,
     *         completed: int,
     *         total: int,
     *         lessons: non-empty-list<array{lesson: Lesson, status: string, reasons: list<string>}>
     *     }>
     * }
     */
    public function summary(User $user): array
    {
        $lessons = Lesson::query()
            ->where('is_published', true)
            ->with('stage')
            ->orderBy('stage_id')
            ->orderBy('sort_order')
            ->orderBy('order_column')
            ->get();

        $progress = LessonProgress::query()
            ->where('user_id', $user->id)
            ->get()
            ->keyBy('lesson_id');

        $completed = 0;
        $stages = [];

        foreach ($lessons as $lesson) {
            $stageId = $lesson->stage_id;
            $stages[$stageId] ??= [
                'stage' => $lesson->stage,
                'completed' => 0,
                'total' => 0,
                'lessons' => [],
            ];

            $stages[$stageId]['total']++;
            $status = data_get($progress->get($lesson->id), 'status', 'locked');
            $gate = $this->canAccess($user, $lesson);

            if ($status === 'completed') {
                $completed++;
                $stages[$stageId]['completed']++;
                $display = 'completed';
            } elseif ($gate['allowed']) {
                $display = $status === 'in_progress' ? 'in_progress' : 'available';
            } else {
                $display = 'locked';
            }

            $stages[$stageId]['lessons'][] = [
                'lesson' => $lesson,
                'status' => $display,
                'reasons' => $gate['reasons'],
            ];
        }

        $total = $lessons->count();

        return [
            'completed' => $completed,
            'total' => $total,
            'percent' => $total > 0 ? round(($completed / $total) * 100, 1) : 0.0,
            'stages' => collect($stages),
        ];
    }
}
