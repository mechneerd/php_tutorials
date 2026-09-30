<?php

use App\Models\Stage;
use App\Services\ProgressGate;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Learning Roadmap')] class extends Component
{
    public function render(ProgressGate $gate)
    {
        $summary = $gate->summary(auth()->user());

        return $this->view([
            'stages' => Stage::query()
                ->where('is_published', true)
                ->orderBy('order_column')
                ->with(['lessons' => fn ($q) => $q->where('is_published', true)])
                ->get(),
            'summary' => $summary,
        ])->layout('layouts::app', ['title' => __('Roadmap')]);
    }
};
?>
<div class="flex flex-1 flex-col gap-6">
    <div>
        <flux:heading size="xl" level="1">{{ __('PHP Learning Roadmap') }}</flux:heading>
        <flux:subheading>{{ __('Beginner → Fundamentals → Intermediate → Advanced → Production → Senior') }}</flux:subheading>
    </div>

    <div class="flex flex-wrap items-center gap-4">
        <flux:badge variant="success">{{ $summary['completed'] }}/{{ $summary['total'] }} lessons</flux:badge>
        <flux:badge>{{ $summary['percent'] }}%</flux:badge>
        <flux:link :href="route('learn.index')" wire:navigate>{{ __('Open dashboard') }}</flux:link>
    </div>

    <div class="grid gap-4 md:grid-cols-2">
        @foreach ($stages as $stage)
            @php
                $stageSummary = $summary['stages'][$stage->id] ?? null;
            @endphp
            <flux:card class="flex flex-col gap-3">
                <div class="flex items-start justify-between gap-2">
                    <flux:heading level="3">{{ $stage->title }}</flux:heading>
                    @if ($stageSummary)
                        <flux:badge>{{ $stageSummary['completed'] }}/{{ $stageSummary['total'] }}</flux:badge>
                    @endif
                </div>

                @if ($stage->description)
                    <flux:text>{{ $stage->description }}</flux:text>
                @endif

                <ul class="flex flex-col gap-2 text-sm">
                    @foreach ($stage->lessons as $lesson)
                        @php
                            $item = collect($stageSummary['lessons'] ?? [])->first(
                                fn ($row) => $row['lesson']->id === $lesson->id
                            ) ?? ['status' => 'available', 'reasons' => []];
                        @endphp
                        <li class="flex items-center justify-between gap-2">
                            <flux:link
                                :href="$item['status'] === 'locked' ? null : route('lessons.show', $lesson)"
                                :class="$item['status'] === 'locked' ? 'opacity-50 no-underline cursor-not-allowed' : ''"
                                wire:navigate
                            >
                                {{ $lesson->code }} — {{ $lesson->title }}
                            </flux:link>

                            @if ($item['status'] === 'completed')
                                <flux:badge variant="success">Done</flux:badge>
                            @elseif ($item['status'] === 'locked')
                                <flux:badge variant="warning" title="{{ implode('; ', $item['reasons']) }}">Locked</flux:badge>
                            @elseif ($item['status'] === 'in_progress')
                                <flux:badge variant="info">In progress</flux:badge>
                            @else
                                <flux:badge>Available</flux:badge>
                            @endif
                        </li>
                    @endforeach

                    @if ($stage->lessons->isEmpty())
                        <li class="text-zinc-500">{{ __('Lessons coming soon.') }}</li>
                    @endif
                </ul>
            </flux:card>
        @endforeach
    </div>
</div>
