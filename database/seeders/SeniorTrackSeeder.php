<?php

namespace Database\Seeders;

use App\Models\CodeReviewDrill;
use App\Models\DesignCase;
use App\Models\Incident;
use App\Models\InternalsTopic;
use App\Models\InterviewSimulation;
use App\Models\StarStory;
use App\Models\User;
use Illuminate\Database\Seeder;

class SeniorTrackSeeder extends Seeder
{
    /**
     * Senior interview track: design cases, incidents, code reviews,
     * internals topics, STAR prompts, and interview simulations.
     */
    public function run(): void
    {
        $this->seedDesignCases();
        $this->seedIncidents();
        $this->seedReviewDrills();
        $this->seedInternalsTopics();
        $this->seedStarPrompts();
        $this->seedSimulations();
    }

    private function seedDesignCases(): void
    {
        foreach ($this->designCaseBlueprints() as $i => $blueprint) {
            DesignCase::query()->updateOrCreate(
                ['slug' => $blueprint['slug']],
                array_merge($blueprint, ['order_column' => $i, 'is_published' => true])
            );
        }
    }

    private function seedIncidents(): void
    {
        foreach ($this->incidentBlueprints() as $blueprint) {
            Incident::query()->updateOrCreate(
                ['slug' => $blueprint['slug']],
                array_merge($blueprint, ['is_published' => true])
            );
        }
    }

    private function seedReviewDrills(): void
    {
        foreach ($this->reviewBlueprints() as $blueprint) {
            CodeReviewDrill::query()->updateOrCreate(
                ['slug' => $blueprint['slug']],
                array_merge($blueprint, ['is_published' => true])
            );
        }
    }

    private function seedInternalsTopics(): void
    {
        foreach ($this->internalsBlueprints() as $i => $blueprint) {
            InternalsTopic::query()->updateOrCreate(
                ['slug' => $blueprint['slug']],
                array_merge($blueprint, ['order_column' => $i, 'is_published' => true])
            );
        }
    }

    private function seedStarPrompts(): void
    {
        $admin = User::query()->where('role', 'admin')->first();
        if (! $admin) {
            return;
        }

        foreach ($this->starPromptBlueprints() as $blueprint) {
            StarStory::query()->firstOrCreate(
                [
                    'user_id' => $admin->id,
                    'prompt_slug' => $blueprint['slug'],
                    'title' => $blueprint['title'],
                ],
                [
                    'status' => 'draft',
                    'tags' => $blueprint['tags'],
                ]
            );
        }
    }

