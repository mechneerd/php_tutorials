<?php

use App\Models\InterviewSession;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('guests cannot open interview practice', function () {
    $this->get(route('interviews'))->assertRedirect(route('login'));
});

test('student can open interview practice', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('interviews'))
        ->assertOk()
        ->assertSee('Interview Mode');
});

test('interview practice offers all tracks including laravel sql and design patterns', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('interviews'))
        ->assertOk()
        ->assertSee('laravel')
        ->assertSee('sql')
        ->assertSee('design patterns');
});

test('starting an interview session stores questions for the track', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test('pages::interview-practice')
        ->set('level', 'beginner')
        ->call('start')
        ->assertHasNoErrors();

    $session = InterviewSession::query()->where('user_id', $user->id)->firstOrFail();

    expect($session->level)->toBe('beginner')
        ->and($session->status)->toBe('in_progress')
        ->and($session->questions)->not->toBeEmpty()
        ->and(count($session->questions))->toBeGreaterThanOrEqual(10);
});

test('laravel sql and design patterns tracks have questions', function () {
    $user = User::factory()->create();

    foreach (['laravel', 'sql', 'design_patterns'] as $track) {
        Livewire::actingAs($user)
            ->test('pages::interview-practice')
            ->set('level', $track)
            ->call('start');

        $session = InterviewSession::query()
            ->where('user_id', $user->id)
            ->where('level', $track)
            ->latest('id')
            ->firstOrFail();

        expect(count($session->questions))->toBeGreaterThanOrEqual(10);
    }
});

test('submitting an answer scores and advances the session', function () {
    $user = User::factory()->create();

    $component = Livewire::actingAs($user)
        ->test('pages::interview-practice')
        ->set('level', 'beginner')
        ->call('start');

    $session = InterviewSession::query()->where('user_id', $user->id)->firstOrFail();
    $q = $session->questions[0];

    $answer = 'A program is instructions on disk while a process is loaded and running with memory and CPU time allocated by the OS kernel.';

    $component->set('answer', $answer)
        ->call('submitAnswer')
        ->assertHasNoErrors();

    expect($component->get('evaluations'))->toHaveCount(1)
        ->and($component->get('evaluations')[0]['score'])->toBeGreaterThan(0);
});

test('short answer is rejected by validation', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test('pages::interview-practice')
        ->set('level', 'beginner')
        ->call('start')
        ->set('answer', 'short')
        ->call('submitAnswer')
        ->assertHasErrors(['answer']);
});

test('completing all questions finishes the session with a score', function () {
    $user = User::factory()->create();

    $component = Livewire::actingAs($user)
        ->test('pages::interview-practice')
        ->set('level', 'beginner')
        ->call('start');

    $session = InterviewSession::query()->where('user_id', $user->id)->firstOrFail();
    $total = count($session->questions);

    $answer = 'A program is instructions stored on disk while a process is that program loaded into memory and executed by the CPU with its own address space and resources over time.';

    for ($i = 0; $i < $total; $i++) {
        $component->set('answer', $answer)->call('submitAnswer');
    }

    $session->refresh();

    expect($session->status)->toBe('completed')
        ->and($session->score)->not->toBeNull()
        ->and($session->completed_at)->not->toBeNull();
});
