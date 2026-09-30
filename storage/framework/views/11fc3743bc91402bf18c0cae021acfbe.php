<?php # [BlazeFolded]:{flux::link}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/link.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::heading}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/heading.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::subheading}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/subheading.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::heading}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/heading.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::badge}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/badge/index.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::button}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/button/index.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::button}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/button/index.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::button}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/button/index.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::heading}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/heading.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::text}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/text.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::link}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/link.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::card}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/card/index.blade.php}:{1789433108} ?>
<?php
use App\Models\InterviewSimulation;
use App\Models\SimulationAttempt;
use Livewire\Attributes\Title;
use Livewire\Component;
?>

<div class="flex flex-1 flex-col gap-6">
    <div>
        <?php ob_start(); ?><a class="inline font-medium underline-offset-[6px] hover:decoration-current underline [[data-color]&gt;&amp;]:text-inherit [[data-color]&gt;&amp;]:decoration-current/20 dark:[[data-color]&gt;&amp;]:decoration-current/50 [[data-color]&gt;&amp;]:hover:decoration-current text-[var(--color-accent-content)] decoration-[color-mix(in_oklab,var(--color-accent-content),transparent_80%)] text-sm" <?php if (($__blazeAttr = route('senior.simulation')) !== false && !is_null($__blazeAttr)): ?>href="<?php echo e($__blazeAttr === true ? 'href' : $__blazeAttr); ?>"<?php endif; unset($__blazeAttr); ?> wire:navigate="" data-flux-link ><?php ob_start(); ?><?php echo e(__('← All loops')); ?><?php echo trim(ob_get_clean()); ?></a><?php echo ltrim(ob_get_clean()); ?>
        <?php ob_start(); ?><h1 class="font-medium [:where(&amp;)]:text-zinc-800 [:where(&amp;)]:dark:text-white text-2xl [&amp;:has(+[data-flux-subheading])]:mb-2 [[data-flux-subheading]+&amp;]:mt-2" data-flux-heading><?php ob_start(); ?><?php echo e($simulation->title); ?><?php echo trim(ob_get_clean()); ?></h1>

        <?php echo ltrim(ob_get_clean()); ?>
        <?php ob_start(); ?><div class="text-sm [:where(&amp;)]:text-zinc-500 [:where(&amp;)]:dark:text-white/70" data-flux-subheading>
    <?php ob_start(); ?><?php echo e($simulation->minutes_total); ?> minutes · segment <?php echo e($segmentIndex + 1); ?>/<?php echo e(count($segments)); ?><?php echo trim(ob_get_clean()); ?>

</div>
<?php echo ltrim(ob_get_clean()); ?>
    </div>

    <div class="flex flex-wrap gap-2">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $segments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $segment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <?php if (!function_exists('_3c7292da65f516e3deba6a0a6c6dcfcc')) { $__blaze->compile('C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/badge/index.blade.php', $__blaze->compiledPath.'/3c7292da65f516e3deba6a0a6c6dcfcc.php'); require $__blaze->compiledPath.'/3c7292da65f516e3deba6a0a6c6dcfcc.php'; } ?>
<?php if (isset($__slots3c7292da65f516e3deba6a0a6c6dcfcc)) { $__slotsStack3c7292da65f516e3deba6a0a6c6dcfcc[] = $__slots3c7292da65f516e3deba6a0a6c6dcfcc; } ?>
<?php if (isset($__attrs3c7292da65f516e3deba6a0a6c6dcfcc)) { $__attrsStack3c7292da65f516e3deba6a0a6c6dcfcc[] = $__attrs3c7292da65f516e3deba6a0a6c6dcfcc; } ?>
<?php $__attrs3c7292da65f516e3deba6a0a6c6dcfcc = ['variant' => $i === $segmentIndex ? 'success' : 'gray','class' => 'cursor-pointer','wire:click' => '$set(\'segmentIndex\', '.e($i).')']; ?>
<?php $__slots3c7292da65f516e3deba6a0a6c6dcfcc = []; ?>
<?php $__blaze->pushData($__attrs3c7292da65f516e3deba6a0a6c6dcfcc); ?>
<?php ob_start(); ?>
                <?php echo e($i + 1); ?>. <?php echo e($segment['type']); ?> (<?php echo e($segment['minutes']); ?>m)
            <?php $__slots3c7292da65f516e3deba6a0a6c6dcfcc['slot'] = new \Illuminate\View\ComponentSlot(trim(ob_get_clean()), []); ?>
