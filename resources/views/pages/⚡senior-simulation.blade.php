<?php

use App\Models\InterviewSimulation;
use App\Models\SimulationAttempt;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Interview simulation')] class extends Component
{
    public function render()
    {
        $simulations = InterviewSimulation::query()->published()->orderBy('id')->get();

        $attempts = SimulationAttempt::query()
            ->where('user_id', auth()->id())
            ->where('is_complete', true)
            ->get()
            ->groupBy('interview_simulation_id')
            ->map(fn ($group) => $group->max('overall_score'));

        return $this->view([
            'simulations' => $simulations,
            'attempts' => $attempts,
        ])->layout('layouts::app', ['title' => __('Interview simulation')]);
    }
};
?>
<div class="flex flex-1 flex-col gap-6">
    <div>
        <flux:heading size="xl" level="1">{{ __('Interview simulation') }}</flux:heading>
        <flux:subheading>{{ __('Full loops · coding + design + internals/review + behavioral') }}</flux:subheading>
    </div>

    <div class="grid gap-4 md:grid-cols-2">
        @forelse ($simulations as $sim)
            @php $best = $attempts->get($sim->id); @endphp
            <flux:card class="flex flex-col p-5" data-test="simulation-card">
                <flux:heading size="sm">{{ $sim->title }}</flux:heading>
                <flux:text class="mt-1 text-zinc-500">{{ $sim->minutes_total }} minutes total</flux:text>
                <div class="mt-3 flex flex-wrap gap-2">
                    @foreach ($sim->segments as $segment)
                        <flux:badge>{{ $segment['type'] }} · {{ $segment['minutes'] }}m</flux:badge>
                    @endforeach
                    @if ($best)
                        <flux:badge variant="success">best {{ $best }}</flux:badge>
                    @endif
                </div>
                <flux:link :href="route('senior.simulation.show', $sim)" wire:navigate class="mt-4 inline-block">
                    Start loop
                </flux:link>
            </flux:card>
        @empty
            <p>No simulations seeded.</p>
        @endforelse
    </div>
</div>
