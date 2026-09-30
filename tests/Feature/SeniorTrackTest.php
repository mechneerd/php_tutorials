<?php

use App\Models\CodeReviewDrill;
use App\Models\DesignAttempt;
use App\Models\DesignCase;
use App\Models\Incident;
use App\Models\IncidentAttempt;
use App\Models\InternalsTopic;
use App\Models\InterviewSession;
use App\Models\InterviewSimulation;
use App\Models\ReviewAttempt;
use App\Models\SimulationAttempt;
use App\Models\StarStory;
use App\Models\User;
use App\Services\SeniorReadiness;
use Database\Seeders\SeniorTrackSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(SeniorTrackSeeder::class);
});

test('guests cannot open senior dashboard', function () {
    $this->get(route('senior.index'))->assertRedirect(route('login'));
});

test('student can open senior dashboard with readiness card', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('senior.index'))
        ->assertOk()
        ->assertSee('readiness-card');
});

test('coding pillar reads interview sessions by level column', function () {
    $user = User::factory()->create();

    InterviewSession::query()->create([
        'user_id' => $user->id,
        'level' => 'coding',
        'status' => 'completed',
        'questions' => [],
        'answers' => [],
        'score' => 80,
        'completed_at' => now(),
    ]);
    InterviewSession::query()->create([
        'user_id' => $user->id,
        'level' => 'coding',
        'status' => 'completed',
        'questions' => [],
        'answers' => [],
        'score' => 60,
        'completed_at' => now(),
    ]);
    InterviewSession::query()->create([
        'user_id' => $user->id,
        'level' => 'beginner',
        'status' => 'completed',
        'questions' => [],
        'answers' => [],
        'score' => 10,
        'completed_at' => now(),
    ]);

    $report = app(SeniorReadiness::class)->forUser($user);

    expect($report['pillars']['coding'])->toBe(70);

    $this->actingAs($user)
        ->get(route('senior.index'))
        ->assertOk()
        ->assertSee('readiness-card');
});

test('senior section pages render for authed student', function () {
    $user = User::factory()->create();

    foreach ([
        'senior.design',
        'senior.incidents',
        'senior.reviews',
        'senior.internals',
        'senior.star',
        'senior.simulation',
    ] as $routeName) {
        $this->actingAs($user)->get(route($routeName))->assertOk();
    }
});

test('design case is seeded and runner accepts submit', function () {
    $user = User::factory()->create();
    $case = DesignCase::query()->where('slug', 'url-shortener')->firstOrFail();

    $this->actingAs($user)->get(route('senior.design.show', $case))->assertOk();

    Livewire::actingAs($user)
        ->test('pages::senior-design-case', ['designCase' => $case])
        ->set('requirements', str_repeat('We need p99 under 20ms and 100M redirects per day with clean expiry. ', 3))
        ->set('api', str_repeat('POST /api/links and GET /{code} with analytics endpoints documented. ', 3))
        ->set('dataModel', str_repeat('links table with unique code index base62 counter strategy and owner. ', 3))
        ->set('bottlenecks', str_repeat('Hot keys cache stampede anycast regions and read replica lag considered. ', 3))
        ->set('failureModes', str_repeat('Cache down DC loss collision open redirect abuse and stampede. ', 3))
        ->set('tradeoffs', str_repeat('When not to cache analytics async only no distributed ID on day one. ', 3))
        ->set('ops', str_repeat('Metrics redirect p99 cache hit ratio and alerting on error budget burn. ', 3))
        ->call('submit')
        ->assertSet('submitted', true)
        ->assertSee('design-score');

    expect(DesignCase::query()->where('slug', 'url-shortener')->exists())->toBeTrue();
});

test('incident diagnose scores correct root cause high', function () {
    $user = User::factory()->create();
    $incident = Incident::query()->where('slug', 'oversell-race')->firstOrFail();

    Livewire::actingAs($user)
        ->test('pages::senior-incident-show', ['incident' => $incident])
        ->set('hypothesis1', 'Read-modify-write race on inventory without row lock')
        ->set('hypothesis2', 'Frontend disabled button only')
        ->set('rootCauseCategory', 'race')
        ->set('fixSteps', implode("\n", [
            'Atomic UPDATE inventory SET available = available - 1 WHERE available >= 1',
            'SELECT FOR UPDATE in one transaction',
            'Reservation TTL model',
            'Ledger for audit',
        ]))
        ->call('diagnose')
        ->assertSet('revealed', true);

    $attempt = IncidentAttempt::query()
        ->where('user_id', $user->id)
        ->where('incident_id', $incident->id)
        ->firstOrFail();

    expect($attempt->score)->toBeGreaterThanOrEqual(70)
        ->and($attempt->is_complete)->toBeTrue();
});

test('incident wrong category scores lower', function () {
    $user = User::factory()->create();
    $incident = Incident::query()->where('slug', 'oversell-race')->firstOrFail();

    Livewire::actingAs($user)
        ->test('pages::senior-incident-show', ['incident' => $incident])
        ->set('hypothesis1', 'it is redis')
        ->set('hypothesis2', 'unrelated')
        ->set('rootCauseCategory', 'infra')
        ->set('fixSteps', 'restart something')
        ->call('diagnose');

    $attempt = IncidentAttempt::query()
        ->where('user_id', $user->id)
        ->where('incident_id', $incident->id)
        ->firstOrFail();

    expect($attempt->score)->toBeLessThan(70);
});

