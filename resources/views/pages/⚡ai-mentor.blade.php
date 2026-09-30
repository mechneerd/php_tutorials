<?php

use App\Services\AiMentor;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('AI Mentor')] class extends Component
{
    public string $message = '';

    public string $intent = 'general';

    /** @var list<array{role: string, content: string, intent: string}> */
    public array $thread = [];

    public function mount(): void
    {
        $this->thread = auth()->user()
            ->mentorMessages()
            ->orderByDesc('id')
            ->limit(20)
            ->get()
            ->reverse()
            ->map(fn ($m): array => [
                'role' => $m->role,
                'content' => $m->content,
                'intent' => $m->intent,
            ])
            ->values()
            ->all();
    }

    public function send(AiMentor $mentor): void
    {
        $this->validate([
            'message' => 'required|string|max:4000',
            'intent' => 'required|string|in:'.implode(',', array_keys(AiMentor::INTENTS)),
        ]);

        $context = [
            'lesson_title' => session('current_lesson_title'),
        ];

        $reply = $mentor->ask(auth()->user(), $this->message, $this->intent, $context);

        $this->thread[] = ['role' => 'user', 'content' => $this->message, 'intent' => $this->intent];
        $this->thread[] = ['role' => 'assistant', 'content' => $reply, 'intent' => $this->intent];

        $this->message = '';
    }

    public function render()
    {
        return $this->view()->layout('layouts::app', ['title' => __('AI Mentor')]);
    }
};
?>
<div class="flex flex-1 flex-col gap-4">
    <div>
        <flux:heading size="xl" level="1">{{ __('AI Mentor') }}</flux:heading>
        <flux:subheading>{{ __('Hint · Explain mistake · Review · Beginner · Harder — knows your lesson context when configured.') }}</flux:subheading>
    </div>

    <div class="flex flex-col gap-3" x-data>
        @forelse ($thread as $entry)
            <div class="{{ $entry['role'] === 'user' ? 'self-end' : 'self-start' }} max-w-[85%]">
                <flux:card class="{{ $entry['role'] === 'user' ? 'bg-sky-950/40' : '' }}">
                    <div class="mb-1 flex items-center gap-2 text-xs text-zinc-400">
                        <span>{{ $entry['role'] === 'user' ? __('You') : __('Mentor') }}</span>
                        @if ($entry['intent'] !== 'general')
                            <flux:badge>{{ $entry['intent'] }}</flux:badge>
                        @endif
                    </div>
                    <div class="whitespace-pre-wrap text-sm">{{ $entry['content'] }}</div>
                </flux:card>
            </div>
        @empty
            <flux:callout variant="info" icon="sparkles">
                {{ __('Ask your first question. Without AI_MENTOR_API_KEY you get a structured offline mentor stub.') }}
            </flux:callout>
        @endforelse
    </div>

    <form wire:submit="send" class="flex flex-col gap-3 rounded-xl border border-zinc-700 bg-zinc-900/50 p-4">
        <div class="flex flex-wrap gap-2">
            @foreach (array_keys(AiMentor::INTENTS) as $key)
                <flux:button
                    size="sm"
                    variant="{{ $intent === $key ? 'primary' : 'ghost' }}"
                    type="button"
                    wire:click="$set('intent', '{{ $key }}')"
                >
                    {{ str_replace('_', ' ', $key) }}
                </flux:button>
            @endforeach
        </div>

        <textarea
            wire:model="message"
            rows="3"
            class="w-full rounded border border-zinc-700 bg-zinc-950 p-3 text-sm"
            placeholder="{{ __('What are you stuck on?') }}"
        ></textarea>

        @error('message') <span class="text-sm text-red-400">{{ $message }}</span> @enderror

        <div>
            <flux:button variant="primary" type="submit" data-test="mentor-send">{{ __('Send') }}</flux:button>
        </div>
    </form>
</div>
