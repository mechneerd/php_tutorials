<?php

use App\Models\CodeReviewDrill;
use App\Models\ReviewAttempt;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Review drill')] class extends Component
{
    public CodeReviewDrill $drill;

    /** @var list<string> */
    public array $selected = [];

    public bool $revealed = false;

    public int $score = 0;

    public int $hits = 0;

    public int $misses = 0;

    public int $falsePositives = 0;

    public function mount(CodeReviewDrill $codeReviewDrill): void
    {
        $this->drill = $codeReviewDrill;

        $attempt = ReviewAttempt::query()
            ->where('user_id', auth()->id())
            ->where('code_review_drill_id', $this->drill->id)
            ->first();

        if ($attempt?->is_complete) {
            $this->selected = array_values($attempt->found_issues ?? []);
            $this->revealed = true;
            $this->score = (int) $attempt->score;
            $this->hits = (int) $attempt->hits;
            $this->misses = (int) $attempt->misses;
            $this->falsePositives = (int) $attempt->false_positives;
        }
    }

    public function toggle(string $id): void
    {
        if (in_array($id, $this->selected, true)) {
            $this->selected = array_values(array_filter($this->selected, fn ($s) => $s !== $id));
        } else {
            $this->selected[] = $id;
        }
    }

    public function submit(): void
    {
        $planted = array_column($this->drill->planted_issues ?? [], 'id');
        $selected = $this->selected;

        $hits = count(array_intersect($selected, $planted));
        $misses = count(array_diff($planted, $selected));
        $falsePositives = count(array_diff($selected, $planted));

        $precision = $hits + $falsePositives > 0 ? $hits / ($hits + $falsePositives) : 0.0;
        $recall = $hits + $misses > 0 ? $hits / ($hits + $misses) : 0.0;
        $f1 = $precision + $recall > 0 ? 2 * $precision * $recall / ($precision + $recall) : 0.0;
        $score = (int) round($f1 * 100);

        ReviewAttempt::query()->updateOrCreate(
            [
                'user_id' => auth()->id(),
                'code_review_drill_id' => $this->drill->id,
            ],
            [
                'found_issues' => $selected,
                'hits' => $hits,
                'misses' => $misses,
                'false_positives' => $falsePositives,
                'score' => $score,
                'is_complete' => true,
            ]
        );

        $this->hits = $hits;
        $this->misses = $misses;
        $this->falsePositives = $falsePositives;
        $this->score = $score;
        $this->revealed = true;
    }

    public function render()
    {
        return $this->view([
            'drill' => $this->drill,
            'revealed' => $this->revealed,
            'score' => $this->score,
            'hits' => $this->hits,
            'misses' => $this->misses,
            'falsePositives' => $this->falsePositives,
            'selected' => $this->selected,
        ])->layout('layouts::app', ['title' => $this->drill->title]);
    }
};
?>
<div class="flex flex-1 flex-col gap-6">
    <div>
        <flux:link :href="route('senior.reviews')" wire:navigate class="text-sm">{{ __('← All drills') }}</flux:link>
        <div class="flex flex-wrap items-center gap-3">
            <flux:heading size="xl" level="1">{{ $drill->title }}</flux:heading>
            <flux:badge>{{ $drill->language }}</flux:badge>
            @if ($revealed)
                <flux:badge variant="success" data-test="review-score">F1 {{ $score }}</flux:badge>
            @endif
        </div>
        <p class="mt-1 text-sm text-zinc-500">{{ $drill->context }}</p>
    </div>

    <div class="grid gap-4 lg:grid-cols-2">
        @foreach ($drill->files as $file)
            <flux:card class="p-4" data-test="review-file">
                <div class="text-xs font-mono text-zinc-500">{{ $file['path'] }}</div>
                <pre class="mt-2 overflow-auto rounded bg-zinc-950 p-3 text-xs leading-relaxed text-zinc-100">{{ $file['code'] }}</pre>
            </flux:card>
        @endforeach
    </div>

    <flux:card class="p-5">
        <flux:heading size="sm">{{ __('Mark issues you would block on') }}</flux:heading>
        <div class="mt-3 space-y-2">
            @foreach ($drill->planted_issues as $issue)
                <label class="flex cursor-pointer items-start gap-3 rounded-lg border border-zinc-200 p-3 dark:border-zinc-700" data-test="issue-option">
                    <input
                        type="checkbox"
                        wire:click="toggle('{{ $issue['id'] }}')"
                        @checked(in_array($issue['id'], $selected, true))
                        class="mt-1"
                        @if ($revealed) disabled @endif
                    />
                    <span>
                        <span class="font-medium">{{ $issue['id'] }}</span>
                        <flux:badge :variant="$issue['severity'] === 'critical' || $issue['severity'] === 'high' ? 'danger' : 'gray'" class="ml-2">
                            {{ $issue['type'] }} / {{ $issue['severity'] }}
                        </flux:badge>
                        <span class="block text-sm text-zinc-500">Hint: {{ $issue['hint'] }}</span>
                        @if ($revealed)
                            <span class="mt-1 block text-sm text-emerald-700 dark:text-emerald-400">Fix: {{ $issue['fix'] }}</span>
                        @endif
                    </span>
                </label>
            @endforeach
        </div>

        @unless ($revealed)
            <flux:button type="button" class="mt-4" wire:click="submit" data-test="review-submit">Submit review</flux:button>
        @endunless

        @if ($revealed)
            <div class="mt-4 rounded-lg bg-zinc-50 p-4 text-sm dark:bg-zinc-800" data-test="review-results">
                Hits <strong>{{ $hits }}</strong> · Misses <strong>{{ $misses }}</strong> · False positives <strong>{{ $falsePositives }}</strong>
                · F1 score <strong>{{ $score }}</strong>
            </div>
        @endif
    </flux:card>
</div>
