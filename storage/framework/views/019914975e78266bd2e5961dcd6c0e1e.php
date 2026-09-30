<?php # [BlazeFolded]:{flux::heading}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/heading.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::subheading}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/subheading.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::heading}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/heading.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::text}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/text.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::heading}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/heading.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::text}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/text.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::link}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/link.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::card}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/card/index.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::heading}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/heading.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::text}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/text.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::link}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/link.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::card}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/card/index.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::heading}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/heading.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::text}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/text.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::link}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/link.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::card}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/card/index.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::heading}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/heading.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::text}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/text.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::link}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/link.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::card}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/card/index.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::heading}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/heading.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::text}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/text.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::link}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/link.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::card}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/card/index.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::heading}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/heading.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::text}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/text.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::link}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/link.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::card}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/card/index.blade.php}:{1789433108} ?>
<?php
use App\Services\SeniorReadiness;
use Livewire\Attributes\Title;
use Livewire\Component;
?>

<div class="flex flex-1 flex-col gap-6">
    <div>
        <?php ob_start(); ?><h1 class="font-medium [:where(&amp;)]:text-zinc-800 [:where(&amp;)]:dark:text-white text-2xl [&amp;:has(+[data-flux-subheading])]:mb-2 [[data-flux-subheading]+&amp;]:mt-2" data-flux-heading><?php ob_start(); ?><?php echo e(__('Senior interview track')); ?><?php echo trim(ob_get_clean()); ?></h1>

        <?php echo ltrim(ob_get_clean()); ?>
        <?php ob_start(); ?><div class="text-sm [:where(&amp;)]:text-zinc-500 [:where(&amp;)]:dark:text-white/70" data-flux-subheading>
    <?php ob_start(); ?><?php echo e(__('Design · incidents · code review · internals · STAR · full loop')); ?><?php echo trim(ob_get_clean()); ?>

</div>
<?php echo ltrim(ob_get_clean()); ?>
    </div>

    <div class="rounded-xl border border-zinc-200 bg-white p-6 dark:border-zinc-700 dark:bg-zinc-900" data-test="readiness-card">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <?php ob_start(); ?><h2 class="font-medium [:where(&amp;)]:text-zinc-800 [:where(&amp;)]:dark:text-white text-base [&amp;:has(+[data-flux-subheading])]:mb-2 [[data-flux-subheading]+&amp;]:mt-2" data-flux-heading><?php ob_start(); ?><?php echo e(__('Readiness')); ?><?php echo trim(ob_get_clean()); ?></h2>

        <?php echo ltrim(ob_get_clean()); ?>
                <?php ob_start(); ?><p class="[:where(&amp;)]:font-normal [:where(&amp;)]:text-sm [:where(&amp;)]:text-zinc-500 [:where(&amp;)]:dark:text-white/70 text-zinc-500" data-flux-text ><?php ob_start(); ?><?php echo e($report['next_action']); ?><?php echo trim(ob_get_clean()); ?></p><?php echo ltrim(ob_get_clean()); ?>
            </div>
            <div class="text-right">
                <div class="text-4xl font-bold" data-test="readiness-overall"><?php echo e($report['overall']); ?></div>
                <?php if (!function_exists('_3c7292da65f516e3deba6a0a6c6dcfcc')) { $__blaze->compile('C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/badge/index.blade.php', $__blaze->compiledPath.'/3c7292da65f516e3deba6a0a6c6dcfcc.php'); require $__blaze->compiledPath.'/3c7292da65f516e3deba6a0a6c6dcfcc.php'; } ?>
<?php if (isset($__slots3c7292da65f516e3deba6a0a6c6dcfcc)) { $__slotsStack3c7292da65f516e3deba6a0a6c6dcfcc[] = $__slots3c7292da65f516e3deba6a0a6c6dcfcc; } ?>
<?php if (isset($__attrs3c7292da65f516e3deba6a0a6c6dcfcc)) { $__attrsStack3c7292da65f516e3deba6a0a6c6dcfcc[] = $__attrs3c7292da65f516e3deba6a0a6c6dcfcc; } ?>
<?php $__attrs3c7292da65f516e3deba6a0a6c6dcfcc = ['variant' => $report['gate_passed'] ? 'success' : 'gray','dataTest' => 'readiness-gate']; ?>
<?php $__slots3c7292da65f516e3deba6a0a6c6dcfcc = []; ?>
<?php $__blaze->pushData($__attrs3c7292da65f516e3deba6a0a6c6dcfcc); ?>
<?php ob_start(); ?>
                    <?php echo e($report['gate_passed'] ? __('Gate passed') : __('Gate not met')); ?>

                <?php $__slots3c7292da65f516e3deba6a0a6c6dcfcc['slot'] = new \Illuminate\View\ComponentSlot(trim(ob_get_clean()), []); ?>
