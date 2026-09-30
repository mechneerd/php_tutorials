# PHP Learning Implementation Plan
## Beginner → Advanced → Senior Developer

**Mentor role:** personal programming mentor, senior engineer, CS teacher, technical interviewer
**Primary source:** Official PHP Manual — `C:\Users\butom\Downloads\php-bigxhtml.html` (63.3 MB, covers PHP 7 & 8)
**Lab project:** Laravel 13 / Livewire 4 / Fortify starter kit in this repository
**Runtime:** PHP 8.5 (per AGENTS.md), SQLite, Pest 5, Pint, Larastan level 7

---

## 1. Ground Rules

1. Request a lesson → full teaching (not a syllabus dump), PHP Manual as authority for language facts.
2. Every lesson ends with exercises: Understand (L1) → Implement (L2) → Debug (L3) → Design (L4) → Production (L5) → Senior (L6).
3. Attempt first; hints before solutions; similar follow-up problem after any given solution.
4. Checkpoint gates: no advancement past a stage until checkpoint passes (questions + coding + explain-it-back).
5. Knowledge graph: every concept connects backward to foundations and forward to the next topic.
6. Laravel repo is the live lab: read and modify real production-style code as concepts arrive.
7. Version-aware: identify PHP version for every feature; never teach deprecated behavior as current.
8. Documentation beats general knowledge when they conflict; state the version and what changed.

---

## 2. Documentation Map (php-bigxhtml.html)

Manual structure → learning stages:

| Manual section | Stages it feeds |
|---|---|
| Getting Started, A simple tutorial | Stage 0–1 |
| Installation and Configuration, Runtime Configuration | Stage 1, Stage 12 |
| Language Reference → Basic syntax, Types, Variables, Constants, Expressions, Operators | Stage 1–3 |
| Language Reference → Control Structures, Functions | Stage 2 |
| Language Reference → Arrays, Strings (function reference) | Stage 4 |
| Language Reference → Classes and Objects, Namespaces, Enumerations, Attributes | Stage 5 |
| Language Reference → Errors, Exceptions, Fibers, Generators | Stage 6, 8 |
| Language Reference → References Explained, Predefined Variables | Stage 3 |
| Predefined Interfaces (Iterator, ArrayAccess, Throwable, Closure, …) | Stage 5, 7 |
| Function reference (per extension) | Stage 4, 10, 11 on demand |

How to use a manual section:
- Extract simple meaning, technical meaning, prerequisites, version notes, common mistakes.
- Build examples: minimal → real-world → bad → improved → production.
- Create exercises + interview questions + links to prior concepts.
- Never only paraphrase — teach it.

---

## 3. Stages (roadmap)

```text
Stage 0   Computer & Program Fundamentals           [no code yet]
Stage 1   PHP Language Fundamentals                 [syntax, types, variables, constants]
Stage 2   Control Flow & Functions (Procedural)     [control structures, functions]
Stage 3   Memory & Runtime Model                    [zvals, refcounting, references, scope]
Stage 4   Arrays & Strings                          [arrays, strings, filesystem, closures]
Stage 5   OOP from Zero → SOLID                     [classes, interfaces, enums, namespaces, DI]
Stage 6   Errors, Exceptions, Failure Handling      [error model, Throwable, recovery]
Stage 7   Data Structures & Algorithms              [applied in PHP]
Stage 8   Concurrency, Fibers, Async & Web Model    [fibers, processes, queues]
Stage 9   Testing & Code Quality                    [Pest, Pint, Larastan, CI]
Stage 10  Databases & Eloquent                      [SQL first, then Laravel layer; Data Mapper → Eloquent]
Stage 11  HTTP, Networking & Security               [protocol, sessions, Fortify, OWASP]
Stage 12  Backend / Laravel Production              [routing, middleware, jobs, cache, deploy, Git & CI]
Stage 13  Design Patterns & Architecture            [patterns, UML, enterprise patterns, DI, SOLID at scale]
Stage 14  System Design & Production Engineering    [scale, observe, recover]
Stage 15  Senior Interview Preparation              [one question at a time]
```

