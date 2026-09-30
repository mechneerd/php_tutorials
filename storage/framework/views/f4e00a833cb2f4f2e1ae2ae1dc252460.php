<?php # [BlazeFolded]:{flux::heading}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/heading.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::badge}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/badge/index.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::badge}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/badge/index.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::button}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/button/index.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::button}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/button/index.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::button}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/button/index.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::button}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/button/index.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::text}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/text.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::callout}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/callout/index.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::card}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/card/index.blade.php}:{1789433108} ?>
<?php
use App\Models\Exercise;
use App\Models\ExerciseAttempt;
use App\Services\CodeRunner;
use Illuminate\Support\Collection;
use Livewire\Attributes\Title;
use Livewire\Component;
?>

<div class="flex flex-col gap-4" x-data>
    <?php ob_start(); ?><h2 class="font-medium [:where(&amp;)]:text-zinc-800 [:where(&amp;)]:dark:text-white text-sm [&amp;:has(+[data-flux-subheading])]:mb-2 [[data-flux-subheading]+&amp;]:mt-2" data-flux-heading><?php ob_start(); ?><?php echo e(__('Exercises')); ?><?php echo trim(ob_get_clean()); ?></h2>

        <?php echo ltrim(ob_get_clean()); ?>

    <div class="flex flex-wrap gap-2">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $exercises; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exercise): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <?php if (!function_exists('_edde4785f0f34069385094b7fab7b02b')) { $__blaze->compile('C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/button/index.blade.php', $__blaze->compiledPath.'/edde4785f0f34069385094b7fab7b02b.php'); require $__blaze->compiledPath.'/edde4785f0f34069385094b7fab7b02b.php'; } ?>
<?php if (isset($__slotsedde4785f0f34069385094b7fab7b02b)) { $__slotsStackedde4785f0f34069385094b7fab7b02b[] = $__slotsedde4785f0f34069385094b7fab7b02b; } ?>
<?php if (isset($__attrsedde4785f0f34069385094b7fab7b02b)) { $__attrsStackedde4785f0f34069385094b7fab7b02b[] = $__attrsedde4785f0f34069385094b7fab7b02b; } ?>
<?php $__attrsedde4785f0f34069385094b7fab7b02b = ['size' => 'sm','variant' => e($activeId === $exercise->id ? 'primary' : 'ghost'),'wire:click' => 'selectExercise('.e($exercise->id).')']; ?>
<?php $__slotsedde4785f0f34069385094b7fab7b02b = []; ?>
<?php $__blaze->pushData($__attrsedde4785f0f34069385094b7fab7b02b); ?>
<?php ob_start(); ?>
                L<?php echo e($exercise->level); ?> — <?php echo e($exercise->title); ?>

            <?php $__slotsedde4785f0f34069385094b7fab7b02b['slot'] = new \Illuminate\View\ComponentSlot(trim(ob_get_clean()), []); ?>
