<?php # [BlazeFolded]:{flux::link}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/link.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::heading}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/heading.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::subheading}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/subheading.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::link}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/link.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::heading}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/heading.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::button}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/button/index.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::button}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/button/index.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::button}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/button/index.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::card}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/card/index.blade.php}:{1789433108} ?>
<?php
use App\Models\InternalsAttempt;
use App\Models\InternalsTopic;
use App\Models\Lesson;
use Livewire\Attributes\Title;
use Livewire\Component;
?>

<div class="flex flex-1 flex-col gap-6">
    <div>
        <?php ob_start(); ?><a class="inline font-medium underline-offset-[6px] hover:decoration-current underline [[data-color]&gt;&amp;]:text-inherit [[data-color]&gt;&amp;]:decoration-current/20 dark:[[data-color]&gt;&amp;]:decoration-current/50 [[data-color]&gt;&amp;]:hover:decoration-current text-[var(--color-accent-content)] decoration-[color-mix(in_oklab,var(--color-accent-content),transparent_80%)] text-sm" <?php if (($__blazeAttr = route('senior.internals')) !== false && !is_null($__blazeAttr)): ?>href="<?php echo e($__blazeAttr === true ? 'href' : $__blazeAttr); ?>"<?php endif; unset($__blazeAttr); ?> wire:navigate="" data-flux-link ><?php ob_start(); ?><?php echo e(__('← All topics')); ?><?php echo trim(ob_get_clean()); ?></a><?php echo ltrim(ob_get_clean()); ?>
        <div class="flex flex-wrap items-center gap-3">
            <?php ob_start(); ?><h1 class="font-medium [:where(&amp;)]:text-zinc-800 [:where(&amp;)]:dark:text-white text-2xl [&amp;:has(+[data-flux-subheading])]:mb-2 [[data-flux-subheading]+&amp;]:mt-2" data-flux-heading><?php ob_start(); ?><?php echo e($topic->title); ?><?php echo trim(ob_get_clean()); ?></h1>

        <?php echo ltrim(ob_get_clean()); ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($submitted): ?>
                <?php if (!function_exists('_3c7292da65f516e3deba6a0a6c6dcfcc')) { $__blaze->compile('C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/badge/index.blade.php', $__blaze->compiledPath.'/3c7292da65f516e3deba6a0a6c6dcfcc.php'); require $__blaze->compiledPath.'/3c7292da65f516e3deba6a0a6c6dcfcc.php'; } ?>
<?php if (isset($__slots3c7292da65f516e3deba6a0a6c6dcfcc)) { $__slotsStack3c7292da65f516e3deba6a0a6c6dcfcc[] = $__slots3c7292da65f516e3deba6a0a6c6dcfcc; } ?>
<?php if (isset($__attrs3c7292da65f516e3deba6a0a6c6dcfcc)) { $__attrsStack3c7292da65f516e3deba6a0a6c6dcfcc[] = $__attrs3c7292da65f516e3deba6a0a6c6dcfcc; } ?>
<?php $__attrs3c7292da65f516e3deba6a0a6c6dcfcc = ['variant' => $score >= 70 ? 'success' : 'warning','dataTest' => 'internals-score']; ?>
<?php $__slots3c7292da65f516e3deba6a0a6c6dcfcc = []; ?>
<?php $__blaze->pushData($__attrs3c7292da65f516e3deba6a0a6c6dcfcc); ?>
<?php ob_start(); ?><?php echo e($score); ?><?php $__slots3c7292da65f516e3deba6a0a6c6dcfcc['slot'] = new \Illuminate\View\ComponentSlot(trim(ob_get_clean()), []); ?>
<?php $__blaze->pushSlots($__slots3c7292da65f516e3deba6a0a6c6dcfcc); ?>
<?php _3c7292da65f516e3deba6a0a6c6dcfcc($__blaze, $__attrs3c7292da65f516e3deba6a0a6c6dcfcc, $__slots3c7292da65f516e3deba6a0a6c6dcfcc, ['variant'], ['dataTest' => 'data-test'], $__this ?? (isset($this) ? $this : null)); ?>
<?php if (! empty($__slotsStack3c7292da65f516e3deba6a0a6c6dcfcc)) { $__slots3c7292da65f516e3deba6a0a6c6dcfcc = array_pop($__slotsStack3c7292da65f516e3deba6a0a6c6dcfcc); } ?>
<?php if (! empty($__attrsStack3c7292da65f516e3deba6a0a6c6dcfcc)) { $__attrs3c7292da65f516e3deba6a0a6c6dcfcc = array_pop($__attrsStack3c7292da65f516e3deba6a0a6c6dcfcc); } ?>
<?php $__blaze->popData(); ?>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
        <?php ob_start(); ?><div class="text-sm [:where(&amp;)]:text-zinc-500 [:where(&amp;)]:dark:text-white/70" data-flux-subheading>
    <?php ob_start(); ?><?php echo e($topic->summary); ?><?php echo trim(ob_get_clean()); ?>

