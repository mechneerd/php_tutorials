<?php

use App\Models\Exercise;
use App\Models\ExerciseAttempt;
use App\Services\CodeRunner;
use Illuminate\Support\Collection;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Exercises')] class extends Component
{
    /** @var Collection<int, Exercise> */
    public $exercises;

    public int $activeId = 0;

    public string $code = '';

    public ?string $output = null;

    public ?string $error = null;

    public ?string $feedback = null;

    public ?bool $passed = null;

    public float $durationMs = 0;

    public function mount(Collection $exercises): void
    {
        $this->exercises = $exercises;
        $first = $exercises->first();
        if ($first) {
            $this->activeId = $first->id;
            $this->code = (string) $first->starter_code;
        }
    }

    public function selectExercise(int $id): void
    {
        $exercise = $this->exercises->firstWhere('id', $id);

        if (! $exercise) {
            return;
        }

        $this->activeId = $exercise->id;
        $this->code = (string) $exercise->starter_code;
        $this->output = null;
        $this->error = null;
        $this->feedback = null;
        $this->passed = null;
    }

    public function showHint(): void
    {
        $exercise = $this->exercises->firstWhere('id', $this->activeId);
        $this->feedback = $exercise?->hints ? 'Hint: '.$exercise->hints : 'No hint available.';
    }

    public function run(CodeRunner $runner): void
    {
        $this->resetRunState();

        if (trim($this->code) === '') {
            $this->error = 'Write some code first.';

            return;
        }

        try {
            $result = $runner->run($this->code);
            $this->output = $result['output'];
            $this->error = $result['error'];
            $this->durationMs = $result['duration_ms'];
            $this->passed = $result['success'];

            if ($result['success'] && $this->currentExercise()?->expected_output) {
                $this->passed = $runner->matchesExpected(
                    (string) $result['output'],
                    (string) $this->currentExercise()->expected_output,
                );
                $this->feedback = $this->passed
                    ? 'Output matches expected result.'
                    : 'Ran successfully, but output does not match expected.';
            }
        } catch (\Throwable $e) {
            $this->error = $e->getMessage();
            $this->passed = false;
        }
    }

    public function submit(CodeRunner $runner): void
    {
        $this->run($runner);
        $exercise = $this->currentExercise();

        if (! $exercise) {
            return;
        }

        ExerciseAttempt::query()->create([
            'user_id' => auth()->id(),
            'exercise_id' => $exercise->id,
            'code' => $this->code,
            'output' => $this->output.($this->error ? "\n".$this->error : ''),
            'passed' => (bool) $this->passed,
            'feedback' => $this->feedback,
            'run_meta' => ['duration_ms' => $this->durationMs],
        ]);
    }

    public function revealSolution(): void
    {
        $exercise = $this->currentExercise();
        if ($exercise?->solution) {
            $this->code = $exercise->solution;
            $this->feedback = 'Solution loaded into the editor. Run it, then re-attempt a similar problem without looking.';
        }
    }

    private function resetRunState(): void
    {
        $this->output = null;
        $this->error = null;
        $this->feedback = null;
        $this->passed = null;
        $this->durationMs = 0;
    }

    private function currentExercise(): ?Exercise
    {
        return $this->exercises->firstWhere('id', $this->activeId);
    }

    public function render()
    {
        return $this->view();
    }
};
?>
<div class="flex flex-col gap-4" x-data>
    <flux:heading level="2">{{ __('Exercises') }}</flux:heading>

    <div class="flex flex-wrap gap-2">
        @foreach ($exercises as $exercise)
            <flux:button
                size="sm"
                variant="{{ $activeId === $exercise->id ? 'primary' : 'ghost' }}"
                wire:click="selectExercise({{ $exercise->id }})"
            >
                L{{ $exercise->level }} — {{ $exercise->title }}
            </flux:button>
        @endforeach
    </div>

    @php($active = $exercises->firstWhere('id', $activeId))

    @if ($active)
        <flux:card class="flex flex-col gap-3">
            <div class="flex flex-wrap items-center gap-2">
                <flux:badge>Level {{ $active->level }}</flux:badge>
                <flux:badge>{{ $active->type }}</flux:badge>
            </div>
            <p class="whitespace-pre-wrap text-sm">{{ $active->prompt }}</p>

            <div class="rounded-lg border border-zinc-700 bg-zinc-950">
                <div class="flex items-center justify-between border-b border-zinc-800 px-3 py-2 text-xs text-zinc-400">
                    <span>PHP Editor</span>
                    <span>{{ $durationMs }} ms</span>
                </div>
                <textarea
                    wire:model="code"
                    rows="12"
                    spellcheck="false"
                    class="w-full bg-transparent p-3 font-mono text-sm text-emerald-300 outline-none"
                    placeholder="&lt;?php echo 'Hello';"
                ></textarea>
            </div>

            <div class="flex flex-wrap gap-2">
                <flux:button variant="primary" wire:click="run" data-test="run-code">{{ __('Run') }}</flux:button>
                <flux:button wire:click="submit" data-test="submit-code">{{ __('Submit') }}</flux:button>
                <flux:button variant="ghost" wire:click="showHint">{{ __('Hint') }}</flux:button>
                <flux:button variant="ghost" wire:click="revealSolution">{{ __('Show solution') }}</flux:button>
            </div>

            @if ($feedback)
                <flux:callout variant="{{ $passed ? 'success' : 'info' }}" icon="information-circle">
                    {{ $feedback }}
                </flux:callout>
            @endif

            @if ($output !== null && $output !== '')
                <div>
                    <flux:text class="text-zinc-500">{{ __('Output') }}</flux:text>
                    <pre class="mt-1 overflow-auto rounded bg-black/40 p-3 text-sm text-zinc-200">{{ $output }}</pre>
                </div>
            @endif

            @if ($error)
                <flux:callout variant="danger" icon="exclamation-circle">
                    <pre class="whitespace-pre-wrap text-sm">{{ $error }}</pre>
                </flux:callout>
            @endif
        </flux:card>
    @endif
</div>
