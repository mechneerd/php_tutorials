<?php

use App\Models\CodeReviewDrill;
use App\Models\ReviewAttempt;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Code review arena')] class extends Component
{
    public function render()
    {
        $drills = CodeReviewDrill::query()->published()->orderBy('id')->get();

        $attempts = ReviewAttempt::query()
            ->where('user_id', auth()->id())
            ->get()
            ->keyBy('code_review_drill_id');

        return $this->view([
            'drills' => $drills,
            'attempts' => $attempts,
        ])->layout('layouts::app', ['title' => __('Code review arena')]);
    }
};
?>
<div class="flex flex-1 flex-col gap-6">
    <div>
        <flux:heading size="xl" level="1">{{ __('Code review arena') }}</flux:heading>
        <flux:subheading>{{ __('Find planted issues · hits, misses, false positives') }}</flux:subheading>
    </div>

    <div class="grid gap-4 md:grid-cols-2">
        @forelse ($drills as $drill)
            @php $attempt = $attempts->get($drill->id); @endphp
            <flux:card class="flex flex-col p-5" data-test="review-drill">
                <div class="flex items-start justify-between gap-3">
                    <flux:heading size="sm">{{ $drill->title }}</flux:heading>
                    <flux:badge>{{ $drill->language }}</flux:badge>
                </div>
                <flux:text class="mt-2 text-zinc-500">{{ $drill->context }}</flux:text>
                <div class="mt-3 flex gap-2">
                    <flux:badge>{{ $drill->est_minutes }}m</flux:badge>
                    <flux:badge>{{ count($drill->planted_issues) }} issues</flux:badge>
                    @if ($attempt?->is_complete)
                        <flux:badge variant="success">{{ $attempt->score }}</flux:badge>
                    @endif
                </div>
                <flux:link :href="route('senior.reviews.show', $drill)" wire:navigate class="mt-4 inline-block">
                    Open PR
                </flux:link>
            </flux:card>
        @empty
            <p>No review drills seeded.</p>
        @endforelse
    </div>
</div>