<?php $__blaze->pushSlots($__slots3c7292da65f516e3deba6a0a6c6dcfcc); ?>
<?php _3c7292da65f516e3deba6a0a6c6dcfcc($__blaze, $__attrs3c7292da65f516e3deba6a0a6c6dcfcc, $__slots3c7292da65f516e3deba6a0a6c6dcfcc, ['variant'], ['dataTest' => 'data-test'], $__this ?? (isset($this) ? $this : null)); ?>
<?php if (! empty($__slotsStack3c7292da65f516e3deba6a0a6c6dcfcc)) { $__slots3c7292da65f516e3deba6a0a6c6dcfcc = array_pop($__slotsStack3c7292da65f516e3deba6a0a6c6dcfcc); } ?>
<?php if (! empty($__attrsStack3c7292da65f516e3deba6a0a6c6dcfcc)) { $__attrs3c7292da65f516e3deba6a0a6c6dcfcc = array_pop($__attrsStack3c7292da65f516e3deba6a0a6c6dcfcc); } ?>
<?php $__blaze->popData(); ?>
            </div>
        </div>

        <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $report['pillars']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $score): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                <div class="rounded-lg bg-zinc-50 p-3 dark:bg-zinc-800">
                    <div class="text-xs uppercase tracking-wide text-zinc-500"><?php echo e(__($key)); ?></div>
                    <div class="mt-1 text-xl font-semibold"><?php echo e($score); ?></div>
                    <div class="mt-2 h-1.5 rounded bg-zinc-200 dark:bg-zinc-700">
                        <div class="h-1.5 rounded bg-emerald-500" style="width: <?php echo e($score); ?>%"></div>
                    </div>
                </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
        </div>
    </div>

    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        <?php ob_start(); ?><div class="border [:where(&amp;)]:bg-white [:where(&amp;)]:border-zinc-200 dark:[:where(&amp;)]:bg-white/10 dark:[:where(&amp;)]:border-white/10 [:where(&amp;)]:p-6 [:where(&amp;)]:rounded-xl [--flux-bleed:1.5rem] p-5" data-flux-card>
    <?php ob_start(); ?>
            <?php ob_start(); ?><div class="font-medium [:where(&amp;)]:text-zinc-800 [:where(&amp;)]:dark:text-white text-sm [&amp;:has(+[data-flux-subheading])]:mb-2 [[data-flux-subheading]+&amp;]:mt-2" data-flux-heading><?php ob_start(); ?><?php echo e(__('System design studio')); ?><?php echo trim(ob_get_clean()); ?></div>
<?php echo ltrim(ob_get_clean()); ?>
            <?php ob_start(); ?><p class="[:where(&amp;)]:font-normal [:where(&amp;)]:text-sm [:where(&amp;)]:text-zinc-500 [:where(&amp;)]:dark:text-white/70 text-zinc-500" data-flux-text ><?php ob_start(); ?><?php echo e($designCount); ?> cases · timed · rubric + model answer<?php echo trim(ob_get_clean()); ?></p><?php echo ltrim(ob_get_clean()); ?>
            <?php ob_start(); ?><a class="inline font-medium underline-offset-[6px] hover:decoration-current underline [[data-color]&gt;&amp;]:text-inherit [[data-color]&gt;&amp;]:decoration-current/20 dark:[[data-color]&gt;&amp;]:decoration-current/50 [[data-color]&gt;&amp;]:hover:decoration-current text-[var(--color-accent-content)] decoration-[color-mix(in_oklab,var(--color-accent-content),transparent_80%)] mt-3 inline-block" <?php if (($__blazeAttr = route('senior.design')) !== false && !is_null($__blazeAttr)): ?>href="<?php echo e($__blazeAttr === true ? 'href' : $__blazeAttr); ?>"<?php endif; unset($__blazeAttr); ?> wire:navigate="" data-flux-link ><?php ob_start(); ?><?php echo e(__('Open studio')); ?><?php echo trim(ob_get_clean()); ?></a><?php echo ltrim(ob_get_clean()); ?>
        <?php echo trim(ob_get_clean()); ?>

