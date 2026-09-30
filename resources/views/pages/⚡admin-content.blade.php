<?php

use App\Models\Exercise;
use App\Models\Lesson;
use App\Models\Stage;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Admin · Content')] class extends Component
{
    public string $stageSlug = '';

    public string $stageTitle = '';

    public string $stageDescription = '';

    public string $lessonStageId = '';

    public string $lessonSlug = '';

    public string $lessonCode = '';

    public string $lessonTitle = '';

    public string $lessonSummary = '';

    public string $lessonBody = '';

    public string $exerciseLessonId = '';

    public string $exerciseTitle = '';

    public string $exercisePrompt = '';

    public int $exerciseLevel = 1;

    public string $exerciseType = 'code';

    public string $exerciseStarter = '';

    public string $exerciseSolution = '';

    public string $exerciseExpected = '';

    public string $exerciseHints = '';

    public ?int $editStageId = null;

    public ?int $editLessonId = null;

    public ?int $editExerciseId = null;

    public string $tab = 'create';

    public function saveStage(): void
    {
        $this->validate([
            'stageSlug' => 'required|string|max:100|unique:stages,slug',
            'stageTitle' => 'required|string|max:255',
            'stageDescription' => 'nullable|string',
        ]);

        Stage::query()->create([
            'slug' => $this->stageSlug,
            'title' => $this->stageTitle,
            'description' => $this->stageDescription ?: null,
            'order_column' => Stage::query()->max('order_column') + 1,
            'is_published' => true,
        ]);

        session()->flash('status', __('Stage created.'));
        $this->reset('stageSlug', 'stageTitle', 'stageDescription');
    }

    public function saveLesson(): void
    {
        $this->validate([
            'lessonStageId' => 'required|integer|exists:stages,id',
            'lessonSlug' => 'required|string|max:100|unique:lessons,slug',
            'lessonCode' => 'required|string|max:50|unique:lessons,code',
            'lessonTitle' => 'required|string|max:255',
            'lessonSummary' => 'nullable|string|max:500',
            'lessonBody' => 'required|string',
        ]);

        Lesson::query()->create([
            'stage_id' => (int) $this->lessonStageId,
            'slug' => $this->lessonSlug,
            'code' => $this->lessonCode,
            'title' => $this->lessonTitle,
            'summary' => $this->lessonSummary ?: null,
            'body_html' => $this->lessonBody,
            'order_column' => Lesson::query()->where('stage_id', $this->lessonStageId)->max('order_column') + 1,
            'sort_order' => Lesson::query()->where('stage_id', $this->lessonStageId)->max('sort_order') + 1,
            'is_published' => true,
            'difficulty' => 'beginner',
            'estimated_minutes' => 15,
        ]);

        session()->flash('status', __('Lesson created.'));
        $this->reset('lessonSlug', 'lessonCode', 'lessonTitle', 'lessonSummary', 'lessonBody');
    }

    public function saveExercise(): void
    {
        $this->validate([
            'exerciseLessonId' => 'required|integer|exists:lessons,id',
            'exerciseTitle' => 'required|string|max:255',
            'exercisePrompt' => 'required|string',
            'exerciseLevel' => 'required|integer|between:1,6',
            'exerciseType' => 'required|in:explain,code,debug,design',
            'exerciseStarter' => 'nullable|string',
            'exerciseSolution' => 'nullable|string',
            'exerciseExpected' => 'nullable|string|max:5000',
            'exerciseHints' => 'nullable|string|max:2000',
        ]);

        Exercise::query()->create([
            'lesson_id' => (int) $this->exerciseLessonId,
            'title' => $this->exerciseTitle,
            'prompt' => $this->exercisePrompt,
            'level' => $this->exerciseLevel,
            'type' => $this->exerciseType,
            'starter_code' => $this->exerciseStarter ?: null,
            'solution' => $this->exerciseSolution ?: null,
            'expected_output' => $this->exerciseExpected ?: null,
            'hints' => $this->exerciseHints ?: null,
            'order_column' => Exercise::query()->where('lesson_id', $this->exerciseLessonId)->max('order_column') + 1,
            'is_published' => true,
        ]);

        session()->flash('status', __('Exercise created.'));
        $this->reset('exerciseTitle', 'exercisePrompt', 'exerciseStarter', 'exerciseSolution', 'exerciseExpected', 'exerciseHints');
    }

    public function editStage(int $id): void
    {
        $stage = Stage::query()->findOrFail($id);
        $this->editStageId = $stage->id;
        $this->stageSlug = $stage->slug;
        $this->stageTitle = $stage->title;
        $this->stageDescription = (string) $stage->description;
        $this->tab = 'create';
    }

    public function updateStage(): void
    {
        abort_unless($this->editStageId, 403);

        $this->validate([
            'stageSlug' => 'required|string|max:100|unique:stages,slug,'.$this->editStageId,
            'stageTitle' => 'required|string|max:255',
            'stageDescription' => 'nullable|string',
        ]);

        Stage::query()->findOrFail($this->editStageId)->update([
            'slug' => $this->stageSlug,
            'title' => $this->stageTitle,
            'description' => $this->stageDescription ?: null,
        ]);

        session()->flash('status', __('Stage updated.'));
        $this->reset('stageSlug', 'stageTitle', 'stageDescription');
        $this->editStageId = null;
    }

    public function editLesson(int $id): void
    {
        $lesson = Lesson::query()->findOrFail($id);
        $this->editLessonId = $lesson->id;
        $this->lessonStageId = (string) $lesson->stage_id;
        $this->lessonSlug = $lesson->slug;
        $this->lessonCode = $lesson->code;
        $this->lessonTitle = $lesson->title;
        $this->lessonSummary = (string) $lesson->summary;
        $this->lessonBody = $lesson->body_html;
        $this->tab = 'create';
    }

    public function updateLesson(): void
    {
        abort_unless($this->editLessonId, 403);

        $this->validate([
            'lessonStageId' => 'required|integer|exists:stages,id',
            'lessonSlug' => 'required|string|max:100|unique:lessons,slug,'.$this->editLessonId,
            'lessonCode' => 'required|string|max:50|unique:lessons,code,'.$this->editLessonId,
            'lessonTitle' => 'required|string|max:255',
            'lessonSummary' => 'nullable|string|max:500',
            'lessonBody' => 'required|string',
        ]);

        Lesson::query()->findOrFail($this->editLessonId)->update([
            'stage_id' => (int) $this->lessonStageId,
            'slug' => $this->lessonSlug,
            'code' => $this->lessonCode,
            'title' => $this->lessonTitle,
            'summary' => $this->lessonSummary ?: null,
            'body_html' => $this->lessonBody,
        ]);

        session()->flash('status', __('Lesson updated.'));
        $this->reset('lessonSlug', 'lessonCode', 'lessonTitle', 'lessonSummary', 'lessonBody');
        $this->editLessonId = null;
    }

    public function editExercise(int $id): void
    {
        $exercise = Exercise::query()->findOrFail($id);
        $this->editExerciseId = $exercise->id;
        $this->exerciseLessonId = (string) $exercise->lesson_id;
        $this->exerciseTitle = $exercise->title;
        $this->exercisePrompt = $exercise->prompt;
        $this->exerciseLevel = $exercise->level;
        $this->exerciseType = $exercise->type;
        $this->exerciseStarter = (string) $exercise->starter_code;
        $this->exerciseSolution = (string) $exercise->solution;
        $this->exerciseExpected = (string) $exercise->expected_output;
        $this->exerciseHints = (string) $exercise->hints;
        $this->tab = 'create';
    }

    public function updateExercise(): void
    {
        abort_unless($this->editExerciseId, 403);

        $this->validate([
            'exerciseLessonId' => 'required|integer|exists:lessons,id',
            'exerciseTitle' => 'required|string|max:255',
            'exercisePrompt' => 'required|string',
            'exerciseLevel' => 'required|integer|between:1,6',
            'exerciseType' => 'required|in:explain,code,debug,design',
            'exerciseStarter' => 'nullable|string',
            'exerciseSolution' => 'nullable|string',
            'exerciseExpected' => 'nullable|string|max:5000',
            'exerciseHints' => 'nullable|string|max:2000',
        ]);

        Exercise::query()->findOrFail($this->editExerciseId)->update([
            'lesson_id' => (int) $this->exerciseLessonId,
            'title' => $this->exerciseTitle,
            'prompt' => $this->exercisePrompt,
            'level' => $this->exerciseLevel,
            'type' => $this->exerciseType,
            'starter_code' => $this->exerciseStarter ?: null,
            'solution' => $this->exerciseSolution ?: null,
            'expected_output' => $this->exerciseExpected ?: null,
            'hints' => $this->exerciseHints ?: null,
        ]);

        session()->flash('status', __('Exercise updated.'));
        $this->reset('exerciseTitle', 'exercisePrompt', 'exerciseStarter', 'exerciseSolution', 'exerciseExpected', 'exerciseHints');
        $this->editExerciseId = null;
    }

    public function deleteStage(int $id): void
    {
        $stage = Stage::query()->findOrFail($id);

        if ($stage->lessons()->exists()) {
            session()->flash('error', __('Stage has lessons — move or delete them first.'));

            return;
        }

        $stage->delete();
        session()->flash('status', __('Stage deleted.'));
        $this->editStageId = null;
    }

    public function deleteLesson(int $id): void
    {
        Lesson::query()->findOrFail($id)->delete();
        session()->flash('status', __('Lesson deleted.'));
        $this->editLessonId = null;
    }

    public function deleteExercise(int $id): void
    {
        Exercise::query()->findOrFail($id)->delete();
        session()->flash('status', __('Exercise deleted.'));
        $this->editExerciseId = null;
    }

    public function toggleLessonPublish(int $id): void
    {
        $lesson = Lesson::query()->findOrFail($id);
        $lesson->is_published = ! $lesson->is_published;
        $lesson->save();

        session()->flash('status', $lesson->is_published ? __('Lesson published.') : __('Lesson unpublished.'));
    }

    public function render()
    {
        return $this->view([
            'stages' => Stage::query()->orderBy('order_column')->get(['id', 'title', 'slug', 'description', 'order_column']),
            'lessons' => Lesson::query()->orderBy('code')->get(['id', 'code', 'title', 'slug', 'stage_id', 'is_published', 'summary', 'body_html']),
            'exercises' => Exercise::query()->with('lesson:id,code,title')->orderBy('lesson_id')->orderBy('order_column')->get(),
            'stageStats' => Stage::query()->withCount('lessons')->orderBy('order_column')->get(['id', 'title', 'slug', 'lessons_count']),
            'lessonStats' => Lesson::query()->withCount('exercises')->orderBy('code')->get(['id', 'code', 'title', 'exercises_count', 'is_published']),
        ])->layout('layouts::app', ['title' => __('Admin content')]);
    }
};
?>
<div class="flex flex-1 flex-col gap-6">
    <div>
        <flux:heading size="xl" level="1">{{ __('Admin · Content CRUD') }}</flux:heading>
        <flux:subheading>{{ __('Create, edit, publish, and delete stages, lessons, and exercises.') }}</flux:subheading>
    </div>

    @if (session('status'))
        <flux:callout variant="success" icon="check-circle">{{ session('status') }}</flux:callout>
    @endif
    @if (session('error'))
        <flux:callout variant="danger" icon="exclamation-circle">{{ session('error') }}</flux:callout>
    @endif

    <div class="flex flex-wrap gap-2">
        <flux:button variant="{{ $tab === 'create' ? 'primary' : 'ghost' }}" wire:click="$set('tab', 'create')">
            {{ $editLessonId || $editStageId || $editExerciseId ? __('Edit / create') : __('Create') }}
        </flux:button>
        <flux:button variant="{{ $tab === 'browse' ? 'primary' : 'ghost' }}" wire:click="$set('tab', 'browse')">
            {{ __('Browse & manage') }}
        </flux:button>
    </div>

    @if ($tab === 'create')
        <div class="grid gap-4 lg:grid-cols-3">
            <flux:card>
                <flux:heading level="3">
                    {{ $editStageId ? __('Edit stage') : __('New stage') }}
                </flux:heading>
                <form
                    wire:submit="{{ $editStageId ? 'updateStage' : 'saveStage' }}"
                    class="mt-3 flex flex-col gap-3"
                >
                    <flux:input wire:model="stageSlug" label="Slug" placeholder="stage-16" />
                    <flux:input wire:model="stageTitle" label="Title" />
                    <textarea wire:model="stageDescription" rows="3" class="rounded border border-zinc-700 bg-zinc-950 p-2 text-sm" placeholder="Description"></textarea>
                    <div class="flex gap-2">
                        <flux:button variant="primary" type="submit">
                            {{ $editStageId ? __('Update stage') : __('Create stage') }}
                        </flux:button>
                        @if ($editStageId)
                            <flux:button variant="ghost" wire:click="reset('editStageId', 'stageSlug', 'stageTitle', 'stageDescription')">
                                {{ __('Cancel') }}
                            </flux:button>
                        @endif
                    </div>
                </form>
            </flux:card>

            <flux:card>
                <flux:heading level="3">
                    {{ $editLessonId ? __('Edit lesson') : __('New lesson') }}
                </flux:heading>
                <form
                    wire:submit="{{ $editLessonId ? 'updateLesson' : 'saveLesson' }}"
                    class="mt-3 flex flex-col gap-3"
                >
                    <label class="text-sm">
                        Stage
                        <select wire:model="lessonStageId" class="mt-1 w-full rounded border border-zinc-700 bg-zinc-950 p-2">
                            <option value="">Select…</option>
                            @foreach ($stages as $stage)
                                <option value="{{ $stage->id }}">{{ $stage->title }}</option>
                            @endforeach
                        </select>
                    </label>
                    <flux:input wire:model="lessonSlug" label="Slug" placeholder="b1-control-flow" />
                    <flux:input wire:model="lessonCode" label="Code" placeholder="B1" />
                    <flux:input wire:model="lessonTitle" label="Title" />
                    <flux:input wire:model="lessonSummary" label="Summary" />
                    <label class="text-sm">
                        Body (HTML)
                        <textarea wire:model="lessonBody" rows="6" class="mt-1 w-full rounded border border-zinc-700 bg-zinc-950 p-2 font-mono text-xs"></textarea>
                    </label>
                    <div class="flex gap-2">
                        <flux:button variant="primary" type="submit">
                            {{ $editLessonId ? __('Update lesson') : __('Create lesson') }}
                        </flux:button>
                        @if ($editLessonId)
                            <flux:button
                                variant="ghost"
                                wire:click="reset('editLessonId', 'lessonSlug', 'lessonCode', 'lessonTitle', 'lessonSummary', 'lessonBody', 'lessonStageId')"
                            >
                                {{ __('Cancel') }}
                            </flux:button>
                        @endif
                    </div>
                </form>
            </flux:card>

            <flux:card>
                <flux:heading level="3">
                    {{ $editExerciseId ? __('Edit exercise') : __('New exercise') }}
                </flux:heading>
                <form
                    wire:submit="{{ $editExerciseId ? 'updateExercise' : 'saveExercise' }}"
                    class="mt-3 flex flex-col gap-3"
                >
                    <label class="text-sm">
                        Lesson
                        <select wire:model="exerciseLessonId" class="mt-1 w-full rounded border border-zinc-700 bg-zinc-950 p-2">
                            <option value="">Select…</option>
                            @foreach ($lessons as $lesson)
                                <option value="{{ $lesson->id }}">{{ $lesson->code }} — {{ $lesson->title }}</option>
                            @endforeach
                        </select>
                    </label>
                    <flux:input wire:model="exerciseTitle" label="Title" />
                    <textarea wire:model="exercisePrompt" rows="3" class="rounded border border-zinc-700 bg-zinc-950 p-2 text-sm" placeholder="Prompt"></textarea>
                    <div class="grid grid-cols-2 gap-2">
                        <label class="text-sm">
                            Level (1–6)
                            <input type="number" min="1" max="6" wire:model="exerciseLevel" class="mt-1 w-full rounded border border-zinc-700 bg-zinc-950 p-2" />
                        </label>
                        <label class="text-sm">
                            Type
                            <select wire:model="exerciseType" class="mt-1 w-full rounded border border-zinc-700 bg-zinc-950 p-2">
                                <option value="explain">explain</option>
                                <option value="code">code</option>
                                <option value="debug">debug</option>
                                <option value="design">design</option>
                            </select>
                        </label>
                    </div>
                    <textarea wire:model="exerciseStarter" rows="3" class="rounded border border-zinc-700 bg-zinc-950 p-2 font-mono text-xs" placeholder="Starter code"></textarea>
                    <textarea wire:model="exerciseSolution" rows="3" class="rounded border border-zinc-700 bg-zinc-950 p-2 font-mono text-xs" placeholder="Solution"></textarea>
                    <flux:input wire:model="exerciseExpected" label="Expected output" />
                    <textarea wire:model="exerciseHints" rows="2" class="rounded border border-zinc-700 bg-zinc-950 p-2 text-sm" placeholder="Hints"></textarea>
                    <div class="flex gap-2">
                        <flux:button variant="primary" type="submit">
                            {{ $editExerciseId ? __('Update exercise') : __('Create exercise') }}
                        </flux:button>
                        @if ($editExerciseId)
                            <flux:button
                                variant="ghost"
                                wire:click="reset('editExerciseId', 'exerciseTitle', 'exercisePrompt', 'exerciseStarter', 'exerciseSolution', 'exerciseExpected', 'exerciseHints')"
                            >
                                {{ __('Cancel') }}
                            </flux:button>
                        @endif
                    </div>
                </form>
            </flux:card>
        </div>
    @else
        <div class="grid gap-4 lg:grid-cols-3">
            <flux:card>
                <flux:heading level="3">{{ __('Stages') }}</flux:heading>
                <ul class="mt-3 flex flex-col gap-2 text-sm">
                    @forelse ($stageStats as $stage)
                        <li class="flex items-center justify-between gap-2 border-b border-zinc-800 pb-2">
                            <span>
                                {{ $stage->title }}
                                <flux:badge>{{ $stage->lessons_count }} lessons</flux:badge>
                            </span>
                            <span class="flex gap-1">
                                <flux:button size="xs" variant="ghost" wire:click="editStage({{ $stage->id }})">{{ __('Edit') }}</flux:button>
                                <flux:button
                                    size="xs"
                                    variant="ghost"
                                    wire:click="deleteStage({{ $stage->id }})"
                                    wire:confirm="{{ __('Delete this stage? It must have no lessons.') }}"
                                >
                                    {{ __('Delete') }}
                                </flux:button>
                            </span>
                        </li>
                    @empty
                        <li>{{ __('No stages.') }}</li>
                    @endforelse
                </ul>
            </flux:card>

            <flux:card>
                <flux:heading level="3">{{ __('Lessons') }}</flux:heading>
                <ul class="mt-3 flex max-h-[28rem] flex-col gap-2 overflow-y-auto text-sm">
                    @forelse ($lessonStats as $lesson)
                        <li class="flex items-center justify-between gap-2 border-b border-zinc-800 pb-2">
                            <span class="min-w-0">
                                {{ $lesson->code }} — {{ $lesson->title }}
                                <flux:badge>{{ $lesson->exercises_count }} ex</flux:badge>
                                @if ($lesson->is_published)
                                    <flux:badge variant="success">{{ __('Live') }}</flux:badge>
                                @else
                                    <flux:badge variant="warning">{{ __('Draft') }}</flux:badge>
                                @endif
                            </span>
                            <span class="flex shrink-0 gap-1">
                                <flux:button size="xs" variant="ghost" wire:click="toggleLessonPublish({{ $lesson->id }})">
                                    {{ $lesson->is_published ? __('Unpublish') : __('Publish') }}
                                </flux:button>
                                <flux:button size="xs" variant="ghost" wire:click="editLesson({{ $lesson->id }})">{{ __('Edit') }}</flux:button>
                                <flux:button
                                    size="xs"
                                    variant="ghost"
                                    wire:click="deleteLesson({{ $lesson->id }})"
                                    wire:confirm="{{ __('Delete this lesson and its exercises?') }}"
                                >
                                    {{ __('Delete') }}
                                </flux:button>
                            </span>
                        </li>
                    @empty
                        <li>{{ __('No lessons.') }}</li>
                    @endforelse
                </ul>
            </flux:card>

            <flux:card>
                <flux:heading level="3">{{ __('Exercises') }}</flux:heading>
                <ul class="mt-3 flex max-h-[28rem] flex-col gap-2 overflow-y-auto text-sm">
                    @forelse ($exercises as $exercise)
                        <li class="flex items-center justify-between gap-2 border-b border-zinc-800 pb-2">
                            <span class="min-w-0">
                                <strong>L{{ $exercise->level }}</strong>
                                {{ $exercise->title }}
                                <flux:text class="text-zinc-500">{{ $exercise->lesson?->code }}</flux:text>
                            </span>
                            <span class="flex shrink-0 gap-1">
                                <flux:button size="xs" variant="ghost" wire:click="editExercise({{ $exercise->id }})">{{ __('Edit') }}</flux:button>
                                <flux:button
                                    size="xs"
                                    variant="ghost"
                                    wire:click="deleteExercise({{ $exercise->id }})"
                                    wire:confirm="{{ __('Delete this exercise?') }}"
                                >
                                    {{ __('Delete') }}
                                </flux:button>
                            </span>
                        </li>
                    @empty
                        <li>{{ __('No exercises.') }}</li>
                    @endforelse
                </ul>
            </flux:card>
        </div>
    @endif
</div>