<?php $__blaze->pushSlots($__slotsedde4785f0f34069385094b7fab7b02b); ?>
<?php _edde4785f0f34069385094b7fab7b02b($__blaze, $__attrsedde4785f0f34069385094b7fab7b02b, $__slotsedde4785f0f34069385094b7fab7b02b, [], [], $__this ?? (isset($this) ? $this : null)); ?>
<?php if (! empty($__slotsStackedde4785f0f34069385094b7fab7b02b)) { $__slotsedde4785f0f34069385094b7fab7b02b = array_pop($__slotsStackedde4785f0f34069385094b7fab7b02b); } ?>
<?php if (! empty($__attrsStackedde4785f0f34069385094b7fab7b02b)) { $__attrsedde4785f0f34069385094b7fab7b02b = array_pop($__attrsStackedde4785f0f34069385094b7fab7b02b); } ?>
<?php $__blaze->popData(); ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
    </div>

    <?php ($active = $exercises->firstWhere('id', $activeId)); ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($active): ?>
        <?php ob_start(); ?><div class="border [:where(&amp;)]:bg-white [:where(&amp;)]:border-zinc-200 dark:[:where(&amp;)]:bg-white/10 dark:[:where(&amp;)]:border-white/10 [:where(&amp;)]:p-6 [:where(&amp;)]:rounded-xl [--flux-bleed:1.5rem] flex flex-col gap-3" data-flux-card>
    <?php ob_start(); ?>
            <div class="flex flex-wrap items-center gap-2">
                <?php ob_start(); ?><div data-flux-badge="data-flux-badge" class="inline-flex items-center font-medium whitespace-nowrap  [print-color-adjust:exact] text-sm py-1 **:data-flux-badge-icon:me-1.5 rounded-md px-2 text-zinc-700 [&amp;_button]:text-zinc-700! dark:text-zinc-200 dark:[&amp;_button]:text-zinc-200! bg-zinc-400/15 dark:bg-zinc-400/40 [&amp;:is(button)]:hover:bg-zinc-400/25 dark:[button]:hover:bg-zinc-400/50">
        <?php ob_start(); ?>Level <?php echo e($active->level); ?><?php echo trim(ob_get_clean()); ?>

    </div>
<?php echo ltrim(ob_get_clean()); ?>
                <?php ob_start(); ?><div data-flux-badge="data-flux-badge" class="inline-flex items-center font-medium whitespace-nowrap  [print-color-adjust:exact] text-sm py-1 **:data-flux-badge-icon:me-1.5 rounded-md px-2 text-zinc-700 [&amp;_button]:text-zinc-700! dark:text-zinc-200 dark:[&amp;_button]:text-zinc-200! bg-zinc-400/15 dark:bg-zinc-400/40 [&amp;:is(button)]:hover:bg-zinc-400/25 dark:[button]:hover:bg-zinc-400/50">
        <?php ob_start(); ?><?php echo e($active->type); ?><?php echo trim(ob_get_clean()); ?>

    </div>
<?php echo ltrim(ob_get_clean()); ?>
            </div>
            <p class="whitespace-pre-wrap text-sm"><?php echo e($active->prompt); ?></p>

            <div class="rounded-lg border border-zinc-700 bg-zinc-950">
                <div class="flex items-center justify-between border-b border-zinc-800 px-3 py-2 text-xs text-zinc-400">
                    <span>PHP Editor</span>
                    <span><?php echo e($durationMs); ?> ms</span>
                </div>
                <textarea
                    wire:model="code"
                    rows="12"
                    spellcheck="false"
                    class="w-full bg-transparent p-3 font-mono text-sm text-emerald-300 outline-none"
                    placeholder="&lt;?php echo 'Hello';"
                ></textarea>
            </div>

            <div class="flex flex-wrap gap-2">
                <?php ob_start(); ?><button type="button" class="relative items-center font-medium justify-center whitespace-nowrap disabled:opacity-50 dark:disabled:opacity-50 disabled:cursor-default disabled:pointer-events-none disabled:shadow-none justify-center h-10 text-sm rounded-lg gap-2 ps-4 pe-4 inline-flex  bg-[var(--color-accent)] hover:bg-[color-mix(in_oklab,_var(--color-accent),_transparent_10%)] text-[var(--color-accent-foreground)] border border-black/10 dark:border-0 shadow-[inset_0px_1px_--theme(--color-white/.2)] [[data-flux-button-group]_&amp;]:border-e-0 [:is([data-flux-button-group]&gt;&amp;:last-child,_[data-flux-button-group]_:last-child&gt;&amp;)]:border-e-[1px] dark:[:is([data-flux-button-group]&gt;&amp;:last-child,_[data-flux-button-group]_:last-child&gt;&amp;)]:border-e-0 [:is([data-flux-button-group]&gt;&amp;:not(:first-child),_[data-flux-button-group]_:not(:first-child)&gt;&amp;)]:border-s-[1px]! [:is([data-flux-button-group]&gt;&amp;:not(:first-child),_[data-flux-button-group]_:not(:first-child)&gt;&amp;)]:border-s-[color-mix(in_srgb,var(--color-accent-foreground),transparent_85%)] *:transition-opacity [&amp;[data-loading]&gt;:not([data-flux-loading-indicator])]:opacity-0 [&amp;[data-flux-loading]&gt;:not([data-flux-loading-indicator])]:opacity-0 [&amp;[data-loading]&gt;[data-flux-loading-indicator]]:opacity-100 [&amp;[data-flux-loading]&gt;[data-flux-loading-indicator]]:opacity-100 data-loading:pointer-events-none data-flux-loading:pointer-events-none" data-flux-button="data-flux-button" data-flux-group-target="data-flux-group-target" wire:target="run" wire:loading.attr="data-flux-loading" wire:click="run" data-test="run-code">
        <div class="absolute inset-0 flex items-center justify-center opacity-0" data-flux-loading-indicator>
                <svg class="shrink-0 [:where(&amp;)]:size-4 animate-spin" data-flux-icon xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true" data-slot="icon">
    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