<?php $__blaze->pushSlots($__slots3c7292da65f516e3deba6a0a6c6dcfcc); ?>
<?php _3c7292da65f516e3deba6a0a6c6dcfcc($__blaze, $__attrs3c7292da65f516e3deba6a0a6c6dcfcc, $__slots3c7292da65f516e3deba6a0a6c6dcfcc, ['variant'], [], $__this ?? (isset($this) ? $this : null)); ?>
<?php if (! empty($__slotsStack3c7292da65f516e3deba6a0a6c6dcfcc)) { $__slots3c7292da65f516e3deba6a0a6c6dcfcc = array_pop($__slotsStack3c7292da65f516e3deba6a0a6c6dcfcc); } ?>
<?php if (! empty($__attrsStack3c7292da65f516e3deba6a0a6c6dcfcc)) { $__attrs3c7292da65f516e3deba6a0a6c6dcfcc = array_pop($__attrsStack3c7292da65f516e3deba6a0a6c6dcfcc); } ?>
<?php $__blaze->popData(); ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
    </div>

    <div class="grid gap-2">
        <?php ob_start(); ?><div class="font-medium [:where(&amp;)]:text-zinc-800 [:where(&amp;)]:dark:text-white text-sm [&amp;:has(+[data-flux-subheading])]:mb-2 [[data-flux-subheading]+&amp;]:mt-2" data-test="simulation-segment-title" data-flux-heading><?php ob_start(); ?><?php echo e($current['prompt'] ?? $current['prompt_ref'] ?? ''); ?><?php echo trim(ob_get_clean()); ?></div>
<?php echo ltrim(ob_get_clean()); ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! empty($current['prompt_ref'])): ?>
            <?php ob_start(); ?><div data-flux-badge="data-flux-badge" class="inline-flex items-center font-medium whitespace-nowrap  [print-color-adjust:exact] text-sm py-1 **:data-flux-badge-icon:me-1.5 rounded-md px-2 text-zinc-700 [&amp;_button]:text-zinc-700! dark:text-zinc-200 dark:[&amp;_button]:text-zinc-200! bg-zinc-400/15 dark:bg-zinc-400/40 [&amp;:is(button)]:hover:bg-zinc-400/25 dark:[button]:hover:bg-zinc-400/50">
        <?php ob_start(); ?><?php echo e($current['prompt_ref']); ?><?php echo trim(ob_get_clean()); ?>

    </div>
<?php echo ltrim(ob_get_clean()); ?>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (! ($completed)): ?>
        <textarea
            wire:model="answers.<?php echo e($segmentIndex); ?>"
            rows="14"
            class="w-full rounded-lg border border-zinc-300 bg-white p-3 font-mono text-sm dark:border-zinc-600 dark:bg-zinc-900"
            data-test="simulation-answer"
            placeholder="Your answer / notes / code / STAR draft for this segment…"
        ></textarea>

        <div class="flex flex-wrap gap-3">
            <?php ob_start(); ?><button type="button" class="relative items-center font-medium justify-center whitespace-nowrap disabled:opacity-50 dark:disabled:opacity-50 disabled:cursor-default disabled:pointer-events-none disabled:shadow-none justify-center h-10 text-sm rounded-lg gap-2 ps-4 pe-4 inline-flex  bg-transparent hover:bg-zinc-800/5 dark:hover:bg-white/15 text-zinc-800 dark:text-white    *:transition-opacity [&amp;[data-loading]&gt;:not([data-flux-loading-indicator])]:opacity-0 [&amp;[data-flux-loading]&gt;:not([data-flux-loading-indicator])]:opacity-0 [&amp;[data-loading]&gt;[data-flux-loading-indicator]]:opacity-100 [&amp;[data-flux-loading]&gt;[data-flux-loading-indicator]]:opacity-100 data-loading:pointer-events-none data-flux-loading:pointer-events-none" data-flux-button="data-flux-button" wire:target="goPrev" wire:loading.attr="data-flux-loading" wire:click="goPrev">
        <div class="absolute inset-0 flex items-center justify-center opacity-0" data-flux-loading-indicator>
                <svg class="shrink-0 [:where(&amp;)]:size-4 animate-spin" data-flux-icon xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true" data-slot="icon">
    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
