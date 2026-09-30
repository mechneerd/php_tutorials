<?php

use App\Models\InterviewSimulation;
use App\Models\SimulationAttempt;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Run simulation')] class extends Component
{
    public InterviewSimulation $simulation;

    public int $segmentIndex = 0;

    /** @var array<int, string> */
    public array $answers = [];

    public bool $completed = false;

    public int $overall = 0;

    public function mount(InterviewSimulation $interviewSimulation): void
    {
        $this->simulation = $interviewSimulation;

        $attempt = SimulationAttempt::query()
            ->where('user_id', auth()->id())
            ->where('interview_simulation_id', $interviewSimulation->id)
            ->where('is_complete', true)
            ->orderByDesc('updated_at')
            ->first();

        if ($attempt) {
            $this->answers = array_map(
                fn ($v) => is_string($v) ? $v : json_encode($v),
                $attempt->segment_answers ?? []
            );
            $this->completed = true;
            $this->overall = (int) $attempt->overall_score;
        }

        if (! $this->answers) {
            foreach ($interviewSimulation->segments ?? [] as $i => $segment) {
                $this->answers[$i] = '';
            }
        }
    }

    public function goNext(): void
    {
        $this->segmentIndex = min(
            count($this->simulation->segments ?? []) - 1,
            $this->segmentIndex + 1
        );
    }

    public function goPrev(): void
    {
        $this->segmentIndex = max(0, $this->segmentIndex - 1);
    }

    public function finish(): void
    {
        $segments = $this->simulation->segments ?? [];
        $perSegment = [];
        $scores = [];

        foreach ($segments as $i => $segment) {
            $text = trim($this->answers[$i] ?? '');
            $len = mb_strlen($text);
            $score = 0;
            if ($len >= 800) {
                $score = 90;
            } elseif ($len >= 400) {
                $score = 75;
            } elseif ($len >= 150) {
                $score = 55;
            } elseif ($len > 0) {
                $score = 35;
            }

            if (($segment['type'] ?? '') === 'behavioral') {
                $lower = mb_strtolower($text);
                $hasStar = str_contains($lower, 'situation') || str_contains($text, "\n");
                $hasMetrics = preg_match('/\d/', $text) === 1;
                if ($hasStar) {
                    $score = min(100, $score + 10);
                }
                if ($hasMetrics) {
                    $score = min(100, $score + 5);
                }
            }

            $perSegment[$i] = $text;
            $scores[$segment['type'] ?? 'segment'] = $score;
        }

        $overall = $scores ? (int) round(array_sum($scores) / count($scores)) : 0;

        SimulationAttempt::query()->updateOrCreate(
            [
                'user_id' => auth()->id(),
                'interview_simulation_id' => $this->simulation->id,
            ],
            [
                'started_at' => now(),
                'completed_at' => now(),
                'segment_answers' => $perSegment,
                'self_scores' => $scores,
                'rubric_results' => $scores,
                'overall_score' => $overall,
                'is_complete' => true,
            ]
        );

        $this->overall = $overall;
        $this->completed = true;
    }

    public function render()
    {
        $segments = $this->simulation->segments ?? [];
        $current = $segments[$this->segmentIndex] ?? ($segments[0] ?? ['prompt' => 'No segments', 'minutes' => 0]);

        return $this->view([
            'simulation' => $this->simulation,
            'segments' => $segments,
            'current' => $current,
            'segmentIndex' => $this->segmentIndex,
            'answer' => $this->answers[$this->segmentIndex] ?? '',
            'completed' => $this->completed,
            'overall' => $this->overall,
        ])->layout('layouts::app', ['title' => $this->simulation->title]);
    }
};
?>
<div class="flex flex-1 flex-col gap-6">
    <div>
        <flux:link :href="route('senior.simulation')" wire:navigate class="text-sm">{{ __('← All loops') }}</flux:link>
        <flux:heading size="xl" level="1">{{ $simulation->title }}</flux:heading>
        <flux:subheading>{{ $simulation->minutes_total }} minutes · segment {{ $segmentIndex + 1 }}/{{ count($segments) }}</flux:subheading>
    </div>

    <div class="flex flex-wrap gap-2">
        @foreach ($segments as $i => $segment)
            <flux:badge :variant="$i === $segmentIndex ? 'success' : 'gray'" class="cursor-pointer" wire:click="$set('segmentIndex', {{ $i }})">
                {{ $i + 1 }}. {{ $segment['type'] }} ({{ $segment['minutes'] }}m)
            </flux:badge>
        @endforeach
    </div>

    <div class="grid gap-2">
        <flux:heading size="sm" data-test="simulation-segment-title">{{ $current['prompt'] ?? $current['prompt_ref'] ?? '' }}</flux:heading>
        @if (! empty($current['prompt_ref']))
            <flux:badge>{{ $current['prompt_ref'] }}</flux:badge>
        @endif
    </div>

    @unless ($completed)
        <textarea
            wire:model="answers.{{ $segmentIndex }}"
            rows="14"
            class="w-full rounded-lg border border-zinc-300 bg-white p-3 font-mono text-sm dark:border-zinc-600 dark:bg-zinc-900"
            data-test="simulation-answer"
            placeholder="Your answer / notes / code / STAR draft for this segment…"
        ></textarea>

        <div class="flex flex-wrap gap-3">
            <flux:button type="button" variant="ghost" wire:click="goPrev">Previous</flux:button>
            <flux:button type="button" variant="ghost" wire:click="goNext">Next</flux:button>
            <flux:button type="button" wire:click="finish" data-test="simulation-finish">Finish & score</flux:button>
        </div>
    @else
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-5 dark:border-emerald-800 dark:bg-emerald-950" data-test="simulation-result">
            <flux:heading size="lg">Overall score: {{ $overall }}</flux:heading>
            <flux:text>{{ $overall >= 70 ? 'Bar met for this loop — weak pillar next?' : 'Below bar — review gap report on dashboard and retry weakest segment.' }}</flux:text>
            <div class="mt-4 flex flex-wrap gap-3">
                <flux:link :href="route('senior.index')" wire:navigate>{{ __('Back to readiness') }}</flux:link>
            </div>
        </div>

        <div class="space-y-3">
            @foreach ($segments as $i => $segment)
                <flux:card class="p-4">
                    <div class="text-sm font-semibold">{{ $i + 1 }}. {{ $segment['type'] }}</div>
                    <pre class="mt-2 max-h-48 overflow-auto whitespace-pre-wrap text-xs">{{ $this->answers[$i] ?? '' }}</pre>
                </flux:card>
            @endforeach
        </div>
    @endunless
</div>
