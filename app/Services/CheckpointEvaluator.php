<?php

namespace App\Services;

use App\Models\Checkpoint;
use App\Models\CheckpointAttempt;
use App\Models\User;

class CheckpointEvaluator
{
    /**
     * Evaluate free-text / multiple-choice answers against question keys.
     *
     * Question shape:
     * [
     *   ['id' => 'q1', 'prompt' => '...', 'type' => 'mcq'|'text', 'options' => [...], 'answer' => '...', 'keywords' => [...]],
     * ]
     *
     * @param  array<int, array<string, mixed>>  $questions
     * @param  array<string, string>  $answers  question id => user answer
     * @return array{score: int, passed: bool, details: list<array<string, mixed>>}
     */
    public function evaluate(Checkpoint $checkpoint, array $questions, array $answers): array
    {
        $total = count($questions);

        if ($total === 0) {
            return ['score' => 0, 'passed' => false, 'details' => []];
        }

        $correct = 0;
        $details = [];

        foreach ($questions as $question) {
            $id = (string) ($question['id'] ?? '');
            $given = trim((string) ($answers[$id] ?? ''));
            $expected = trim((string) ($question['answer'] ?? ''));
            $type = (string) ($question['type'] ?? 'text');
            $isCorrect = false;

            if ($type === 'mcq') {
                $isCorrect = $given !== '' && strcasecmp($given, $expected) === 0;
            } else {
                $keywords = $question['keywords'] ?? [];
                if (is_array($keywords) && $keywords !== []) {
                    $lower = mb_strtolower($given);
                    $hits = 0;
                    foreach ($keywords as $keyword) {
                        if (str_contains($lower, mb_strtolower((string) $keyword))) {
                            $hits++;
                        }
                    }
                    $isCorrect = $hits > 0 && $hits >= (int) ceil(count($keywords) / 2);
                } elseif ($expected !== '') {
                    $isCorrect = levenshtein(mb_strtolower($given), mb_strtolower($expected)) <= 3
                        || str_contains(mb_strtolower($given), mb_strtolower($expected));
                }
            }

            if ($isCorrect) {
                $correct++;
            }

            $details[] = [
                'id' => $id,
                'correct' => $isCorrect,
                'given' => $given,
                'expected' => $expected,
            ];
        }

        $score = (int) round(($correct / $total) * 100);
        $passed = $score >= (int) $checkpoint->pass_score;

        return [
            'score' => $score,
            'passed' => $passed,
            'details' => $details,
        ];
    }

    /**
     * Record an evaluation attempt for the user.
     *
     * @param  array<int, array<string, mixed>>  $questions
     * @param  array<string, string>  $answers
     */
    public function record(User $user, Checkpoint $checkpoint, array $questions, array $answers): CheckpointAttempt
    {
        $result = $this->evaluate($checkpoint, $questions, $answers);

        return CheckpointAttempt::query()->create([
            'user_id' => $user->id,
            'checkpoint_id' => $checkpoint->id,
            'answers' => $answers,
            'score' => $result['score'],
            'passed' => $result['passed'],
            'feedback' => $result['details'],
        ]);
    }

    public function hasPassed(User $user, Checkpoint $checkpoint): bool
    {
        return CheckpointAttempt::query()
            ->where('user_id', $user->id)
            ->where('checkpoint_id', $checkpoint->id)
            ->where('passed', true)
            ->exists();
    }
}
