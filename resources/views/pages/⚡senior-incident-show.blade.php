<?php

use App\Models\Incident;
use App\Models\IncidentAttempt;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Incident war room')] class extends Component
{
    public Incident $incident;

    public string $hypothesis1 = '';

    public string $hypothesis2 = '';

    public string $rootCauseCategory = '';

    public string $fixSteps = '';

    public bool $revealed = false;

    public int $score = 0;

    public int $hypothesisCount = 0;

    public function mount(Incident $incident): void
    {
        $this->incident = $incident;

        $attempt = IncidentAttempt::query()
            ->where('user_id', auth()->id())
            ->where('incident_id', $incident->id)
            ->first();

        if ($attempt) {
            $hyp = $attempt->hypotheses ?? [];
            $this->hypothesis1 = $hyp[0] ?? '';
            $this->hypothesis2 = $hyp[1] ?? '';
            $this->rootCauseCategory = (string) ($attempt->root_cause_category ?? '');
            $this->fixSteps = implode("\n", $attempt->fix_steps ?? []);
            $this->revealed = (bool) $attempt->is_complete;
            $this->score = (int) $attempt->score;
            $this->hypothesisCount = count($hyp);
        }
    }

    public function addHypothesis(): void
    {
        $this->hypothesisCount = min(4, $this->hypothesisCount + 1);
    }

    public function diagnose(): void
    {
        $incident = $this->incident;
        $hypotheses = array_values(array_filter([
            trim($this->hypothesis1),
            trim($this->hypothesis2),
        ]));

        $score = 0;
        $correct = $incident->root_cause_category === $this->rootCauseCategory;

        if ($correct) {
            $score += 50;
        }

        if (count($hypotheses) >= 2) {
            $score += 15;
        } elseif (count($hypotheses) === 1) {
            $score += 5;
        }

        $fixLines = array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $this->fixSteps) ?: []));
        $wanted = $incident->fix_steps ?? [];
        $matched = 0;
        foreach ($wanted as $step) {
            foreach ($fixLines as $line) {
                similar_text(mb_strtolower($line), mb_strtolower((string) $step), $pct);
                if ($pct >= 55) {
                    $matched++;
                    break;
                }
            }
        }
        if ($wanted) {
            $score += (int) round(min(35, $matched / count($wanted) * 35));
        }

        $score = (int) min(100, $score);

        IncidentAttempt::query()->updateOrCreate(
            [
                'user_id' => auth()->id(),
                'incident_id' => $incident->id,
            ],
            [
                'hypotheses' => $hypotheses,
                'root_cause_category' => $this->rootCauseCategory,
                'fix_steps' => $fixLines,
                'score' => $score,
                'is_complete' => true,
            ]
        );

        $this->score = $score;
        $this->revealed = true;
    }

    public function render()
    {
        return $this->view([
            'incident' => $this->incident,
            'revealed' => $this->revealed,
            'score' => $this->score,
            'hypothesisCount' => $this->hypothesisCount,
        ])->layout('layouts::app', ['title' => $this->incident->title]);
    }
};
?>
<div class="flex flex-1 flex-col gap-6">
    <div>
        <flux:link :href="route('senior.incidents')" wire:navigate class="text-sm">{{ __('← All incidents') }}</flux:link>
        <div class="flex flex-wrap items-center gap-3">
            <flux:heading size="xl" level="1">{{ $incident->title }}</flux:heading>
            <flux:badge variant="danger">{{ $incident->severity }}</flux:badge>
            @if ($revealed)
                <flux:badge variant="success" data-test="incident-score">{{ $score }}</flux:badge>
            @endif
        </div>
    </div>

    <div class="grid gap-4 lg:grid-cols-2">
        <flux:card class="p-5" data-test="incident-symptom">
            <flux:heading size="sm">{{ __('Symptom (pager)') }}</flux:heading>
            <p class="mt-2 text-sm">{{ $incident->symptom }}</p>
            @if ($incident->blast_radius)
                <flux:heading size="xs" class="mt-3">{{ __('Blast radius') }}</flux:heading>
                <p class="text-sm">{{ $incident->blast_radius }}</p>
            @endif
        </flux:card>

        <flux:card class="p-5">
            <flux:heading size="sm">{{ __('Logs / traces / metrics') }}</flux:heading>
            @if ($incident->logs)
                <pre class="mt-2 overflow-auto rounded bg-zinc-950 p-3 text-xs text-zinc-100">{{ $incident->logs }}</pre>
            @endif
            @if ($incident->traces)
                <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">{{ $incident->traces }}</p>
            @endif
            @if ($incident->metrics)
                <div class="mt-2 flex flex-wrap gap-2">
                    @foreach ($incident->metrics as $k => $v)
                        <flux:badge>{{ $k }}: {{ $v }}</flux:badge>
                    @endforeach
                </div>
            @endif
            @if ($incident->artifacts)
                <div class="mt-3 space-y-2">
                    @foreach ($incident->artifacts as $label => $content)
                        <div>
                            <div class="text-xs font-semibold text-zinc-500">{{ $label }}</div>
                            <pre class="overflow-auto rounded bg-zinc-100 p-2 text-xs dark:bg-zinc-800">{{ $content }}</pre>
                        </div>
                    @endforeach
                </div>
            @endif
            @if ($incident->wrong_paths)
                <div class="mt-3">
                    <flux:heading size="xs">{{ __('Common wrong paths (red herrings)') }}</flux:heading>
                    <ul class="mt-1 list-disc pl-5 text-sm text-zinc-500">
                        @foreach ($incident->wrong_paths as $path)
                            <li>{{ $path }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </flux:card>
    </div>

    @if (! $revealed)
        <form wire:submit.prevent="diagnose" class="space-y-4 rounded-xl border border-zinc-200 p-5 dark:border-zinc-700">
            <flux:heading size="sm">{{ __('Your diagnosis') }}</flux:heading>

            <div>
                <label class="text-sm font-medium">Hypothesis 1 (with evidence)</label>
                <textarea wire:model="hypothesis1" rows="2" class="mt-1 w-full rounded-lg border border-zinc-300 bg-white p-3 text-sm dark:border-zinc-600 dark:bg-zinc-900" data-test="hypothesis-1"></textarea>
            </div>

            @if ($hypothesisCount >= 1)
                <div>
                    <label class="text-sm font-medium">Hypothesis 2</label>
                    <textarea wire:model="hypothesis2" rows="2" class="mt-1 w-full rounded-lg border border-zinc-300 bg-white p-3 text-sm dark:border-zinc-600 dark:bg-zinc-900"></textarea>
                </div>
                <flux:button type="button" variant="ghost" size="sm" wire:click="addHypothesis">Add another hypothesis</flux:button>
            @else
                <flux:button type="button" variant="ghost" size="sm" wire:click="addHypothesis">Add second hypothesis</flux:button>
            @endif

            <div>
                <label class="text-sm font-medium">Root cause category</label>
                <select wire:model="rootCauseCategory" class="mt-1 w-full rounded-lg border border-zinc-300 bg-white p-2 text-sm dark:border-zinc-600 dark:bg-zinc-900" data-test="root-cause-category">
                    <option value="">Select…</option>
                    <option value="code">code</option>
                    <option value="schema">schema</option>
                    <option value="config">config</option>
                    <option value="infra">infra</option>
                    <option value="dependency">dependency</option>
                    <option value="race">race</option>
                </select>
            </div>

            <div>
                <label class="text-sm font-medium">Fix steps (one per line)</label>
                <textarea wire:model="fixSteps" rows="4" class="mt-1 w-full rounded-lg border border-zinc-300 bg-white p-3 text-sm dark:border-zinc-600 dark:bg-zinc-900" data-test="fix-steps"></textarea>
            </div>

            <flux:button type="submit" data-test="diagnose-submit">Reveal diagnosis & score</flux:button>
        </form>
    @else
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-5 dark:border-emerald-800 dark:bg-emerald-950" data-test="incident-reveal">
            <flux:heading size="sm">{{ __('Model diagnosis') }}</flux:heading>
            <p class="mt-2 text-sm">{{ $incident->correct_diagnosis }}</p>
            <flux:heading size="xs" class="mt-3">{{ __('Canonical fix steps') }}</flux:heading>
            <ol class="mt-1 list-decimal space-y-1 pl-5 text-sm">
                @foreach ($incident->fix_steps ?? [] as $step)
                    <li>{{ $step }}</li>
                @endforeach
            </ol>
        </div>
    @endif
</div>
