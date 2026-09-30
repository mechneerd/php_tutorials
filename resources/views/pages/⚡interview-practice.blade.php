<?php

use App\Models\InterviewSession;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Interview Practice')] class extends Component
{
    public string $level = 'beginner';

    public ?InterviewSession $session = null;

    public int $currentIndex = 0;

    public string $answer = '';

    /** @var list<array{score: int, passed: bool, notes: string}> */
    public array $evaluations = [];

    public function start(): void
    {
        $questions = $this->questionBank()[$this->level] ?? [];

        $this->session = InterviewSession::query()->create([
            'user_id' => auth()->id(),
            'level' => $this->level,
            'status' => 'in_progress',
            'questions' => $questions,
            'answers' => [],
        ]);

        $this->currentIndex = 0;
        $this->answer = '';
        $this->evaluations = [];
    }

    public function submitAnswer(): void
    {
        if (! $this->session) {
            return;
        }

        $this->validate(['answer' => 'required|string|min:10']);

        $questions = $this->session->questions ?? [];
        $question = $questions[$this->currentIndex] ?? null;

        if (! $question) {
            return;
        }

        $evaluation = $this->evaluateAnswer((string) $question['answer'], $this->answer, $question['keywords'] ?? []);
        $this->evaluations[] = $evaluation;

        $answers = $this->session->answers ?? [];
        $answers[(string) $question['id']] = [
            'given' => $this->answer,
            'score' => $evaluation['score'],
        ];
        $this->session->answers = $answers;

        $this->answer = '';
        $this->currentIndex++;

        if ($this->currentIndex >= count($questions)) {
            $scores = array_column($this->evaluations, 'score');
            $avg = $scores ? (int) round(array_sum($scores) / count($scores)) : 0;

            $this->session->status = 'completed';
            $this->session->score = $avg;
            $this->session->evaluation = ['per_question' => $this->evaluations];
            $this->session->completed_at = now();
            $this->session->save();
        } else {
            $this->session->save();
        }
    }

    public function newSession(): void
    {
        $this->session = null;
        $this->currentIndex = 0;
        $this->answer = '';
        $this->evaluations = [];
    }

    /**
     * @return array<string, list<array<string, mixed>>>
     */
    private function questionBank(): array
    {
        return [
            'beginner' => [
                ['id' => 'b1', 'prompt' => 'What is the difference between a program and a process?', 'answer' => 'A program is instructions on disk; a process is that program loaded and running with memory and CPU time.', 'keywords' => ['disk', 'running', 'memory', 'loaded']],
                ['id' => 'b2', 'prompt' => 'Explain == vs === in PHP.', 'answer' => '== compares values with type juggling; === compares value and type strictly.', 'keywords' => ['type', 'strict', 'juggling', 'same']],
                ['id' => 'b3', 'prompt' => 'How do you run a PHP file from the terminal?', 'answer' => 'php path/to/file.php using the CLI SAPI.', 'keywords' => ['php ', 'cli', 'terminal', 'command']],
                ['id' => 'b4', 'prompt' => 'What does PHP stand for and what is a SAPI?', 'answer' => 'PHP: Hypertext Preprocessor. A SAPI is the interface between PHP and the host (CLI, FPM, Apache module).', 'keywords' => ['hypertext', 'sapi', 'cli', 'fpm', 'interface']],
                ['id' => 'b5', 'prompt' => 'Name the PHP scalar types and one composite type.', 'answer' => 'Scalars: int, float, string, bool. Composite: array (and object). null is special.', 'keywords' => ['int', 'float', 'string', 'bool', 'array']],
                ['id' => 'b6', 'prompt' => 'What is the difference between echo and print in PHP?', 'answer' => 'echo can take multiple arguments and returns void; print takes one expression and returns 1. Both write a string.', 'keywords' => ['multiple', 'return', 'string', 'write', 'one']],
                ['id' => 'b7', 'prompt' => 'What does PHP_EOL do?', 'answer' => 'It is the platform end-of-line constant (newline on Linux, CRLF on Windows) used for portable CLI output.', 'keywords' => ['newline', 'platform', 'line', 'eol', 'portable']],
                ['id' => 'b8', 'prompt' => 'Why use a semicolon after PHP statements?', 'answer' => 'PHP uses semicolons as statement terminators; missing one is a parse error.', 'keywords' => ['terminator', 'parse', 'statement', 'semicolon', 'error']],
                ['id' => 'b9', 'prompt' => 'What is a variable in PHP and how is it written?', 'answer' => 'A named storage for a value, written with a $ prefix (e.g. $name). Variables are loosely typed by default.', 'keywords' => ['$', 'named', 'storage', 'value', 'loose']],
                ['id' => 'b10', 'prompt' => 'Explain single-quoted vs double-quoted strings briefly.', 'answer' => 'Single quotes are literal (almost no interpolation); double quotes interpolate variables and escape sequences.', 'keywords' => ['literal', 'interpolat', 'escape', 'variable', 'single']],
                ['id' => 'b11', 'prompt' => 'What are superglobals in PHP? Name two.', 'answer' => 'Arrays available everywhere without global keyword, e.g. $_GET, $_POST, $_SERVER, $_COOKIE, $_SESSION.', 'keywords' => ['global', 'get', 'post', 'server', 'cookie']],
                ['id' => 'b12', 'prompt' => 'What is the difference between GET and POST HTTP methods?', 'answer' => 'GET sends data in the URL (cacheable, length-limited); POST sends a body (larger, not idempotent by default).', 'keywords' => ['url', 'body', 'cache', 'idempotent', 'data']],
                ['id' => 'b13', 'prompt' => 'How do you include another PHP file?', 'answer' => 'include/require (and _once variants); require fatals if missing, include warns.', 'keywords' => ['include', 'require', 'once', 'file', 'fatal']],
                ['id' => 'b14', 'prompt' => 'What does isset() check?', 'answer' => 'That a variable is set and is not null — returns false for null or undefined.', 'keywords' => ['set', 'null', 'defined', 'variable', 'check']],
                ['id' => 'b15', 'prompt' => 'Name three PHP array types.', 'answer' => 'Indexed (numeric keys), associative (string keys), multidimensional (arrays of arrays).', 'keywords' => ['indexed', 'associative', 'multi', 'key', 'numeric']],
                ['id' => 'b16', 'prompt' => 'What is the difference between null and empty string?', 'answer' => 'null means no value; empty string is a string of length 0 — different types, both falsy in loose context.', 'keywords' => ['null', 'string', 'length', 'falsy', 'type']],
                ['id' => 'b17', 'prompt' => 'How do you convert a string to an integer in PHP?', 'answer' => 'Cast (int)$s, intval($s), or rely on numeric context — leading non-numeric truncates.', 'keywords' => ['cast', 'intval', 'int', 'convert', 'numeric']],
                ['id' => 'b18', 'prompt' => 'What is a boolean in PHP and what values are falsy?', 'answer' => 'true/false; falsy: false, 0, 0.0, "" , null, [] — everything else is truthy.', 'keywords' => ['true', 'false', 'falsy', 'zero', 'empty']],
                ['id' => 'b19', 'prompt' => 'Why use PHP_EOL instead of \\n in CLI scripts?', 'answer' => 'PHP_EOL is platform-correct (\\n on Linux, \\r\\n on Windows) for portable output.', 'keywords' => ['platform', 'portable', 'newline', 'windows', 'linux']],
                ['id' => 'b20', 'prompt' => 'What does var_dump do that echo does not?', 'answer' => 'var_dump shows type and structure (great for debugging); echo only prints a string representation.', 'keywords' => ['type', 'structure', 'debug', 'string', 'print']],
            ],
            'intermediate' => [
                ['id' => 'i1', 'prompt' => 'When would you pass an argument by reference in PHP, and what is a risk?', 'answer' => 'When you intentionally mutate the callers variable for performance or multi-return; risk is surprising side effects for callers.', 'keywords' => ['mutat', 'side effect', 'reference', '&']],
                ['id' => 'i2', 'prompt' => 'What problem do interfaces solve that inheritance alone does not?', 'answer' => 'They define a contract so unrelated classes can be used polymorphically without tight coupling to a base class.', 'keywords' => ['contract', 'polymorph', 'coupl', 'unrelated']],
                ['id' => 'i3', 'prompt' => 'Describe an N+1 query problem and one fix.', 'answer' => 'Loading a list then one query per row; fix with eager loading (with()) or a join.', 'keywords' => ['eager', 'with(', 'join', 'per row', 'n+1']],
                ['id' => 'i4', 'prompt' => 'What is the difference between an abstract class and an interface?', 'answer' => 'An abstract class can hold state and shared implementation with some abstract methods; an interface is a pure contract (multiple allowed).', 'keywords' => ['state', 'implementation', 'contract', 'multiple', 'abstract']],
                ['id' => 'i5', 'prompt' => 'When is a closure useful vs a plain named function?', 'answer' => 'When you need a small callable with captured lexical scope, callbacks, or array_map-style transforms without polluting global names.', 'keywords' => ['captur', 'scope', 'callback', 'callable', 'lexical']],
                ['id' => 'i6', 'prompt' => 'What does strict_types=1 change?', 'answer' => 'Scalar type hints and returns become strict (no numeric string juggling) for calls in that file.', 'keywords' => ['strict', 'scalar', 'no juggl', 'type hint', 'declare']],
                ['id' => 'i7', 'prompt' => 'Explain copy-on-write in one or two sentences.', 'answer' => 'Values are refcounted and shared until a write, at which point the engine separates (copies) so writers do not affect other holders.', 'keywords' => ['refcount', 'share', 'write', 'separat', 'copy']],
                ['id' => 'i8', 'prompt' => 'What is dependency injection and why prefer it over new inside a class?', 'answer' => 'Pass collaborators in (usually constructor) so dependencies are explicit, swappable, and testable; new couples to concrete types.', 'keywords' => ['constructor', 'explicit', 'test', 'coupl', 'inject']],
                ['id' => 'i9', 'prompt' => 'How do transactions help and when should they start/end?', 'answer' => 'They group writes into an atomic unit; begin before related writes, commit on success, rollback on failure; keep them short.', 'keywords' => ['atomic', 'commit', 'rollback', 'unit', 'short']],
                ['id' => 'i10', 'prompt' => 'What is the difference between == and === for arrays?', 'answer' => '== allows same key/value pairs with type juggling; === requires same order, types, and values.', 'keywords' => ['order', 'type', 'strict', 'juggl', 'same']],
                ['id' => 'i11', 'prompt' => 'When should you use traits instead of inheritance?', 'answer' => 'To reuse methods across unrelated classes without a shared base — PHP has single class inheritance only.', 'keywords' => ['reuse', 'method', 'unrelated', 'single', 'inherit']],
                ['id' => 'i12', 'prompt' => 'What is a generator (yield) useful for?', 'answer' => 'Lazily producing values one at a time — process large files/streams without loading everything into memory.', 'keywords' => ['lazy', 'yield', 'stream', 'memory', 'one']],
                ['id' => 'i13', 'prompt' => 'How do you handle exceptions in PHP?', 'answer' => 'try/catch/finally; throw typed exceptions; rethrow with context; finally always runs for cleanup.', 'keywords' => ['try', 'catch', 'finally', 'throw', 'cleanup']],
                ['id' => 'i14', 'prompt' => 'What is the difference between an interface and a trait?', 'answer' => 'Interface defines a contract (methods must be implemented); trait provides reusable method implementations you can use in multiple classes.', 'keywords' => ['contract', 'implement', 'reuse', 'method', 'class']],
                ['id' => 'i15', 'prompt' => 'What does late static binding (static::) do?', 'answer' => 'Resolves static:: at runtime based on the called class — useful for inheritance-friendly static factories.', 'keywords' => ['runtime', 'called', 'class', 'static', 'inherit']],
                ['id' => 'i16', 'prompt' => 'Why prefer composition over inheritance in application code?', 'answer' => 'Composition reduces fragile base-class coupling and lets you mix behaviors without deep is-a trees.', 'keywords' => ['fragile', 'coupl', 'mix', 'behavior', 'is-a']],
                ['id' => 'i17', 'prompt' => 'What is a value object vs an entity?', 'answer' => 'Value object is immutable and equal by value (Money); entity has identity/id and can change over time (User).', 'keywords' => ['immutable', 'identity', 'value', 'equal', 'entity']],
                ['id' => 'i18', 'prompt' => 'When is copy-on-write triggered in PHP?', 'answer' => 'When one zval sharing a value is written — the engine separates (copies) so other holders keep the old value.', 'keywords' => ['write', 'separat', 'copy', 'share', 'zval']],
                ['id' => 'i19', 'prompt' => 'What are first-class callable syntax and arrow functions good for?', 'answer' => 'Short callbacks for array_map/usort/filter without verbose function() wrappers — concise functional pipelines.', 'keywords' => ['callback', 'array', 'short', 'fn(', 'pipeline']],
                ['id' => 'i20', 'prompt' => 'How do you make a PHP class immutable?', 'answer' => 'readonly properties (or private + no setters), construct fully in constructor, return new instances for changes.', 'keywords' => ['readonly', 'constructor', 'private', 'setter', 'new']],
            ],
            'senior' => [
                ['id' => 's1', 'prompt' => 'Design decision: monolith vs service for a 5-person team shipping weekly. What do you optimize for?', 'answer' => 'Usually operational simplicity and delivery speed: modular monolith first; split only when scaling or team boundaries demand it.', 'keywords' => ['simplic', 'modular', 'trade', 'scale', 'team']],
                ['id' => 's2', 'prompt' => 'How do you make a background job consumer safe to run twice (at-least-once delivery)?', 'answer' => 'Idempotent handlers with unique keys or status transitions so duplicate execution has no extra effect.', 'keywords' => ['idempot', 'duplicate', 'unique', 'exactly']],
                ['id' => 's3', 'prompt' => 'Walk through debugging a production latency spike. First three steps.', 'answer' => 'Check recent deploys/config, inspect metrics/logs/traces for the hot path, then isolate DB vs app vs external dependency.', 'keywords' => ['metric', 'log', 'trace', 'deploy', 'db']],
                ['id' => 's4', 'prompt' => 'How do you roll out a schema change safely on a large table?', 'answer' => 'Expand/contract: add nullable columns first, backfill in batches, switch reads/writes, then drop later; avoid long locks.', 'keywords' => ['expand', 'backfill', 'batch', 'lock', 'contract']],
                ['id' => 's5', 'prompt' => 'What metrics matter for a queue-based worker fleet?', 'answer' => 'Queue depth/age, processing latency p95, failure/retry rate, consumer lag, and dead-letter volume.', 'keywords' => ['depth', 'lag', 'latency', 'retry', 'dead']],
                ['id' => 's6', 'prompt' => 'When would you choose eventual consistency over strong consistency?', 'answer' => 'When availability and partition tolerance matter more than immediate reads (feeds, counters, geo-distributed reads) and UX can tolerate lag.', 'keywords' => ['availab', 'partition', 'lag', 'geo', 'read']],
                ['id' => 's7', 'prompt' => 'How do you prevent a cache stampede on a hot key?', 'answer' => 'Lock/single-flight so one request rebuilds, jitter TTLs, and serve stale while revalidating.', 'keywords' => ['lock', 'single', 'jitter', 'ttl', 'stale']],
                ['id' => 's8', 'prompt' => 'What does idempotency mean for a payment API?', 'answer' => 'Retrying the same request with the same key must not charge twice; store the key and return the original result.', 'keywords' => ['key', 'twice', 'retry', 'original', 'charge']],
                ['id' => 's9', 'prompt' => 'How do you make a feature flag rollout safe?', 'answer' => 'Default off, staged percentage, metrics/alerts on error budget, easy kill switch, and no permanent dual paths without expiry.', 'keywords' => ['default', 'percent', 'kill', 'alert', 'expiry']],
                ['id' => 's10', 'prompt' => 'Explain the trade-off between consistency and availability under network partition (CAP).', 'answer' => 'During a partition you choose CP (refuse some writes for consistency) or AP (accept updates that may conflict later).', 'keywords' => ['partition', 'cp', 'ap', 'consist', 'availab']],
                ['id' => 's11', 'prompt' => 'How do you design a system for zero-downtime deploys?', 'answer' => 'Blue/green or rolling deploys, health checks, graceful drain, backward-compatible migrations, and easy rollback.', 'keywords' => ['rolling', 'health', 'rollback', 'graceful', 'compat']],
                ['id' => 's12', 'prompt' => 'What is back-pressure and when does it matter?', 'answer' => 'Slowing the producer when consumers cannot keep up — protects queues/memory from unbounded growth.', 'keywords' => ['slow', 'producer', 'consumer', 'queue', 'bound']],
                ['id' => 's13', 'prompt' => 'How would you rate-limit a public API securely?', 'answer' => 'Token bucket or sliding window per API key/IP in Redis; fail closed on config errors; return 429 with Retry-After.', 'keywords' => ['token', 'sliding', 'redis', '429', 'key']],
                ['id' => 's14', 'prompt' => 'When would you choose PostgreSQL over MySQL (or vice versa)?', 'answer' => 'Postgres for complex queries, JSON, strict standards; MySQL for simple high-throughput web workloads and ecosystem familiarity.', 'keywords' => ['complex', 'json', 'throughput', 'standard', 'web']],
                ['id' => 's15', 'prompt' => 'What is a circuit breaker and why use it on external calls?', 'answer' => 'Stops hammering a failing dependency after repeated errors, fails fast, then half-opens to test recovery.', 'keywords' => ['fail', 'fast', 'external', 'recover', 'error']],
                ['id' => 's16', 'prompt' => 'How do you version a REST API without breaking clients?', 'answer' => 'URL or header versioning (v1/v2), additive-only changes, deprecation headers, and a migration window.', 'keywords' => ['version', 'additive', 'deprecat', 'header', 'client']],
                ['id' => 's17', 'prompt' => 'What metrics define a healthy queue worker?', 'answer' => 'Queue depth/age, processing latency p95, failure rate, retry volume, dead-letter count, and consumer lag.', 'keywords' => ['depth', 'latency', 'failure', 'retry', 'lag']],
                ['id' => 's18', 'prompt' => 'How do you secure secrets in a production Laravel app?', 'answer' => 'In .env or a secret manager, never in git; rotate keys; least privilege; mask in logs; inject at deploy time.', 'keywords' => ['env', 'git', 'rotate', 'mask', 'manager']],
            ],
            'coding' => [
                ['id' => 'c1', 'prompt' => 'Write (describe) a function that returns the most frequent integer in a list. What complexity?', 'answer' => 'Count with a hash map in O(n) time and O(n) space; or sort and scan in O(n log n).', 'keywords' => ['o(n)', 'hash', 'map', 'count', 'sort']],
                ['id' => 'c2', 'prompt' => 'How does binary search work and what must be true of the input?', 'answer' => 'Repeatedly halve a sorted range by comparing the midpoint; input must be sorted with consistent order.', 'keywords' => ['sort', 'mid', 'half', 'o(log']],
                ['id' => 'c3', 'prompt' => 'Reverse a singly linked list — outline the algorithm and complexity.', 'answer' => 'Iterate with prev/curr; point curr->next to prev; advance. O(n) time, O(1) extra space.', 'keywords' => ['prev', 'curr', 'pointer', 'o(n)', 'reverse']],
                ['id' => 'c4', 'prompt' => 'Detect a cycle in a linked list. Name the algorithm.', 'answer' => 'Floyd tortoise and hare: slow and fast pointers; if they meet, a cycle exists. O(n) time, O(1) space.', 'keywords' => ['floyd', 'slow', 'fast', 'pointer', 'meet']],
                ['id' => 'c5', 'prompt' => 'What is the time complexity of building a hash map from n items on average?', 'answer' => 'O(n) average with good hashing; worst case O(n^2) on heavy collisions.', 'keywords' => ['o(n)', 'hash', 'average', 'collision', 'build']],
                ['id' => 'c6', 'prompt' => 'Given sorted arrays, how do you merge them efficiently?', 'answer' => 'Two-pointer or k-way heap merge; total O(N log k) for k arrays of total length N with a heap, or O(N) for pairwise two-pointer merges.', 'keywords' => ['two', 'pointer', 'heap', 'merge', 'o(n']],
                ['id' => 'c7', 'prompt' => 'How would you check if two strings are anagrams?', 'answer' => 'Count character frequencies (array/hash) and compare; or sort both and compare. O(n) with counts, O(n log n) with sort.', 'keywords' => ['count', 'frequenc', 'sort', 'hash', 'anagram']],
                ['id' => 'c8', 'prompt' => 'What data structure gives O(1) average insert, delete, and lookup?', 'answer' => 'A hash map / hash set (on average, assuming good hash and low load).', 'keywords' => ['hash', 'o(1)', 'average', 'map', 'set']],
                ['id' => 'c9', 'prompt' => 'When is BFS preferred over DFS?', 'answer' => 'When you need shortest path in unweighted graphs, level order, or nearest neighbors first; DFS for cycles, topo, components.', 'keywords' => ['shortest', 'level', 'nearest', 'dfs', 'path']],
                ['id' => 'c10', 'prompt' => 'How does memoization change recursive Fibonacci?', 'answer' => 'Caches results so each n is computed once: O(n) time and space instead of O(2^n) time.', 'keywords' => ['cache', 'o(n)', 'once', 'repeated', 'exponential']],
                ['id' => 'c11', 'prompt' => 'Solve Two Sum conceptually: given nums and target, return indices of two numbers that add up.', 'answer' => 'Hash map of value→index; for each num check if target-num exists; O(n) time.', 'keywords' => ['hash', 'map', 'complement', 'o(n)', 'index']],
                ['id' => 'c12', 'prompt' => 'What is the sliding window technique? When do you use it?', 'answer' => 'Maintain a window of elements for subarray/substring problems (max sum, longest unique) in O(n).', 'keywords' => ['window', 'subarray', 'substring', 'o(n)', 'expand']],
                ['id' => 'c13', 'prompt' => 'How do you detect a valid parentheses string?', 'answer' => 'Stack: push opens, pop on close, valid if stack empty at end and never underflow.', 'keywords' => ['stack', 'push', 'pop', 'open', 'close']],
                ['id' => 'c14', 'prompt' => 'What is Kadane’s algorithm for?', 'answer' => 'Maximum subarray sum in O(n) by tracking current ending-here vs global best.', 'keywords' => ['max', 'subarray', 'sum', 'o(n)', 'kadane']],
                ['id' => 'c15', 'prompt' => 'Merge two sorted linked lists — outline approach.', 'answer' => 'Two pointers; always take the smaller head; attach remainder; O(n+m) time.', 'keywords' => ['two', 'pointer', 'smaller', 'merge', 'list']],
                ['id' => 'c16', 'prompt' => 'How do you find the middle of a linked list?', 'answer' => 'Slow/fast pointers: fast moves 2, slow moves 1; when fast ends, slow is middle — O(n), O(1) space.', 'keywords' => ['slow', 'fast', 'pointer', 'middle', 'o(1)']],
                ['id' => 'c17', 'prompt' => 'What is topological sort and when is it used?', 'answer' => 'Order nodes so dependencies come first (course schedule, build order) — requires a DAG; DFS or Kahn’s algorithm.', 'keywords' => ['order', 'depend', 'dag', 'dfs', 'kahn']],
                ['id' => 'c18', 'prompt' => 'How does a min-heap help with top-K frequent elements?', 'answer' => 'Count with hash map, push into size-k min-heap by frequency — O(n log k).', 'keywords' => ['heap', 'count', 'frequency', 'o(n log', 'top']],
                ['id' => 'c19', 'prompt' => 'Valid palindrome: how do you check efficiently?', 'answer' => 'Two pointers from both ends, skip non-alnum, compare lowercased — O(n) time, O(1) space.', 'keywords' => ['two', 'pointer', 'alnum', 'lower', 'o(1)']],
                ['id' => 'c20', 'prompt' => 'What is the time complexity of quicksort average vs worst case?', 'answer' => 'Average O(n log n); worst O(n²) on bad pivots — mitigated by random/median-of-three pivot.', 'keywords' => ['o(n log', 'pivot', 'worst', 'average', 'random']],
            ],
            'debugging' => [
                ['id' => 'd1', 'prompt' => 'Production: white screen, no logs. What do you check first?', 'answer' => 'PHP/FPM error logs, display_errors/app debug, recent changes, disk full, fatal parse errors in new code.', 'keywords' => ['log', 'fatal', 'deploy', 'disk', 'display']],
                ['id' => 'd2', 'prompt' => 'Symptom: intermittent 500s under load only. Name two hypotheses.', 'answer' => 'DB connection pool exhaustion; race in session/cache; timeout on slow query under concurrency.', 'keywords' => ['timeout', 'pool', 'race', 'deadlock', 'concurr']],
                ['id' => 'd3', 'prompt' => 'A foreach mutates the array it iterates. What can go wrong and how do you fix it?', 'answer' => 'Skipping elements or undefined index; iterate a copy, collect keys first, or use array_filter-style rebuild.', 'keywords' => ['copy', 'keys', 'skip', 'index', 'rebuild']],
                ['id' => 'd4', 'prompt' => 'Undefined variable warnings flood logs. What are two root causes?', 'answer' => 'Missing null coalesce/default; conditional definition without else; typos; scopes that never assign.', 'keywords' => ['typo', 'conditional', 'default', 'scope', 'assign']],
                ['id' => 'd5', 'prompt' => 'How do you prove a bug is race-related?', 'answer' => 'Reproduce under concurrency/load, vary timing, look at interleaved logs, or run with deterministic scheduling/locks.', 'keywords' => ['concurr', 'timing', 'load', 'interleav', 'lock']],
                ['id' => 'd6', 'prompt' => 'Session works locally but users get logged out randomly. What do you check?', 'answer' => 'Session driver (cookie vs server), load balancer stickiness, cookie domain/secure/samesite, clock skew, GC lifetime.', 'keywords' => ['driver', 'cookie', 'sticky', 'domain', 'lifetime']],
                ['id' => 'd7', 'prompt' => 'A test passes locally and fails in CI only sometimes. Name two causes.', 'answer' => 'Order-dependent shared state; time/timezone; missing env vars; flaky external dependency.', 'keywords' => ['order', 'time', 'env', 'state', 'flaky']],
                ['id' => 'd8', 'prompt' => 'How do you debug a slow HTTP request end-to-end?', 'answer' => 'Add timing at middleware, DB, and external calls; check query logs and traces; compare against baseline.', 'keywords' => ['timing', 'trace', 'query', 'middleware', 'baseline']],
                ['id' => 'd9', 'prompt' => 'Memory usage climbs until fatal OOM. What do you inspect?', 'answer' => 'Leaked static arrays, growing caches, huge queries held in memory, unclosed resources, recursion without exit.', 'keywords' => ['leak', 'static', 'cache', 'huge', 'unclosed']],
                ['id' => 'd10', 'prompt' => 'CORS works in dev but fails in prod. What is a common cause?', 'answer' => 'Missing/wrong Access-Control headers on the API host, preflight OPTIONS not allowed, or cookie credentials mismatch.', 'keywords' => ['header', 'preflight', 'options', 'origin', 'credential']],
                ['id' => 'd11', 'prompt' => 'A Laravel page returns 500 with no visible message. First three checks.', 'answer' => 'storage/logs/laravel.log, APP_DEBUG in local only, recent deploy/commit, and php-fpm/nginx error log.', 'keywords' => ['log', 'debug', 'deploy', 'fpm', 'storage']],
                ['id' => 'd12', 'prompt' => 'How do you debug a failing queue job?', 'answer' => 'Check failed_jobs table, job payload, retries/backoff, worker logs, and reproduce with queue:work -v.', 'keywords' => ['failed', 'payload', 'retry', 'worker', 'queue']],
                ['id' => 'd13', 'prompt' => 'Session cookie not set in production — what do you check?', 'answer' => 'secure/samesite/domain flags, HTTPS termination, session driver, cookie path, and proxy trust settings.', 'keywords' => ['secure', 'samesite', 'https', 'driver', 'proxy']],
                ['id' => 'd14', 'prompt' => 'Eloquent returns empty collection though SQL works in client. Why?', 'answer' => 'Wrong connection/database, soft-deleted rows, global scope, timezone/date filter, or environment mismatch.', 'keywords' => ['scope', 'soft', 'connection', 'timezone', 'env']],
                ['id' => 'd15', 'prompt' => 'How do you reproduce a bug that only happens for one user?', 'answer' => 'Impersonate/seed their data, reproduce with their permissions and fixtures, freeze time, capture their request id.', 'keywords' => ['impersonat', 'fixture', 'permission', 'time', 'request']],
            ],
            'system_design' => [
                ['id' => 'y1', 'prompt' => 'Sketch a URL shortener: write path and read path concerns.', 'answer' => 'Write: unique short code generation + insert; Read: high QPS cache + redirect; consider base62 IDs and cache invalidation.', 'keywords' => ['cache', 'base62', 'redirect', 'unique', 'qps']],
                ['id' => 'y2', 'prompt' => 'What does eventual consistency mean for a cart service?', 'answer' => 'Reads may lag writes across replicas; UX must tolerate stale cart briefly or read-your-writes for the owner.', 'keywords' => ['replica', 'stale', 'lag', 'read-your', 'consisten']],
                ['id' => 'y3', 'prompt' => 'How would you design a rate limiter for a public API?', 'answer' => 'Token bucket or sliding window in Redis keyed by user/IP; fail closed on misconfig; return 429 with Retry-After.', 'keywords' => ['token', 'sliding', 'redis', '429', 'bucket']],
                ['id' => 'y4', 'prompt' => 'Design a notification fan-out: email + push for a new post. Sync or async?', 'answer' => 'Async queue workers per channel; enqueue on publish; retries with backoff; track delivery state separately.', 'keywords' => ['queue', 'async', 'worker', 'retry', 'fan']],
                ['id' => 'y5', 'prompt' => 'How do you store and retrieve a feed of recent items efficiently?', 'answer' => 'Write to a timeline table/cache; paginate by id cursor; avoid offset scans on large data; consider pull vs push fan-out.', 'keywords' => ['cursor', 'offset', 'timeline', 'paginate', 'fan']],
                ['id' => 'y6', 'prompt' => 'What are the options for file upload at scale?', 'answer' => 'Direct-to-object-storage with short-lived signed URLs, size/type limits, async processing, and CDN for delivery.', 'keywords' => ['signed', 'storage', 'cdn', 'limit', 'async']],
                ['id' => 'y7', 'prompt' => 'Sketch search for products: full-text vs external engine.', 'answer' => 'MySQL FULLTEXT/LIKE for small catalogs; Elasticsearch/Meilisearch for typo tolerance, facets, and ranking at scale.', 'keywords' => ['fulltext', 'elastic', 'facet', 'rank', 'typo']],
                ['id' => 'y8', 'prompt' => 'How do you handle multi-tenant data isolation?', 'answer' => 'Tenant id on every row or schema-per-tenant; always filter by tenant in queries; separate keys in cache.', 'keywords' => ['tenant', 'row', 'schema', 'filter', 'isolation']],
                ['id' => 'y9', 'prompt' => 'What is the role of a CDN for a PHP app?', 'answer' => 'Cache static assets and optionally HTML/JSON at the edge to cut latency and origin load; careful with auth cookies.', 'keywords' => ['edge', 'static', 'cache', 'origin', 'latency']],
                ['id' => 'y10', 'prompt' => 'When would you introduce a read replica?', 'answer' => 'When read load or analytical queries overwhelm the primary and slight replication lag is acceptable for those reads.', 'keywords' => ['replica', 'read', 'lag', 'primary', 'load']],
                ['id' => 'y11', 'prompt' => 'Design a chat/messenger backend at a high level: storage and delivery.', 'answer' => 'Message store (SQL/NoSQL) + fan-out via websockets/push; sequence ids; offline delivery; presence optional.', 'keywords' => ['store', 'websocket', 'fan', 'sequence', 'offline']],
                ['id' => 'y12', 'prompt' => 'How would you design a payment system safely?', 'answer' => 'Idempotency keys, ledger rows, provider webhooks with signatures, reconcile jobs, never store raw card data (use PSP).', 'keywords' => ['idempot', 'ledger', 'webhook', 'reconcile', 'psp']],
                ['id' => 'y13', 'prompt' => 'What is a unique ID generator strategy at scale?', 'answer' => 'Snowflake-style time+machine+seq, UUIDv7, or database sequences with ranges — avoid naive auto-increment across shards.', 'keywords' => ['snowflake', 'uuid', 'sequence', 'shard', 'time']],
                ['id' => 'y14', 'prompt' => 'Sketch an e-commerce checkout: cart, inventory, order.', 'answer' => 'Cart service, reserve stock transactionally, create order + payment intent, async confirmation email/receipt.', 'keywords' => ['cart', 'stock', 'order', 'payment', 'reserve']],
                ['id' => 'y15', 'prompt' => 'How do you handle file storage and delivery at scale?', 'answer' => 'Object storage (S3) with signed upload URLs, async processing, CDN for reads, lifecycle policies.', 'keywords' => ['s3', 'signed', 'cdn', 'async', 'lifecycle']],
                ['id' => 'y16', 'prompt' => 'What is the role of a message queue in a web app?', 'answer' => 'Decouple producers from consumers, buffer spikes, enable retries and parallel workers for slow work.', 'keywords' => ['decouple', 'buffer', 'retry', 'worker', 'async']],
                ['id' => 'y17', 'prompt' => 'How would you design a rate limiter for a multi-tenant API?', 'answer' => 'Per tenant/API key counters in Redis (token bucket), config per plan, fail closed, expose remaining quota headers.', 'keywords' => ['tenant', 'redis', 'token', 'quota', 'header']],
                ['id' => 'y18', 'prompt' => 'When is eventual consistency acceptable?', 'answer' => 'Feeds, counters, analytics, geo-replicated reads where UX tolerates lag; not for ledger balance displays.', 'keywords' => ['lag', 'feed', 'counter', 'ledger', 'ux']],
            ],
            'explain' => [
                ['id' => 'e1', 'prompt' => 'Explain reference counting in PHP to a junior developer using an analogy.', 'answer' => 'Each value has a counter of references; when count hits zero the value is freed; cycles need the GC.', 'keywords' => ['count', 'free', 'zero', 'cycle', 'gc']],
                ['id' => 'e2', 'prompt' => 'Explain middleware in one paragraph.', 'answer' => 'Layers that wrap a request/response to cross-cut concerns (auth, CSRF, logging) before/after the core handler.', 'keywords' => ['request', 'response', 'cross', 'auth', 'before']],
                ['id' => 'e3', 'prompt' => 'Explain what an ORM buys you and what it costs.', 'answer' => 'Buys: expressive queries, portability, relations. Costs: N+1 risk, hidden SQL, harder complex joins and tuning.', 'keywords' => ['relation', 'sql', 'n+1', 'express', 'hidden']],
                ['id' => 'e4', 'prompt' => 'Explain REST to a colleague who knows only forms.', 'answer' => 'Resources as URLs; verbs are HTTP methods (GET/POST/PUT/DELETE); stateless; standard status codes; JSON bodies common.', 'keywords' => ['resource', 'verb', 'method', 'status', 'stateless']],
                ['id' => 'e5', 'prompt' => 'What is a race condition? Give a non-web analogy.', 'answer' => 'Outcome depends on timing of interleaved steps. Analogy: two people editing one whiteboard without locking.', 'keywords' => ['timing', 'interleav', 'lock', 'order', 'concurr']],
                ['id' => 'e6', 'prompt' => 'Explain why we cache and the hardest part of caching.', 'answer' => 'To avoid repeated expensive work. The hard part is invalidation: knowing when cached data is stale.', 'keywords' => ['expensive', 'invalidat', 'stale', 'repeated', 'ttl']],
                ['id' => 'e7', 'prompt' => 'Explain SOLID in one sentence each (acronym is fine).', 'answer' => 'SRP: one reason to change. OCP: open for extension, closed for modification. LSP: subtypes substitutable. ISP: small interfaces. DIP: depend on abstractions.', 'keywords' => ['single', 'open', 'liskov', 'interface', 'depend']],
                ['id' => 'e8', 'prompt' => 'Explain the difference between authentication and authorization simply.', 'answer' => 'Authn proves who you are; authz decides what you may do. Login vs permission check.', 'keywords' => ['identity', 'who', 'permission', 'access', 'prove']],
                ['id' => 'e9', 'prompt' => 'What does it mean that HTTP is stateless?', 'answer' => 'Each request is independent; the server does not remember prior requests unless you send cookies/session ids.', 'keywords' => ['independent', 'cookie', 'session', 'remember', 'request']],
                ['id' => 'e10', 'prompt' => 'Explain opcache in plain language.', 'answer' => 'It stores compiled opcodes in shared memory so PHP skips re-parsing/re-compiling scripts on every request.', 'keywords' => ['opcode', 'memory', 'compile', 'skip', 'shared']],
                ['id' => 'e11', 'prompt' => 'Explain the difference between a stack and a heap in simple terms.', 'answer' => 'Stack holds local variables and call frames with automatic lifetime; heap holds objects/arrays with reference-counted lifetime.', 'keywords' => ['local', 'frame', 'object', 'array', 'reference']],
                ['id' => 'e12', 'prompt' => 'What is an idempotent operation? Give an HTTP example.', 'answer' => 'Doing it multiple times has the same effect as once; GET and PUT are idempotent, POST is not by default.', 'keywords' => ['same', 'effect', 'once', 'get', 'put']],
                ['id' => 'e13', 'prompt' => 'Explain what a design pattern is and when NOT to use one.', 'answer' => 'A reusable solution template to a common design problem; skip patterns when a simple function/if is clearer and there is only one case.', 'keywords' => ['reusable', 'solution', 'simple', 'overkill', 'yagni']],
                ['id' => 'e14', 'prompt' => 'What is the difference between authentication and session management?', 'answer' => 'Authentication verifies identity at login; session management tracks the authenticated identity across subsequent requests.', 'keywords' => ['identity', 'login', 'track', 'request', 'cookie']],
                ['id' => 'e15', 'prompt' => 'Explain what ACID means for a database transaction.', 'answer' => 'Atomicity all-or-nothing; Consistency valid states; Isolation concurrent txns do not interfere; Durability committed data survives crashes.', 'keywords' => ['atomic', 'consist', 'isolat', 'durab', 'transaction']],
            ],
            'laravel' => [
                ['id' => 'l1', 'prompt' => 'What is the Laravel service container and why does it exist?', 'answer' => 'An IoC/DI container that resolves and injects class dependencies so code is testable and loosely coupled.', 'keywords' => ['container', 'inject', 'depend', 'resolve', 'ioc']],
                ['id' => 'l2', 'prompt' => 'Difference between bind() and singleton() in the container?', 'answer' => 'bind() creates a new instance on each resolve; singleton() shares one instance for the app lifetime.', 'keywords' => ['new', 'each', 'shared', 'instance', 'resolve']],
                ['id' => 'l3', 'prompt' => 'What does Eloquent ORM give you over raw SQL?', 'answer' => 'Active-record models, relationships, casts, scopes, events, and readable query builder — at the cost of N+1 risk and hidden SQL.', 'keywords' => ['model', 'relation', 'query', 'active', 'n+1']],
                ['id' => 'l4', 'prompt' => 'How do you prevent N+1 queries in Eloquent?', 'answer' => 'Eager load relations with with() or load(), or use a join/select aggregate when appropriate.', 'keywords' => ['eager', 'with(', 'load', 'join', 'n+1']],
                ['id' => 'l5', 'prompt' => 'What is the difference between middleware and route middleware?', 'answer' => 'Middleware is a class that filters requests; route middleware is which middleware stack is attached to a specific route/group.', 'keywords' => ['filter', 'request', 'route', 'stack', 'attach']],
                ['id' => 'l6', 'prompt' => 'When would you dispatch a job to the queue?', 'answer' => 'For slow, retryable, or deferrable work (emails, exports, webhooks) so the HTTP request stays fast.', 'keywords' => ['slow', 'retry', 'email', 'background', 'defer']],
                ['id' => 'l7', 'prompt' => 'What is the difference between events and listeners in Laravel?', 'answer' => 'An event is a fact that happened; a listener reacts to it — decouples producers from side effects.', 'keywords' => ['event', 'listen', 'decoupl', 'react', 'side']],
                ['id' => 'l8', 'prompt' => 'How does Laravel validation work on a form request?', 'answer' => 'FormRequest classes define rules; Laravel validates before controller logic and redirects back with errors on failure.', 'keywords' => ['formrequest', 'rules', 'valid', 'redirect', 'error']],
                ['id' => 'l9', 'prompt' => 'What is a Laravel policy and when is it used?', 'answer' => 'A class that authorizes actions on a model for the current user (view/update/delete) — keeps authz out of controllers.', 'keywords' => ['authoriz', 'policy', 'user', 'action', 'model']],
                ['id' => 'l10', 'prompt' => 'Difference between seeders and factories?', 'answer' => 'Factories generate fake model data for tests; seeders insert known/demo data into the database.', 'keywords' => ['factory', 'seed', 'fake', 'test', 'demo']],
                ['id' => 'l11', 'prompt' => 'What does the service provider boot() vs register() do?', 'answer' => 'register() binds services into the container; boot() runs after all providers are registered (routes, events, views).', 'keywords' => ['register', 'boot', 'bind', 'container', 'provider']],
                ['id' => 'l12', 'prompt' => 'How do migrations help in team development?', 'answer' => 'They version schema changes so every dev/CI shares the same structure without hand-edited SQL dumps.', 'keywords' => ['schema', 'version', 'structure', 'team', 'sql']],
                ['id' => 'l13', 'prompt' => 'What is the difference between update() and save() on an Eloquent model?', 'answer' => 'update($attrs) sets attributes and saves in one call; save() persists changes already made on the model instance.', 'keywords' => ['attribute', 'save', 'persist', 'instance', 'change']],
                ['id' => 'l14', 'prompt' => 'When would you use a database transaction in Laravel?', 'answer' => 'When multiple writes must succeed or fail together (transfer money, create order + items).', 'keywords' => ['atomic', 'multi', 'write', 'rollback', 'commit']],
                ['id' => 'l15', 'prompt' => 'What is route model binding?', 'answer' => 'Laravel resolves route parameters to Eloquent models automatically (by id or slug) and 404s if missing.', 'keywords' => ['route', 'model', 'resolve', 'parameter', '404']],
                ['id' => 'l16', 'prompt' => 'How do you protect a Laravel app from mass assignment vulnerabilities?', 'answer' => 'Use $fillable/$guarded on models and validated $request->validated() — never $request->all() blindly into create().', 'keywords' => ['fillable', 'guarded', 'valid', 'mass', 'assign']],
                ['id' => 'l17', 'prompt' => 'What is the difference between cache remember and cache get/put?', 'answer' => 'remember() gets or computes and stores in one call; get/put is manual two-step with explicit TTL.', 'keywords' => ['remember', 'get', 'put', 'ttl', 'cache']],
                ['id' => 'l18', 'prompt' => 'Explain the Laravel request lifecycle in broad strokes.', 'answer' => 'Public index boots app, middleware runs, route resolves to controller, response returned, middleware post-processing, terminate.', 'keywords' => ['boot', 'middleware', 'route', 'controller', 'response']],
                ['id' => 'l19', 'prompt' => 'What is the difference between artisan make:model and a migration?', 'answer' => 'make:model creates the Eloquent class (optionally with migration/factory/seeder); the migration file defines the table schema.', 'keywords' => ['model', 'migration', 'schema', 'eloquent', 'artisan']],
                ['id' => 'l20', 'prompt' => 'How do you test a Livewire component in Pest?', 'answer' => 'Livewire::test(Component::class)->set(...)->call(...)->assertSee/assertSet — drives the component server-side.', 'keywords' => ['livewire', 'test', 'call', 'assert', 'pest']],
            ],
            'design_patterns' => [
                ['id' => 'g1', 'prompt' => 'What is the Factory Method pattern and when would you use it in Laravel?', 'answer' => 'Factory Method lets a subclass or factory decide which concrete class to instantiate. In Laravel you would use it when creating different payment gateways or notifiers per environment behind one interface.', 'keywords' => ['factory', 'concrete', 'subclass', 'interface', 'create']],
                ['id' => 'g2', 'prompt' => 'Explain the difference between Factory Method and Abstract Factory.', 'answer' => 'Factory Method creates one product via an overridden method; Abstract Factory creates a family of related objects without specifying concrete classes.', 'keywords' => ['family', 'related', 'one product', 'concrete', 'family of']],
                ['id' => 'g3', 'prompt' => 'What problem does the Builder pattern solve?', 'answer' => 'It constructs complex objects step by step when the constructor would otherwise need many optional parameters or telescoping constructors.', 'keywords' => ['step', 'complex', 'optional', 'constructor', 'assemble']],
                ['id' => 'g4', 'prompt' => 'When is Prototype useful and what is its main risk?', 'answer' => 'Prototype clones a configured template to produce new objects cheaply. The risk is shared mutable state if the clone is shallow.', 'keywords' => ['clone', 'template', 'shallow', 'mutable', 'copy']],
                ['id' => 'g5', 'prompt' => 'Define the Singleton pattern and one serious drawback.', 'answer' => 'Singleton ensures a class has only one instance with a global access point. Drawback: hidden global state makes tests and reasoning about lifecycles harder.', 'keywords' => ['one instance', 'global', 'static', 'test', 'state']],
                ['id' => 'g6', 'prompt' => 'What is the Adapter pattern? Give a PHP example.', 'answer' => 'Adapter converts one interface into another clients expect. Example: wrapping a legacy payment SDK so it matches your PaymentGateway interface.', 'keywords' => ['interface', 'wrap', 'convert', 'legacy', 'compatib']],
                ['id' => 'g7', 'prompt' => 'Explain the Decorator pattern with a real example.', 'answer' => 'Decorator wraps an object to add behavior while keeping the same interface. Example: stacking logging, caching, and retry around a raw HTTP client.', 'keywords' => ['wrap', 'same interface', 'add behavior', 'stack', 'delegate']],
                ['id' => 'g8', 'prompt' => 'How does the Facade pattern differ from Adapter?', 'answer' => 'Facade simplifies a complex subsystem with a single easy entry point; Adapter changes an existing interface so it fits a client that expects another.', 'keywords' => ['simplify', 'subsystem', 'easy', 'change interface', 'entry']],
                ['id' => 'g9', 'prompt' => 'What is the Proxy pattern and name three uses.', 'answer' => 'Proxy stands in for another object with the same interface. Uses: lazy loading, access control, remote proxy, logging, caching.', 'keywords' => ['stand', 'same interface', 'lazy', 'access', 'remote']],
                ['id' => 'g10', 'prompt' => 'Define the Strategy pattern.', 'answer' => 'Strategy defines a family of interchangeable algorithms and lets the client pick one at runtime, usually via a shared interface.', 'keywords' => ['algorithm', 'interchange', 'interface', 'runtime', 'swap']],
                ['id' => 'g11', 'prompt' => 'Explain the Observer pattern and its trade-off.', 'answer' => 'Observer notifies a list of dependents when the subject changes, loosening coupling. Trade-off: order and debugging become harder as listeners multiply.', 'keywords' => ['notify', 'dependents', 'decoupl', 'listener', 'event']],
                ['id' => 'g12', 'prompt' => 'When should you choose Command over a direct method call?', 'answer' => 'When you need to queue, log, undo, or parameterize operations as objects — for example Laravel queued jobs or form requests.', 'keywords' => ['queue', 'undo', 'encapsulat', 'object', 'parameter']],
                ['id' => 'g13', 'prompt' => 'What is the Template Method pattern?', 'answer' => 'Template Method defines the skeleton of an algorithm in a base class and lets subclasses override specific steps without changing the structure.', 'keywords' => ['skeleton', 'base class', 'override', 'steps', 'algorithm']],
                ['id' => 'g14', 'prompt' => 'Explain the Iterator pattern in modern PHP.', 'answer' => 'Iterator gives a standard way to traverse a collection without exposing its internals. PHP ships with Iterator, IteratorAggregate, and the foreach protocol.', 'keywords' => ['traverse', 'collection', 'foreach', 'interface', 'hide']],
                ['id' => 'g15', 'prompt' => 'What is the State pattern and how does it differ from Strategy?', 'answer' => 'State lets an object change behavior when its internal state changes, often by swapping the state object itself. Strategy is chosen externally by the client and usually does not represent a lifecycle.', 'keywords' => ['internal state', 'change behavior', 'swap', 'external', 'lifecycle']],
                ['id' => 'g16', 'prompt' => 'Define the Chain of Responsibility pattern.', 'answer' => 'It passes a request along a chain of handlers until one handles it, decoupling sender from the concrete receiver. Example: middleware pipeline.', 'keywords' => ['chain', 'handlers', 'pass', 'decoupl', 'middleware']],
                ['id' => 'g17', 'prompt' => 'What is the Null Object pattern and when is it better than null checks?', 'answer' => 'Null Object is a do-nothing collaborator that replaces null. It is better when callers should always call methods without conditionals, such as an optional logger.', 'keywords' => ['no-op', 'null', 'optional', 'logger', 'conditional']],
                ['id' => 'g18', 'prompt' => 'When should you NOT use a design pattern?', 'answer' => 'When a plain function or a single if is clearer, when there is only one concrete case, or when YAGNI says the flexibility will never be used.', 'keywords' => ['yagni', 'simple', 'overkill', 'one case', 'plain']],
                ['id' => 'g19', 'prompt' => 'Map Laravel middleware to a GoF pattern.', 'answer' => 'Middleware is aDecorator (and the pipeline is Chain of Responsibility): each layer wraps the next handler and can add behavior before and after.', 'keywords' => ['decorator', 'chain', 'wrap', 'pipeline', 'handler']],
                ['id' => 'g20', 'prompt' => 'What is the difference between composition and inheritance as a design tool?', 'answer' => 'Composition builds objects from collaborating parts and is easier to change at runtime; inheritance couples subclasses to a fixed base hierarchy.', 'keywords' => ['composition', 'has-a', 'inheritance', 'is-a', 'coupl']],
            ],
            'sql' => [
                ['id' => 'q1', 'prompt' => 'What is the difference between INNER JOIN and LEFT JOIN?', 'answer' => 'INNER returns only matching rows from both tables; LEFT returns all left rows plus matches (NULL if no match).', 'keywords' => ['inner', 'left', 'match', 'null', 'rows']],
                ['id' => 'q2', 'prompt' => 'When should you add an index on a column?', 'answer' => 'On columns used often in WHERE, JOIN, ORDER BY with high selectivity — indexes speed reads but slow writes and use space.', 'keywords' => ['where', 'join', 'order', 'select', 'speed']],
                ['id' => 'q3', 'prompt' => 'What does EXPLAIN tell you about a query?', 'answer' => 'The execution plan: which indexes, join order, rows scanned, and whether it does full table scans or filesort.', 'keywords' => ['plan', 'index', 'scan', 'join', 'rows']],
                ['id' => 'q4', 'prompt' => 'Explain ACID in the context of SQL transactions.', 'answer' => 'Atomicity all-or-nothing; Consistency constraints hold; Isolation concurrent txns; Durability survives crash after commit.', 'keywords' => ['atomic', 'consist', 'isolat', 'durab', 'commit']],
                ['id' => 'q5', 'prompt' => 'What is the difference between DELETE and TRUNCATE?', 'answer' => 'DELETE removes rows one by one (can be filtered, logged, rolled back); TRUNCATE clears the table fast with minimal logging.', 'keywords' => ['delete', 'truncate', 'filter', 'log', 'fast']],
                ['id' => 'q6', 'prompt' => 'What is a primary key vs a unique key?', 'answer' => 'Primary key uniquely identifies rows and is the clustered index (one per table); unique key allows one NULL and is non-clustered.', 'keywords' => ['unique', 'primary', 'null', 'cluster', 'identify']],
                ['id' => 'q7', 'prompt' => 'How do you find duplicate emails in a users table?', 'answer' => 'SELECT email FROM users GROUP BY email HAVING COUNT(*) > 1;', 'keywords' => ['group', 'having', 'count', 'duplicate', 'select']],
                ['id' => 'q8', 'prompt' => 'What is a SQL injection and how do you prevent it?', 'answer' => 'Injecting SQL via user input; prevent with parameterized/prepared statements — never concatenate user data into SQL.', 'keywords' => ['inject', 'prepared', 'parameter', 'concat', 'input']],
                ['id' => 'q9', 'prompt' => 'When is a covering index useful?', 'answer' => 'When all columns needed for a query are in the index so the engine can answer without touching the table rows.', 'keywords' => ['cover', 'index', 'column', 'table', 'scan']],
                ['id' => 'q10', 'prompt' => 'What is the difference between WHERE and HAVING?', 'answer' => 'WHERE filters rows before grouping; HAVING filters groups after GROUP BY aggregation.', 'keywords' => ['where', 'having', 'group', 'filter', 'aggregat']],
                ['id' => 'q11', 'prompt' => 'What does a LEFT JOIN with NULL on the right side mean?', 'answer' => 'There was no matching row in the right table for that left row.', 'keywords' => ['null', 'no match', 'right', 'left', 'missing']],
                ['id' => 'q12', 'prompt' => 'How do you paginate a large result set efficiently?', 'answer' => 'Prefer keyset/cursor pagination on an indexed id over deep OFFSET, which still scans skipped rows.', 'keywords' => ['cursor', 'offset', 'keyset', 'index', 'page']],
                ['id' => 'q13', 'prompt' => 'What is normalization (1NF/2NF/3NF) in one sentence each?', 'answer' => '1NF atomic values no repeating groups; 2NF no partial dependency on part of composite key; 3NF no transitive dependency on non-key.', 'keywords' => ['atomic', 'dependency', 'key', 'transitive', 'partial']],
                ['id' => 'q14', 'prompt' => 'When would denormalization be acceptable?', 'answer' => 'Read-heavy dashboards or caching tables where you intentionally accept redundancy for query speed.', 'keywords' => ['read', 'redundan', 'speed', 'cache', 'dashboard']],
                ['id' => 'q15', 'prompt' => 'What is a deadlock in MySQL and how do you avoid it?', 'answer' => 'Two transactions wait on each others locks; avoid with consistent lock order, short transactions, and retries.', 'keywords' => ['lock', 'wait', 'order', 'short', 'retry']],
                ['id' => 'q16', 'prompt' => 'What is the difference between UNION and UNION ALL?', 'answer' => 'UNION removes duplicate rows; UNION ALL keeps all rows and is faster because it skips the dedup step.', 'keywords' => ['duplicate', 'all', 'dedup', 'faster', 'combine']],
                ['id' => 'q17', 'prompt' => 'How do you count rows per category efficiently?', 'answer' => 'SELECT category, COUNT(*) FROM t GROUP BY category; ensure an index on category if the table is large.', 'keywords' => ['count', 'group', 'category', 'index', 'aggregate']],
                ['id' => 'q18', 'prompt' => 'What is an N+1 problem at the SQL level?', 'answer' => 'One query for a list then one query per row for a relation — fix with JOIN/eager loading or a single IN query.', 'keywords' => ['one', 'per row', 'join', 'eager', 'in(']],
            ],
        ];
    }

    /**
     * @param  list<string>  $keywords
     * @return array{score: int, passed: bool, notes: string}
     */
    private function evaluateAnswer(string $ideal, string $given, array $keywords): array
    {
        $lower = mb_strtolower($given);
        $hits = 0;

        foreach ($keywords as $keyword) {
            if (str_contains($lower, mb_strtolower($keyword))) {
                $hits++;
            }
        }

        $ratio = $keywords ? $hits / count($keywords) : 0.5;
        $score = (int) max(0, min(100, round($ratio * 100)));

        if (mb_strlen($given) < 40) {
            $score = min($score, 50);
        }

        return [
            'score' => $score,
            'passed' => $score >= 70,
            'notes' => $score >= 70
                ? 'Solid — hits key concepts.'
                : 'Missing concepts. Ideal answer touches: '.implode(', ', array_slice($keywords, 0, 5)).'. Ideal: '.$ideal,
        ];
    }

    public function render()
    {
        $questions = $this->session?->questions ?? [];
        $current = $questions[$this->currentIndex] ?? null;

        return $this->view([
            'current' => $current,
            'total' => count($questions),
        ])->layout('layouts::app', ['title' => __('Interviews')]);
    }
};
?>
<div class="flex flex-1 flex-col gap-4">
    <div>
        <flux:heading size="xl" level="1">{{ __('Interview Mode') }}</flux:heading>
        <flux:subheading>{{ __('Beginner · Intermediate · Senior · Coding · Debugging · System design · Explain · Laravel · SQL · Design patterns') }}</flux:subheading>
    </div>

    @if (! $session)
        <flux:card class="flex flex-col gap-3">
            <flux:heading level="3">{{ __('Choose a track') }}</flux:heading>
            <div class="flex flex-wrap gap-2">
                @foreach (['beginner', 'intermediate', 'senior', 'coding', 'debugging', 'system_design', 'explain', 'laravel', 'sql', 'design_patterns'] as $track)
                    <flux:button
                        variant="{{ $level === $track ? 'primary' : 'ghost' }}"
                        wire:click="$set('level', '{{ $track }}')"
                    >
                        {{ str_replace('_', ' ', $track) }}
                    </flux:button>
                @endforeach
            </div>
            <div>
                <flux:button variant="primary" wire:click="start" data-test="start-interview">
                    {{ __('Start session') }}
                </flux:button>
            </div>
        </flux:card>
    @elseif ($session->status === 'in_progress' && $current)
        <flux:card class="flex flex-col gap-4">
            <div class="flex items-center justify-between">
                <flux:badge>{{ $session->level }}</flux:badge>
                <flux:text class="text-zinc-500">Question {{ $currentIndex + 1 }} / {{ $total }}</flux:text>
            </div>

            <p class="text-base font-medium">{{ $current['prompt'] }}</p>

            <textarea
                wire:model="answer"
                rows="6"
                class="w-full rounded border border-zinc-700 bg-zinc-950 p-3 text-sm"
                placeholder="{{ __('Answer in your own words (min 10 characters)…') }}"
            ></textarea>
            @error('answer') <span class="text-sm text-red-400">{{ $message }}</span> @enderror

            <div>
                <flux:button variant="primary" wire:click="submitAnswer" data-test="submit-interview-answer">
                    {{ __('Submit answer') }}
                </flux:button>
            </div>

            @if ($evaluations !== [])
                <div class="text-sm text-zinc-400">
                    Last score: {{ $evaluations[array_key_last($evaluations)]['score'] }}%
                </div>
            @endif
        </flux:card>
    @else
        <flux:card class="flex flex-col gap-3">
            <flux:heading level="3">{{ __('Session complete') }}</flux:heading>
            <flux:heading size="lg">Score: {{ $session->score }}%</flux:heading>

            <ul class="flex flex-col gap-2 text-sm">
                @foreach ($evaluations as $i => $row)
                    <li>
                        <strong>Q{{ $i + 1 }}:</strong> {{ $row['score'] }}% —
                        <span class="{{ $row['passed'] ? 'text-green-500' : 'text-amber-500' }}">{{ $row['notes'] }}</span>
                    </li>
                @endforeach
            </ul>

            <div class="flex gap-2">
                <flux:button variant="primary" wire:click="newSession">{{ __('New session') }}</flux:button>
            </div>
        </flux:card>
    @endif
</div>