</div>
<?php echo ltrim(ob_get_clean()); ?>
        <?php ob_start(); ?><div class="border [:where(&amp;)]:bg-white [:where(&amp;)]:border-zinc-200 dark:[:where(&amp;)]:bg-white/10 dark:[:where(&amp;)]:border-white/10 [:where(&amp;)]:p-6 [:where(&amp;)]:rounded-xl [--flux-bleed:1.5rem] p-5" data-flux-card>
    <?php ob_start(); ?>
            <?php ob_start(); ?><div class="font-medium [:where(&amp;)]:text-zinc-800 [:where(&amp;)]:dark:text-white text-sm [&amp;:has(+[data-flux-subheading])]:mb-2 [[data-flux-subheading]+&amp;]:mt-2" data-flux-heading><?php ob_start(); ?><?php echo e(__('Production debugging lab')); ?><?php echo trim(ob_get_clean()); ?></div>
<?php echo ltrim(ob_get_clean()); ?>
            <?php ob_start(); ?><p class="[:where(&amp;)]:font-normal [:where(&amp;)]:text-sm [:where(&amp;)]:text-zinc-500 [:where(&amp;)]:dark:text-white/70 text-zinc-500" data-flux-text ><?php ob_start(); ?><?php echo e($incidentCount); ?> incidents · logs, metrics, diagnosis<?php echo trim(ob_get_clean()); ?></p><?php echo ltrim(ob_get_clean()); ?>
            <?php ob_start(); ?><a class="inline font-medium underline-offset-[6px] hover:decoration-current underline [[data-color]&gt;&amp;]:text-inherit [[data-color]&gt;&amp;]:decoration-current/20 dark:[[data-color]&gt;&amp;]:decoration-current/50 [[data-color]&gt;&amp;]:hover:decoration-current text-[var(--color-accent-content)] decoration-[color-mix(in_oklab,var(--color-accent-content),transparent_80%)] mt-3 inline-block" <?php if (($__blazeAttr = route('senior.incidents')) !== false && !is_null($__blazeAttr)): ?>href="<?php echo e($__blazeAttr === true ? 'href' : $__blazeAttr); ?>"<?php endif; unset($__blazeAttr); ?> wire:navigate="" data-flux-link ><?php ob_start(); ?><?php echo e(__('Open war room')); ?><?php echo trim(ob_get_clean()); ?></a><?php echo ltrim(ob_get_clean()); ?>
        <?php echo trim(ob_get_clean()); ?>

</div>
<?php echo ltrim(ob_get_clean()); ?>
        <?php ob_start(); ?><div class="border [:where(&amp;)]:bg-white [:where(&amp;)]:border-zinc-200 dark:[:where(&amp;)]:bg-white/10 dark:[:where(&amp;)]:border-white/10 [:where(&amp;)]:p-6 [:where(&amp;)]:rounded-xl [--flux-bleed:1.5rem] p-5" data-flux-card>
    <?php ob_start(); ?>
            <?php ob_start(); ?><div class="font-medium [:where(&amp;)]:text-zinc-800 [:where(&amp;)]:dark:text-white text-sm [&amp;:has(+[data-flux-subheading])]:mb-2 [[data-flux-subheading]+&amp;]:mt-2" data-flux-heading><?php ob_start(); ?><?php echo e(__('Code review arena')); ?><?php echo trim(ob_get_clean()); ?></div>
