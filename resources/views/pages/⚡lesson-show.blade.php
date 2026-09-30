<?php

use App\Models\Lesson;
use App\Services\ProgressGate;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Lesson')] class extends Component
{
    public Lesson $lesson;

    public bool $allowed = true;

    /** @var list<string> */
    public array $lockReasons = [];

    public function mount(ProgressGate $gate, Lesson $lesson): void
    {
        $this->lesson = $lesson->load([
            'stage',
            'exercises',
            'checkpoints',
            'prerequisites',
            'dependentLessons',
        ]);

        abort_unless($this->lesson->is_published, 404);

        $result = $gate->canAccess(auth()->user(), $this->lesson);
        $this->allowed = $result['allowed'];
        $this->lockReasons = $result['reasons'];

        if ($this->allowed) {
            $gate->markInProgress(auth()->user(), $this->lesson);
        }
    }

    public function markComplete(ProgressGate $gate): void
    {
        abort_unless($this->allowed, 403);

        $checkpoint = $this->lesson->checkpoints()->first();

        if ($this->lesson->requires_checkpoint && $checkpoint) {
            $passed = app(\App\Services\CheckpointEvaluator::class)
                ->hasPassed(auth()->user(), $checkpoint);

            if (! $passed) {
                session()->flash('error', __('Pass the checkpoint before completing this lesson.'));

                return;
            }
        }

        $gate->markCompleted(auth()->user(), $this->lesson);
        session()->flash('status', __('Lesson completed.'));

        $this->redirectIntended(default: route('learn.index', absolute: false));
    }

    public function render()
    {
        return $this->view([
            'related' => $this->lesson->relatedLessons(),
            'unlocks' => $this->lesson->dependentLessons,
            'prev' => $this->lesson->prevLesson(),
            'next' => $this->lesson->nextLesson(),
        ])->layout('layouts::app', [
            'title' => $this->lesson->title,
        ]);
    }
};
?>
<div class="flex flex-1 flex-col gap-6">
    <div class="flex flex-wrap items-start justify-between gap-3">
        <div>
            <flux:badge>{{ $this->lesson->stage?->title }}</flux:badge>
            <flux:heading size="xl" level="1" class="mt-2">{{ $this->lesson->title }}</flux:heading>
            @if ($this->lesson->summary)
                <flux:subheading>{{ $this->lesson->summary }}</flux:subheading>
            @endif
            <div class="mt-2 flex flex-wrap gap-2">
                <flux:badge>{{ $this->lesson->code }}</flux:badge>
                <flux:badge>{{ $this->lesson->estimated_minutes }} min</flux:badge>
                <flux:badge>{{ $this->lesson->difficulty }}</flux:badge>
            </div>
        </div>

        <div class="flex flex-col items-end gap-2">
            <flux:link :href="route('roadmap')" wire:navigate>{{ __('Back to roadmap') }}</flux:link>
            @if ($this->lesson->prerequisites->isNotEmpty())
                <flux:text class="text-sm text-zinc-500">
                    {{ __('Prerequisites:') }}
                    {{ $this->lesson->prerequisites->pluck('code')->join(', ') }}
                </flux:text>
            @endif
        </div>
    </div>

    @if (! $allowed)
        <flux:callout variant="warning" icon="lock-closed">
            <flux:heading size="sm">{{ __('Locked') }}</flux:heading>
            <ul class="mt-1 list-inside list-disc">
                @foreach ($lockReasons as $reason)
                    <li>{{ $reason }}</li>
                @endforeach
            </ul>
        </flux:callout>
    @else
        @if (session('status'))
            <flux:callout variant="success" icon="check-circle">{{ session('status') }}</flux:callout>
        @endif
        @if (session('error'))
            <flux:callout variant="danger" icon="exclamation-circle">{{ session('error') }}</flux:callout>
        @endif

        <flux:card class="prose prose-invert max-w-none dark:prose-invert">
            {!! $this->lesson->body_html !!}
        </flux:card>

        @if ($this->lesson->exercises->isNotEmpty())
            <livewire:pages::exercise-panel :exercises="$this->lesson->exercises" :lesson="$this->lesson" />
        @endif

        @if ($this->lesson->checkpoints->isNotEmpty())
            <livewire:pages::checkpoint-panel :checkpoint="$this->lesson->checkpoints->first()" :lesson="$this->lesson" />
        @endif

        <flux:card data-test="knowledge-graph">
            <flux:heading level="3">{{ __('Knowledge graph') }}</flux:heading>
            <div class="mt-3 grid gap-4 text-sm md:grid-cols-3">
                <div>
                    <flux:text class="font-medium text-zinc-400">{{ __('Requires') }}</flux:text>
                    <ul class="mt-1 flex flex-col gap-1">
                        @forelse ($this->lesson->prerequisites as $pre)
                            <li>
                                <flux:link :href="route('lessons.show', $pre)" wire:navigate>
                                    {{ $pre->code }} — {{ $pre->title }}
                                </flux:link>
                            </li>
                        @empty
                            <li class="text-zinc-500">{{ __('Entry point') }}</li>
                        @endforelse
                    </ul>
                </div>
                <div>
                    <flux:text class="font-medium text-zinc-400">{{ __('Unlocks') }}</flux:text>
                    <ul class="mt-1 flex flex-col gap-1">
                        @forelse ($unlocks as $unlock)
                            <li>
                                <flux:link :href="route('lessons.show', $unlock)" wire:navigate>
                                    {{ $unlock->code }} — {{ $unlock->title }}
                                </flux:link>
                            </li>
                        @empty
                            <li class="text-zinc-500">{{ __('End of chain') }}</li>
                        @endforelse
                    </ul>
                </div>
                <div>
                    <flux:text class="font-medium text-zinc-400">{{ __('Related') }}</flux:text>
                    <ul class="mt-1 flex flex-col gap-1">
                        @forelse ($related as $rel)
                            <li>
                                <flux:link :href="route('lessons.show', $rel)" wire:navigate>
                                    {{ $rel->code }} — {{ $rel->title }}
                                </flux:link>
                            </li>
                        @empty
                            <li class="text-zinc-500">{{ __('No lateral links yet') }}</li>
                        @endforelse
                    </ul>
                </div>
            </div>
            <div class="mt-4 flex flex-wrap justify-between gap-2 border-t border-zinc-800 pt-3">
                @if ($prev)
                    <flux:link :href="route('lessons.show', $prev)" wire:navigate>
                        ← {{ $prev->code }} {{ $prev->title }}
                    </flux:link>
                @else
                    <span></span>
                @endif
                @if ($next)
                    <flux:link :href="route('lessons.show', $next)" wire:navigate>
                        {{ $next->code }} {{ $next->title }} →
                    </flux:link>
                @endif
            </div>
        </flux:card>

        <div class="flex flex-wrap items-center gap-3">
            <flux:button variant="primary" wire:click="markComplete" data-test="complete-lesson-button">
                {{ __('Mark lesson complete') }}
            </flux:button>
            <flux:button variant="ghost" href="{{ route('mentor') }}" wire:navigate>
                {{ __('Ask AI mentor') }}
            </flux:button>
        </div>
    @endif
</div>
