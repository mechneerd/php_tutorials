<?php

namespace App\Services;

use Illuminate\Support\Facades\Process;
use RuntimeException;

class CodeRunner
{
    /**
     * Run PHP code in a sandboxed child process.
     *
     * @return array{success: bool, output: string, error: string|null, duration_ms: float, exit_code: int|null}
     */
    public function run(string $code, ?string $stdin = null): array
    {
        if (! (bool) config('code_runner.enabled')) {
            throw new RuntimeException('Code runner is disabled.');
        }

        $this->assertSafe($code);

        $timeout = (int) config('code_runner.timeout_seconds', 3);
        $temp = tempnam(sys_get_temp_dir(), 'php_learn_');

        if ($temp === false) {
            throw new RuntimeException('Unable to create temp file for code runner.');
        }

        $file = $temp.'.php';
        rename($temp, $file);

        try {
            file_put_contents($file, $code);

            $start = microtime(true);

            $result = Process::timeout($timeout)
                ->input($stdin ?? '')
                ->run([
                    PHP_BINARY,
                    '-d', 'display_errors=1',
                    '-d', 'error_reporting=E_ALL',
                    '-n',
                    $file,
                ]);

            $durationMs = round((microtime(true) - $start) * 1000, 2);

            return [
                'success' => $result->successful(),
                'output' => $result->output(),
                'error' => $result->errorOutput() !== '' ? $result->errorOutput() : null,
                'duration_ms' => $durationMs,
                'exit_code' => $result->exitCode(),
            ];
        } finally {
            if (is_file($file)) {
                @unlink($file);
            }
        }
    }

    /**
     * Compare stdout to expected output (trim + normalize newlines).
     */
    public function matchesExpected(string $output, string $expected): bool
    {
        return $this->normalize($output) === $this->normalize($expected);
    }

    private function normalize(string $value): string
    {
        $value = str_replace("\r\n", "\n", $value);

        return trim($value);
    }

    /**
     * Deny obvious dangerous constructs for shared hosting safety.
     * Defense-in-depth: process isolation + timeout remain the real boundary.
     */
    private function assertSafe(string $code): void
    {
        $patterns = [
            '/\beval\s*\(/i',
            '/\bshell_exec\s*\(/i',
            '/\bexec\s*\(/i',
            '/\bsystem\s*\(/i',
            '/\bpassthru\s*\(/i',
            '/\bproc_open\s*\(/i',
            '/\bpopen\s*\(/i',
            '/`[^`]*`/',
            '/\bcurl_exec\s*\(/i',
            '/\bfile_put_contents\s*\(/i',
            '/\bunlink\s*\(/i',
            '/\bfsockopen\s*\(/i',
            '/\bdl\s*\(/i',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $code) === 1) {
                throw new RuntimeException('Blocked potentially unsafe function in code runner.');
            }
        }
    }
}
