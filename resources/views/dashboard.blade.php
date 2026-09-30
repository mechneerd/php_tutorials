<x-layouts::app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:heading size="xl">{{ __('PHP Learning') }}</flux:heading>
        <flux:text>{{ __('Redirecting to your learning dashboard…') }}</flux:text>
        <flux:button variant="primary" href="{{ route('learn.index') }}" wire:navigate>
            {{ __('Open dashboard') }}
        </flux:button>
    </div>
</x-layouts::app>