</svg>
                    </div>
        
        
                    
            
            <span><?php ob_start(); ?>Previous<?php echo trim(ob_get_clean()); ?></span>
    </button>
<?php echo ltrim(ob_get_clean()); ?>
            <?php ob_start(); ?><button type="button" class="relative items-center font-medium justify-center whitespace-nowrap disabled:opacity-50 dark:disabled:opacity-50 disabled:cursor-default disabled:pointer-events-none disabled:shadow-none justify-center h-10 text-sm rounded-lg gap-2 ps-4 pe-4 inline-flex  bg-transparent hover:bg-zinc-800/5 dark:hover:bg-white/15 text-zinc-800 dark:text-white    *:transition-opacity [&amp;[data-loading]&gt;:not([data-flux-loading-indicator])]:opacity-0 [&amp;[data-flux-loading]&gt;:not([data-flux-loading-indicator])]:opacity-0 [&amp;[data-loading]&gt;[data-flux-loading-indicator]]:opacity-100 [&amp;[data-flux-loading]&gt;[data-flux-loading-indicator]]:opacity-100 data-loading:pointer-events-none data-flux-loading:pointer-events-none" data-flux-button="data-flux-button" wire:target="goNext" wire:loading.attr="data-flux-loading" wire:click="goNext">
        <div class="absolute inset-0 flex items-center justify-center opacity-0" data-flux-loading-indicator>
                <svg class="shrink-0 [:where(&amp;)]:size-4 animate-spin" data-flux-icon xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true" data-slot="icon">
    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
</svg>
                    </div>
        
        
                    
            
            <span><?php ob_start(); ?>Next<?php echo trim(ob_get_clean()); ?></span>
    </button>
<?php echo ltrim(ob_get_clean()); ?>
            <?php ob_start(); ?><button type="button" class="relative items-center font-medium justify-center whitespace-nowrap disabled:opacity-50 dark:disabled:opacity-50 disabled:cursor-default disabled:pointer-events-none disabled:shadow-none justify-center h-10 text-sm rounded-lg gap-2 ps-4 pe-4 inline-flex  bg-white hover:bg-zinc-50 dark:bg-zinc-700 dark:hover:bg-zinc-600/75 text-zinc-800 dark:text-white border border-zinc-200 hover:border-zinc-200 disabled:border-zinc-200 border-b-zinc-300/80 dark:border-zinc-600 dark:hover:border-zinc-600 dark:disabled:border-zinc-600 shadow-xs [[data-flux-button-group]_&amp;]:border-s-0 [:is([data-flux-button-group]&gt;&amp;:first-child,_[data-flux-button-group]_:first-child&gt;&amp;)]:border-s-[1px] *:transition-opacity [&amp;[data-loading]&gt;:not([data-flux-loading-indicator])]:opacity-0 [&amp;[data-flux-loading]&gt;:not([data-flux-loading-indicator])]:opacity-0 [&amp;[data-loading]&gt;[data-flux-loading-indicator]]:opacity-100 [&amp;[data-flux-loading]&gt;[data-flux-loading-indicator]]:opacity-100 data-loading:pointer-events-none data-flux-loading:pointer-events-none" data-flux-button="data-flux-button" data-flux-group-target="data-flux-group-target" wire:target="finish" wire:loading.attr="data-flux-loading" wire:click="finish" data-test="simulation-finish">
        <div class="absolute inset-0 flex items-center justify-center opacity-0" data-flux-loading-indicator>
                <svg class="shrink-0 [:where(&amp;)]:size-4 animate-spin" data-flux-icon xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true" data-slot="icon">
    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