Gates:
- Stages 0–6 are mandatory foundations; no skipping.
- Stage 7+ unlocks only after prior checkpoint.
- If mastery is demonstrated, compress/skip forward with evidence.
- If the same mistake repeats, stop and reteach the underlying concept.

---

## 4. Knowledge Graphs

### Memory & execution
```text
Program (source .php)
  → Process (php binary / SAPI)
    → Virtual memory
       ├── Code (opcodes / shared)
       ├── Data (globals, function tables)
       ├── Heap (objects, arrays, strings > threshold)
       └── Stack (call frames, locals)
  → Variables → Types → zvals → Value vs reference
  → Scope → Lifetime → Stack frames / request shutdown
  → References (&) → Reference counting → Cycles → GC
  → Memory bugs in PHP: leaks, OOM, cycles (not manual free)
```

### Functions → closures → functional style
```text
Functions → Scope → Call stack → Recursion
  → Closures (use) → Higher-order functions
  → array_* pipelines → Immutability preferences
```

### OOP chain
```text
Problem: tangled procedural state
  → Objects (state + behavior) → Classes → Constructor
  → Encapsulation (visibility) → Abstraction
  → Inheritance → Polymorphism → Interfaces / abstract
  → Composition over inheritance → SOLID
  → DI / containers → Design patterns → Architecture
```

### Errors
```text
Exception vs error → Throwable hierarchy
  → try/catch/finally → Propagation → Custom exceptions
  → Error vs Exception (programmer vs runtime)
  → Logging / metrics → Retry / timeout / graceful degradation
```

### Web request lifecycle (your app)
```text
HTTP request
  → SAPI (php-fpm / artisan serve)
  → bootstrap/app.php (Application::configure)
  → middleware stack (web, auth, verified)
  → route (routes/web.php, routes/settings.php)
  → Livewire component / controller action
  → Eloquent (SQLite) → response → session/cookie
  → Fortify for auth edges (login, reset, verify)
```

---

## 5. Detailed Phase Plans

### Phase A — Foundations (Stages 0–3)

**Goal:** Explain what happens when PHP runs a line: file → process → memory.

| ID | Lesson (manual anchor) | Deliverable | Checkpoint item |
|----|------------------------|-------------|-----------------|
| A0 | What is a program; CPU/RAM/storage; compiler vs interpreter; process vs thread | Diagram: source → process → output | Explain program→process in own words |
| A1 | What is PHP; CLI vs web SAPI; embed in HTML | `php -v`; run `hello.php` from terminal | Run + explain CLI vs Apache |
| A2 | Basic syntax: `<?php`, `?>`, escaping HTML, comments, `;` | Convert 3 HTML snippets to PHP | Debug: broken tags |
| A3 | Types: null, bool, int, float, string; casting; `var_dump`/`gettype` | Type identification worksheet (20 expressions) | Type-juggling traps |
| A4 | Variables, scope, constants (`const`, `define`) | Refactor script with named constants | Scope shadowing debug |
| A5 | Expressions vs statements; operator precedence | Tip calculator CLI | Precedence debug |
| A6 | Memory: request lifecycle, stack frames, zvals, refcount (PHP model) | ASCII diagram of a function call; predict then run | Why `$a = $b` does not always deep-copy |

**Project L1:** CLI expense tracker — categories, totals, report; data in PHP arrays; later persists to JSON.

**Phase A exercises mix:** L1 explain, L2 write, L3 fix broken snippet, L4 design data shape, L5 handle empty/negative input, L6 justify type choices.

---

### Phase B — Procedural Mastery (Stage 2 + 4)

**Goal:** Modular procedural code; own arrays/strings/files.

| ID | Lesson | Deliverable |
|----|--------|-------------|
| B1 | Control structures: if/elseif, `match`, for/foreach/while, break/continue, early return | FizzBuzz variants; sieve of Eratosthenes |
| B2 | Functions: params, returns, by-value vs by-ref, variadics, default values, return types | Refactor Phase A project into functions |
| B3 | Arrays: list/assoc/multi; `array_*`; iteration patterns | Grade-book manager |
| B4 | Strings: interpolation, concatenation, heredoc/nowdoc, encoding basics, `str*` | CSV parser |
| B5 | Filesystem & CLI I/O: `file_get_contents`, JSON encode/decode | Expense tracker load/save JSON |
| B6 | Closures, `use`, first-class callables, higher-order functions | Custom map/filter pipeline |

