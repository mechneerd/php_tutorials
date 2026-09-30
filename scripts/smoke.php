<?php

use App\Models\Lesson;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

require __DIR__.'/../vendor/autoload.php';
$app = require __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

$paths = [
    '/',
    '/login',
    '/register',
    '/roadmap',
    '/learn',
    '/playground',
    '/mentor',
    '/interviews',
    '/projects',
    '/senior',
    '/senior/design',
    '/senior/incidents',
    '/senior/reviews',
    '/senior/internals',
    '/senior/star',
    '/senior/simulation',
];

$lesson = Lesson::query()->first();
if ($lesson) {
    $paths[] = '/lessons/'.$lesson->slug;
}

foreach ($paths as $path) {
    $request = Request::create($path, 'GET');
    $response = $kernel->handle($request);
    $status = $response->getStatusCode();
    $ok = in_array($status, [200, 302], true);
    echo ($ok ? 'OK  ' : 'FAIL').' '.$path.' => '.$status.PHP_EOL;
    $kernel->terminate($request, $response);
    if (! $ok) {
        exit(1);
    }
}
