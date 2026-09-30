<?php

use App\Services\CodeRunner;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Code Playground')] class extends Component
{
    public string $code = "<?php\n\n\$name = 'Prasanth';\necho \$name;\n";

    public ?string $output = null;

    public ?string $error = null;

    public float $durationMs = 0;

    public function run(CodeRunner $runner): void
    {
        $this->output = null;
        $this->error = null;
        $this->durationMs = 0;

        try {
            $result = $runner->run($this->code);
            $this->output = $result['output'];
            $this->error = $result['error'];
            $this->durationMs = $result['duration_ms'];

            if (! $result['success'] && $this->error === null) {
                $this->error = 'Process exited with code '.$result['exit_code'];
            }
        } catch (\Throwable $e) {
            $this->error = $e->getMessage();
        }
    }

    public function resetCode(): void
    {
        $this->code = "<?php\n\n\$name = 'Prasanth';\necho \$name;\n";
        $this->output = null;
        $this->error = null;
    }

    public function render()
    {
        return $this->view()->layout('layouts::app', ['title' => __('Playground')]);
    }
};
?>
<div class="flex flex-1 flex-col gap-4">
    <div>
        <flux:heading size="xl" level="1">{{ __('Code Playground') }}</flux:heading>
        <flux:subheading>{{ __('Write PHP, run it in a sandboxed process (timeout + unsafe-function blocklist).') }}</flux:subheading>
    </div>

    <div class="rounded-lg border border-zinc-700 bg-zinc-950">
        <div class="flex items-center justify-between border-b border-zinc-800 px-3 py-2 text-xs text-zinc-400">
            <span>editor.php</span>
            <span>{{ $durationMs }} ms</span>
        </div>
        <textarea
            wire:model="code"
            rows="16"
            spellcheck="false"
            class="w-full bg-transparent p-4 font-mono text-sm text-emerald-300 outline-none"
        ></textarea>
    </div>

    <div class="flex flex-wrap gap-2">
        <flux:button variant="primary" wire:click="run" data-test="playground-run">{{ __('Run') }}</flux:button>
        <flux:button variant="ghost" wire:click="resetCode">{{ __('Reset') }}</flux:button>
    </div>

    @if ($output !== null && $output !== '')
        <div>
            <flux:text class="text-zinc-500">{{ __('stdout') }}</flux:text>
            <pre class="mt-1 overflow-auto rounded bg-black/40 p-4 text-sm text-zinc-100">{{ $output }}</pre>
        </div>
    @endif

    @if ($error)
        <flux:callout variant="danger" icon="exclamation-circle">
            <pre class="whitespace-pre-wrap text-sm">{{ $error }}</pre>
        </flux:callout>
    @endif
</div>
