<?php

namespace App\Services;

use App\Models\MentorMessage;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiMentor
{
    public const INTENTS = [
        'hint' => 'Give a hint only. Do not reveal the full solution.',
        'explain_mistake' => 'Explain the mistake in the learner code. Do not paste fixed code.',
        'review' => 'Review the code like a senior engineer: correctness, clarity, edge cases.',
        'beginner' => 'Explain like the learner is a complete beginner. Use first principles.',
        'harder' => 'Propose a harder related problem with clear constraints.',
        'general' => 'Answer the learner question as a PHP mentor.',
    ];

    /**
     * @param  array<string, mixed>  $context
     */
    public function ask(User $user, string $message, string $intent = 'general', array $context = []): string
    {
        MentorMessage::query()->create([
            'user_id' => $user->id,
            'lesson_id' => $context['lesson_id'] ?? null,
            'exercise_id' => $context['exercise_id'] ?? null,
            'role' => 'user',
            'intent' => $intent,
            'content' => $message,
            'context' => $context,
        ]);

        $reply = $this->complete($user, $message, $intent, $context);

        MentorMessage::query()->create([
            'user_id' => $user->id,
            'lesson_id' => $context['lesson_id'] ?? null,
            'exercise_id' => $context['exercise_id'] ?? null,
            'role' => 'assistant',
            'intent' => $intent,
            'content' => $reply,
            'context' => $context,
        ]);

        return $reply;
    }

    /**
     * @param  array<string, mixed>  $context
     */
    private function complete(User $user, string $message, string $intent, array $context): string
    {
        $apiKey = (string) config('services.ai_mentor.key');

        if ($apiKey === '') {
            return $this->stubReply($user, $message, $intent, $context);
        }

        $history = MentorMessage::query()
            ->where('user_id', $user->id)
            ->orderByDesc('id')
            ->limit(12)
            ->get()
            ->reverse()
            ->map(fn (MentorMessage $m): array => [
                'role' => $m->role === 'assistant' ? 'assistant' : 'user',
                'content' => $m->content,
            ])
            ->values()
            ->all();

        $system = $this->systemPrompt($user, $intent, $context);

        try {
            $response = Http::withToken($apiKey)
                ->timeout(30)
                ->post(rtrim((string) config('services.ai_mentor.url'), '/').'/chat/completions', [
                    'model' => (string) config('services.ai_mentor.model'),
                    'messages' => array_merge(
                        [['role' => 'system', 'content' => $system]],
                        $history,
                        [['role' => 'user', 'content' => $message]],
                    ),
                    'temperature' => 0.4,
                ]);

            if ($response->failed()) {
                Log::warning('AI mentor HTTP failure', ['status' => $response->status()]);

                return $this->stubReply($user, $message, $intent, $context);
            }

            return (string) data_get($response->json(), 'choices.0.message.content', $this->stubReply($user, $message, $intent, $context));
        } catch (\Throwable $e) {
            Log::warning('AI mentor error: '.$e->getMessage());

            return $this->stubReply($user, $message, $intent, $context);
        }
    }

    /**
     * @param  array<string, mixed>  $context
     */
    private function systemPrompt(User $user, string $intent, array $context): string
    {
        $instruction = self::INTENTS[$intent] ?? self::INTENTS['general'];

        $progress = '';
        if (! empty($context['lesson_title'])) {
            $progress .= 'Current lesson: '.$context['lesson_title']."\n";
        }
        if (! empty($context['learner_code'])) {
            $progress .= "Learner code:\n```\n".$context['learner_code']."\n```\n";
        }
        if (! empty($context['exercise_prompt'])) {
            $progress .= "Exercise:\n".$context['exercise_prompt']."\n";
        }

        $lines = [
            'You are a PHP mentor for a beginner to senior learning platform.',
            'Learner name: '.$user->name.'.',
            'Intent: '.$instruction,
            'Rules:',
            '- Teach from first principles.',
            '- Prefer hints over full answers unless intent is review.',
            '- Use modern PHP 8 idioms.',
            '- Be concise but complete.',
            '- Never invent language features; if unsure, say so.',
            $progress,
        ];

        return implode("\n", $lines);
    }

    /**
     * @param  array<string, mixed>  $context
     */
    private function stubReply(User $user, string $message, string $intent, array $context): string
    {
        $lesson = (string) ($context['lesson_title'] ?? 'the current lesson');

        return match ($intent) {
            'hint' => 'Hint (no full solution): re-read the steps in "'.$lesson.'". Ask yourself: what is the input, what is the output, and which PHP construct maps input to output? Reply with what you already tried.',
            'explain_mistake' => 'I can see your attempt. Check: (1) missing php open tag or semicolon, (2) wrong variable name/case, (3) type juggling with == vs ===. Explain which line surprises you and I will go deeper without pasting fixed code yet.',
            'review' => 'Senior review checklist for your snippet: correctness, edge cases (empty/null), naming, early returns, and whether a test would catch a regression. Paste the exact code if it was not included.',
            'beginner' => "Let's slow down. A PHP script is a list of instructions. Start by writing the smallest program that prints one value, then grow it one step at a time. What is the first line you want to run?",
            'harder' => 'Harder variant: take the same idea and add input validation + a failing edge case test. State constraints before coding: max length, allowed characters, and expected error message.',
            default => 'I am the offline mentor stub (set AI_MENTOR_API_KEY for full AI). You asked: "'.$message.'" about '.$lesson.'. Try: explain your approach in 3 bullets, then show the smallest code that implements bullet 1.',
        };
    }
}