</svg>
                    </div>
        
        
                    
            
            <span><?php ob_start(); ?><?php echo e(__('Run')); ?><?php echo trim(ob_get_clean()); ?></span>
    </button>
<?php echo ltrim(ob_get_clean()); ?>
                <?php ob_start(); ?><button type="button" class="relative items-center font-medium justify-center whitespace-nowrap disabled:opacity-50 dark:disabled:opacity-50 disabled:cursor-default disabled:pointer-events-none disabled:shadow-none justify-center h-10 text-sm rounded-lg gap-2 ps-4 pe-4 inline-flex  bg-white hover:bg-zinc-50 dark:bg-zinc-700 dark:hover:bg-zinc-600/75 text-zinc-800 dark:text-white border border-zinc-200 hover:border-zinc-200 disabled:border-zinc-200 border-b-zinc-300/80 dark:border-zinc-600 dark:hover:border-zinc-600 dark:disabled:border-zinc-600 shadow-xs [[data-flux-button-group]_&amp;]:border-s-0 [:is([data-flux-button-group]&gt;&amp;:first-child,_[data-flux-button-group]_:first-child&gt;&amp;)]:border-s-[1px] *:transition-opacity [&amp;[data-loading]&gt;:not([data-flux-loading-indicator])]:opacity-0 [&amp;[data-flux-loading]&gt;:not([data-flux-loading-indicator])]:opacity-0 [&amp;[data-loading]&gt;[data-flux-loading-indicator]]:opacity-100 [&amp;[data-flux-loading]&gt;[data-flux-loading-indicator]]:opacity-100 data-loading:pointer-events-none data-flux-loading:pointer-events-none" data-flux-button="data-flux-button" data-flux-group-target="data-flux-group-target" wire:target="submit" wire:loading.attr="data-flux-loading" wire:click="submit" data-test="submit-code">
        <div class="absolute inset-0 flex items-center justify-center opacity-0" data-flux-loading-indicator>
                <svg class="shrink-0 [:where(&amp;)]:size-4 animate-spin" data-flux-icon xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true" data-slot="icon">
    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
</svg>
                    </div>
        
        
                    
            
            <span><?php ob_start(); ?><?php echo e(__('Submit')); ?><?php echo trim(ob_get_clean()); ?></span>
    </button>
