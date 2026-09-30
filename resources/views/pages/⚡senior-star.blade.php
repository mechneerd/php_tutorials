<?php

use App\Models\StarStory;
use Illuminate\Support\Str;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('STAR stories')] class extends Component
{
    public const PROMPTS = [
        ['slug' => 'disagreed-tech-lead', 'title' => 'Disagreed with tech lead / drove consensus'],
        ['slug' => 'owned-outage', 'title' => 'Production incident you led or owned'],
        ['slug' => 'bad-migration', 'title' => 'Bad migration / risky deploy you mitigated'],
        ['slug' => 'cut-scope', 'title' => 'Cut scope under deadline'],
        ['slug' => 'mentored', 'title' => 'Mentored or unblocked someone'],
        ['slug' => 'tech-debt-won', 'title' => 'Technical debt argument that won'],
        ['slug' => 'failure-owned', 'title' => 'Failure you owned and what changed'],
        ['slug' => 'cross-team', 'title' => 'Multi-team cross-boundary delivery'],
    ];

    public ?int $editingId = null;

    public string $promptSlug = '';

    public string $title = '';

    public string $situation = '';

    public string $task = '';

    public string $action = '';

    public string $result = '';

    public string $metrics = '';

    public string $lessonsLearned = '';

    public string $status = 'draft';

    public function createNew(?string $promptSlug = null): void
    {
        $this->resetForm();
        if ($promptSlug) {
            $this->promptSlug = $promptSlug;
            $prompt = collect(self::PROMPTS)->firstWhere('slug', $promptSlug);
            $this->title = $prompt['title'] ?? Str::headline($promptSlug);
        }
    }

    public function edit(int $storyId): void
    {
        $story = StarStory::query()
            ->where('user_id', auth()->id())
            ->findOrFail($storyId);

        $this->editingId = $story->id;
        $this->promptSlug = (string) $story->prompt_slug;
        $this->title = $story->title;
        $this->situation = (string) $story->situation;
        $this->task = (string) $story->task;
        $this->action = (string) $story->action;
        $this->result = (string) $story->result;
        $this->metrics = implode("\n", $story->metrics ?? []);
        $this->lessonsLearned = (string) $story->lessons_learned;
        $this->status = $story->status;
    }

    public function save(): void
    {
        $this->validate([
            'title' => 'required|string|max:200',
            'situation' => 'required|string|min:20',
            'task' => 'required|string|min:10',
            'action' => 'required|string|min:20',
            'result' => 'required|string|min:15',
            'status' => 'required|in:draft,rehearsed,interview_ready',
        ]);

        $metrics = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $this->metrics) ?: [])));

        $payload = [
            'prompt_slug' => $this->promptSlug ?: null,
            'title' => $this->title,
            'situation' => $this->situation,
            'task' => $this->task,
            'action' => $this->action,
            'result' => $this->result,
            'metrics' => $metrics,
            'lessons_learned' => $this->lessonsLearned,
            'status' => $this->status,
            'tags' => [$this->status === 'interview_ready' ? 'ready' : 'draft'],
        ];

        if ($this->editingId) {
            StarStory::query()
                ->where('user_id', auth()->id())
                ->whereKey($this->editingId)
                ->update($payload);
        } else {
            StarStory::query()->create(array_merge($payload, ['user_id' => auth()->id()]));
            $this->editingId = null;
        }

        $this->resetForm();
        session()->flash('status', 'story-saved');
    }

    public function markReady(int $storyId): void
    {
        StarStory::query()
            ->where('user_id', auth()->id())
            ->whereKey($storyId)
            ->update(['status' => 'interview_ready']);
    }

    public function resetForm(): void
    {
        $this->reset([
            'editingId',
            'promptSlug',
            'title',
            'situation',
            'task',
            'action',
            'result',
            'metrics',
            'lessonsLearned',
            'status',
        ]);
    }

    public function render()
    {
        $stories = StarStory::query()
            ->where('user_id', auth()->id())
            ->orderByDesc('updated_at')
            ->get();

        return $this->view([
            'stories' => $stories,
            'prompts' => self::PROMPTS,
            'editingId' => $this->editingId,
            'title' => $this->title,
            'situation' => $this->situation,
            'task' => $this->task,
            'action' => $this->action,
            'result' => $this->result,
            'metrics' => $this->metrics,
            'lessonsLearned' => $this->lessonsLearned,
            'promptSlug' => $this->promptSlug,
            'status' => $this->status,
        ])->layout('layouts::app', ['title' => __('STAR stories')]);
    }
};
?>
<div class="flex flex-1 flex-col gap-6">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <flux:heading size="xl" level="1">{{ __('STAR story builder') }}</flux:heading>
            <flux:subheading>{{ $stories->where('status', 'interview_ready')->count() }}/5 interview-ready · gate</flux:subheading>
        </div>
        <flux:button type="button" wire:click="createNew" data-test="star-new">{{ __('New blank story') }}</flux:button>
    </div>

    <div class="grid gap-4 md:grid-cols-2">
        <flux:card class="p-5">
            <flux:heading size="sm">{{ __('Prompts') }}</flux:heading>
            <div class="mt-3 space-y-2">
                @foreach ($prompts as $prompt)
                    <flux:button
                        type="button"
                        variant="ghost"
                        size="sm"
                        class="w-full justify-start"
                        wire:click="createNew('{{ $prompt['slug'] }}')"
                        data-test="star-prompt"
                    >
                        {{ $prompt['title'] }}
                    </flux:button>
                @endforeach
            </div>
        </flux:card>

        <flux:card class="p-5">
            <flux:heading size="sm">{{ $editingId ? __('Edit story') : __('Draft story') }}</flux:heading>
            <form wire:submit.prevent="save" class="mt-3 space-y-3">
                <input wire:model="title" placeholder="Title" class="w-full rounded-lg border border-zinc-300 bg-white p-2 text-sm dark:border-zinc-600 dark:bg-zinc-900" data-test="star-title" />
                <div wire:loading wire:target="title" class="text-xs text-zinc-400">validating…</div>
                <textarea wire:model="situation" rows="2" placeholder="Situation — context, stakes" class="w-full rounded-lg border border-zinc-300 bg-white p-2 text-sm dark:border-zinc-600 dark:bg-zinc-900" data-test="star-situation"></textarea>
                <textarea wire:model="task" rows="2" placeholder="Task — your responsibility" class="w-full rounded-lg border border-zinc-300 bg-white p-2 text-sm dark:border-zinc-600 dark:bg-zinc-900"></textarea>
                <textarea wire:model="action" rows="3" placeholder="Action — what YOU did (trade-offs)" class="w-full rounded-lg border border-zinc-300 bg-white p-2 text-sm dark:border-zinc-600 dark:bg-zinc-900" data-test="star-action"></textarea>
                <textarea wire:model="result" rows="2" placeholder="Result — measurable outcome" class="w-full rounded-lg border border-zinc-300 bg-white p-2 text-sm dark:border-zinc-600 dark:bg-zinc-900" data-test="star-result"></textarea>
                <textarea wire:model="metrics" rows="2" placeholder="Metrics (one per line): p99 2.1s → 180ms" class="w-full rounded-lg border border-zinc-300 bg-white p-2 text-sm dark:border-zinc-600 dark:bg-zinc-900"></textarea>
                <textarea wire:model="lessonsLearned" rows="2" placeholder="What you'd do differently" class="w-full rounded-lg border border-zinc-300 bg-white p-2 text-sm dark:border-zinc-600 dark:bg-zinc-900"></textarea>
                <select wire:model="status" class="w-full rounded-lg border border-zinc-300 bg-white p-2 text-sm dark:border-zinc-600 dark:bg-zinc-900">
                    <option value="draft">draft</option>
                    <option value="rehearsed">rehearsed</option>
                    <option value="interview_ready">interview_ready</option>
                </select>
                <div class="flex gap-2">
                    <flux:button type="submit" data-test="star-save">{{ __('Save story') }}</flux:button>
                    <flux:button type="button" variant="ghost" wire:click="resetForm">{{ __('Clear') }}</flux:button>
                </div>
                @error('title') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                @error('situation') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
            </form>
        </flux:card>
    </div>

    <div class="space-y-3" data-test="star-list">
        @forelse ($stories as $story)
            <flux:card class="flex flex-wrap items-center justify-between gap-3 p-4">
                <div>
                    <div class="font-medium">{{ $story->title }}</div>
                    <flux:badge :variant="$story->status === 'interview_ready' ? 'success' : ($story->status === 'rehearsed' ? 'warning' : 'gray')">
                        {{ $story->status }}
                    </flux:badge>
                </div>
                <div class="flex gap-2">
                    <flux:button type="button" size="sm" variant="ghost" wire:click="edit({{ $story->id }})">Edit</flux:button>
                    @unless ($story->status === 'interview_ready')
                        <flux:button type="button" size="sm" wire:click="markReady({{ $story->id }})" data-test="star-ready">Mark interview-ready</flux:button>
                    @endunless
                </div>
            </flux:card>
        @empty
            <p>No stories yet — pick a prompt above.</p>
        @endforelse
    </div>
</div>
