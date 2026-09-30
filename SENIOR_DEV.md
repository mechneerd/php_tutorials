# Senior Developer Track — Implementation Plan

> Goal: turn the existing beginner→senior PHP learning app into a platform that actually prepares someone to **pass a senior PHP/Laravel technical interview** (coding + system design + PHP internals + production debugging + behavioral).
>
> This plan **does not redesign** Stages 0–15, lessons A0–P2, exercises, checkpoints, admin CRUD, or the knowledge graph. It **adds** new layers on top.

---

## 1. Current baseline (what we already ship)

| Area | Status |
|------|--------|
| Stages 0–15 curriculum | ✅ 16 stages, 48 lessons |
| Exercises L1–L6 | ✅ 407 exercises, min 3/lesson |
| Checkpoints + gating | ✅ 19 checkpoints, ProgressGate |
| Projects L1 / L3 / L6 | ✅ 3 projects |
| Interview bank | ✅ 10 Qs × 7 tracks (built-in to interview page) |
| Knowledge graph | ✅ related + prereq + unlocks + prev/next |
| Admin CRUD | ✅ create/edit/delete + publish |
| Playground / AI mentor (stub) / smoke tests | ✅ |

**Gap for senior:** internals depth, real system design practice, incident/debug drills, code review, behavioral STAR, timed full-loop simulation, portfolio project with ops story.

---

## 2. Product principles (senior track)

1. **Depth over breadth** — fewer topics, harder prompts, model answers, rubrics.
2. **Defend trade-offs** — every design answer must name *when not to*.
3. **Artifacts, not vibes** — each module produces something you can show or rehearse (diagram, postmortem, review, STAR story).
4. **Timed pressure** — senior loops are timed; drills default to timers.
5. **Reuse existing patterns** — SFC `⚡` pages, `$this->view(...)`, seeded blueprint arrays, Livewire actions, Flux UI, Pint, Pest.

---

## 3. Architecture overview (new pieces only)

```
app/
  Models/
    DesignCase.php              (new)
    Incident.php                (new)
    CodeReviewDrill.php         (new)
    StarStory.php               (new)
    InterviewSimulation.php     (new)
    InternalsTopic.php          (new)  — or reuse Lesson + metadata track
  Services/
    DesignCaseEvaluator.php     (new)
    IncidentDiagnoser.php       (new)
    ReviewGrader.php            (new)
    SimulationScorer.php        (new)
    SeniorReadiness.php         (new)

database/migrations/
  create_design_cases_table
  create_incidents_table
  create_incident_attempts_table
  create_code_review_drills_table
  create_review_attempts_table
  create_star_stories_table
  create_interview_simulations_table
  create_simulation_attempts_table
  create_internals_topics_table
  (optional) add track column or metadata key on lessons

database/seeders/
  SeniorTrackSeeder.php         (new) — cases, incidents, reviews, internals, sims
  LearningPlatformSeeder.php    (extend run() only: call SeniorTrackSeeder; no restructure of A0–P2)

resources/views/pages/
  ⚡senior-dashboard.blade.php       — readiness score + entry points
  ⚡senior-design.blade.php          — design studio list + case runner
  ⚡senior-incidents.blade.php       — production debugging lab
  ⚡senior-code-review.blade.php     — PR review arena
  ⚡senior-star.blade.php            — behavioral story builder
  ⚡senior-simulation.blade.php      — full interview loop
  ⚡senior-internals.blade.php       — PHP internals explain-back

routes/web.php  — register under auth + can:admin optional; all under /senior/*
sidebar         — “Senior track” group when ready (or admin-only until polished)
tests/Feature/SeniorTrackTest.php
```

**DB philosophy:** store prompts/answers as JSON (same pattern as `checkpoints.questions`), attempts as rows for progress. Prefer `updateOrCreate` seeders so re-seed is idempotent.

---

## 4. Module specs

### 4.1 PHP Internals Track (`senior-internals`)

**Model `InternalsTopic`**
- `slug`, `title`, `summary`, `body_html` (deep dive), `explain_back_prompts` json, `related_lesson_code` nullable, `order_column`, `is_published`

**Seed content (min 10 topics)**
1. Request lifecycle (HTTP → SAPI → bootstrap → opcodes → response)
2. zval, refcount, COW, separating arrays/strings
3. GC roots, cycles, `gc_collect_cycles`
4. Classes: object handlers, properties tables, `__get` cost
5. Opcache / JIT (what actually compiles, when JIT kicks in)
6. Autoloaders & composer dump-autoload, classmap vs PSR-4
7. Fibers vs generators vs processes
8. Error model: warnings, Error vs Exception, handlers
9. Memory: request peak, static persistence, leaks in FPM
10. CLI vs FPM vs (mention) Octane/long-running pitfalls

