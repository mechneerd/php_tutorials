<?php

use App\Models\Project;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Projects')] class extends Component
{
    public function render()
    {
        return $this->view([
            'projects' => Project::query()
                ->where('is_published', true)
                ->with('stage')
                ->orderBy('order_column')
                ->get(),
        ])->layout('layouts::app', ['title' => __('Projects')]);
    }
};
?>
<div class="flex flex-1 flex-col gap-4">
    <div>
        <flux:heading size="xl" level="1">{{ __('Projects') }}</flux:heading>
        <flux:subheading>{{ __('L1 CLI → L2 app → L3 OOP → L4 API → L5 authz → L6 production → L7 system design') }}</flux:subheading>
    </div>

    <div class="grid gap-4 md:grid-cols-2">
        @forelse ($projects as $project)
            <flux:card class="flex flex-col gap-3">
                <div class="flex items-start justify-between gap-2">
                    <flux:heading level="3">{{ $project->title }}</flux:heading>
                    <flux:badge>{{ $project->level }}</flux:badge>
                </div>

                <flux:text>{{ $project->description }}</flux:text>

                @if ($project->requirements)
                    <div>
                        <flux:text class="text-zinc-500">{{ __('Requirements') }}</flux:text>
                        <ul class="mt-1 list-inside list-disc text-sm">
                            @foreach ($project->requirements as $req)
                                <li>{{ $req }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if ($project->milestones)
                    <div>
                        <flux:text class="text-zinc-500">{{ __('Milestones') }}</flux:text>
                        <ul class="mt-1 flex flex-wrap gap-2">
                            @foreach ($project->milestones as $milestone)
                                <flux:badge>{{ $milestone['title'] ?? $milestone }}</flux:badge>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if ($project->evaluation_criteria)
                    <div>
                        <flux:text class="text-zinc-500">{{ __('Evaluation') }}</flux:text>
                        <ul class="mt-1 list-inside list-disc text-sm">
                            @foreach ($project->evaluation_criteria as $criterion)
                                <li>{{ $criterion }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </flux:card>
        @empty
            <flux:callout variant="info">{{ __('No projects seeded yet.') }}</flux:callout>
        @endforelse
    </div>
</div>
