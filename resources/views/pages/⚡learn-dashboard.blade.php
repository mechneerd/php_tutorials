<?php

use App\Services\ProgressGate;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Learn')] class extends Component
{
    public function render(ProgressGate $gate)
    {
        $summary = $gate->summary(auth()->user());
        $nextLesson = null;

        foreach ($summary['stages'] as $stageData) {
            foreach ($stageData['lessons'] as $item) {
                if (in_array($item['status'], ['available', 'in_progress'], true)) {
                    $nextLesson = $item['lesson'];
                    break 2;
                }
            }
        }

        return $this->view([
            'summary' => $summary,
            'nextLesson' => $nextLesson,
        ])->layout('layouts::app', ['title' => __('Learn')]);
    }
};
?>
<div class="flex flex-1 flex-col gap-6">
    <div>
        <flux:heading size="xl" level="1">{{ __('Learning Dashboard') }}</flux:heading>
        <flux:subheading>{{ __('Progress, next lesson, and gates') }}</flux:subheading>
    </div>

    <div class="grid gap-4 md:grid-cols-3">
        <flux:card>
            <flux:text class="text-zinc-500">{{ __('Completed') }}</flux:text>
            <flux:heading size="lg">{{ $summary['completed'] }} / {{ $summary['total'] }}</flux:heading>
        </flux:card>
        <flux:card>
            <flux:text class="text-zinc-500">{{ __('Overall') }}</flux:text>
            <flux:heading size="lg">{{ $summary['percent'] }}%</flux:heading>
            <div class="mt-2 h-2 w-full rounded bg-zinc-200 dark:bg-zinc-700">
                <div class="h-2 rounded bg-emerald-500" style="width: {{ $summary['percent'] }}%"></div>
            </div>
        </flux:card>
        <flux:card class="flex flex-col gap-2">
            <flux:text class="text-zinc-500">{{ __('Next up') }}</flux:text>
            @if ($nextLesson)
                <flux:link :href="route('lessons.show', $nextLesson)" wire:navigate class="font-medium">
                    {{ $nextLesson->title }}
                </flux:link>
                <flux:button variant="primary" href="{{ route('lessons.show', $nextLesson) }}" wire:navigate>
                    {{ __('Continue') }}
                </flux:button>
            @else
                <flux:text>{{ $summary['completed'] === $summary['total'] && $summary['total'] > 0 ? __('All published lessons complete!') : __('No unlocked lessons yet — start Stage 0.') }}</flux:text>
                <flux:button variant="primary" href="{{ route('roadmap') }}" wire:navigate>
                    {{ __('Open roadmap') }}
                </flux:button>
            @endif
        </flux:card>
    </div>

    <flux:card>
        <flux:heading level="3">{{ __('By stage') }}</flux:heading>
        <div class="mt-4 flex flex-col gap-4">
            @foreach ($summary['stages'] as $stageData)
                @php
                    $pct = $stageData['total'] > 0 ? round(($stageData['completed'] / $stageData['total']) * 100) : 0;
                @endphp
                <div>
                    <div class="mb-1 flex items-center justify-between text-sm">
                        <span>{{ $stageData['stage']->title }}</span>
                        <span class="text-zinc-500">{{ $stageData['completed'] }}/{{ $stageData['total'] }}</span>
                    </div>
                    <div class="h-2 w-full rounded bg-zinc-200 dark:bg-zinc-700">
                        <div class="h-2 rounded bg-sky-500" style="width: {{ $pct }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </flux:card>

    <div class="flex flex-wrap gap-3">
        <flux:button href="{{ route('roadmap') }}" wire:navigate>{{ __('Roadmap') }}</flux:button>
        <flux:button href="{{ route('playground') }}" wire:navigate>{{ __('Code playground') }}</flux:button>
        <flux:button href="{{ route('mentor') }}" wire:navigate>{{ __('AI mentor') }}</flux:button>
        <flux:button href="{{ route('interviews') }}" wire:navigate>{{ __('Interview mode') }}</flux:button>
        <flux:button href="{{ route('projects') }}" wire:navigate>{{ __('Projects') }}</flux:button>
    </div>
</div>