**Page behavior**
- Topic list → detail with body + “Explain it back” (textarea → keyword scorer like interview `evaluateAnswer`)
- Link to existing related lesson via `related_lesson_code`

**Exit criteria badge:** all explain-backs ≥ 70% for topic set.

---

### 4.2 System Design Studio (`senior-design`)

**Model `DesignCase`**
- `slug`, `title`, `level` enum(`mid`,`senior`), `track` enum(`product`,`infra`,`data`), `prompt` (requirements + constraints), `constraints` json, `rubric` json (`[{dimension, weight, bar}]`), `model_answer_html`, `follow_ups` json, `est_minutes` default 45, `order_column`, `is_published`

**Seed 12 cases (senior-heavy)**
| # | Case | Focus |
|---|------|--------|
| 1 | URL shortener | IDs, cache, read path |
| 2 | Multi-tenant SaaS isolation | tenancy models, security |
| 3 | Payment / checkout with retries | idempotency, money |
| 4 | Notification fan-out | queues, fanout vs pull |
| 5 | Rate limiter at edge | Redis, algorithms |
| 6 | Product search | SQL vs engine, ranking |
| 7 | Inventory / stock reservation | locks, oversell |
| 8 | Feature flag platform | eval latency, propagation |
| 9 | Chat / presence | websockets, ordering |
| 10 | Video transcode pipeline | workers, progress, cost |
| 11 | Feed / timeline | pagination, cache invalidation |
| 12 | Modular monolith → service split | when/how not to |

**Case runner (Livewire)**
1. Timer starts on open (default 45m, show countdown).
2. Structured panels: Requirements → API sketch → Data model → Bottlenecks → Failure modes → **Trade-offs (required)** → Ops/observability.
3. Freeform markdown/textarea per section (persist attempt).
4. On submit: compare checklist against `rubric` + show `model_answer_html`.
5. Self-score sliders per dimension → stored score.
6. Follow-ups appear after submit (e.g. “Region failover?”).

**Evaluator service (non-AI baseline)**
- Keyword/section completeness → checklist score (same spirit as checkpoint keywords).
- Optional later: hook `AiMentor` when `AI_MENTOR_API_KEY` set.

---

### 4.3 Production Debugging Lab (`senior-incidents`)

**Model `Incident`**
- `slug`, `title`, `severity` enum(`sev1`,`sev2`,`sev3`), `symptom` (what pager said), `logs` text, `metrics` json (synthetic series or descriptions), `traces` text, `artifacts` json (config snippets, SQL), `wrong_paths` json (red herrings), `correct_diagnosis` text, `fix_steps` json, `root_cause_category` enum(`code`,`schema`,`config`,`infra`,`dependency`,`race`), `blast_radius`, `est_minutes`, `is_published`

**Seed 10 incidents**
1. Memory climbing until OOM on FPM (static cache growth / leak)
2. Intermittent deadlock under parallel checkout
3. N+1 after “small” template change — p99 explodes
4. 500s only in prod: missing env var / wrong cache store
5. Queue consumer processes job twice → double email (idempotency)
6. Cache stampede on hot product page at deploy
7. Slow query after overnight backfill — plan regression
8. Session logouts on LB without sticky sessions / cookie misconfig
9. Race: oversell stock (read-modify-write)
10. Deploy rollback drill: bad migration + expand/contract

**Runner**
1. Read-only “war room” UI: symptom, logs, metrics, artifacts tabs.
2. Hypotheses: student adds ranked hypotheses (min 2) with evidence fields.
3. “Request more data” optional actions (reveal next log line / metric) with cost in time.
4. Reveal diagnosis → compare; then **fix steps** checklist.
5. Auto-generate **STAR draft** from attempt → push into 4.5 store.

**Scoring:** correct root cause category + key fix steps present; time bonus.

---

### 4.4 Code Review Arena (`senior-code-review`)

**Model `CodeReviewDrill`**
- `slug`, `title`, `language` (`php`/`blade`/`sql`/`yaml`), `diff_html` or `files` json (`[{path, code}]`), `planted_issues` json (`[{line_ref, type, severity, hint, fix}]`), `context` (PR description), `est_minutes`, `is_published`