<?php echo ltrim(ob_get_clean()); ?>
                <?php ob_start(); ?><button type="button" class="relative items-center font-medium justify-center whitespace-nowrap disabled:opacity-50 dark:disabled:opacity-50 disabled:cursor-default disabled:pointer-events-none disabled:shadow-none justify-center h-10 text-sm rounded-lg gap-2 ps-4 pe-4 inline-flex  bg-transparent hover:bg-zinc-800/5 dark:hover:bg-white/15 text-zinc-800 dark:text-white    *:transition-opacity [&amp;[data-loading]&gt;:not([data-flux-loading-indicator])]:opacity-0 [&amp;[data-flux-loading]&gt;:not([data-flux-loading-indicator])]:opacity-0 [&amp;[data-loading]&gt;[data-flux-loading-indicator]]:opacity-100 [&amp;[data-flux-loading]&gt;[data-flux-loading-indicator]]:opacity-100 data-loading:pointer-events-none data-flux-loading:pointer-events-none" data-flux-button="data-flux-button" wire:target="showHint" wire:loading.attr="data-flux-loading" wire:click="showHint">
        <div class="absolute inset-0 flex items-center justify-center opacity-0" data-flux-loading-indicator>
                <svg class="shrink-0 [:where(&amp;)]:size-4 animate-spin" data-flux-icon xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true" data-slot="icon">
    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
</svg>
                    </div>
        
        
                    
            
            <span><?php ob_start(); ?><?php echo e(__('Hint')); ?><?php echo trim(ob_get_clean()); ?></span>
    </button>
<?php echo ltrim(ob_get_clean()); ?>
                <?php ob_start(); ?><button type="button" class="relative items-center font-medium justify-center whitespace-nowrap disabled:opacity-50 dark:disabled:opacity-50 disabled:cursor-default disabled:pointer-events-none disabled:shadow-none justify-center h-10 text-sm rounded-lg gap-2 ps-4 pe-4 inline-flex  bg-transparent hover:bg-zinc-800/5 dark:hover:bg-white/15 text-zinc-800 dark:text-white    *:transition-opacity [&amp;[data-loading]&gt;:not([data-flux-loading-indicator])]:opacity-0 [&amp;[data-flux-loading]&gt;:not([data-flux-loading-indicator])]:opacity-0 [&amp;[data-loading]&gt;[data-flux-loading-indicator]]:opacity-100 [&amp;[data-flux-loading]&gt;[data-flux-loading-indicator]]:opacity-100 data-loading:pointer-events-none data-flux-loading:pointer-events-none" data-flux-button="data-flux-button" wire:target="revealSolution" wire:loading.attr="data-flux-loading" wire:click="revealSolution">
        <div class="absolute inset-0 flex items-center justify-center opacity-0" data-flux-loading-indicator>
                <svg class="shrink-0 [:where(&amp;)]:size-4 animate-spin" data-flux-icon xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true" data-slot="icon">
    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
</svg>
                    </div>
        
        
                    
            
            <span><?php ob_start(); ?><?php echo e(__('Show solution')); ?><?php echo trim(ob_get_clean()); ?></span>
    </button>
<?php echo ltrim(ob_get_clean()); ?>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($feedback): ?>
                <?php if (!function_exists('_4de0ac57123305c2f2dda8b4a02af66f')) { $__blaze->compile('C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/callout/index.blade.php', $__blaze->compiledPath.'/4de0ac57123305c2f2dda8b4a02af66f.php'); require $__blaze->compiledPath.'/4de0ac57123305c2f2dda8b4a02af66f.php'; } ?>
<?php if (isset($__slots4de0ac57123305c2f2dda8b4a02af66f)) { $__slotsStack4de0ac57123305c2f2dda8b4a02af66f[] = $__slots4de0ac57123305c2f2dda8b4a02af66f; } ?>
<?php if (isset($__attrs4de0ac57123305c2f2dda8b4a02af66f)) { $__attrsStack4de0ac57123305c2f2dda8b4a02af66f[] = $__attrs4de0ac57123305c2f2dda8b4a02af66f; } ?>
<?php $__attrs4de0ac57123305c2f2dda8b4a02af66f = ['variant' => e($passed ? 'success' : 'info'),'icon' => 'information-circle']; ?>
<?php $__slots4de0ac57123305c2f2dda8b4a02af66f = []; ?>
<?php $__blaze->pushData($__attrs4de0ac57123305c2f2dda8b4a02af66f); ?>
<?php ob_start(); ?>
                    <?php echo e($feedback); ?>

                <?php $__slots4de0ac57123305c2f2dda8b4a02af66f['slot'] = new \Illuminate\View\ComponentSlot(trim(ob_get_clean()), []); ?>