**Checkpoint B:** Debug broken procedural program — you diagnose first; I evaluate reasoning before revealing the fix.

**Project L2:** Contact book — add/search/edit/delete; validation; file persistence; report command.

---

### Phase C — OOP from Zero (Stage 5)

**Goal:** Justify every OOP decision; no memorization.

Teaching sequence per concept:
```text
Problem → Naive solution → Problem with naive → Better design
  → PHP implementation → Production example → Trade-offs → When NOT to use
```

| # | Topic | Manual anchor |
|---|-------|---------------|
| C1 | Why procedural breaks at scale → objects | Classes and Objects intro |
| C2 | Class vs object; state + behavior; `$this` | Classes and Objects |
| C3 | Constructor; property promotion (PHP 8); typed properties | Classes and Objects |
| C4 | Visibility: public/protected/private; encapsulation *why* | Classes and Objects |
| C5 | Abstraction vs implementation detail | — |
| C6 | Inheritance; `extends`; when it's wrong | Classes and Objects |
| C7 | Polymorphism; interfaces; abstract classes | Interfaces section |
| C8 | Composition over inheritance (live refactor) | — |
| C9 | SOLID — each principle with a smell in *this* repo | — |
| C10 | Namespaces, PSR-4, Composer autoload | Namespaces |
| C11 | Enums, Attributes, readonly, constructor promotion | Enumerations, Attributes |
| C12 | Magic methods responsibly (`__get`, `__toString`, …) | Predefined methods |
| C13 | Static vs instance; DI vs globals; service container idea | — |

**Lab (this repo):** `app/Models/User.php` (casts, initials, Fillable/Hidden attributes), `app/Concerns/ProfileValidationRules.php` (trait), `app/Providers/*`, `app/Actions/Fortify/ResetUserPassword.php` (action + interface). Identify each concept live.

**Project L3:** Library management — `Book`, `Member`, `Loan`, repository persistence, unit tests.

**Checkpoint C:** Explain-it-back — teach *composition vs inheritance* to a junior; graded on correctness, depth, missing details.

---

### Phase D — Errors, Testing, Databases (Stages 6, 9, 10)

| ID | Lesson | Anchor |
|----|--------|--------|
| D1 | Error vs exception; `Throwable` tree; `Error` vs `Exception` | Errors, Exceptions, Predefined Exceptions |
| D2 | try/catch/finally; propagation; custom exceptions; when to catch | Exceptions |
| D3 | Fail-fast vs recover; logging vs throwing; error handlers | Runtime Configuration / Errors |
| D4 | Unit vs feature vs e2e; Pest style in this repo | tests/, Pest.php, phpunit.xml |
| D5 | Test doubles: mock/stub/fake; what not to test | — |
| D6 | TDD rhythm; red/green/refactor | — |
| D7 | SQL fundamentals: tables, keys, joins, indexes, transactions, ACID | — |
| D8 | Eloquent as a layer: models, migrations, factories, N+1 | database/, UserFactory |
| D9 | Data Mapper, Identity Map, Unit of Work → Eloquent equivalents (K4) | hand-built mapper lab |

**Project L4:** REST-style endpoints in Laravel — validation, form requests or Livewire rules, Pest feature tests, typed errors, JSON errors.

---

### Phase E — Web, Security, Backend (Stages 11–12)