test('code review all planted issues yields high f1 score', function () {
    $user = User::factory()->create();
    $drill = CodeReviewDrill::query()->where('slug', 'controller-mass-assign-n1')->firstOrFail();

    $component = Livewire::actingAs($user)
        ->test('pages::senior-review-show', ['codeReviewDrill' => $drill]);

    foreach (array_column($drill->planted_issues, 'id') as $id) {
        $component->call('toggle', $id);
    }

    $component->call('submit')
        ->assertSet('revealed', true)
        ->assertSee('review-score');

    $attempt = ReviewAttempt::query()
        ->where('user_id', $user->id)
        ->where('code_review_drill_id', $drill->id)
        ->firstOrFail();

    expect($attempt->score)->toBe(100)
        ->and($attempt->misses)->toBe(0)
        ->and($attempt->false_positives)->toBe(0);
});

test('internals explain back can pass seventy', function () {
    $user = User::factory()->create();
    $topic = InternalsTopic::query()->where('slug', 'zval-refcount-cow')->firstOrFail();

    Livewire::actingAs($user)
        ->test('pages::senior-internal-show', ['internalsTopic' => $topic])
        ->set('answer', 'A zval holds the value. Arrays are refcounted; assignment shares structure until a write forces separation (copy-on-write). unset only drops one binding so memory stays if another ref remains. Passing by reference also forces separation from shared COW structures.')
        ->call('submit')
        ->assertSet('submitted', true)
        ->assertSee('internals-score');
});

test('star story create and mark interview ready', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test('pages::senior-star')
        ->call('createNew', 'owned-outage')
        ->set('title', 'Owned checkout outage')
        ->set('situation', 'During peak traffic the payment webhooks failed and orders stalled for 18 minutes.')
        ->set('task', 'I was incident commander and had to restore service without losing money events.')
        ->set('action', 'I froze risky deploys, rolled back, added idempotency keys, and opened a status page.')
        ->set('result', 'Restored in 18 minutes with zero duplicate charges after the fix.')
        ->set('status', 'interview_ready')
        ->call('save')
        ->assertHasNoErrors();

    $story = StarStory::query()->where('user_id', $user->id)->firstOrFail();
    expect($story->status)->toBe('interview_ready');
});

test('simulation finish stores attempt with score', function () {
    $user = User::factory()->create();
    $sim = InterviewSimulation::query()->where('slug', 'loop-a-product-backend')->firstOrFail();

    $component = Livewire::actingAs($user)
        ->test('pages::senior-simulation-show', ['interviewSimulation' => $sim]);

    foreach ($sim->segments as $i => $segment) {
        $component->set("answers.{$i}", str_repeat('Detailed senior answer with architecture notes and trade-offs. ', 12));
    }

    $component->call('finish')
        ->assertSet('completed', true)
        ->assertSee('simulation-result');

    $attempt = SimulationAttempt::query()
        ->where('user_id', $user->id)
        ->where('interview_simulation_id', $sim->id)
        ->firstOrFail();

    expect($attempt->is_complete)->toBeTrue()
        ->and($attempt->overall_score)->toBeGreaterThan(70);
});

test('senior readiness gate reflects perfect user work', function () {
    $user = User::factory()->create();
    $readiness = app(SeniorReadiness::class);

    $empty = $readiness->forUser($user);
    expect($empty['overall'])->toBe(0)
        ->and($empty['gate_passed'])->toBeFalse();

    DesignAttempt::query()->create([
        'user_id' => $user->id,
        'design_case_id' => DesignCase::query()->first()->id,
        'score' => 90,
        'is_complete' => true,
    ]);
    IncidentAttempt::query()->create([
        'user_id' => $user->id,
        'incident_id' => Incident::query()->first()->id,
        'score' => 90,
        'is_complete' => true,
    ]);
    foreach (range(1, 5) as $i) {
        StarStory::query()->create([
            'user_id' => $user->id,
            'title' => "Story {$i}",
            'situation' => str_repeat('Situation context with stakes and constraints. ', 2),
            'task' => 'Owned the outcome end to end as senior engineer.',
            'action' => 'Coordinated teams, chose trade-offs, shipped mitigations carefully.',
            'result' => 'Improved p99 and prevented recurrence with metrics proof.',
            'status' => 'interview_ready',
        ]);
    }
    SimulationAttempt::query()->create([
        'user_id' => $user->id,
        'interview_simulation_id' => InterviewSimulation::query()->first()->id,
        'overall_score' => 85,
        'is_complete' => true,
        'started_at' => now(),
        'completed_at' => now(),
    ]);

    $report = $readiness->forUser($user);
    expect($report['pillars']['design'])->toBe(90)
        ->and($report['pillars']['incidents'])->toBe(90)
        ->and($report['pillars']['star'])->toBe(100)
        ->and($report['pillars']['simulation'])->toBe(85)
        ->and($report['gate_passed'])->toBeTrue()
        ->and($report['overall'])->toBeGreaterThanOrEqual(50);
});

test('senior seeder is idempotent', function () {
    $designs = DesignCase::query()->count();
    $incidents = Incident::query()->count();
    $drills = CodeReviewDrill::query()->count();
    $topics = InternalsTopic::query()->count();
    $sims = InterviewSimulation::query()->count();

    $this->seed(SeniorTrackSeeder::class);

    expect(DesignCase::query()->count())->toBe($designs)
        ->and(Incident::query()->count())->toBe($incidents)
        ->and(CodeReviewDrill::query()->count())->toBe($drills)
        ->and(InternalsTopic::query()->count())->toBe($topics)
        ->and(InterviewSimulation::query()->count())->toBe($sims)
        ->and($designs)->toBe(12)
        ->and($incidents)->toBe(10)
        ->and($drills)->toBe(6)
        ->and($topics)->toBe(10)
        ->and($sims)->toBe(3);
});