</svg>
                    </div>
        
        
                    
            
            <span><?php ob_start(); ?>Finish & score<?php echo trim(ob_get_clean()); ?></span>
    </button>
<?php echo ltrim(ob_get_clean()); ?>
        </div>
    <?php else: ?>
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-5 dark:border-emerald-800 dark:bg-emerald-950" data-test="simulation-result">
            <?php ob_start(); ?><div class="font-medium [:where(&amp;)]:text-zinc-800 [:where(&amp;)]:dark:text-white text-base [&amp;:has(+[data-flux-subheading])]:mb-2 [[data-flux-subheading]+&amp;]:mt-2" data-flux-heading><?php ob_start(); ?>Overall score: <?php echo e($overall); ?><?php echo trim(ob_get_clean()); ?></div>
<?php echo ltrim(ob_get_clean()); ?>
            <?php ob_start(); ?><p class="[:where(&amp;)]:font-normal [:where(&amp;)]:text-sm [:where(&amp;)]:text-zinc-500 [:where(&amp;)]:dark:text-white/70" data-flux-text ><?php ob_start(); ?><?php echo e($overall >= 70 ? 'Bar met for this loop — weak pillar next?' : 'Below bar — review gap report on dashboard and retry weakest segment.'); ?><?php echo trim(ob_get_clean()); ?></p><?php echo ltrim(ob_get_clean()); ?>
            <div class="mt-4 flex flex-wrap gap-3">
                <?php ob_start(); ?><a class="inline font-medium underline-offset-[6px] hover:decoration-current underline [[data-color]&gt;&amp;]:text-inherit [[data-color]&gt;&amp;]:decoration-current/20 dark:[[data-color]&gt;&amp;]:decoration-current/50 [[data-color]&gt;&amp;]:hover:decoration-current text-[var(--color-accent-content)] decoration-[color-mix(in_oklab,var(--color-accent-content),transparent_80%)]" <?php if (($__blazeAttr = route('senior.index')) !== false && !is_null($__blazeAttr)): ?>href="<?php echo e($__blazeAttr === true ? 'href' : $__blazeAttr); ?>"<?php endif; unset($__blazeAttr); ?> wire:navigate="" data-flux-link ><?php ob_start(); ?><?php echo e(__('Back to readiness')); ?><?php echo trim(ob_get_clean()); ?></a><?php echo ltrim(ob_get_clean()); ?>
            </div>
        </div>

        <div class="space-y-3">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $segments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $segment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <?php ob_start(); ?><div class="border [:where(&amp;)]:bg-white [:where(&amp;)]:border-zinc-200 dark:[:where(&amp;)]:bg-white/10 dark:[:where(&amp;)]:border-white/10 [:where(&amp;)]:p-6 [:where(&amp;)]:rounded-xl [--flux-bleed:1.5rem] p-4" data-flux-card>
    <?php ob_start(); ?>
                    <div class="text-sm font-semibold"><?php echo e($i + 1); ?>. <?php echo e($segment['type']); ?></div>
                    <pre class="mt-2 max-h-48 overflow-auto whitespace-pre-wrap text-xs"><?php echo e($this->answers[$i] ?? ''); ?></pre>
                <?php echo trim(ob_get_clean()); ?>

</div>
<?php echo ltrim(ob_get_clean()); ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div><?php /**PATH C:\wamp64\www\php_tutorials\storage\framework\views/livewire/views/05c56a15.blade.php ENDPATH**/ ?>