**Issue types**
- `security`, `bug`, `perf`, `n+1`, `naming`, `design`, `testing`, `ops`, `type`, `concurrency`

**Seed 6 drills**
1. Laravel controller: mass assignment + missing policy + N+1
2. Service with hidden `new` deps, no tests, swallowing exceptions
3. SQL migration: non-concurrent index on huge table + wrong column type
4. Blade: raw `{!! !!}` XSS + logic in view
5. Queue job: non-idempotent + infinite retry + PII in payload
6. Docker/CI: secrets in image, `latest` tags, no healthcheck

**Runner**
- Show PR + files; student marks issues (click lines or select from list).
- After submit: hits/misses/false positives vs planted list → score.
- Show model review comments (phrasing a senior would leave).

---

### 4.5 STAR Story Builder (`senior-star`)

**Model `StarStory`**
- `user_id`, `prompt_slug`, `title`, `situation`, `task`, `action`, `result`, `metrics` json, `lessons_learned`, `tags` json, `status` enum(`draft`,`rehearsed`,`interview_ready`), timestamps

**Seed prompts (behavioral, senior)**
- Disagreed with tech lead / drove consensus
- Production incident you led or owned
- Bad migration / risky deploy and how you mitigated
- Cut scope under deadline
- Mentored or unblocked someone
- Technical debt argument that won
- Failure you owned and what changed
- Multi-team cross-boundary delivery

**UX**
- Guided S-T-A-R fields + “numbers” prompt (%, hours, $$, customers).
- Rubric checklist: conflict, ownership, measurable result, no blame, what you’d do differently.
- “Rehearse aloud” timer (5 min) → mark `rehearsed`.
- Export/copy block for interview notes.

---

### 4.6 Interview Simulation (`senior-simulation`)

**Model `InterviewSimulation`**
- `slug`, `title`, `minutes_total`, `segments` json  
  `[ {type: coding|design|internals|behavioral, minutes, prompt_ref} ]`, `is_published`

**Model `SimulationAttempt`**
- `user_id`, `simulation_id`, `started_at`, `completed_at`, `segment_answers` json, `self_scores` json, `rubric_results` json, `overall_score`

**Seed 2–3 full loops**
1. **Loop A (product backend senior):** coding 50 + design 45 + internals 20 + behavioral 25  
2. **Loop B (platform/infra lean):** coding 40 + design 50 + incidents 20 + behavioral 20  
3. **Loop C (Laravel-heavy):** coding 45 + code review 30 + design 40 + behavioral 25  

**Behavior**
- Single page wizard; global countdown; segment switch locked to order (or free with warning).
- Pulls prompts from existing banks (interview tracks, design cases, review drills, internals explain-back, STAR).
- On finish: auto keyword scores + self-rubric; produce **gap report** (weak dimensions).

---

### 4.7 Senior Readiness Score (`senior-dashboard`)

**Service `SeniorReadiness`**
Aggregate user metrics (0–100 per pillar, overall gate):

| Pillar | Source | Weight |
|--------|--------|--------|
| Design cases passed (≥ rubric bar) | design_attempts | 25% |
| Incidents diagnosed correctly | incident_attempts | 20% |
| Code review F1 (hits vs false positives) | review_attempts | 15% |
| Internals explain-back pass rate | attempts on topics | 15% |
| Timed coding (existing interview coding track avg) | interview_sessions | 10% |
| STAR stories `interview_ready` count (≥5) | star_stories | 10% |
| Full simulation ≥ 70 | simulation_attempts | 5% |

**Gate copy (example):**  
“Book senior screens when Design ≥ 70, Incidents ≥ 70, STAR ≥ 5 ready, Simulation ≥ 70.”

Dashboard UI: radial/bars per pillar + “next recommended action” (lowest pillar CTA).

---

### 4.8 Live coding (optional phase 2)

If not covered enough by existing interview `coding` track:
- **Model `CodingDrill`:** prompt, constraints, starter, solution, test cases (php snippets run via existing `CodeRunner`), `est_minutes` (30–45), rubric notes.
- 20–30 domain-shaped problems (parser, rate limiter state machine, merge schedules, LRU, dedupe stream) — not pure LeetCode grind.
- Reuse playground runner + store attempts.

---

## 5. Content authoring checklist (must ship with v1)

