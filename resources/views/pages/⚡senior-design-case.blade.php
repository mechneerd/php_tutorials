<?php

use App\Models\DesignAttempt;
use App\Models\DesignCase;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

new #[Title('Design case')] class extends Component
{
    public DesignCase $case;

    #[Url]
    public string $slug = '';

    public string $requirements = '';

    public string $api = '';

    public string $dataModel = '';

    public string $bottlenecks = '';

    public string $failureModes = '';

    public string $tradeoffs = '';

    public string $ops = '';

    public bool $submitted = false;

    public int $score = 0;

    public function mount(DesignCase $designCase): void
    {
        $this->case = $designCase;
        $this->slug = $designCase->slug;

        $attempt = DesignAttempt::query()
            ->where('user_id', auth()->id())
            ->where('design_case_id', $designCase->id)
            ->first();

        if ($attempt?->sections) {
            $sections = $attempt->sections;
            $this->requirements = $sections['requirements'] ?? '';
            $this->api = $sections['api'] ?? '';
            $this->dataModel = $sections['data_model'] ?? '';
            $this->bottlenecks = $sections['bottlenecks'] ?? '';
            $this->failureModes = $sections['failure_modes'] ?? '';
            $this->tradeoffs = $sections['tradeoffs'] ?? '';
            $this->ops = $sections['ops'] ?? '';
            $this->submitted = (bool) $attempt->is_complete;
            $this->score = (int) $attempt->score;
        }
    }

    public function save(): void
    {
        DesignAttempt::query()->updateOrCreate(
            [
                'user_id' => auth()->id(),
                'design_case_id' => $this->case->id,
            ],
            [
                'sections' => $this->sectionsPayload(),
                'is_complete' => false,
            ]
        );
    }

    public function submit(): void
    {
        $sections = $this->sectionsPayload();
        $score = $this->scoreSections($sections, $this->case);

        DesignAttempt::query()->updateOrCreate(
            [
                'user_id' => auth()->id(),
                'design_case_id' => $this->case->id,
            ],
            [
                'sections' => $sections,
                'score' => $score,
                'is_complete' => true,
            ]
        );

        $this->score = $score;
        $this->submitted = true;
    }

    /**
     * @return array<string, string>
     */
    private function sectionsPayload(): array
    {
        return [
            'requirements' => $this->requirements,
            'api' => $this->api,
            'data_model' => $this->dataModel,
            'bottlenecks' => $this->bottlenecks,
            'failure_modes' => $this->failureModes,
            'tradeoffs' => $this->tradeoffs,
            'ops' => $this->ops,
        ];
    }

    /**
     * @param array<string, string> $sections
     */
    private function scoreSections(array $sections, DesignCase $designCase): int
    {
        $weights = [
            'requirements' => 15,
            'api' => 15,
            'data_model' => 20,
            'bottlenecks' => 20,
            'failure_modes' => 15,
            'tradeoffs' => 15,
            'ops' => 5,
        ];

        $max = array_sum($weights);
        $got = 0;

        foreach ($weights as $key => $weight) {
            $len = mb_strlen(trim($sections[$key] ?? ''));
            if ($len >= 400) {
                $got += $weight;
            } elseif ($len >= 150) {
                $got += (int) round($weight * 0.75);
            } elseif ($len >= 40) {
                $got += (int) round($weight * 0.4);
            }
        }

        // Senior cases require an explicit trade-off section non-empty
        if (mb_strlen(trim($sections['tradeoffs'])) < 40) {
            $got = max(0, $got - 20);
        }

        return (int) min(100, round($got / $max * 100));
    }

    public function render()
    {
        return $this->view([
            'case' => $this->case,
            'submitted' => $this->submitted,
            'score' => $this->score,
        ])->layout('layouts::app', ['title' => $this->case->title]);
    }
};
?>
<div class="flex flex-1 flex-col gap-6">
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <flux:link :href="route('senior.design')" wire:navigate class="text-sm">{{ __('← All cases') }}</flux:link>
            <flux:heading size="xl" level="1">{{ $case->title }}</flux:heading>
            <flux:subheading>{{ $case->est_minutes }} minutes · {{ $case->track }} · {{ $case->level }}</flux:subheading>
        </div>
        @if ($submitted)
            <flux:badge variant="success" class="text-base" data-test="design-score">Score {{ $score }}</flux:badge>
        @endif
    </div>

    <div class="rounded-xl border border-zinc-200 bg-zinc-50 p-5 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:heading size="sm">{{ __('Prompt') }}</flux:heading>
        <p class="mt-2 whitespace-pre-wrap text-sm">{{ $case->prompt }}</p>
        @if ($case->constraints)
            <div class="mt-3 flex flex-wrap gap-2">
                @foreach ($case->constraints as $constraint)
                    <flux:badge>{{ $constraint }}</flux:badge>
                @endforeach
            </div>
        @endif
    </div>

    <form wire:submit.prevent="submit" class="grid gap-4 md:grid-cols-2">
        <div class="md:col-span-2">
            <flux:heading size="xs">{{ __('Requirements') }}</flux:heading>
            <textarea wire:model="requirements" rows="3" class="mt-1 w-full rounded-lg border border-zinc-300 bg-white p-3 text-sm dark:border-zinc-600 dark:bg-zinc-900" data-test="design-requirements"></textarea>
        </div>
        <div>
            <flux:heading size="xs">{{ __('API sketch') }}</flux:heading>
            <textarea wire:model="api" rows="4" class="mt-1 w-full rounded-lg border border-zinc-300 bg-white p-3 text-sm dark:border-zinc-600 dark:bg-zinc-900"></textarea>
        </div>
        <div>
            <flux:heading size="xs">{{ __('Data model') }}</flux:heading>
            <textarea wire:model="dataModel" rows="4" class="mt-1 w-full rounded-lg border border-zinc-300 bg-white p-3 text-sm dark:border-zinc-600 dark:bg-zinc-900"></textarea>
        </div>
        <div>
            <flux:heading size="xs">{{ __('Bottlenecks') }}</flux:heading>
            <textarea wire:model="bottlenecks" rows="4" class="mt-1 w-full rounded-lg border border-zinc-300 bg-white p-3 text-sm dark:border-zinc-600 dark:bg-zinc-900"></textarea>
        </div>
        <div>
            <flux:heading size="xs">{{ __('Failure modes') }}</flux:heading>
            <textarea wire:model="failureModes" rows="4" class="mt-1 w-full rounded-lg border border-zinc-300 bg-white p-3 text-sm dark:border-zinc-600 dark:bg-zinc-900"></textarea>
        </div>
        <div class="md:col-span-2">
            <flux:heading size="xs">{{ __('Trade-offs (required for senior bar)') }}</flux:heading>
            <textarea wire:model="tradeoffs" rows="4" class="mt-1 w-full rounded-lg border border-zinc-300 bg-white p-3 text-sm dark:border-zinc-600 dark:bg-zinc-900" data-test="design-tradeoffs"></textarea>
        </div>
        <div class="md:col-span-2">
            <flux:heading size="xs">{{ __('Ops & observability') }}</flux:heading>
            <textarea wire:model="ops" rows="3" class="mt-1 w-full rounded-lg border border-zinc-300 bg-white p-3 text-sm dark:border-zinc-600 dark:bg-zinc-900"></textarea>
        </div>

        <div class="md:col-span-2 flex flex-wrap gap-3">
            <flux:button type="button" variant="ghost" wire:click="save">{{ __('Save draft') }}</flux:button>
            <flux:button type="submit" data-test="design-submit">{{ __('Submit for rubric') }}</flux:button>
        </div>
    </form>

    @if ($submitted)
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-5 dark:border-emerald-800 dark:bg-emerald-950" data-test="design-model">
            <flux:heading size="sm">{{ __('Model answer') }}</flux:heading>
            <div class="prose dark:prose-invert mt-2 max-w-none text-sm">{!! $case->model_answer_html !!}</div>

            @if ($case->rubric)
                <flux:heading size="xs" class="mt-4">{{ __('Rubric') }}</flux:heading>
                <ul class="mt-2 space-y-1 text-sm">
                    @foreach ($case->rubric as $row)
                        <li><strong>{{ $row['dimension'] }}</strong> ({{ $row['weight'] }}) — {{ $row['bar'] }}</li>
                    @endforeach
                </ul>
            @endif

            @if ($case->follow_ups)
                <flux:heading size="xs" class="mt-4">{{ __('Follow-up probes') }}</flux:heading>
                <ul class="mt-2 list-disc space-y-1 pl-5 text-sm">
                    @foreach ($case->follow_ups as $followUp)
                        <li>{{ $followUp }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
    @endif
</div>