</div>
<?php echo ltrim(ob_get_clean()); ?>
    </div>

    <div class="prose dark:prose-invert max-w-none rounded-xl border border-zinc-200 p-5 dark:border-zinc-700" data-test="internals-body">
        <?php echo $topic->body_html; ?>

    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($related): ?>
        <?php ob_start(); ?><a class="inline font-medium underline-offset-[6px] hover:decoration-current underline [[data-color]&gt;&amp;]:text-inherit [[data-color]&gt;&amp;]:decoration-current/20 dark:[[data-color]&gt;&amp;]:decoration-current/50 [[data-color]&gt;&amp;]:hover:decoration-current text-[var(--color-accent-content)] decoration-[color-mix(in_oklab,var(--color-accent-content),transparent_80%)]" <?php if (($__blazeAttr = route('lessons.show', $related)) !== false && !is_null($__blazeAttr)): ?>href="<?php echo e($__blazeAttr === true ? 'href' : $__blazeAttr); ?>"<?php endif; unset($__blazeAttr); ?> wire:navigate="" data-flux-link ><?php ob_start(); ?>
            Related lesson: <?php echo e($related->code); ?> — <?php echo e($related->title); ?>

        <?php echo trim(ob_get_clean()); ?></a><?php echo ltrim(ob_get_clean()); ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($prompts): ?>
        <?php ob_start(); ?><div class="border [:where(&amp;)]:bg-white [:where(&amp;)]:border-zinc-200 dark:[:where(&amp;)]:bg-white/10 dark:[:where(&amp;)]:border-white/10 [:where(&amp;)]:p-6 [:where(&amp;)]:rounded-xl [--flux-bleed:1.5rem] p-5" data-flux-card>
    <?php ob_start(); ?>
            <?php ob_start(); ?><div class="font-medium [:where(&amp;)]:text-zinc-800 [:where(&amp;)]:dark:text-white text-sm [&amp;:has(+[data-flux-subheading])]:mb-2 [[data-flux-subheading]+&amp;]:mt-2" data-flux-heading><?php ob_start(); ?><?php echo e(__('Explain it back')); ?><?php echo trim(ob_get_clean()); ?></div>
<?php echo ltrim(ob_get_clean()); ?>
            <p class="mt-1 text-sm text-zinc-500">Prompt <?php echo e($promptIndex + 1); ?>/<?php echo e(count($prompts)); ?></p>
            <p class="mt-2 font-medium"><?php echo e($prompts[$promptIndex]); ?></p>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (! ($submitted)): ?>
                <textarea wire:model="answer" rows="6" class="mt-3 w-full rounded-lg border border-zinc-300 bg-white p-3 text-sm dark:border-zinc-600 dark:bg-zinc-900" data-test="internals-answer" placeholder="Answer as if the interviewer just asked…"></textarea>
                <div class="mt-3 flex flex-wrap gap-3">
                    <?php ob_start(); ?><button type="button" class="relative items-center font-medium justify-center whitespace-nowrap disabled:opacity-50 dark:disabled:opacity-50 disabled:cursor-default disabled:pointer-events-none disabled:shadow-none justify-center h-10 text-sm rounded-lg gap-2 ps-4 pe-4 inline-flex  bg-white hover:bg-zinc-50 dark:bg-zinc-700 dark:hover:bg-zinc-600/75 text-zinc-800 dark:text-white border border-zinc-200 hover:border-zinc-200 disabled:border-zinc-200 border-b-zinc-300/80 dark:border-zinc-600 dark:hover:border-zinc-600 dark:disabled:border-zinc-600 shadow-xs [[data-flux-button-group]_&amp;]:border-s-0 [:is([data-flux-button-group]&gt;&amp;:first-child,_[data-flux-button-group]_:first-child&gt;&amp;)]:border-s-[1px] *:transition-opacity [&amp;[data-loading]&gt;:not([data-flux-loading-indicator])]:opacity-0 [&amp;[data-flux-loading]&gt;:not([data-flux-loading-indicator])]:opacity-0 [&amp;[data-loading]&gt;[data-flux-loading-indicator]]:opacity-100 [&amp;[data-flux-loading]&gt;[data-flux-loading-indicator]]:opacity-100 data-loading:pointer-events-none data-flux-loading:pointer-events-none" data-flux-button="data-flux-button" data-flux-group-target="data-flux-group-target" wire:target="submit" wire:loading.attr="data-flux-loading" wire:click="submit" data-test="internals-submit">
        <div class="absolute inset-0 flex items-center justify-center opacity-0" data-flux-loading-indicator>
                <svg class="shrink-0 [:where(&amp;)]:size-4 animate-spin" data-flux-icon xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true" data-slot="icon">
    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
