<?php

use App\Models\InternalsAttempt;
use App\Models\InternalsTopic;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('PHP internals')] class extends Component
{
    public function render()
    {
        $topics = InternalsTopic::query()->published()->orderBy('order_column')->get();

        $passed = InternalsAttempt::query()
            ->where('user_id', auth()->id())
            ->where('is_complete', true)
            ->where('score', '>=', 70)
            ->pluck('internals_topic_id')
            ->flip();

        return $this->view([
            'topics' => $topics,
            'passed' => $passed,
        ])->layout('layouts::app', ['title' => __('PHP internals')]);
    }
};
?>
<div class="flex flex-1 flex-col gap-6">
    <div>
        <flux:heading size="xl" level="1">{{ __('PHP internals') }}</flux:heading>
        <flux:subheading>{{ __('Deep dives - explain-back prompts - 70% gate per topic') }}</flux:subheading>
    </div>

    <div class="grid gap-4 md:grid-cols-2">
        @forelse ($topics as $topic)
            <flux:card class="flex flex-col p-5" data-test="internals-topic">
                <div class="flex items-start justify-between gap-3">
                    <flux:heading size="sm">{{ $topic->title }}</flux:heading>
                    @if ($passed->has($topic->id))
                        <flux:badge variant="success">passed</flux:badge>
                    @endif
                </div>
                <flux:text class="mt-2 text-zinc-500">{{ $topic->summary }}</flux:text>
                <flux:link :href="route('senior.internals.show', $topic)" wire:navigate class="mt-4 inline-block">
                    Study and explain back
                </flux:link>
            </flux:card>
        @empty
            <p>No internals topics seeded.</p>
        @endforelse
    </div>
</div>
