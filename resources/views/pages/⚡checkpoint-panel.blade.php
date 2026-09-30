<?php

use App\Models\Checkpoint;
use App\Models\Lesson;
use App\Services\CheckpointEvaluator;
use App\Services\ProgressGate;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Checkpoint')] class extends Component
{
    public Checkpoint $checkpoint;

    public Lesson $lesson;

    /** @var array<string, string> */
    public array $answers = [];

    public ?array $result = null;

    public function mount(Checkpoint $checkpoint, Lesson $lesson): void
    {
        $this->checkpoint = $checkpoint->loadMissing('lesson');
        $this->lesson = $lesson;

        foreach ($checkpoint->questions ?? [] as $question) {
            $this->answers[$question['id']] = '';
        }
    }

    public function submit(CheckpointEvaluator $evaluator, ProgressGate $gate): void
    {
        $attempt = $evaluator->record(
            auth()->user(),
            $this->checkpoint,
            $this->checkpoint->questions ?? [],
            $this->answers,
        );

        $this->result = [
            'score' => $attempt->score,
            'passed' => $attempt->passed,
            'feedback' => $attempt->feedback,
        ];

        if ($attempt->passed) {
            session()->flash('status', __('Checkpoint passed (:score%).', ['score' => $attempt->score]));
        } else {
            session()->flash('error', __('Score :score% — need :pass%. Review the lesson and retry.', [
                'score' => $attempt->score,
                'pass' => $this->checkpoint->pass_score,
            ]));
        }
    }

    public function render()
    {
        return $this->view();
    }
};
?>
<div class="flex flex-col gap-4">
    <div class="flex flex-wrap items-center justify-between gap-2">
        <flux:heading level="2">{{ $checkpoint->title }}</flux:heading>
        <flux:badge variant="info">Pass ≥ {{ $checkpoint->pass_score }}%</flux:badge>
    </div>

    <flux:callout variant="info" icon="academic-cap">
        {{ __('Gate: you cannot complete this lesson until you pass. Failed attempt → remediation (re-read lesson), then retry.') }}
    </flux:callout>

    <form wire:submit="submit" class="flex flex-col gap-5">
        @foreach ($checkpoint->questions ?? [] as $index => $question)
            <flux:card class="flex flex-col gap-3">
                <div class="flex items-start justify-between gap-2">
                    <strong>Q{{ $index + 1 }}.</strong>
                    <flux:text class="flex-1">{{ $question['prompt'] }}</flux:text>
                </div>

                @if (($question['type'] ?? 'text') === 'mcq')
                    <div class="flex flex-col gap-2">
                        @foreach ($question['options'] ?? [] as $option)
                            <label class="flex items-center gap-2 text-sm">
                                <input
                                    type="radio"
                                    wire:model="answers.{{ $question['id'] }}"
                                    value="{{ $option }}"
                                />
                                <span>{{ $option }}</span>
                            </label>
                        @endforeach
                    </div>
                @else
                    <textarea
                        wire:model="answers.{{ $question['id'] }}"
                        rows="2"
                        class="w-full rounded border border-zinc-700 bg-zinc-900 p-2 text-sm"
                        placeholder="{{ __('Your answer') }}"
                    ></textarea>
                @endif
            </flux:card>
        @endforeach

        <div>
            <flux:button variant="primary" type="submit" data-test="submit-checkpoint">
                {{ __('Submit checkpoint') }}
            </flux:button>
        </div>
    </form>

    @if ($result)
        <flux:callout variant="{{ $result['passed'] ? 'success' : 'danger' }}" icon="{{ $result['passed'] ? 'check-circle' : 'x-circle' }}">
            <flux:heading size="sm">
                {{ $result['passed'] ? __('Passed') : __('Not passed') }} — {{ $result['score'] }}%
            </flux:heading>
            <ul class="mt-2 list-inside list-disc text-sm">
                @foreach ($result['feedback'] ?? [] as $row)
                    <li class="{{ $row['correct'] ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                        {{ $row['correct'] ? '✓' : '✗' }}
                        {{ $row['correct'] ? 'Correct' : 'Incorrect' }}
                        @if (! $row['correct'] && filled($row['expected']))
                            — expected contains: {{ $row['expected'] }}
                        @endif
                    </li>
                @endforeach
            </ul>
        </flux:callout>
    @endif
</div>