<?php echo ltrim(ob_get_clean()); ?>
            <?php ob_start(); ?><p class="[:where(&amp;)]:font-normal [:where(&amp;)]:text-sm [:where(&amp;)]:text-zinc-500 [:where(&amp;)]:dark:text-white/70 text-zinc-500" data-flux-text ><?php ob_start(); ?><?php echo e($reviewCount); ?> PRs · planted issues · F1 score<?php echo trim(ob_get_clean()); ?></p><?php echo ltrim(ob_get_clean()); ?>
            <?php ob_start(); ?><a class="inline font-medium underline-offset-[6px] hover:decoration-current underline [[data-color]&gt;&amp;]:text-inherit [[data-color]&gt;&amp;]:decoration-current/20 dark:[[data-color]&gt;&amp;]:decoration-current/50 [[data-color]&gt;&amp;]:hover:decoration-current text-[var(--color-accent-content)] decoration-[color-mix(in_oklab,var(--color-accent-content),transparent_80%)] mt-3 inline-block" <?php if (($__blazeAttr = route('senior.reviews')) !== false && !is_null($__blazeAttr)): ?>href="<?php echo e($__blazeAttr === true ? 'href' : $__blazeAttr); ?>"<?php endif; unset($__blazeAttr); ?> wire:navigate="" data-flux-link ><?php ob_start(); ?><?php echo e(__('Open arena')); ?><?php echo trim(ob_get_clean()); ?></a><?php echo ltrim(ob_get_clean()); ?>
        <?php echo trim(ob_get_clean()); ?>

</div>
<?php echo ltrim(ob_get_clean()); ?>
        <?php ob_start(); ?><div class="border [:where(&amp;)]:bg-white [:where(&amp;)]:border-zinc-200 dark:[:where(&amp;)]:bg-white/10 dark:[:where(&amp;)]:border-white/10 [:where(&amp;)]:p-6 [:where(&amp;)]:rounded-xl [--flux-bleed:1.5rem] p-5" data-flux-card>
    <?php ob_start(); ?>
            <?php ob_start(); ?><div class="font-medium [:where(&amp;)]:text-zinc-800 [:where(&amp;)]:dark:text-white text-sm [&amp;:has(+[data-flux-subheading])]:mb-2 [[data-flux-subheading]+&amp;]:mt-2" data-flux-heading><?php ob_start(); ?><?php echo e(__('PHP internals')); ?><?php echo trim(ob_get_clean()); ?></div>
<?php echo ltrim(ob_get_clean()); ?>
            <?php ob_start(); ?><p class="[:where(&amp;)]:font-normal [:where(&amp;)]:text-sm [:where(&amp;)]:text-zinc-500 [:where(&amp;)]:dark:text-white/70 text-zinc-500" data-flux-text ><?php ob_start(); ?><?php echo e($internalsCount); ?> topics · explain-back gate<?php echo trim(ob_get_clean()); ?></p><?php echo ltrim(ob_get_clean()); ?>
            <?php ob_start(); ?><a class="inline font-medium underline-offset-[6px] hover:decoration-current underline [[data-color]&gt;&amp;]:text-inherit [[data-color]&gt;&amp;]:decoration-current/20 dark:[[data-color]&gt;&amp;]:decoration-current/50 [[data-color]&gt;&amp;]:hover:decoration-current text-[var(--color-accent-content)] decoration-[color-mix(in_oklab,var(--color-accent-content),transparent_80%)] mt-3 inline-block" <?php if (($__blazeAttr = route('senior.internals')) !== false && !is_null($__blazeAttr)): ?>href="<?php echo e($__blazeAttr === true ? 'href' : $__blazeAttr); ?>"<?php endif; unset($__blazeAttr); ?> wire:navigate="" data-flux-link ><?php ob_start(); ?><?php echo e(__('Open internals')); ?><?php echo trim(ob_get_clean()); ?></a><?php echo ltrim(ob_get_clean()); ?>
        <?php echo trim(ob_get_clean()); ?>

</div>
<?php echo ltrim(ob_get_clean()); ?>
        <?php ob_start(); ?><div class="border [:where(&amp;)]:bg-white [:where(&amp;)]:border-zinc-200 dark:[:where(&amp;)]:bg-white/10 dark:[:where(&amp;)]:border-white/10 [:where(&amp;)]:p-6 [:where(&amp;)]:rounded-xl [--flux-bleed:1.5rem] p-5" data-flux-card>
    <?php ob_start(); ?>
            <?php ob_start(); ?><div class="font-medium [:where(&amp;)]:text-zinc-800 [:where(&amp;)]:dark:text-white text-sm [&amp;:has(+[data-flux-subheading])]:mb-2 [[data-flux-subheading]+&amp;]:mt-2" data-flux-heading><?php ob_start(); ?><?php echo e(__('STAR stories')); ?><?php echo trim(ob_get_clean()); ?></div>
