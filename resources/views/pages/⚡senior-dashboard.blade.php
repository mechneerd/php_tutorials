<?php

use App\Services\SeniorReadiness;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Senior Track')] class extends Component
{
    public function render(SeniorReadiness $readiness)
    {
        $report = $readiness->forUser(auth()->user());

        return $this->view([
            'report' => $report,
            'designCount' => \App\Models\DesignCase::query()->published()->count(),
            'incidentCount' => \App\Models\Incident::query()->published()->count(),
            'reviewCount' => \App\Models\CodeReviewDrill::query()->published()->count(),
            'internalsCount' => \App\Models\InternalsTopic::query()->published()->count(),
            'simulationCount' => \App\Models\InterviewSimulation::query()->published()->count(),
            'starReady' => \App\Models\StarStory::query()
                ->where('user_id', auth()->id())
                ->where('status', 'interview_ready')
                ->count(),
        ])->layout('layouts::app', ['title' => __('Senior track')]);
    }
};
?>
<div class="flex flex-1 flex-col gap-6">
    <div>
        <flux:heading size="xl" level="1">{{ __('Senior interview track') }}</flux:heading>
        <flux:subheading>{{ __('Design · incidents · code review · internals · STAR · full loop') }}</flux:subheading>
    </div>

    <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900" data-test="readiness-card">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <flux:heading size="lg" level="2">{{ __('Readiness') }}</flux:heading>
                <flux:text class="text-zinc-500">{{ $report['next_action'] }}</flux:text>
            </div>
            <div class="text-right">
                <div class="text-4xl font-bold" data-test="readiness-overall">{{ $report['overall'] }}</div>
                <flux:badge :variant="$report['gate_passed'] ? 'success' : 'gray'" data-test="readiness-gate">
                    {{ $report['gate_passed'] ? __('Gate passed') : __('Gate not met') }}
                </flux:badge>
            </div>
        </div>

        <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($report['pillars'] as $key => $score)
                <div class="rounded-lg bg-zinc-50 p-3 dark:bg-zinc-800">
                    <div class="text-xs uppercase tracking-wide text-zinc-500">{{ __($key) }}</div>
                    <div class="mt-1 text-xl font-semibold">{{ $score }}</div>
                    <div class="mt-2 h-1.5 rounded bg-zinc-200 dark:bg-zinc-700">
                        <div class="h-1.5 rounded bg-emerald-500" style="width: {{ $score }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        <flux:card class="p-5">
            <flux:heading size="sm">{{ __('System design studio') }}</flux:heading>
            <flux:text class="text-zinc-500">{{ $designCount }} cases · timed · rubric + model answer</flux:text>
            <flux:link :href="route('senior.design')" wire:navigate class="mt-3 inline-block">{{ __('Open studio') }}</flux:link>
        </flux:card>
        <flux:card class="p-5">
            <flux:heading size="sm">{{ __('Production debugging lab') }}</flux:heading>
            <flux:text class="text-zinc-500">{{ $incidentCount }} incidents · logs, metrics, diagnosis</flux:text>
            <flux:link :href="route('senior.incidents')" wire:navigate class="mt-3 inline-block">{{ __('Open war room') }}</flux:link>
        </flux:card>
        <flux:card class="p-5">
            <flux:heading size="sm">{{ __('Code review arena') }}</flux:heading>
            <flux:text class="text-zinc-500">{{ $reviewCount }} PRs · planted issues · F1 score</flux:text>
            <flux:link :href="route('senior.reviews')" wire:navigate class="mt-3 inline-block">{{ __('Open arena') }}</flux:link>
        </flux:card>
        <flux:card class="p-5">
            <flux:heading size="sm">{{ __('PHP internals') }}</flux:heading>
            <flux:text class="text-zinc-500">{{ $internalsCount }} topics · explain-back gate</flux:text>
            <flux:link :href="route('senior.internals')" wire:navigate class="mt-3 inline-block">{{ __('Open internals') }}</flux:link>
        </flux:card>
        <flux:card class="p-5">
            <flux:heading size="sm">{{ __('STAR stories') }}</flux:heading>
            <flux:text class="text-zinc-500">{{ $starReady }}/5 interview-ready · behavioral bank</flux:text>
            <flux:link :href="route('senior.star')" wire:navigate class="mt-3 inline-block">{{ __('Open stories') }}</flux:link>
        </flux:card>
        <flux:card class="p-5">
            <flux:heading size="sm">{{ __('Interview simulation') }}</flux:heading>
            <flux:text class="text-zinc-500">{{ $simulationCount }} full loops · coding + design + behavioral</flux:text>
            <flux:link :href="route('senior.simulation')" wire:navigate class="mt-3 inline-block">{{ __('Open simulation') }}</flux:link>
        </flux:card>
    </div>
</div>