<?php $__blaze->pushSlots($__slots4de0ac57123305c2f2dda8b4a02af66f); ?>
<?php _4de0ac57123305c2f2dda8b4a02af66f($__blaze, $__attrs4de0ac57123305c2f2dda8b4a02af66f, $__slots4de0ac57123305c2f2dda8b4a02af66f, [], [], $__this ?? (isset($this) ? $this : null)); ?>
<?php if (! empty($__slotsStack4de0ac57123305c2f2dda8b4a02af66f)) { $__slots4de0ac57123305c2f2dda8b4a02af66f = array_pop($__slotsStack4de0ac57123305c2f2dda8b4a02af66f); } ?>
<?php if (! empty($__attrsStack4de0ac57123305c2f2dda8b4a02af66f)) { $__attrs4de0ac57123305c2f2dda8b4a02af66f = array_pop($__attrsStack4de0ac57123305c2f2dda8b4a02af66f); } ?>
<?php $__blaze->popData(); ?>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($output !== null && $output !== ''): ?>
                <div>
                    <?php ob_start(); ?><p class="[:where(&amp;)]:font-normal [:where(&amp;)]:text-sm [:where(&amp;)]:text-zinc-500 [:where(&amp;)]:dark:text-white/70 text-zinc-500" data-flux-text ><?php ob_start(); ?><?php echo e(__('Output')); ?><?php echo trim(ob_get_clean()); ?></p><?php echo ltrim(ob_get_clean()); ?>
                    <pre class="mt-1 overflow-auto rounded bg-black/40 p-3 text-sm text-zinc-200"><?php echo e($output); ?></pre>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($error): ?>
                <?php ob_start(); ?><div class="@container p-2 flex border rounded-xl border-(--callout-border) bg-(--callout-background) [&amp;_[data-slot=heading]]:text-(--callout-heading) [&amp;_[data-slot=text]]:text-(--callout-text) [--callout-border:var(--color-red-200)] dark:[--callout-border:color-mix(in_oklab,var(--color-red-400),transparent_50%)] [--callout-background:var(--color-red-50)] dark:[--callout-background:color-mix(in_oklab,var(--color-red-400),transparent_90%)] [--callout-heading:var(--color-red-700)] dark:[--callout-heading:var(--color-red-200)] [--callout-text:var(--color-red-700)] dark:[--callout-text:var(--color-red-300)] [--callout-icon:var(--color-red-400)] dark:[--callout-icon:var(--color-red-400)]" data-flux-callout>
            <div class="ps-2 py-2 pe-0 flex items-baseline">
            <svg class="shrink-0 [:where(&amp;)]:size-5 inline-block size-5 text-[var(--callout-icon)] dark:text-[var(--callout-icon)]" data-flux-icon xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
  <path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-8-5a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-1.5 0v-4.5A.75.75 0 0 1 10 5Zm0 10a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd"/>
</svg>

                </div>
    
    <div class="ps-2 flex-1 ">
        <div class="flex-1 py-2 pe-3 @md:pe-4 flex flex-col justify-center gap-2" data-slot="content">
            
            
            <?php ob_start(); ?>
                    <pre class="whitespace-pre-wrap text-sm"><?php echo e($error); ?></pre>
                <?php echo trim(ob_get_clean()); ?>

        </div>

            </div>

    </div>
<?php echo ltrim(ob_get_clean()); ?>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php echo trim(ob_get_clean()); ?>

</div>
<?php echo ltrim(ob_get_clean()); ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div><?php /**PATH C:\wamp64\www\php_tutorials\storage\framework\views/livewire/views/0212065f.blade.php ENDPATH**/ ?>