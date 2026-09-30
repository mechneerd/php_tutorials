<?php

use App\Models\InternalsAttempt;
use App\Models\InternalsTopic;
use App\Models\Lesson;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Internals topic')] class extends Component
{
    public InternalsTopic $topicModel;

    public int $promptIndex = 0;

    public string $answer = '';

    public bool $submitted = false;

    public int $score = 0;

    public function mount(InternalsTopic $internalsTopic): void
    {
        $this->topicModel = $internalsTopic;

        $attempt = InternalsAttempt::query()
            ->where('user_id', auth()->id())
            ->where('internals_topic_id', $internalsTopic->id)
            ->where('is_complete', true)
            ->orderByDesc('updated_at')
            ->first();

        if ($attempt) {
            $this->answer = (string) $attempt->answer;
            $this->score = (int) $attempt->score;
            $this->submitted = true;
        }
    }

    public function nextPrompt(): void
    {
        $prompts = $this->topicModel->explain_back_prompts ?? [];
        if (! $prompts) {
            return;
        }
        $this->promptIndex = ($this->promptIndex + 1) % count($prompts);
        $this->answer = '';
        $this->submitted = false;
        $this->score = 0;
    }

    public function submit(): void
    {
        $topic = $this->topicModel;
        $prompts = $topic->explain_back_prompts ?? [];
        $prompt = (string) ($prompts[$this->promptIndex] ?? '');
        $score = $this->scoreAnswer($prompt, $this->answer, $topic);

        InternalsAttempt::query()->create([
            'user_id' => auth()->id(),
            'internals_topic_id' => $topic->id,
            'prompt_index' => $this->promptIndex,
            'answer' => $this->answer,
            'score' => $score,
            'is_complete' => true,
        ]);

        $this->score = $score;
        $this->submitted = true;
    }

    private function scoreAnswer(string $prompt, string $answer, InternalsTopic $topic): int
    {
        $text = mb_strtolower(trim($answer));

        if (mb_strlen($text) < 40) {
            return 25;
        }

        if (mb_strlen($text) >= 120) {
            return 70;
        }

        $keywords = [
            'zval' => ['zval', 'value'],
            'refcount' => ['refcount', 'reference count', 'ref count'],
            'cow' => ['copy-on-write', 'copy on write', 'cow', 'separate'],
            'opcache' => ['opcache', 'opcode'],
            'request' => ['request', 'kernel', 'middleware', 'bootstrap'],
            'fiber' => ['fiber', 'generator', 'yield', 'suspend'],
            'cycle' => ['cycle', 'gc', 'garbage'],
            'psr-4' => ['psr-4', 'autoload', 'namespace', 'composer'],
        ];

        $blob = $text.' '.mb_strtolower($topic->title.' '.$topic->summary).' '.mb_strtolower($prompt);
        $hit = 0;
        $total = 0;
        foreach ($keywords as $unused) {
            $total++;
            foreach ($unused as $word) {
                if (str_contains($blob, $word) || str_contains($text, $word)) {
                    $hit++;
                    break;
                }
            }
        }

        $ratio = $total > 0 ? $hit / $total : 0;
        $score = 40 + (int) round($ratio * 45);

        if (mb_strlen($text) >= 80) {
            $score += 15;
        }

        return (int) min(100, $score);
    }

    public function render()
    {
        $prompts = $this->topicModel->explain_back_prompts ?? [];
        $related = $this->topicModel->related_lesson_code
            ? Lesson::query()->where('code', $this->topicModel->related_lesson_code)->first()
            : null;

        return $this->view([
            'topic' => $this->topicModel,
            'prompts' => $prompts,
            'related' => $related,
            'submitted' => $this->submitted,
            'score' => $this->score,
            'promptIndex' => $this->promptIndex,
        ])->layout('layouts::app', ['title' => $this->topicModel->title]);
    }
};
?>
<div class="flex flex-1 flex-col gap-6">
    <div>
        <flux:link :href="route('senior.internals')" wire:navigate class="text-sm">{{ __('← All topics') }}</flux:link>
        <div class="flex flex-wrap items-center gap-3">
            <flux:heading size="xl" level="1">{{ $topic->title }}</flux:heading>
            @if ($submitted)
                <flux:badge :variant="$score >= 70 ? 'success' : 'warning'" data-test="internals-score">{{ $score }}</flux:badge>
            @endif
        </div>
        <flux:subheading>{{ $topic->summary }}</flux:subheading>
    </div>

    <div class="prose dark:prose-invert max-w-none rounded-xl border border-zinc-200 p-5 dark:border-zinc-700" data-test="internals-body">
        {!! $topic->body_html !!}
    </div>

    @if ($related)
        <flux:link :href="route('lessons.show', $related)" wire:navigate>
            Related lesson: {{ $related->code }} — {{ $related->title }}
        </flux:link>
    @endif

    @if ($prompts)
        <flux:card class="p-5">
            <flux:heading size="sm">{{ __('Explain it back') }}</flux:heading>
            <p class="mt-1 text-sm text-zinc-500">Prompt {{ $promptIndex + 1 }}/{{ count($prompts) }}</p>
            <p class="mt-2 font-medium">{{ $prompts[$promptIndex] }}</p>

            @unless ($submitted)
                <textarea wire:model="answer" rows="6" class="mt-3 w-full rounded-lg border border-zinc-300 bg-white p-3 text-sm dark:border-zinc-600 dark:bg-zinc-900" data-test="internals-answer" placeholder="Answer as if the interviewer just asked…"></textarea>
                <div class="mt-3 flex flex-wrap gap-3">
                    <flux:button type="button" wire:click="submit" data-test="internals-submit">Score my answer</flux:button>
                    <flux:button type="button" variant="ghost" wire:click="nextPrompt">Next prompt</flux:button>
                </div>
            @else
                <div class="mt-3 rounded-lg bg-zinc-50 p-4 text-sm dark:bg-zinc-800" data-test="internals-result">
                    Score <strong>{{ $score }}</strong>{{ $score >= 70 ? ' — passed (≥70)' : ' — keep going (need 70)' }}
                    <div class="mt-2 whitespace-pre-wrap text-zinc-600 dark:text-zinc-400">{{ $answer }}</div>
                </div>
                <flux:button type="button" variant="ghost" class="mt-3" wire:click="nextPrompt">Next prompt</flux:button>
            @endunless
        </flux:card>
    @endif
</div>
