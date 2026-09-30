<?php

use App\Models\Lesson;
use App\Models\Stage;
use App\Models\User;
use App\Services\CheckpointEvaluator;
use App\Services\CodeRunner;
use Database\Seeders\LearningPlatformSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(LearningPlatformSeeder::class);
});

test('roadmap page can be rendered by authenticated student', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('roadmap'))
        ->assertOk();
});

test('guests cannot open roadmap', function () {
    $this->get(route('roadmap'))->assertRedirect(route('login'));
});

test('stage 0 a0 lesson is seeded and accessible first', function () {
    $user = User::factory()->create();
    $lesson = Lesson::query()->where('slug', 'a0-what-a-program-is')->firstOrFail();

    $this->actingAs($user)
        ->get(route('lessons.show', $lesson))
        ->assertOk()
        ->assertSee('A0');
});

test('a1 is locked until a0 is completed', function () {
    $user = User::factory()->create();
    $a0 = Lesson::query()->where('slug', 'a0-what-a-program-is')->firstOrFail();
    $a1 = Lesson::query()->where('slug', 'a1-running-php')->firstOrFail();

    $response = $this->actingAs($user)->get(route('lessons.show', $a1));
    $response->assertOk()->assertSee('Locked');

    $user->lessonProgress()->create([
        'lesson_id' => $a0->id,
        'status' => 'completed',
        'completed_at' => now(),
    ]);

    $this->actingAs($user)
        ->get(route('lessons.show', $a1))
        ->assertOk()
        ->assertDontSee('Complete prerequisite');
});

test('completing lesson without passing checkpoint is blocked', function () {
    $user = User::factory()->create();
    $a0 = Lesson::query()->where('slug', 'a0-what-a-program-is')->firstOrFail();

    Livewire::actingAs($user)
        ->test('pages::lesson-show', ['lesson' => $a0])
        ->call('markComplete');

    expect($user->lessonProgress()->where('lesson_id', $a0->id)->where('status', 'completed')->exists())->toBeFalse();
});

test('code runner executes simple php', function () {
    $result = app(CodeRunner::class)->run('<?php echo 1 + 1;');

    expect($result['success'])->toBeTrue()
        ->and(trim($result['output']))->toBe('2');
});

test('code runner blocks shell_exec', function () {
    $this->expectException(RuntimeException::class);

    app(CodeRunner::class)->run('<?php shell_exec("ls");');
});

test('checkpoint evaluator scores mcq', function () {
    $user = User::factory()->create();
    $stage = Stage::query()->where('slug', 'stage-0')->firstOrFail();
    $checkpoint = $stage->lessons()->first()->checkpoints()->first();

    $attempt = app(CheckpointEvaluator::class)->record(
        $user,
        $checkpoint,
        $checkpoint->questions,
        [
            'q1' => 'process',
            'q2' => 'Source is parsed to opcodes, then executed (often with opcache)',
            'q3' => 'SAPI',
            'q4' => 'memory',
        ],
    );

    expect($attempt->passed)->toBeTrue()
        ->and($attempt->score)->toBeGreaterThanOrEqual(70);
});

test('register page can be rendered', function () {
    $this->get(route('register'))->assertOk();
});

test('students can register', function () {
    $response = $this->post(route('register'), [
        'name' => 'Student One',
        'email' => 'student@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $response->assertSessionHasNoErrors();
    $this->assertAuthenticated();
    expect(User::query()->where('email', 'student@example.com')->first()->role)->toBe('student');
});

test('admin content requires admin role', function () {
    $student = User::factory()->create();
    $this->actingAs($student)
        ->get(route('admin.content'))
        ->assertForbidden();

    $admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($admin)
        ->get(route('admin.content'))
        ->assertOk();
});

test('playground runs', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test('pages::code-playground')
        ->call('run')
        ->assertSet('error', null)
        ->assertSee('Prasanth');
});

test('admin can create update and delete a stage', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    Livewire::actingAs($admin)
        ->test('pages::admin-content')
        ->set('stageSlug', 'stage-99')
        ->set('stageTitle', 'Capstone')
        ->set('stageDescription', 'Final')
        ->call('saveStage')
        ->assertHasNoErrors();

    $stage = Stage::query()->where('slug', 'stage-99')->firstOrFail();
    expect($stage->title)->toBe('Capstone');

    Livewire::actingAs($admin)
        ->test('pages::admin-content')
        ->call('editStage', $stage->id)
        ->set('stageTitle', 'Capstone Updated')
        ->call('updateStage')
        ->assertHasNoErrors();

    expect($stage->fresh()->title)->toBe('Capstone Updated');

    Livewire::actingAs($admin)
        ->test('pages::admin-content')
        ->call('deleteStage', $stage->id);

    expect(Stage::query()->where('slug', 'stage-99')->exists())->toBeFalse();
});