    private function seedSimulations(): void
    {
        foreach ($this->simulationBlueprints() as $blueprint) {
            InterviewSimulation::query()->updateOrCreate(
                ['slug' => $blueprint['slug']],
                array_merge($blueprint, ['is_published' => true])
            );
        }
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function designCaseBlueprints(): array
    {
        return [
            [
                'slug' => 'url-shortener',
                'title' => 'URL Shortener',
                'level' => 'senior',
                'track' => 'product',
                'prompt' => 'Design a URL shortener serving 100M redirects/day. Users create short links; redirects must be p99 < 20ms. Support custom slugs, expiry, and basic click analytics.',
                'constraints' => ['read-heavy ~99:1 read:write', 'global traffic', 'expired links 404 cleanly', 'no auth required to redirect'],
                'rubric' => [
                    ['dimension' => 'Requirements clarity', 'weight' => 15, 'bar' => 'Names functional + non-functional + scale numbers'],
                    ['dimension' => 'API design', 'weight' => 15, 'bar' => 'POST /shorten, GET /{code}, analytics endpoints'],
                    ['dimension' => 'Data model', 'weight' => 20, 'bar' => 'Link table + indexes; base62/counter vs hash trade-off'],
                    ['dimension' => 'Bottlenecks', 'weight' => 20, 'bar' => 'Hot keys, cache, DB single region vs anycast'],
                    ['dimension' => 'Failure modes', 'weight' => 15, 'bar' => 'Cache miss storm, collision, DC loss'],
                    ['dimension' => 'Trade-offs', 'weight' => 15, 'bar' => 'When NOT to cache; analytics async path'],
                ],
                'model_answer_html' => '<h3>Sketch</h3><p>API: <code>POST /api/links</code> returns code; <code>GET /{code}</code> 301/302 to target. Store <code>links(id, code, target, owner_id, expires_at, created_at)</code> with unique index on <code>code</code>.</p><h3>Code generation</h3><p>Prefer auto-increment id + base62 encode (no collision checks) over pure hash of URL (needs uniqueness retry). Custom slugs: separate unique constraint.</p><h3>Read path</h3><p>Redis GET code → target; miss → DB by unique code → populate cache (TTL until expiry). Redirect from app edge; do not follow target in server.</p><h3>Analytics</h3><p>Fire-and-forget queue event (click, geo, referrer); batch to time-series or nightly rollup. Never write-click on the redirect hot path.</p><h3>Failure modes</h3><p>Cache down: serve from DB with short TTL; stampede: singleflight/lock on miss; multi-region: replicate hot set or route by region with async global sync.</p><h3>When not to overbuild</h3><p>At 10k/day a single MySQL + Redis is enough; do not start with custom distributed ID service day one.</p>',
                'follow_ups' => ['Region failover for redirects?', 'How do you prevent open-redirect abuse?', 'What if custom slug is trademarked?'],
                'est_minutes' => 45,
            ],
            [
                'slug' => 'multi-tenant-saas',
                'title' => 'Multi-Tenant SaaS Isolation',
                'level' => 'senior',
                'track' => 'product',
                'prompt' => 'Design tenancy for a B2B SaaS with 5k workspaces. Needs strong data isolation, per-tenant config, noisy-neighbor protection, and a path to enterprise single-tenant deploy.',
                'constraints' => ['shared cluster default', 'enterprise wants dedicated', 'GDPR delete tenant', 'no cross-tenant reads ever'],
                'rubric' => [
                    ['dimension' => 'Tenancy model', 'weight' => 25, 'bar' => 'Shared DB + tenant_id vs schema vs DB — when each'],
                    ['dimension' => 'Security', 'weight' => 20, 'bar' => 'Global scopes, middleware, keys, audit'],
                    ['dimension' => 'Noisy neighbor', 'weight' => 15, 'bar' => 'Quotas, pool limits, per-tenant rate limits'],
                    ['dimension' => 'Config & features', 'weight' => 15, 'bar' => 'tenant settings, feature flags, branding'],
                    ['dimension' => 'Migration path', 'weight' => 15, 'bar' => 'Promote to dedicated without rewrite'],
                    ['dimension' => 'Trade-offs', 'weight' => 10, 'bar' => 'Why not row-level security only / why not schema-per-tenant early'],
                ],
                'model_answer_html' => '<p><strong>Start shared DB + <code>tenant_id</code> everywhere</strong> with forced global scope + policy checks. Cheap until compliance forces more.</p><p>Schema-per-tenant for mid-tier regulated; DB-per-tenant for enterprise export — design a <code>TenantConnection</code> resolver so app code does not care.</p><p>Noisy neighbors: per-tenant queue workers / rate limits / statement timeouts; move heavy tenants to dedicated pool.</p><p>Enterprise: same app image, different connection config + optional schema — avoid forking the codebase.</p>',
                'follow_ups' => ['How do backfills avoid cross-tenant locks?', 'Where do you store tenant encryption keys?'],
                'est_minutes' => 45,
            ],
            [
                'slug' => 'payment-checkout-idempotency',
                'title' => 'Payment Checkout with Retries',
                'level' => 'senior',
                'track' => 'product',
                'prompt' => 'Design checkout: cart → order → payment intent → webhook from PSP. Must be idempotent under client retries and PSP webhook redelivery. Support partial refunds and out-of-order events.',
                'constraints' => ['money must balance', 'PSP webhooks at-least-once', 'client can double-click Pay', 'audit trail required'],
                'rubric' => [
                    ['dimension' => 'Idempotency keys', 'weight' => 25, 'bar' => 'Client key on create + webhook event id unique'],
                    ['dimension' => 'State machine', 'weight' => 20, 'bar' => 'Order/payment states + legal transitions only'],
                    ['dimension' => 'Consistency', 'weight' => 20, 'bar' => 'Outbox / transactional write vs external call'],
                    ['dimension' => 'Out-of-order', 'weight' => 15, 'bar' => 'Event timestamps / ignore stale transitions'],
                    ['dimension' => 'Refunds & audit', 'weight' => 10, 'bar' => 'Append-only ledger'],
                    ['dimension' => 'Trade-offs', 'weight' => 10, 'bar' => 'Sync PSP call vs async confirm UX'],
                ],
                'model_answer_html' => '<p>POST /checkout with <code>Idempotency-Key</code>; unique column stores response.</p><p>DB row order + payment row; PSP call after commit via outbox worker (or careful two-phase: create pending → call PSP → update; reconcile on timeout).</p><p>Webhooks: <code>unique(event_id)</code>, apply only if <code>event_time</code> newer than current status. Never trust amount from client — amount from order snapshot.</p><p>Ledger entries for charge/refund; never UPDATE balance blindly.</p>',
                'follow_ups' => ['PSP down mid-checkout?', 'How do you prove totals in an audit?'],
                'est_minutes' => 45,
            ],
            [
                'slug' => 'notification-fanout',
                'title' => 'Notification Fan-out',
                'level' => 'senior',
                'track' => 'infra',
                'prompt' => 'Design notifications: in-app, email, push, webhooks. 50k publishes/sec peak. Users follow topics and each other. Must support digests, quiet hours, and per-channel prefs.',
                'constraints' => ['exactly-once in-app preferred', 'email at-least-once OK', 'prefs checked at send', 'webhooks signed'],
                'rubric' => [
                    ['dimension' => 'Fan-out model', 'weight' => 25, 'bar' => 'Push timeline vs pull inbox trade-off'],
                    ['dimension' => 'Queueing', 'weight' => 20, 'bar' => 'Per-channel workers, retries, DLQ'],
                    ['dimension' => 'Prefs & quiet hours', 'weight' => 15, 'bar' => 'Eval at enqueue vs at send'],
                    ['dimension' => 'Delivery guarantees', 'weight' => 20, 'bar' => 'Idempotency keys per user+event'],
                    ['dimension' => 'Scale tactics', 'weight' => 10, 'bar' => 'Batching, sampling, celebrity problem'],
                    ['dimension' => 'Trade-offs', 'weight' => 10, 'bar' => 'Digest vs immediate; sync prefs cost'],
                ],
                'model_answer_html' => '<p>Event bus → fan-out workers. Celebrities: fan-out-on-timeline for normal, fan-out-on-read for mega accounts.</p><p>Channels separate queues (email provider limits ≠ push). Idempotency: <code>hash(user, event, channel)</code>.</p><p>Quiet hours: enqueue delayed job after prefs snapshot + recheck at send.</p><p>Webhook delivery: HMAC sign, exponential backoff, disable after N failures.</p>',
                'follow_ups' => ['How do you throttle a bad webhook consumer?', 'Digest coalescing strategy?'],
                'est_minutes' => 45,
            ],
            [
                'slug' => 'rate-limiter-edge',
                'title' => 'Distributed Rate Limiter',
                'level' => 'senior',
                'track' => 'infra',
                'prompt' => 'Design a rate limiter for a public API: per-key RPS, burst, and daily quotas. Limits enforced at edge across N API nodes with <1ms overhead when possible.',
                'constraints' => ['distributed counters', 'fail-open vs fail-closed decision', 'headers show remaining', 'cost of accuracy'],
                'rubric' => [
                    ['dimension' => 'Algorithm', 'weight' => 25, 'bar' => 'Token bucket vs sliding window vs GCRA'],
                    ['dimension' => 'Storage', 'weight' => 20, 'bar' => 'Redis pipeline, Lua atomicity, local cache layers'],
                    ['dimension' => 'Consistency', 'weight' => 15, 'bar' => 'Approx OK for rate limits — when not'],
                    ['dimension' => 'Failure mode', 'weight' => 20, 'bar' => 'Redis down: fail-open vs closed by risk'],
                    ['dimension' => 'Observability', 'weight' => 10, 'bar' => 'X-RateLimit-* + metrics'],
                    ['dimension' => 'Trade-offs', 'weight' => 10, 'bar' => 'Local-only inaccurate vs always-remote latency'],
                ],
                'model_answer_html' => '<p>Token bucket in Redis with Lua (incr + expire atomically) or sliding window log for precision at low RPS.</p><p>Two layers: node-local token bucket (cheap) + global Redis for hard daily quota.</p><p>Fail-open for read APIs, fail-closed for auth/login. Always emit remaining headers from last known state.</p>',
                'follow_ups' => ['How would you implement per-customer cost-weighted limits?', 'Hot key sharding?'],
                'est_minutes' => 45,
            ],
            [
                'slug' => 'product-search',
                'title' => 'Product Search',
                'level' => 'senior',
                'track' => 'data',
                'prompt' => 'Design search over 10M SKUs: full-text, filters (price, brand, rating), facets, typo tolerance, and sort. Index freshness < 30s after admin edit.',
                'constraints' => ['SQL not enough eventually', 'facet counts expensive', 'reindex without downtime', 'PHP/Laravel app'],
                'rubric' => [
                    ['dimension' => 'Engine choice', 'weight' => 25, 'bar' => 'MySQL fulltext vs ES/OpenSearch vs Meilisearch — when'],
                    ['dimension' => 'Index pipeline', 'weight' => 20, 'bar' => 'CDC/outbox → indexer, idempotent docs'],
                    ['dimension' => 'Query features', 'weight' => 20, 'bar' => 'Filters, facets, typo, boosts'],
                    ['dimension' => 'Freshness vs load', 'weight' => 15, 'bar' => 'near-real-time refresh settings'],
                    ['dimension' => 'Failure modes', 'weight' => 10, 'bar' => 'Index down → degraded SQL fallback?'],
                    ['dimension' => 'Trade-offs', 'weight' => 10, 'bar' => 'Dual-write pitfalls'],
                ],
                'model_answer_html' => '<p>Start MySQL FULLTEXT + filters if catalog is simple; move to OpenSearch when facets/typo/relevance matter.</p><p>Index via transactional outbox → consumer (avoid dual-write). Version docs by <code>updated_at</code> to drop out-of-order.</p><p>Zero-downtime: build new index alias → swap.</p>',
                'follow_ups' => ['How do you A/B relevance?', 'Who owns ranking config?'],
                'est_minutes' => 45,
            ],
            [
                'slug' => 'inventory-oversell',
                'title' => 'Inventory Reservation (No Oversell)',
                'level' => 'senior',
                'track' => 'product',
                'prompt' => 'Design inventory for flash sales: 100k users on 1k units. No oversell under concurrency. Hold stock during checkout, release on timeout, support partial allocations.',
                'constraints' => ['high contention hot row', 'checkout TTL 10–15 min', 'audit stock movements', 'refund returns stock'],
                'rubric' => [
                    ['dimension' => 'Concurrency control', 'weight' => 30, 'bar' => 'SELECT FOR UPDATE / atomic UPDATE qty / queue serialization'],
                    ['dimension' => 'Reservation TTL', 'weight' => 20, 'bar' => 'Lease rows + sweeper'],
                    ['dimension' => 'Hot item strategy', 'weight' => 20, 'bar' => 'Pre-partition stock, mutex queue, or dedicated service'],
                    ['dimension' => 'Idempotency', 'weight' => 10, 'bar' => 'Same cart double-submit'],
                    ['dimension' => 'Recovery', 'weight' => 10, 'bar' => 'Crash mid-reserve'],
                    ['dimension' => 'Trade-offs', 'weight' => 10, 'bar' => 'Eventual oversell-and-compensate sometimes acceptable?'],
                ],
                'model_answer_html' => '<p><code>UPDATE inventory SET reserved = reserved + :n, available = available - :n WHERE sku = :s AND available >= :n</code> — affected rows = success.</p><p>Reservation table with <code>expires_at</code>; cron releases expired. Hot SKU: admit via queue or pre-split stock into N shards.</p><p>Never read-then-write without guard. All stock changes append to movement ledger.</p>',
                'follow_ups' => ['What if payment fails after reserve?', 'Multi-warehouse allocation?'],
                'est_minutes' => 45,
            ],
            [
                'slug' => 'feature-flag-platform',
                'title' => 'Feature Flag Platform',
                'level' => 'senior',
                'track' => 'infra',
                'prompt' => 'Design feature flags: percentage rollouts, targeting rules, kill switches. Evaluation < 1ms in-process. Changes propagate globally < 5s.',
                'constraints' => ['no network on eval hot path', 'kill switch must be fast', 'audit who changed flag', 'offline eval snapshot'],
                'rubric' => [
                    ['dimension' => 'Eval model', 'weight' => 25, 'bar' => 'SDK local rules vs remote eval'],
                    ['dimension' => 'Propagation', 'weight' => 20, 'bar' => 'PUB/SUB or long-poll + versioned snapshot'],
                    ['dimension' => 'Consistency', 'weight' => 15, 'bar' => 'Stale window accepted'],
                    ['dimension' => 'Safety', 'weight' => 20, 'bar' => 'Kill switch path, staged kill, permissions'],
                    ['dimension' => 'Auditability', 'weight' => 10, 'bar' => 'Change log + default deny'],
                    ['dimension' => 'Trade-offs', 'weight' => 10, 'bar' => 'Percentage by user vs by request stickiness'],
                ],
                'model_answer_html' => '<p>Control plane writes rules; data path: SDKs subscribe or poll versioned JSON snapshot. Eval is pure function(rules, context).</p><p>Kill switch: separate emergency key path with higher priority + force refresh.</p><p>Bucketing: hash(userId) — document that percentage rollouts are sticky per id not per request.</p>',
                'follow_ups' => ['How do you prevent flag debt?', 'Multi-variate experiments?'],
                'est_minutes' => 45,
            ],
            [
                'slug' => 'chat-presence',
                'title' => 'Chat with Presence',
                'level' => 'senior',
                'track' => 'infra',
                'prompt' => 'Design 1:1 and group chat (≤500 members): message order, delivery receipts, online presence, offline push.',
                'constraints' => ['total order per conversation', 'at-least-once delivery', 'read state per user', 'scale to 10M DAU'],
                'rubric' => [
                    ['dimension' => 'Ordering', 'weight' => 25, 'bar' => 'Per-conversation sequence / Lamport vs timestamp'],
                    ['dimension' => 'Transport', 'weight' => 20, 'bar' => 'WS gateway, sticky sessions, reconnect resume'],
                    ['dimension' => 'Storage', 'weight' => 15, 'bar' => 'Messages table + fan-out read model'],
                    ['dimension' => 'Delivery/read', 'weight' => 20, 'bar' => 'ACK states, last-read cursor'],
                    ['dimension' => 'Failure modes', 'weight' => 10, 'bar' => 'Duplicate messages, clock skew'],
                    ['dimension' => 'Trade-offs', 'weight' => 10, 'bar' => 'Sync vs async fan-out for groups'],
                ],
                'model_answer_html' => '<p>Server assigns monotonically increasing <code>conv_seq</code> — client timestamps are not trusted for order.</p><p>WS gateway; on reconnect client sends <code>last_seq</code> for catch-up. Persist message then fan-out to online sockets (groups: only online members get push; offline read from DB).</p><p>Receipts: separate lightweight events; last-read per user cursor.</p>',
                'follow_ups' => ['Edited/deleted message semantics?', 'E2E encryption impact?'],
                'est_minutes' => 45,
            ],
            [
                'slug' => 'video-transcode-pipeline',
                'title' => 'Video Transcode Pipeline',
                'level' => 'senior',
                'track' => 'infra',
                'prompt' => 'Design upload → transcode → poster → HLS package → CDN publish. Users see progressive progress. Retries safe. Cost control on burst uploads.',
                'constraints' => ['long-running jobs', 'exactly-once publish', 'per-rendition progress', 'budget alerts'],
                'rubric' => [
                    ['dimension' => 'Job orchestration', 'weight' => 25, 'bar' => 'Queue stages, idempotent job keys, DAG vs linear'],
                    ['dimension' => 'Storage', 'weight' => 15, 'bar' => 'Multipart upload, intermediate artifacts'],
                    ['dimension' => 'Progress & UX', 'weight' => 15, 'bar' => 'Stage events → WebSocket/SSE'],
                    ['dimension' => 'Reliability', 'weight' => 20, 'bar' => 'Retry with backoff, poison job, checksum verify'],
                    ['dimension' => 'Cost', 'weight' => 15, 'bar' => 'Concurrency caps, spot workers, tiered renditions'],
                    ['dimension' => 'Trade-offs', 'weight' => 10, 'bar' => 'FFmpeg on FPM vs workers (never FPM long jobs)'],
                ],
                'model_answer_html' => '<p>Never run FFmpeg on web request. Pipeline: upload complete → <code>job_id</code> → workers: probe → transcode variants → package → atomic CDN pointer flip.</p><p>Progress: each stage emits events; UI subscribes. Retry stage only; content-addressed output paths avoid partial overwrite.</p>',
                'follow_ups' => ['How do you cancel a running job?', 'DR for the worker fleet?'],
                'est_minutes' => 45,
            ],
            [
                'slug' => 'timeline-feed',
                'title' => 'Timeline / Feed',
                'level' => 'senior',
                'track' => 'data',
                'prompt' => 'Design a social feed: follow graph, ranked + reverse-chron modes, infinite pagination, invalidation when authors delete posts.',
                'constraints' => ['fan-out cost on celebrities', 'cursor pagination stable', 'deletes propagate', 'PHP/Laravel'],
                'rubric' => [
                    ['dimension' => 'Fan-out strategy', 'weight' => 30, 'bar' => 'Push vs pull vs hybrid — concrete thresholds'],
                    ['dimension' => 'Pagination', 'weight' => 20, 'bar' => 'Keyset/cursor not OFFSET'],
                    ['dimension' => 'Ranking', 'weight' => 15, 'bar' => 'Separate candidate generation vs scoring'],
                    ['dimension' => 'Invalidation', 'weight' => 15, 'bar' => 'Tombstones or delete from timelines'],
                    ['dimension' => 'Cache', 'weight' => 10, 'bar' => 'Redis timelines + TTL/stampede'],
                    ['dimension' => 'Trade-offs', 'weight' => 10, 'bar' => 'Storage blowup of pre-computed feeds'],
                ],
                'model_answer_html' => '<p>Hybrid: push to followers when author followers &lt; N; pull/merge on read for celebrities.</p><p>Cursor = (rank_score, id). Deletes: remove from fan-out stores + tombstone id set for hot caches.</p>',
                'follow_ups' => ['How do you backfill a new ranking model?', 'Mute/block semantics?'],
                'est_minutes' => 45,
            ],
            [
                'slug' => 'monolith-to-services',
                'title' => 'Modular Monolith → Services Split',
                'level' => 'senior',
                'track' => 'infra',
                'prompt' => 'You own a Laravel monolith (billing, catalog, auth, notifications). Leadership wants services. Propose a split plan: boundaries, strangler steps, what NOT to extract first.',
                'constraints' => ['team of 12', 'no big-bang rewrite', 'shared DB today', 'billing correctness critical'],
                'rubric' => [
                    ['dimension' => 'Bounded contexts', 'weight' => 25, 'bar' => 'Domain seams not layer seams'],
                    ['dimension' => 'Extraction order', 'weight' => 20, 'bar' => 'Why X first / why billing last'],
                    ['dimension' => 'Data ownership', 'weight' => 20, 'bar' => 'Split DB, anti-corruption, sync'],
                    ['dimension' => 'Communication', 'weight' => 15, 'bar' => 'Sync API vs events; contracts'],
                    ['dimension' => 'Org fit', 'weight' => 10, 'bar' => 'Conway — team per service only if ready'],
                    ['dimension' => 'Trade-offs', 'weight' => 10, 'bar' => 'Explicit case to STAY monolith longer'],
                ],
                'model_answer_html' => '<p>First modularize inside monolith (folders + enforced boundaries + no cross-module joins). Extract only where scale/deploys diverge.</p><p>Often extract: search, media processing, webhooks. Keep billing in monolith longest — money paths need one transaction boundary until you build saga properly.</p><p>Data: each service owns its tables; integrate via APIs/events; run dual-read temporarily.</p><p><strong>Senior answer includes:</strong> “We should not extract Y until Z signal.”</p>',
                'follow_ups' => ['How do you do distributed transactions during transition?', 'How do you measure success of the split?'],
                'est_minutes' => 45,
            ],
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function incidentBlueprints(): array
    {
        return [
            [
                'slug' => 'fpm-oom-climb',
                'title' => 'Memory climbing until FPM OOM',
                'severity' => 'sev1',
                'symptom' => 'p99 flat then 503 storm. RSS per FPM worker climbs 1MB/min under normal traffic; workers restart; some requests fatal with OOM.',
                'logs' => '[php] Allowed memory size of 134217728 bytes exhausted at Peak 134217726 — triggered in CacheItem::get after 47 min uptime',
                'metrics' => ['rss_per_worker' => 'monotonic climb', 'restarts_per_hour' => 'from 0 to 40', 'traffic' => 'flat'],
                'traces' => 'Request profile: same endpoint stores growing array in a static property keyed by tenant',
                'artifacts' => ['config' => 'static $memo = []; // in Middleware\TenantContext'],
                'wrong_paths' => ['Try lowering memory_limit only', 'Blame Redis', 'Assume traffic spike'],
                'correct_diagnosis' => 'Static request-cache in middleware never cleared across queue/worker reuse or grows unbounded per tenant — memory leak in long-lived workers / accumulating static.',
                'fix_steps' => ['Find static/cache holding per-request data', 'Clear at kernel terminate or use request-scoped container binding', 'Add leak test: run N requests, assert memory', 'Lower memory_limit is mitigation not fix'],
                'root_cause_category' => 'code',
                'blast_radius' => 'All PHP workers; full site 503s',
                'est_minutes' => 25,
            ],
            [
                'slug' => 'checkout-deadlock',
                'title' => 'Intermittent deadlock on parallel checkout',
                'severity' => 'sev1',
                'symptom' => '~0.3% of checkouts fail with SQLSTATE[40001] deadlock found when trying to get lock; user sees generic 500. Higher during flash sale.',
                'logs' => "ERROR 1213 (40001): Deadlock found when trying to get lock; try restarting transaction\nTransaction 1: UPDATE orders, UPDATE inventory\nTransaction 2: UPDATE inventory, UPDATE orders",
                'metrics' => ['deadlocks_per_min' => '0–12 spiky', 'traffic' => 'elevated sale'],
                'artifacts' => ['tx1' => 'UPDATE orders SET ... WHERE id=?; UPDATE inventory ...', 'tx2' => 'UPDATE inventory ...; UPDATE orders ...'],
                'wrong_paths' => ['Just retry forever', 'Disable FK checks', 'Move to MyISAM'],
                'correct_diagnosis' => 'Two transactions acquire row locks in opposite order (orders→inventory vs inventory→orders) causing classic lock-order inversion deadlock.',
                'fix_steps' => ['Define global lock order (always inventory then orders)', 'Or single service method owns both updates in one order', 'Add bounded retry with backoff for residual deadlocks', 'Index the WHERE columns to shrink lock scope'],
                'root_cause_category' => 'race',
                'blast_radius' => 'Checkout path under concurrency',
                'est_minutes' => 25,
            ],
            [
                'slug' => 'n1-template-regression',
                'title' => 'p99 explodes after small Blade change',
                'severity' => 'sev2',
                'symptom' => 'Deploy of “just a UI tweak” → list page p99 80ms → 2.1s. APM shows query count 12 → 1840 on same URL.',
                'logs' => 'slowlog: SELECT * FROM users WHERE id = ? (1840 times in one request)',
                'metrics' => ['p99' => '2100ms', 'sql_per_request' => '1840'],
                'traces' => 'View loop: $order->user->profile->avatar',
                'artifacts' => ['blade' => '{{ $order->user->profile->full_name }} inside @foreach over orders'],
                'wrong_paths' => ['Add cache in front only', 'Blame MySQL', 'Scale FPM'],
                'correct_diagnosis' => 'Template change introduced N+1: eager loads missing for user/profile accessed inside loop.',
                'fix_steps' => ['with([user.profile]) eager load', 'Add test asserting query count bound on page', 'Enable debug query log in CI for hot pages'],
                'root_cause_category' => 'code',
                'blast_radius' => 'Orders admin list; DB CPU up',
                'est_minutes' => 15,
            ],
            [
                'slug' => 'prod-only-500-missing-env',
                'title' => '500s only in production after config change',
                'severity' => 'sev2',
                'symptom' => 'Staging OK, prod 500 on /api/webhooks. Error: Undefined array key "PAYMENT_WEBHOOK_SECRET". Started after new provider enable.',
                'logs' => 'local.ERROR: Undefined array key "PAYMENT_WEBHOOK_SECRET" in config/services.php',
                'metrics' => ['error_rate' => '100% on webhook route', 'other_routes' => 'healthy'],
                'artifacts' => ['config' => '$secret = config("services.payments.webhook_secret") used as string'],
                'wrong_paths' => ['Rollback app blindly without config', 'Hardcode secret in repo'],
                'correct_diagnosis' => 'New config key required by code was never added to production env (or config cache stale without new key).',
                'fix_steps' => ['Add env var / secret manager entry', 'php artisan config:clear or rebuild cache', 'Fail fast on boot when required secrets missing', 'Add deploy check for config keys'],
                'root_cause_category' => 'config',
                'blast_radius' => 'Payment webhooks down — delayed fulfillment',
                'est_minutes' => 15,
            ],
            [
                'slug' => 'queue-double-email',
                'title' => 'Double emails from queue consumer',
                'severity' => 'sev2',
                'symptom' => 'Users report 2 identical emails per order ~5% of time. Jobs show same payload processed twice with different job ids.',
                'logs' => 'job.EmailOrder processed attempt=1; 30s later job.EmailOrder processed again different uuid',
                'metrics' => ['duplicate_send_rate' => '5%', 'queue' => 'redis'],
                'artifacts' => ['job' => 'Mail::to($user)->send(...) // no unique guard'],
                'wrong_paths' => ['Set max_attempts=1', 'Blame mail provider'],
                'correct_diagnosis' => 'At-least-once queue redelivery (visibility timeout / worker crash after side effect before delete) without idempotent send guard.',
                'fix_steps' => ['Idempotency key unique(job_type, order_id, user_id)', 'Mark processed in DB before/after send with transaction pattern or outbox', 'Shorter visibility only if job time bounded'],
                'root_cause_category' => 'race',
                'blast_radius' => 'Customer trust / support load',
                'est_minutes' => 20,
            ],
            [
                'slug' => 'cache-stampede-deploy',
                'title' => 'Cache stampede at deploy on hot product page',
                'severity' => 'sev2',
                'symptom' => 'At deploy, cache flush → product page DB QPS 50→4000 in 30s; MySQL connections pegged; some timeouts.',
                'logs' => 'DB: Too many connections; app: waiting on DB pool',
                'metrics' => ['db_qps' => 'spike on deploy', 'cache_hit_ratio' => '99% → 2% → recover'],
                'artifacts' => ['deploy' => 'php artisan cache:clear in post-release hook'],
                'wrong_paths' => ['Just raise max_connections forever', 'Disable cache'],
                'correct_diagnosis' => 'Cold cache stampede: all workers rebuild same expensive key concurrently after flush (and non-sticky version bump).',
                'fix_steps' => ['Never full-flush; use cache key versioning / tags', 'Singleflight: lock or probabilistic early recompute', 'Stale-while-revalidate', 'Pre-warm top URLs after deploy'],
                'root_cause_category' => 'config',
                'blast_radius' => 'Full catalog browse during deploy window',
                'est_minutes' => 25,
            ],
            [
                'slug' => 'slow-query-backfill',
                'title' => 'Slow queries after overnight backfill',
                'severity' => 'sev2',
                'symptom' => 'Morning p95 on search 40ms → 900ms. EXPLAIN shows full table scan where it used ref before.',
                'logs' => 'EXPLAIN: type=ALL key=NULL rows=18000000 Extra: Using where',
                'metrics' => ['p95' => '900ms', 'rows_examined' => 'millions'],
                'artifacts' => ['schema' => 'ALTER TABLE products ADD COLUMN search_tsv ... -- index not created / wrong column type'],
                'wrong_paths' => ['Add LIMIT only', 'Buy bigger DB immediately'],
                'correct_diagnosis' => 'Backfill migration added/changed column used in WHERE without (or with dropped) supporting index — plan regression.',
                'fix_steps' => ['CREATE INDEX CONCURRENTLY / online DDL', 'Validate EXPLAIN in migration test', 'Drop unused indexes if write path slow'],
                'root_cause_category' => 'schema',
                'blast_radius' => 'Search & list endpoints',
                'est_minutes' => 20,
            ],
            [
                'slug' => 'session-logouts-lb',
                'title' => 'Random logouts behind load balancer',
                'severity' => 'sev3',
                'symptom' => 'Users randomly logged out. Multiple app servers; session cookie valid. Worse after autoscaling.',
                'logs' => 'auth fails: session id not found in store for some requests',
                'metrics' => ['logout_rate' => 'correlates with new instances'],
                'artifacts' => ['session' => 'SESSION_DRIVER=file on each node'],
                'wrong_paths' => ['Shorten cookie lifetime', 'Blame users clearing cookies'],
                'correct_diagnosis' => 'File sessions local to each node without sticky sessions — requests landing on other nodes miss session.',
                'fix_steps' => ['Move sessions to shared store (database/redis)', 'Or enforce sticky sessions (less ideal)', 'Verify cookie domain/secure/SameSite'],
                'root_cause_category' => 'config',
                'blast_radius' => 'Auth UX across app',
                'est_minutes' => 15,
            ],
            [
                'slug' => 'oversell-race',
                'title' => 'Oversell under concurrent add-to-cart buy',
                'severity' => 'sev1',
                'symptom' => 'Flash item qty=1 sold to 3 customers. Inventory went negative for 12 orders.',
                'logs' => 'SELECT available FROM inventory — read 1; three UPDATE ... available=available-1 all succeeded from read=1 pattern',
                'metrics' => ['negative_stock' => '12 SKUs'],
                'artifacts' => ['code' => 'if ($inv->available > 0) { $inv->available--; $inv->save(); }'],
                'wrong_paths' => ['Only SELECT FOR UPDATE at checkout UI', 'Trust front-end to disable button'],
                'correct_diagnosis' => 'Read-modify-write race: check and decrement not atomic; no row lock or conditional update.',
                'fix_steps' => ['Atomic UPDATE ... SET available = available - ? WHERE available >= ?', 'Or SELECT FOR UPDATE in one transaction', 'Reservation TTL model', 'Ledger for audit'],
                'root_cause_category' => 'race',
                'blast_radius' => 'Revenue, customer service, trust',
                'est_minutes' => 25,
            ],
            [
                'slug' => 'bad-migration-rollback',
                'title' => 'Bad migration — expand/contract drill',
                'severity' => 'sev1',
                'symptom' => 'Release renames column users.phone → mobile in-place; old code still reads phone; 50% of instances on old version during rolling deploy → 500s on profile.',
                'logs' => 'SQLSTATE[42S22]: Column not found: users.phone',
                'metrics' => ['error_rate' => '50% of web during roll'],
                'artifacts' => ['migration' => "renameColumn('phone', 'mobile')"],
                'wrong_paths' => ['Hotfix code with COALESCE hacks forever', 'Force all-down redeploy with downtime'],
                'correct_diagnosis' => 'Breaking schema change incompatible with rolling deploy (no expand/contract phases).',
                'fix_steps' => ['Add new column, dual-write, backfill, switch reads, drop old in later release', 'Or rollback migration then fix-forward with compatible migration', 'Document expand/contract in team runbook'],
                'root_cause_category' => 'schema',
                'blast_radius' => 'Profile/settings 500s during deploy',
                'est_minutes' => 30,
            ],
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function reviewBlueprints(): array
    {
        return [
            [
                'slug' => 'controller-mass-assign-n1',
                'title' => 'Controller: mass assign, policy gap, N+1',
                'language' => 'php',
                'context' => 'PR: “Add team member listing endpoint” for Laravel API.',
                'files' => [
                    ['path' => 'app/Http/Controllers/TeamMemberController.php', 'code' => "<?php\n\nnamespace App\\Http\\Controllers;\n\nuse App\\Models\\User;\nuse Illuminate\\Http\\Request;\n\nclass TeamMemberController extends Controller\n{\n    public function index(Request \$request)\n    {\n        \$team = User::where('team_id', \$request->team_id)->get();\n\n        return response()->json(\$team);\n    }\n\n    public function store(Request \$request)\n    {\n        \$user = User::create(\$request->all());\n\n        return response()->json(\$user, 201);\n    }\n\n    public function destroy(\$id)\n    {\n        User::findOrFail(\$id)->delete();\n\n        return response()->json(['ok' => true]);\n    }\n}\n"],
                ],
                'planted_issues' => [
                    ['id' => 'mass-assign', 'type' => 'security', 'severity' => 'high', 'hint' => 'create($request->all())', 'fix' => 'Use validated $request->validated() and explicit fillable; never trust role/team_id from client'],
                    ['id' => 'missing-authz', 'type' => 'security', 'severity' => 'high', 'hint' => 'destroy has no policy', 'fix' => 'Authorize with policy delete + team scoping before destroy'],
                    ['id' => 'idor-team', 'type' => 'security', 'severity' => 'high', 'hint' => 'team_id from request unchecked', 'fix' => 'Scope to auth()->user()->team_id, ignore client team_id or verify membership'],
                    ['id' => 'no-validation', 'type' => 'bug', 'severity' => 'medium', 'hint' => 'store lacks validate()', 'fix' => '$request->validate([...])'],
                    ['id' => 'soft-delete-n1', 'type' => 'perf', 'severity' => 'low', 'hint' => 'Potential future N+1 if profiles loaded in loop', 'fix' => 'Eager load relations when serializing members'],
                ],
                'est_minutes' => 15,
            ],
            [
                'slug' => 'service-swallow-deps',
                'title' => 'Service: hidden deps, swallowed exceptions, no tests',
                'language' => 'php',
                'context' => 'PR: “Refactor invoice sending into service”.',
                'files' => [
                    ['path' => 'app/Services/InvoiceMailer.php', 'code' => "<?php\n\nnamespace App\\Services;\n\nclass InvoiceMailer\n{\n    public function send(\$invoice)\n    {\n        try {\n            \$pdf = app(PdfService::class)->render(\$invoice);\n            \\Mail::to(\$invoice->customer_email)->send(new InvoiceMail(\$invoice, \$pdf));\n        } catch (\\Exception \$e) {\n            // ignore\n        }\n    }\n}\n"],
                ],
                'planted_issues' => [
                    ['id' => 'swallow', 'type' => 'bug', 'severity' => 'high', 'hint' => 'catch Exception ignore', 'fix' => 'Log + rethrow or failed() domain result; silent catch hides failed invoices'],
                    ['id' => 'no-test', 'type' => 'testing', 'severity' => 'medium', 'hint' => 'no unit/feature test in PR', 'fix' => 'Add test that Mail::fake asserted, failure path asserted'],
                    ['id' => 'broad-catch', 'type' => 'bug', 'severity' => 'medium', 'hint' => 'catch (\\Exception)', 'fix' => 'Catch specific exceptions; Error should not be caught here'],
                    ['id' => 'hidden-container', 'type' => 'design', 'severity' => 'low', 'hint' => 'app() inside method', 'fix' => 'Constructor inject PdfService for explicit deps'],
                ],
                'est_minutes' => 12,
            ],
            [
                'slug' => 'migration-index-sql',
                'title' => 'Migration: non-concurrent index + wrong type',
                'language' => 'sql',
                'context' => 'PR ships with production table orders (80M rows).',
                'files' => [
                    ['path' => 'database/migrations/2026_09_01_add_lookup_indexes.php', 'code' => "<?php\n// ...\nSchema::table('orders', function (Blueprint \$t) {\n    \$t->index(['status', 'created_at']);\n    \$t->string('external_ref')->nullable()->index();\n});\n"],
                ],
                'planted_issues' => [
                    ['id' => 'lock-table', 'type' => 'ops', 'severity' => 'high', 'hint' => 'plain index() on 80M rows', 'fix' => 'Use DB::statement CREATE INDEX CONCURRENTLY (Postgres) or online DDL / gh-ost pattern'],
                    ['id' => 'string-length', 'type' => 'schema', 'severity' => 'medium', 'hint' => 'string default 255 index bloat', 'fix' => 'Size column to real max length used for lookups'],
                    ['id' => 'no-down-safe', 'type' => 'ops', 'severity' => 'low', 'hint' => 'down() drop may block', 'fix' => 'Document rollback; concurrent drop'],
                ],
                'est_minutes' => 12,
            ],
            [
                'slug' => 'blade-xss',
                'title' => 'Blade: raw output + logic in view',
                'language' => 'blade',
                'context' => 'PR: render user bio on profile.',
                'files' => [
                    ['path' => 'resources/views/profile/bio.blade.php', 'code' => "@if (auth()->user()->isVip())\n    <div class=\"vip\">{{ \$user->name }}</div>\n@else\n    <div>{{ \$user->name }}</div>\n@endif\n\n{!! \$user->bio !!}\n\n@php\n    \$discount = \$user->orders->count() > 10 ? 0.9 : 1.0;\n@endphp\n<p>Price: {{ \$product->price * \$discount }}</p>\n"],
                ],
                'planted_issues' => [
                    ['id' => 'xss-bio', 'type' => 'security', 'severity' => 'high', 'hint' => '{!! $user->bio !!}', 'fix' => 'Escape with {{ }} or purify HTML if markdown allowed'],
                    ['id' => 'logic-in-view', 'type' => 'design', 'severity' => 'medium', 'hint' => '@php discount in view', 'fix' => 'Compute discount in view model / presenter'],
                    ['id' => 'n1-orders', 'type' => 'perf', 'severity' => 'medium', 'hint' => '$user->orders->count() in view', 'fix' => 'withCount or pass from controller; avoid queries in blade'],
                ],
                'est_minutes' => 10,
            ],
            [
                'slug' => 'queue-pii-retries',
                'title' => 'Queue job: non-idempotent, infinite retry, PII in payload',
                'language' => 'php',
                'context' => 'PR: async welcome + charge email.',
                'files' => [
                    ['path' => 'app/Jobs/SendBillingEmails.php', 'code' => <<<'EOT'
<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendBillingEmails implements ShouldQueue
{
    use Queueable;

    public $tries = 999;
    public $timeout = 600;

    public function __construct(public array $customer) {}

    public function handle(): void
    {
        \Mail::raw($this->customer['card_last4'].' receipt', function ($m) use ($customer) {
            $m->to($this->customer['email'])->subject('Receipt');
        });
        ChargeLogger::log($this->customer);
    }
}
EOT],
                ],
                'planted_issues' => [
                    ['id' => 'infinite-tries', 'type' => 'ops', 'severity' => 'high', 'hint' => 'tries=999', 'fix' => 'Backoff + max tries + failed() handler / DLQ'],
                    ['id' => 'pii-payload', 'type' => 'security', 'severity' => 'high', 'hint' => 'card_last4 + email in queue payload', 'fix' => 'Pass only customer_id; load PII inside job from DB'],
                    ['id' => 'long-timeout', 'type' => 'ops', 'severity' => 'medium', 'hint' => 'timeout=600', 'fix' => 'Keep jobs short; split work; align with queue visibility'],
                    ['id' => 'non-idempotent', 'type' => 'concurrency', 'severity' => 'medium', 'hint' => 'no idempotency guard', 'fix' => 'Unique send key per invoice'],
                ],
                'est_minutes' => 15,
            ],
            [
                'slug' => 'docker-ci-secrets',
                'title' => 'CI/Docker: secrets in image, latest tags, no healthcheck',
                'language' => 'yaml',
                'context' => 'PR: production Dockerfile + workflow.',
                'files' => [
                    ['path' => 'Dockerfile', 'code' => "FROM php:8.4-fpm-latest\nENV APP_KEY=base64:xxxx SUPER_SECRET_KEY\nCOPY . /var/www\nRUN chmod -R 777 storage\n"],
                    ['path' => '.github/workflows/deploy.yml', 'code' => "steps:\n  - run: docker build -t app:latest .\n  - run: docker push registry/app:latest\n  - run: docker compose up -d\n"],
                ],
                'planted_issues' => [
                    ['id' => 'secret-in-image', 'type' => 'security', 'severity' => 'critical', 'hint' => 'ENV APP_KEY / SECRET in Dockerfile', 'fix' => 'Inject at runtime from secret manager; never bake keys'],
                    ['id' => 'latest-tag', 'type' => 'ops', 'severity' => 'high', 'hint' => ':latest', 'fix' => 'Immutable tags (git sha) for reproducible deploys'],
                    ['id' => 'chmod-777', 'type' => 'security', 'severity' => 'medium', 'hint' => '777 storage', 'fix' => 'Owner-appropriate perms; non-root user'],
                    ['id' => 'no-healthcheck', 'type' => 'ops', 'severity' => 'medium', 'hint' => 'compose up without health', 'fix' => 'HEALTHCHECK + wait for healthy before traffic'],
                    ['id' => 'copy-all', 'type' => 'ops', 'severity' => 'low', 'hint' => 'COPY . includes .env', 'fix' => '.dockerignore for .env, vendor, git'],
                ],
                'est_minutes' => 12,
            ],
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function internalsBlueprints(): array
    {
        return [
            [
                'slug' => 'request-lifecycle',
                'title' => 'Request Lifecycle',
                'summary' => 'From HTTP bytes to response: SAPI, bootstrap, container, middleware, router, controller, terminate.',
                'body_html' => '<p><strong>1. SAPI receives bytes</strong> (FPM/CLI/PHP built-in). PHP parses request into superglobals.</p><p><strong>2. Autoload + bootstrap</strong>: composer autoloader, create Application, load config/env.</p><p><strong>3. HTTP Kernel</strong>: global middleware → route middleware → controller.</p><p><strong>4. Router</strong> matches method+URI to action; container resolves dependencies.</p><p><strong>5. Response</strong> sent; <code>terminate()</code> runs long-lived cleanups.</p><p>Senior note: middleware order, deferred providers, and what happens on <code>exit</code> mid-pipeline.</p>',
                'explain_back_prompts' => [
                    'Walk through what happens from TCP packet to Blade render in Laravel.',
                    'What runs in terminate() vs handle()? Give a real use for each.',
                    'How does route caching change boot cost?',
                ],
                'related_lesson_code' => 'B2',
            ],
            [
                'slug' => 'zval-refcount-cow',
                'title' => 'zval, Refcount, Copy-on-Write',
                'summary' => 'PHP values are zvals; arrays/strings are refcounted; separation on write.',
                'body_html' => '<p>A <strong>zval</strong> holds type + value (or pointer). Arrays/objects/resources are refcounted.</p><p><code>$b = $a</code> for arrays: same underlying structure, refcount++ (COW).</p><p>Write to $b → <strong>separate</strong> (copy) so $a unchanged.</p><p>unset decrements; at 0 memory frees (objects run destructor).</p><p><code>unset($a)</code> only removes that variable binding — if $b still shares, data stays.</p><p>Senior: explains “why did this array copy blow memory?” and why passing by reference forces separation.</p>',
                'explain_back_prompts' => [
                    'Why does unset($a) not always free memory immediately?',
                    'When does an assignment of an array actually copy data?',
                    'How does passing by reference interact with COW?',
                ],
                'related_lesson_code' => 'C2',
            ],
            [
                'slug' => 'gc-cycles',
                'title' => 'Garbage Collection Cycles',
                'summary' => 'Root buffer + cycle collector for reference cycles.',
                'body_html' => '<p>Refcount alone cannot free cycles (A→B→A both refcount ≥1).</p><p>PHP buffers possible roots and runs <code>gc_collect_cycles()</code> (also periodically) to find garbage cycles.</p><p>Cost: CPU spike when root buffer full — long-running workers must watch this.</p><p>Closures binding $this create cycles often — common senior debugging topic.</p>',
                'explain_back_prompts' => [
                    'Describe how PHP frees reference cycles.',
                    'When would you call gc_collect_cycles() manually in a worker?',
                    'Give an example of a cycle involving $this and a closure.',
                ],
                'related_lesson_code' => 'G2',
            ],
            [
                'slug' => 'object-handlers',
                'title' => 'Objects, Properties, Magic Overhead',
                'summary' => 'How object properties and __get/__set work under the hood.',
                'body_html' => '<p>Objects have a properties table (or slots for known props). Dynamic props deprecate in 8.2+ unless #[AllowDynamicProperties].</p><p><code>__get</code> runs only for inaccessible/missing props — can hide bugs and cost per access.</p><p>Typed properties and readonly give invariants with less runtime ambiguity.</p><p>Senior: explain memory layout differences between stdClass and typed class; why cloning is shallow by default (__clone).</p>',
                'explain_back_prompts' => [
                    'When is __get invoked vs normal property fetch?',
                    'Why is default clone shallow and what does __clone fix?',
                    'How do readonly properties change invariants?',
                ],
                'related_lesson_code' => 'C6',
            ],
            [
                'slug' => 'opcache-jit',
                'title' => 'Opcache and JIT',
                'summary' => 'Compile to opcodes, cache them; JIT emits native code for hot paths.',
                'body_html' => '<p>PHP compiles source → <strong>opcodes</strong> each request unless <strong>opcache</strong> stores them (shared memory).</p><p>opcache validates file mtime/sha — deploy must restart or reset properly.</p><p><strong>JIT</strong> (PHP 8+) compiles hot opcode regions to native machine code — big win for tight numeric loops, little for typical DB-bound web I/O.</p><p>Senior: know <code>opcache.enable</code>, <code>memory_consumption</code>, when JIT helps vs when to fix algorithms.</p>',
                'explain_back_prompts' => [
                    'What does opcache actually cache?',
                    'When would you expect JIT to help a Laravel app?',
                    'How can a deploy leave you running stale opcache code?',
                ],
                'related_lesson_code' => 'P2',
            ],
            [
                'slug' => 'autoloading-psr4',
                'title' => 'Autoloading and Composer',
                'summary' => 'PSR-4 rules, classmap vs optimized, dump-autoload pitfalls.',
                'body_html' => '<p>PSR-4: namespace maps to directory; class file path derived from FQCN.</p><p><code>composer dump-autoload -o</code> builds classmap (prod) vs lazy PSR-4 (dev).</p><p>Files autoload (<code>"files"</code>) run every request — can slow boot.</p><p>Senior: diagnose “class not found” after rename; explain why mixed PSR-4/classmap packages exist.</p>',
                'explain_back_prompts' => [
                    'How does PSR-4 resolve App\\Models\\User to a file?',
                    'Why optimize autoloader in production only?',
                    'Common causes of Class not found in deploy?',
                ],
                'related_lesson_code' => 'C0',
            ],
            [
                'slug' => 'fibers-generators',
                'title' => 'Fibers vs Generators vs Processes',
                'summary' => 'Cooperative multitasking primitives and when each fits.',
                'body_html' => '<p><strong>Generators</strong> (yield): lazy sequences, streaming — not parallel execution.</p><p><strong>Fibers</strong>: suspend/resume full call stacks — building block for async (not magic threads).</p><p>True parallelism still needs processes/threads/Swoole-style runtimes with care around shared nothing PHP state.</p><p>Senior: answer “can PHP do concurrent IO?” with nuance — fibers + event loop ≠ preemption.</p>',
                'explain_back_prompts' => [
                    'Difference between yield and Fiber suspension?',
                    'Why is PHP-FPM still process-based for most apps?',
                    'When are generators the right tool in a Laravel codebase?',
                ],
                'related_lesson_code' => 'P0',
            ],
            [
                'slug' => 'error-model',
                'title' => 'Error and Exception Model',
                'summary' => 'Error hierarchy, handlers, finally, and failure design.',
                'body_html' => '<p>PHP 7+: <code>Error</code> and <code>Exception</code> both implement <code>Throwable</code>.</p><p>Type errors, ArgumentCountError are Errors — not always caught with <code>catch (Exception)</code>.</p><p>Handlers: set_error_handler, set_exception_handler; <code>@</code> suppress is rarely a strategy.</p><p>Senior: design domain exceptions; decide what is recoverable; avoid catch-all that hides bugs.</p>',
                'explain_back_prompts' => [
                    'catch (Exception) vs catch (Throwable): what do you miss?',
                    'When is a custom domain exception the right abstraction?',
                    'What does finally guarantee during return/exception?',
                ],
                'related_lesson_code' => 'C3',
            ],
            [
                'slug' => 'memory-peak-workers',
                'title' => 'Memory in FPM vs CLI Workers',
                'summary' => 'Request lifecycle memory vs long-lived workers.',
                'body_html' => '<p>FPM: memory mostly resets per request (with cleanup caveats — statics, extensions).</p><p>CLI/queue/Octane: state persists — leaks accumulate; caches grow; singletons hold stale data.</p><p>Measure: <code>memory_get_peak_usage(true)</code>, RSS metrics, leak tests over N iterations.</p><p>Senior: explain why “works in one artisan tinker” ≠ stable worker.</p>',
                'explain_back_prompts' => [
                    'Why can a queue worker OOM while FPM looks fine?',
                    'How would you write a regression test for a memory leak?',
                    'What state must not be stored in a static across jobs?',
                ],
                'related_lesson_code' => 'P1',
            ],
            [
                'slug' => 'cli-fpm-octane',
                'title' => 'CLI, FPM, and Long-Running App Pitfalls',
                'summary' => 'SAPI differences and app-lifetime assumptions.',
                'body_html' => '<p>Same code, different SAPI: env loading, connection reuse, signal handling differ.</p><p>Long-running servers (Octane/Swoole) break assumptions: <code>once</code> singletons, event listeners re-registration, static caches, non-flushed DB transactions.</p><p>Senior: know when Octane helps (high RPS read path) and the migration checklist for services.</p>',
                'explain_back_prompts' => [
                    'Name 3 assumptions that break under Octane.',
                    'How would you handle graceful shutdown of a queue worker?',
                    'When would you choose FPM over Octane?',
                ],
                'related_lesson_code' => 'N4',
            ],
        ];
    }

    /**
     * @return list<array{slug:string,title:string,tags:list<string>}>
     */
    private function starPromptBlueprints(): array
    {
        return [
            ['slug' => 'disagreed-tech-lead', 'title' => 'Disagreed with tech lead / drove consensus', 'tags' => ['conflict', 'influence']],
            ['slug' => 'owned-outage', 'title' => 'Production incident you led or owned', 'tags' => ['incident', 'ownership']],
            ['slug' => 'bad-migration', 'title' => 'Bad migration / risky deploy you mitigated', 'tags' => ['delivery', 'risk']],
            ['slug' => 'cut-scope', 'title' => 'Cut scope under deadline', 'tags' => ['delivery', 'judgment']],
            ['slug' => 'mentored', 'title' => 'Mentored or unblocked someone', 'tags' => ['mentoring']],
            ['slug' => 'tech-debt-won', 'title' => 'Technical debt argument that won', 'tags' => ['influence', 'architecture']],
            ['slug' => 'failure-owned', 'title' => 'Failure you owned and what changed', 'tags' => ['failure', 'growth']],
            ['slug' => 'cross-team', 'title' => 'Multi-team cross-boundary delivery', 'tags' => ['collaboration']],
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function simulationBlueprints(): array
    {
        return [
            [
                'slug' => 'loop-a-product-backend',
                'title' => 'Loop A — Product Backend Senior',
                'minutes_total' => 145,
                'segments' => [
                    ['type' => 'coding', 'minutes' => 50, 'prompt' => 'Timed coding: implement a rate-limiting token bucket in PHP with tests (or solve interview coding track problem).', 'ref' => 'interview:coding'],
                    ['type' => 'design', 'minutes' => 45, 'prompt_ref' => 'design:payment-checkout-idempotency', 'prompt' => 'System design: payment checkout with idempotency (use Design Studio case).'],
                    ['type' => 'internals', 'minutes' => 20, 'prompt_ref' => 'internals:zval-refcount-cow', 'prompt' => 'Deep dive: explain zval/refcount/COW and when arrays actually copy.'],
                    ['type' => 'behavioral', 'minutes' => 30, 'prompt' => 'Behavioral: walk through a production incident you owned using STAR (8 min story + probe questions on trade-offs and what you changed).'],
                ],
            ],
            [
                'slug' => 'loop-b-platform-infra',
                'title' => 'Loop B — Platform / Infra Lean',
                'minutes_total' => 130,
                'segments' => [
                    ['type' => 'coding', 'minutes' => 40, 'prompt' => 'Timed coding: implement idempotent webhook receiver + signature verify with tests.'],
                    ['type' => 'design', 'minutes' => 50, 'prompt_ref' => 'design:rate-limiter-edge', 'prompt' => 'System design: distributed rate limiter (Design Studio).'],
                    ['type' => 'incidents', 'minutes' => 20, 'prompt_ref' => 'incident:cache-stampede-deploy', 'prompt' => 'Debugging: cache stampede at deploy — diagnose from artifacts then propose fix.'],
                    ['type' => 'behavioral', 'minutes' => 20, 'prompt' => 'Behavioral: technical debt argument that won — influence without authority.'],
                ],
            ],
            [
                'slug' => 'loop-c-laravel-heavy',
                'title' => 'Loop C — Laravel-Heavy Product Engineer',
                'minutes_total' => 140,
                'segments' => [
                    ['type' => 'coding', 'minutes' => 45, 'prompt' => 'Timed coding: Eloquent-heavy query + N+1 diagnosis under constraints.'],
                    ['type' => 'code_review', 'minutes' => 30, 'prompt_ref' => 'review:controller-mass-assign-n1', 'prompt' => 'Code review: find security/design/perf issues in the PR (Review Arena).'],
                    ['type' => 'design', 'minutes' => 40, 'prompt_ref' => 'design:multi-tenant-saas', 'prompt' => 'System design: multi-tenant isolation (Design Studio).'],
                    ['type' => 'behavioral', 'minutes' => 25, 'prompt' => 'Behavioral: mentored someone through a hard production issue.'],
                ],
            ],
        ];
    }
}