| ID | Lesson | Lab in this repo |
|----|--------|------------------|
| E1 | HTTP: methods, status, headers, cookies, keep-alive | routes, middleware |
| E2 | Sessions vs tokens; auth vs authorization | config/session.php, Fortify |
| E3 | Password hashing; reset flow; email verification | ResetUserPassword, Fortify views |
| E4 | CSRF, XSS, SQLi, injection — mechanism + prevention | @csrf, Eloquent binding, escaping |
| E5 | Routing, named routes, middleware lifecycle | routes/web.php, settings.php |
| E6 | Livewire request lifecycle; server-side state | pages/⚡profile, ⚡security |
| E7 | Queues, jobs, cache, rate limiting | RateLimiter in FortifyServiceProvider |
| E8 | API design: resources, pagination, versioning, idempotency | future endpoints |

**Project L5:** Ship a feature end-to-end in this app (e.g., roles/permissions or API tokens): requirements → design → migrate → implement → test → review notes → deploy considerations.

---

### Phase F — Senior Track (Stages 13–15)

| ID | Activity |
|----|----------|
| F1 | Design patterns only after hitting the problem: Factory, Strategy, Repository, Decorator, Observer, DI |
| F2 | Read unfamiliar code: map dependencies; propose a change with trade-offs |
| F3 | Code review drill: find bugs, smells, security holes in a provided diff |
| F4 | System design progressive: URL shortener → chat → notifications → auth → e-commerce |
| F5 | Production: env/config, structured logs, metrics, tracing, CI/CD, Docker, reverse proxy, blue/green, rollback |
| F6 | Performance: measure first — Big O, profiling, DB explain, cache invalidation |
| F7 | Git/team: branch model, rebase vs merge, PR etiquette, semver (M3) |
| F8 | Incident debugging: given symptoms, form hypotheses, instrument, fix |
| F9 | UML: class + sequence diagrams for this app (N4) |
| F10 | GoF catalog applied: Factory, Strategy, Observer, Decorator, Command, Null Object, Visitor (N5) |
| F11 | Enterprise patterns mapped to Laravel: Front/Page/Application Controller, Template View, Transaction Script, Domain Model, Registry (N6) |

**Project L6/L7:** Production-style backend — auth + roles + API + queues + cache + tests + observability + deploy runbook.

---

## 6. Exercise Levels (use every major lesson)

| Level | Name | Expectation |
|-------|------|-------------|
| 1 | Understand | Answer conceptual questions |
| 2 | Implement | Write working code |
| 3 | Debug | Fix broken code; explain root cause first |
| 4 | Design | Choose structure; justify |
| 5 | Production | Edge cases, security, ops constraints |
| 6 | Senior | Architectural decision + trade-offs + interview defense |

Anti-cheat: repeated ask-for-answer → hints → solution → independent similar problem. Recognition ≠ understanding.

---

## 7. Checkpoints (gate criteria)

### Checkpoint A (after Stage 0–3)
1. Explain file → process → memory for `php hello.php`.
2. Name 4 primitive types + one composite; show type juggling pitfall.
3. Trace call stack for a recursive function; predict overflow.
4. Write: refactor 20-line script into 4 functions with types.
5. Debug: wrong operator precedence producing bad total.
6. Explain-it-back: reference counting vs copy-on-write in PHP.

### Checkpoint B (after Stage 2+4)
1. Design contact-book data shape; implement search by field.
2. Debug: loop mutating array while iterating.
3. Explain: when to pass by reference vs return new value.
4. Implement: CSV → array → report with closures.
5. Production: invalid UTF-8 / missing file handling.

### Checkpoint C (after Stage 5)
1. Refactor inheritance tree to composition; justify.
2. Map SOLID violation in a provided snippet; fix it.
3. Explain PSR-4 autoload for `App\Models\User`.
4. Design: payment method polymorphism (interface vs enum vs strategy).
5. Explain-it-back: composition vs inheritance.

### Checkpoint D–F
- D: write feature test for a failing use case; write SQL for 3-table join with index rationale.
- E: walk through login request end-to-end; patch an XSS or CSRF flaw; justify middleware order.
- F: system design 45-min drill; code review with ≥5 actionable findings; incident RCA.

---

## 8. Session / Weekly Operating Model

```text
Day 1–2  Lesson + minimal examples + L1–L2 exercises
Day 3    L3 debug (attempt before hints)
Day 4    L4–L5 design/production exercise
Day 5    Project work + tests + Pint
Day 6    Checkpoint or explain-it-back
Day 7    Buffer / review / "what next" analysis
```