**Design cases:** 12 × (prompt, constraints, rubric, model answer, 3 follow-ups)  
**Incidents:** 10 × (symptom, fake logs/metrics, wrong paths, diagnosis, fix steps)  
**Reviews:** 6 × (PR files, ≥4 planted issues each, model comments)  
**Internals:** 10 topics × body + 3 explain-backs each  
**STAR:** 8 prompts + rubric  
**Simulations:** 2–3 loops wired to real refs above  
**Coding (phase 2):** 20 drills with tests  

Prefer one `SeniorTrackSeeder` with arrays mirroring `LearningPlatformSeeder` style (blueprints + `updateOrCreate`).

---

## 6. UI / routes

```
/senior                     → ⚡senior-dashboard
/senior/internals           → list
/senior/internals/{topic}   → detail + explain-back
/senior/design              → case list
/senior/design/{case}       → runner
/senior/incidents           → list
/senior/incidents/{incident}→ war room
/senior/reviews             → list
/senior/reviews/{drill}     → arena
/senior/star                → story list/editor
/senior/simulation/{slug}   → full loop
```

- Auth middleware for all.
- Sidebar: new **Senior track** group (show to all authed users once seeded; hide incomplete tools behind `is_published`).
- Admin: optional extend **Admin · Content** with tabs for design/incident/review CRUD (phase 2 of admin polish — don’t block v1).

---

## 7. Testing plan (Pest)

`tests/Feature/SeniorTrackTest.php`

1. Guest redirected from `/senior`.
2. Student sees dashboard after auth.
3. Design case submit stores attempt + shows model answer section.
4. Incident: wrong category scores below bar; correct category ≥ bar.
5. Review drill: empty submission → low score; all planted issues → high score.
6. Internals explain-back keywords → pass ≥70.
7. STAR create/update/status transitions.
8. Simulation completes → attempt row + segment answers.
9. `SeniorReadiness` thresholds: user with seeded “perfect” attempts shows overall ≥ gate (use factories or seed doubles).
10. Seeder idempotent: run `SeniorTrackSeeder` twice → same counts.

Keep existing 46 tests green; run `vendor/bin/pint --format agent` after PHP edits.

---

## 8. Phased delivery

### Phase 0 — Scaffold (½ day)
- [ ] Migrations + empty models + routes + empty SFC pages
- [ ] Sidebar group + dashboard shell
- [ ] `SeniorTrackSeeder` stub + call from `DatabaseSeeder` / `LearningPlatformSeeder::run()`
- [ ] Feature tests for routes auth

### Phase 1 — Design Studio + Internals (2–3 days)
- [ ] `DesignCase` CRUD seed + runner + rubric self-score
- [ ] `InternalsTopic` seed + explain-back scorer
- [ ] Tests 3, 6
- [ ] Wire dashboard pillars for design + internals

### Phase 2 — Incidents + Code Review (2–3 days)
- [ ] War room UI + hypothesis submit + reveal
- [ ] Review arena + scoring
- [ ] Tests 4, 5
- [ ] Auto STAR draft from incident (hand-off to Phase 3)

### Phase 3 — STAR + Simulation + Readiness (2 days)
- [ ] Story builder + rubric checklist
- [ ] Simulation wizard + gap report
- [ ] `SeniorReadiness` + dashboard completion
- [ ] Tests 7–9

### Phase 4 — Content fill + polish (ongoing)
- [ ] Author all blueprint content (section 5)
- [ ] Admin tabs for design/incident/review (optional)
- [ ] Live coding drills (optional)
- [ ] AI mentor deep integration when API key present (optional)

---

## 9. Explicit non-goals (do not touch)

- Do **not** rewrite Stages 0–15 lesson bodies or break A0/A1 slugs/prereqs (existing tests).
- Do **not** remove 407 exercises, checkpoints, knowledge graph, or admin CRUD.
- Do **not** add new npm frameworks or change Livewire SFC conventions.
- Do **not** require external paid APIs for v1 scoring (keyword/rubric first).

---

## 10. Definition of done (senior track v1)

- [ ] 12 design cases + 10 incidents + 6 reviews + 10 internals + 8 STAR prompts + 2 simulations seeded
- [ ] All `/senior/*` pages work for authed student
- [ ] Readiness dashboard shows all pillars with real data
- [ ] `SeniorTrackTest` green; full `php artisan test --compact` green; Pint clean
- [ ] Smoke script extended with `/senior` 302/200
- [ ] README or in-app “How to use senior track” blurb (only if you ask for docs)

---

## 11. Suggested immediate next step

Start **Phase 0 + Phase 1** (scaffold, design studio, internals) — highest interview ROI and reuses existing scoring/UI patterns with least risk to the shipping curriculum.