<?php echo ltrim(ob_get_clean()); ?>
            <?php ob_start(); ?><p class="[:where(&amp;)]:font-normal [:where(&amp;)]:text-sm [:where(&amp;)]:text-zinc-500 [:where(&amp;)]:dark:text-white/70 text-zinc-500" data-flux-text ><?php ob_start(); ?><?php echo e($starReady); ?>/5 interview-ready · behavioral bank<?php echo trim(ob_get_clean()); ?></p><?php echo ltrim(ob_get_clean()); ?>
            <?php ob_start(); ?><a class="inline font-medium underline-offset-[6px] hover:decoration-current underline [[data-color]&gt;&amp;]:text-inherit [[data-color]&gt;&amp;]:decoration-current/20 dark:[[data-color]&gt;&amp;]:decoration-current/50 [[data-color]&gt;&amp;]:hover:decoration-current text-[var(--color-accent-content)] decoration-[color-mix(in_oklab,var(--color-accent-content),transparent_80%)] mt-3 inline-block" <?php if (($__blazeAttr = route('senior.star')) !== false && !is_null($__blazeAttr)): ?>href="<?php echo e($__blazeAttr === true ? 'href' : $__blazeAttr); ?>"<?php endif; unset($__blazeAttr); ?> wire:navigate="" data-flux-link ><?php ob_start(); ?><?php echo e(__('Open stories')); ?><?php echo trim(ob_get_clean()); ?></a><?php echo ltrim(ob_get_clean()); ?>
        <?php echo trim(ob_get_clean()); ?>

</div>
<?php echo ltrim(ob_get_clean()); ?>
        <?php ob_start(); ?><div class="border [:where(&amp;)]:bg-white [:where(&amp;)]:border-zinc-200 dark:[:where(&amp;)]:bg-white/10 dark:[:where(&amp;)]:border-white/10 [:where(&amp;)]:p-6 [:where(&amp;)]:rounded-xl [--flux-bleed:1.5rem] p-5" data-flux-card>
    <?php ob_start(); ?>
            <?php ob_start(); ?><div class="font-medium [:where(&amp;)]:text-zinc-800 [:where(&amp;)]:dark:text-white text-sm [&amp;:has(+[data-flux-subheading])]:mb-2 [[data-flux-subheading]+&amp;]:mt-2" data-flux-heading><?php ob_start(); ?><?php echo e(__('Interview simulation')); ?><?php echo trim(ob_get_clean()); ?></div>
<?php echo ltrim(ob_get_clean()); ?>
            <?php ob_start(); ?><p class="[:where(&amp;)]:font-normal [:where(&amp;)]:text-sm [:where(&amp;)]:text-zinc-500 [:where(&amp;)]:dark:text-white/70 text-zinc-500" data-flux-text ><?php ob_start(); ?><?php echo e($simulationCount); ?> full loops · coding + design + behavioral<?php echo trim(ob_get_clean()); ?></p><?php echo ltrim(ob_get_clean()); ?>
            <?php ob_start(); ?><a class="inline font-medium underline-offset-[6px] hover:decoration-current underline [[data-color]&gt;&amp;]:text-inherit [[data-color]&gt;&amp;]:decoration-current/20 dark:[[data-color]&gt;&amp;]:decoration-current/50 [[data-color]&gt;&amp;]:hover:decoration-current text-[var(--color-accent-content)] decoration-[color-mix(in_oklab,var(--color-accent-content),transparent_80%)] mt-3 inline-block" <?php if (($__blazeAttr = route('senior.simulation')) !== false && !is_null($__blazeAttr)): ?>href="<?php echo e($__blazeAttr === true ? 'href' : $__blazeAttr); ?>"<?php endif; unset($__blazeAttr); ?> wire:navigate="" data-flux-link ><?php ob_start(); ?><?php echo e(__('Open simulation')); ?><?php echo trim(ob_get_clean()); ?></a><?php echo ltrim(ob_get_clean()); ?>
        <?php echo trim(ob_get_clean()); ?>

</div>
<?php echo ltrim(ob_get_clean()); ?>
    </div>
</div><?php /**PATH C:\wamp64\www\php_tutorials\storage\framework\views/livewire/views/30f43791.blade.php ENDPATH**/ ?>