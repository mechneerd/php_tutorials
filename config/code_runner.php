<?php

return [
    'enabled' => (bool) env('CODE_RUNNER_ENABLED', true),
    'timeout_seconds' => (int) env('CODE_RUNNER_TIMEOUT_SECONDS', 3),
];