test('admin can update a lesson and toggle publish', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $lesson = Lesson::query()->where('slug', 'a0-what-a-program-is')->firstOrFail();

    Livewire::actingAs($admin)
        ->test('pages::admin-content')
        ->call('editLesson', $lesson->id)
        ->set('lessonTitle', 'A0 Renamed')
        ->call('updateLesson')
        ->assertHasNoErrors();

    expect($lesson->fresh()->title)->toBe('A0 Renamed');

    Livewire::actingAs($admin)
        ->test('pages::admin-content')
        ->call('toggleLessonPublish', $lesson->id);

    expect((bool) $lesson->fresh()->is_published)->toBeFalse();
});

test('admin can update an exercise', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $exercise = $lesson = Lesson::query()->where('slug', 'a0-what-a-program-is')->firstOrFail()
        ->exercises()->firstOrFail();

    Livewire::actingAs($admin)
        ->test('pages::admin-content')
        ->call('editExercise', $exercise->id)
        ->set('exerciseTitle', 'Exercise Renamed')
        ->call('updateExercise')
        ->assertHasNoErrors();

    expect($exercise->fresh()->title)->toBe('Exercise Renamed');
});

test('lesson page shows knowledge graph links', function () {
    $user = User::factory()->create();
    $a0 = Lesson::query()->where('slug', 'a0-what-a-program-is')->firstOrFail();
    $a1 = Lesson::query()->where('slug', 'a1-running-php')->firstOrFail();

    expect($a0->relatedLessons())->not->toBeEmpty()
        ->and($a0->nextLesson()?->id)->toBe($a1->id)
        ->and($a1->prevLesson()?->id)->toBe($a0->id)
        ->and($a0->dependentLessons()->pluck('lessons.id'))->toContain($a1->id);

    $this->actingAs($user)
        ->get(route('lessons.show', $a0))
        ->assertOk()
        ->assertSee('Knowledge graph', false)
        ->assertSee('data-test="knowledge-graph"', false);
});

test('every lesson has at least three exercises', function () {
    $thin = Lesson::query()
        ->withCount('exercises')
        ->get()
        ->filter(fn (Lesson $l) => $l->exercises_count < 3);

    expect($thin->pluck('code')->all())->toBeEmpty();
});

test('zandstra gap lessons are seeded with graph wiring', function () {
    $codes = ['K4', 'M3', 'N4', 'N5', 'N6'];

    $lessons = Lesson::query()->whereIn('code', $codes)->get()->keyBy('code');

    expect($lessons->keys()->all())->toEqualCanonicalizing($codes);

    $l1 = Lesson::query()->where('code', 'L1')->firstOrFail();
    $prereqCodes = fn (Lesson $lesson): array => $lesson->prerequisites()->pluck('code')->all();

    expect($prereqCodes($lessons['K4']))->toContain('K3')
        ->and($prereqCodes($l1))->toContain('K4')
        ->and($prereqCodes($lessons['M3']))->toContain('M2')
        ->and($prereqCodes($lessons['N4']))->toContain('N3')
        ->and($prereqCodes($lessons['N5']))->toContain('N4')
        ->and($prereqCodes($lessons['N6']))->toContain('N5')
        ->and($prereqCodes(Lesson::query()->where('code', 'N1')->firstOrFail()))->toContain('M3')
        ->and($prereqCodes(Lesson::query()->where('code', 'O1')->firstOrFail()))->toContain('N6');

    foreach ($lessons as $lesson) {
        expect($lesson->exercises()->count())->toBeGreaterThanOrEqual(3)
            ->and($lesson->is_published)->toBeTrue();
    }

    expect($lessons['N6']->requires_checkpoint)->toBeTrue()
        ->and($lessons['N6']->checkpoints()->exists())->toBeTrue()
        ->and($lessons['K4']->requires_checkpoint)->toBeTrue()
        ->and($lessons['K4']->checkpoints()->exists())->toBeTrue()
        ->and($lessons['M3']->checkpoints()->exists())->toBeTrue();
});
