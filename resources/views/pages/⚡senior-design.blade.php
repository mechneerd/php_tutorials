<?php

use App\Models\DesignCase;
use App\Models\DesignAttempt;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('System design studio')] class extends Component
{
    public function render()
    {
        $cases = DesignCase::query()->published()->orderBy('order_column')->get();
        $userId = auth()->id();

        $attempts = DesignAttempt::query()
            ->where('user_id', $userId)
            ->get()
            ->keyBy('design_case_id');

        return $this->view([
            'cases' => $cases,
            'attempts' => $attempts,
        ])->layout('layouts::app', ['title' => __('Design studio')]);
    }
};
?>
<div class="flex flex-1 flex-col gap-6">
    <div>
        <flux:heading size="xl" level="1">{{ __('System design studio') }}</flux:heading>
        <flux:subheading>{{ __('45-minute cases · trade-off defense · model answers') }}</flux:subheading>
    </div>

    <div class="grid gap-4 md:grid-cols-2">
        @forelse ($cases as $case)
            @php $attempt = $attempts->get($case->id); @endphp
            <flux:card class="flex flex-col p-5" data-test="design-case">
                <div class="flex items-start justify-between gap-3">
                    <flux:heading size="sm">{{ $case->title }}</flux:heading>
                    <flux:badge :variant="$case->level === 'senior' ? 'success' : 'gray'">{{ $case->level }}</flux:badge>
                </div>
                <flux:text class="mt-2 line-clamp-3 text-zinc-500">{{ $case->prompt }}</flux:text>
                <div class="mt-4 flex flex-wrap items-center gap-2">
                    <flux:badge>{{ $case->track }}</flux:badge>
                    <flux:badge>{{ $case->est_minutes }}m</flux:badge>
                    @if ($attempt?->is_complete)
                        <flux:badge variant="success">score {{ $attempt->score }}</flux:badge>
                    @elseif ($attempt)
                        <flux:badge variant="warning">in progress</flux:badge>
                    @endif
                </div>
                <flux:link :href="route('senior.design.show', $case)" wire:navigate class="mt-4 inline-block">
                    {{ $attempt?->is_complete ? __('Review again') : __('Start case') }}
                </flux:link>
            </flux:card>
        @empty
            <p>No design cases seeded yet.</p>
        @endforelse
    </div>
</div>
