<?php

use App\Models\Incident;
use App\Models\IncidentAttempt;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Production debugging lab')] class extends Component
{
    public function render()
    {
        $incidents = Incident::query()->published()->orderBy('severity')->orderBy('id')->get();

        $attempts = IncidentAttempt::query()
            ->where('user_id', auth()->id())
            ->get()
            ->keyBy('incident_id');

        return $this->view([
            'incidents' => $incidents,
            'attempts' => $attempts,
        ])->layout('layouts::app', ['title' => __('Debugging lab')]);
    }
};
?>
<div class="flex flex-1 flex-col gap-6">
    <div>
        <flux:heading size="xl" level="1">{{ __('Production debugging lab') }}</flux:heading>
        <flux:subheading>{{ __('Diagnose from artifacts · defend root cause · fix checklist') }}</flux:subheading>
    </div>

    <div class="grid gap-4 md:grid-cols-2">
        @forelse ($incidents as $incident)
            @php $attempt = $attempts->get($incident->id); @endphp
            <flux:card class="flex flex-col p-5" data-test="incident-card">
                <div class="flex items-start justify-between gap-3">
                    <flux:heading size="sm">{{ $incident->title }}</flux:heading>
                    <flux:badge :variant="$incident->severity === 'sev1' ? 'danger' : ($incident->severity === 'sev2' ? 'warning' : 'gray')">
                        {{ $incident->severity }}
                    </flux:badge>
                </div>
                <flux:text class="mt-2 line-clamp-3 text-zinc-500">{{ $incident->symptom }}</flux:text>
                <div class="mt-4 flex items-center gap-2">
                    <flux:badge>{{ $incident->root_cause_category }}</flux:badge>
                    @if ($attempt?->is_complete)
                        <flux:badge variant="success">{{ $attempt->score }}</flux:badge>
                    @endif
                </div>
                <flux:link :href="route('senior.incidents.show', $incident)" wire:navigate class="mt-4 inline-block">
                    Open war room
                </flux:link>
            </flux:card>
        @empty
            <p>No incidents seeded.</p>
        @endforelse
    </div>
</div>
