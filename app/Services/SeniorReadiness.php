<?php

namespace App\Services;

use App\Models\DesignAttempt;
use App\Models\IncidentAttempt;
use App\Models\InternalsAttempt;
use App\Models\InternalsTopic;
use App\Models\ReviewAttempt;
use App\Models\SimulationAttempt;
use App\Models\StarStory;
use App\Models\User;

/**
 * Aggregate senior-track progress into pillar scores (0–100)
 * and an overall readiness gate.
 *
 * @phpstan-type Pillars array{design:int, incidents:int, reviews:int, internals:int, coding:int, star:int, simulation:int}
 * @phpstan-type Report array{pillars:Pillars, overall:int, gate_passed:bool, next_action:string}
 */
class SeniorReadiness
{
    public const GATE_DESIGN = 70;

    public const GATE_INCIDENTS = 70;

    public const GATE_STAR = 100;

    public const GATE_SIMULATION = 70;

    /**
     * @return Report
     */
    public function forUser(User $user): array
    {
        $pillars = [
            'design' => $this->designScore($user),
            'incidents' => $this->incidentScore($user),
            'reviews' => $this->reviewScore($user),
            'internals' => $this->internalsScore($user),
            'coding' => $this->codingScore($user),
            'star' => $this->starScore($user),
            'simulation' => $this->simulationScore($user),
        ];

        $weights = [
            'design' => 25,
            'incidents' => 20,
            'reviews' => 15,
            'internals' => 15,
            'coding' => 10,
            'star' => 10,
            'simulation' => 5,
        ];

        $overall = 0;
        foreach ($weights as $key => $weight) {
            $overall += (int) round($pillars[$key] * $weight / 100);
        }

        $gatePassed = $pillars['design'] >= self::GATE_DESIGN
            && $pillars['incidents'] >= self::GATE_INCIDENTS
            && $pillars['star'] >= self::GATE_STAR
            && $pillars['simulation'] >= self::GATE_SIMULATION;

        return [
            'pillars' => $pillars,
            'overall' => $overall,
            'gate_passed' => $gatePassed,
            'next_action' => $this->nextAction($pillars, $user),
        ];
    }

    private function designScore(User $user): int
    {
        $best = DesignAttempt::query()
            ->where('user_id', $user->id)
            ->where('is_complete', true)
            ->select('score')
            ->pluck('score');

        return $best->isEmpty() ? 0 : (int) $best->max();
    }

    private function incidentScore(User $user): int
    {
        $scores = IncidentAttempt::query()
            ->where('user_id', $user->id)
            ->where('is_complete', true)
            ->select('score')
            ->pluck('score');

        if ($scores->isEmpty()) {
            return 0;
        }

        return (int) round($scores->avg());
    }

    private function reviewScore(User $user): int
    {
        $scores = ReviewAttempt::query()
            ->where('user_id', $user->id)
            ->where('is_complete', true)
            ->select('score')
            ->pluck('score');

        if ($scores->isEmpty()) {
            return 0;
        }

        return (int) round($scores->avg());
    }

    private function internalsScore(User $user): int
    {
        $attempts = InternalsAttempt::query()
            ->where('user_id', $user->id)
            ->where('is_complete', true)
            ->get(['internals_topic_id', 'score']);

        if ($attempts->isEmpty()) {
            return 0;
        }

        $passed = $attempts->filter(fn ($a) => $a->score >= 70)->unique('internals_topic_id')->count();
        $total = (int) InternalsTopic::query()->where('is_published', true)->count();

        if ($total === 0) {
            return 0;
        }

        return (int) min(100, round($passed / $total * 100));
    }

    private function codingScore(User $user): int
    {
        $sessions = $user->interviewSessions()
            ->where('level', 'coding')
            ->whereNotNull('score')
            ->get(['score']);

        if ($sessions->isEmpty()) {
            return 0;
        }

        return (int) min(100, round($sessions->avg('score')));
    }

    private function starScore(User $user): int
    {
        $ready = StarStory::query()
            ->where('user_id', $user->id)
            ->where('status', 'interview_ready')
            ->count();

        return min(100, (int) round($ready / 5 * 100));
    }

    private function simulationScore(User $user): int
    {
        $best = SimulationAttempt::query()
            ->where('user_id', $user->id)
            ->where('is_complete', true)
            ->select('overall_score')
            ->pluck('overall_score');

        return $best->isEmpty() ? 0 : (int) $best->max();
    }

    /**
     * @param  Pillars  $pillars
     */
    private function nextAction(array $pillars, User $user): string
    {
        $candidates = [
            ['key' => 'design', 'label' => 'Complete a system design case', 'route' => 'senior.design'],
            ['key' => 'incidents', 'label' => 'Diagnose a production incident', 'route' => 'senior.incidents'],
            ['key' => 'reviews', 'label' => 'Run a code review drill', 'route' => 'senior.reviews'],
            ['key' => 'internals', 'label' => 'Pass an internals explain-back', 'route' => 'senior.internals'],
            ['key' => 'coding', 'label' => 'Do a timed coding interview track', 'route' => 'senior.simulation'],
            ['key' => 'star', 'label' => 'Draft 5 STAR stories to interview-ready', 'route' => 'senior.star'],
            ['key' => 'simulation', 'label' => 'Finish a full interview simulation', 'route' => 'senior.simulation'],
        ];

        usort($candidates, fn ($a, $b) => $pillars[$a['key']] <=> $pillars[$b['key']]);
        $weakest = $candidates[0];

        return $weakest['label'];
    }
}