Mental model tracking (per concept): Not learned → Introduced → Practicing → Understands → Strong → Advanced.

Command vocabulary:

| Phrase | Action |
|--------|--------|
| `Start A1` / `Teach A1` | Full lesson + exercises |
| `Give me everything about X` | Complete beginner→senior treatment; split if huge |
| `Checkpoint A` | Run gate; hold if not ready |
| `Explain-it-back: topic` | You teach; I grade |
| `Debug this` + code | You diagnose; I evaluate reasoning first |
| `What should I learn next?` | Analyze state → justify next D from A+B+C |
| Paste manual section | Teach it: prereqs, internals, exercises, interview Qs |
| `Interview: senior` | One question; wait; evaluate; ideal answer |
| `Adjust the plan` | Revise pace/stages/projects |

---

## 9. Terminology Protocol

First use of any term:
```text
Term
Simple meaning
Technical meaning
Example
Why it matters
```

Never use unexplained: abstraction, polymorphism, ownership, concurrency, immutability, race condition, idempotency, serialization, dependency inversion, etc.

---

## 10. Code Example Standard

For each important concept:
1. Minimal example
2. Real-world example
3. Bad implementation
4. Improved implementation
5. Production-quality implementation

Explain important lines; drop trivial line-by-line explanation as level rises.

Comparisons via table only when they clarify: stack vs heap, value vs reference, class vs object, composition vs inheritance, interface vs abstract, auth vs authn/authz, SQL vs NoSQL, concurrency vs parallelism.

---

## 11. Project Ladder

| Level | Project | Unlocks |
|-------|---------|---------|
| 1 | CLI expense tracker | Stages 1–2 |
| 2 | Contact book + file persistence | Stage 4 |
| 3 | Library management (OOP + tests) | Stage 5–6 |
| 4 | REST API (Laravel) | Stages 9–10 |
| 5 | Feature: authz / API tokens in this app | Stages 11–12 |
| 6 | Production backend (queues, cache, observability) | Stages 13–14 |
| 7 | Distributed design project | Stage 14–15 |

Every project includes: requirements, architecture, data design, API design, implementation, tests, error handling, security, performance notes, docs, Git workflow, deploy notes.

---

## 12. Interview Mode

- Junior: syntax, fundamentals, basic algorithms.
- Mid: OOP, DS, DB, APIs, testing, debugging.
- Senior: architecture, system design, concurrency, performance, security, scalability, trade-offs, review, incidents, leadership.

Protocol: one question → wait for your answer → score correctness/depth/clarity/gaps vs senior bar → show ideal answer → sometimes follow-up.

---

## 13. Source & Version Discipline

- Language facts: official manual (`php-bigxhtml.html`) first.
- CS concepts: established technical knowledge.
- Ecosystem/jobs/libs: web search; clearly label as external vs manual.
- Always: version introduced / current status / deprecations / behavioral changes.
- Current target: PHP 8.x features (typed properties, promotion, enums, match, attributes, fibers, readonly) — flag anything PHP 7-only as legacy.

---

## 14. Definition of Done (program exit criteria)

**Fundamentals:** Explain how a PHP program executes from disk to response.
**Language:** Idiomatic modern PHP 8; know when not to use a feature.
**CS:** Memory (PHP model), DS&A, OS basics, networking, concurrency.
**Backend:** Secure, tested, maintainable APIs/services (Laravel).
**Production:** Deploy, monitor, debug, optimize.
**Architecture:** Design systems; defend trade-offs.
**Senior:** Read unfamiliar code, review, hard debugging, mentor.
**Interviews:** Pass via understanding, not memorization.

---

## 15. Immediate Next Action

**Start with `A0`/`A1`:** What a program is; PHP CLI vs web; run first script; explain process model.

Reply:
- `Start A1` — begin lesson
- `Start Stage 0` — computer fundamentals first
- `Adjust the plan` — change pace, projects, ordering
- or name a lesson/stage/manual section to teach now