</svg>
                    </div>
        
        
                    
            
            <span><?php ob_start(); ?>Score my answer<?php echo trim(ob_get_clean()); ?></span>
    </button>
<?php echo ltrim(ob_get_clean()); ?>
                    <?php ob_start(); ?><button type="button" class="relative items-center font-medium justify-center whitespace-nowrap disabled:opacity-50 dark:disabled:opacity-50 disabled:cursor-default disabled:pointer-events-none disabled:shadow-none justify-center h-10 text-sm rounded-lg gap-2 ps-4 pe-4 inline-flex  bg-transparent hover:bg-zinc-800/5 dark:hover:bg-white/15 text-zinc-800 dark:text-white    *:transition-opacity [&amp;[data-loading]&gt;:not([data-flux-loading-indicator])]:opacity-0 [&amp;[data-flux-loading]&gt;:not([data-flux-loading-indicator])]:opacity-0 [&amp;[data-loading]&gt;[data-flux-loading-indicator]]:opacity-100 [&amp;[data-flux-loading]&gt;[data-flux-loading-indicator]]:opacity-100 data-loading:pointer-events-none data-flux-loading:pointer-events-none" data-flux-button="data-flux-button" wire:target="nextPrompt" wire:loading.attr="data-flux-loading" wire:click="nextPrompt">
        <div class="absolute inset-0 flex items-center justify-center opacity-0" data-flux-loading-indicator>
                <svg class="shrink-0 [:where(&amp;)]:size-4 animate-spin" data-flux-icon xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true" data-slot="icon">
    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
</svg>
                    </div>
        
        
                    
            
            <span><?php ob_start(); ?>Next prompt<?php echo trim(ob_get_clean()); ?></span>
    </button>
<?php echo ltrim(ob_get_clean()); ?>
                </div>
            <?php else: ?>
                <div class="mt-3 rounded-lg bg-zinc-50 p-4 text-sm dark:bg-zinc-800" data-test="internals-result">
                    Score <strong><?php echo e($score); ?></strong><?php echo e($score >= 70 ? ' — passed (≥70)' : ' — keep going (need 70)'); ?>

                    <div class="mt-2 whitespace-pre-wrap text-zinc-600 dark:text-zinc-400"><?php echo e($answer); ?></div>
                </div>
                <?php ob_start(); ?><button type="button" class="relative items-center font-medium justify-center whitespace-nowrap disabled:opacity-50 dark:disabled:opacity-50 disabled:cursor-default disabled:pointer-events-none disabled:shadow-none justify-center h-10 text-sm rounded-lg gap-2 ps-4 pe-4 inline-flex  bg-transparent hover:bg-zinc-800/5 dark:hover:bg-white/15 text-zinc-800 dark:text-white    *:transition-opacity [&amp;[data-loading]&gt;:not([data-flux-loading-indicator])]:opacity-0 [&amp;[data-flux-loading]&gt;:not([data-flux-loading-indicator])]:opacity-0 [&amp;[data-loading]&gt;[data-flux-loading-indicator]]:opacity-100 [&amp;[data-flux-loading]&gt;[data-flux-loading-indicator]]:opacity-100 data-loading:pointer-events-none data-flux-loading:pointer-events-none   mt-3" data-flux-button="data-flux-button" wire:target="nextPrompt" wire:loading.attr="data-flux-loading" wire:click="nextPrompt">
        <div class="absolute inset-0 flex items-center justify-center opacity-0" data-flux-loading-indicator>
                <svg class="shrink-0 [:where(&amp;)]:size-4 animate-spin" data-flux-icon xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true" data-slot="icon">
    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
</svg>
                    </div>
        
        
                    
            
            <span><?php ob_start(); ?>Next prompt<?php echo trim(ob_get_clean()); ?></span>
    </button>
<?php echo ltrim(ob_get_clean()); ?>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php echo trim(ob_get_clean()); ?>

</div>
<?php echo ltrim(ob_get_clean()); ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div><?php /**PATH C:\wamp64\www\php_tutorials\storage\framework\views/livewire/views/2c308123.blade.php ENDPATH**/ ?>