<?php # [BlazeFolded]:{flux::heading}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/heading.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::subheading}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/subheading.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::heading}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/heading.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::text}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/text.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::badge}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/badge/index.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::badge}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/badge/index.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::link}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/link.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::card}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/card/index.blade.php}:{1789433108} ?>
<?php
use App\Models\Incident;
use App\Models\IncidentAttempt;
use Livewire\Attributes\Title;
use Livewire\Component;
?>

<div class="flex flex-1 flex-col gap-6">
    <div>
        <?php ob_start(); ?><h1 class="font-medium [:where(&amp;)]:text-zinc-800 [:where(&amp;)]:dark:text-white text-2xl [&amp;:has(+[data-flux-subheading])]:mb-2 [[data-flux-subheading]+&amp;]:mt-2" data-flux-heading><?php ob_start(); ?><?php echo e(__('Production debugging lab')); ?><?php echo trim(ob_get_clean()); ?></h1>

        <?php echo ltrim(ob_get_clean()); ?>
        <?php ob_start(); ?><div class="text-sm [:where(&amp;)]:text-zinc-500 [:where(&amp;)]:dark:text-white/70" data-flux-subheading>
    <?php ob_start(); ?><?php echo e(__('Diagnose from artifacts · defend root cause · fix checklist')); ?><?php echo trim(ob_get_clean()); ?>

</div>
<?php echo ltrim(ob_get_clean()); ?>
    </div>

    <div class="grid gap-4 md:grid-cols-2">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $incidents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $incident): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <?php $attempt = $attempts->get($incident->id); ?>
            <?php ob_start(); ?><div class="border [:where(&amp;)]:bg-white [:where(&amp;)]:border-zinc-200 dark:[:where(&amp;)]:bg-white/10 dark:[:where(&amp;)]:border-white/10 [:where(&amp;)]:p-6 [:where(&amp;)]:rounded-xl [--flux-bleed:1.5rem] flex flex-col p-5" data-test="incident-card" data-flux-card>
    <?php ob_start(); ?>
                <div class="flex items-start justify-between gap-3">
                    <?php ob_start(); ?><div class="font-medium [:where(&amp;)]:text-zinc-800 [:where(&amp;)]:dark:text-white text-sm [&amp;:has(+[data-flux-subheading])]:mb-2 [[data-flux-subheading]+&amp;]:mt-2" data-flux-heading><?php ob_start(); ?><?php echo e($incident->title); ?><?php echo trim(ob_get_clean()); ?></div>
<?php echo ltrim(ob_get_clean()); ?>
                    <?php if (!function_exists('_3c7292da65f516e3deba6a0a6c6dcfcc')) { $__blaze->compile('C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/badge/index.blade.php', $__blaze->compiledPath.'/3c7292da65f516e3deba6a0a6c6dcfcc.php'); require $__blaze->compiledPath.'/3c7292da65f516e3deba6a0a6c6dcfcc.php'; } ?>
<?php if (isset($__slots3c7292da65f516e3deba6a0a6c6dcfcc)) { $__slotsStack3c7292da65f516e3deba6a0a6c6dcfcc[] = $__slots3c7292da65f516e3deba6a0a6c6dcfcc; } ?>
<?php if (isset($__attrs3c7292da65f516e3deba6a0a6c6dcfcc)) { $__attrsStack3c7292da65f516e3deba6a0a6c6dcfcc[] = $__attrs3c7292da65f516e3deba6a0a6c6dcfcc; } ?>
<?php $__attrs3c7292da65f516e3deba6a0a6c6dcfcc = ['variant' => $incident->severity === 'sev1' ? 'danger' : ($incident->severity === 'sev2' ? 'warning' : 'gray')]; ?>
<?php $__slots3c7292da65f516e3deba6a0a6c6dcfcc = []; ?>
<?php $__blaze->pushData($__attrs3c7292da65f516e3deba6a0a6c6dcfcc); ?>
<?php ob_start(); ?>
                        <?php echo e($incident->severity); ?>

                    <?php $__slots3c7292da65f516e3deba6a0a6c6dcfcc['slot'] = new \Illuminate\View\ComponentSlot(trim(ob_get_clean()), []); ?>
<?php $__blaze->pushSlots($__slots3c7292da65f516e3deba6a0a6c6dcfcc); ?>
<?php _3c7292da65f516e3deba6a0a6c6dcfcc($__blaze, $__attrs3c7292da65f516e3deba6a0a6c6dcfcc, $__slots3c7292da65f516e3deba6a0a6c6dcfcc, ['variant'], [], $__this ?? (isset($this) ? $this : null)); ?>
<?php if (! empty($__slotsStack3c7292da65f516e3deba6a0a6c6dcfcc)) { $__slots3c7292da65f516e3deba6a0a6c6dcfcc = array_pop($__slotsStack3c7292da65f516e3deba6a0a6c6dcfcc); } ?>
<?php if (! empty($__attrsStack3c7292da65f516e3deba6a0a6c6dcfcc)) { $__attrs3c7292da65f516e3deba6a0a6c6dcfcc = array_pop($__attrsStack3c7292da65f516e3deba6a0a6c6dcfcc); } ?>
<?php $__blaze->popData(); ?>
                </div>
                <?php ob_start(); ?><p class="[:where(&amp;)]:font-normal [:where(&amp;)]:text-sm [:where(&amp;)]:text-zinc-500 [:where(&amp;)]:dark:text-white/70 mt-2 line-clamp-3 text-zinc-500" data-flux-text ><?php ob_start(); ?><?php echo e($incident->symptom); ?><?php echo trim(ob_get_clean()); ?></p><?php echo ltrim(ob_get_clean()); ?>
                <div class="mt-4 flex items-center gap-2">
                    <?php ob_start(); ?><div data-flux-badge="data-flux-badge" class="inline-flex items-center font-medium whitespace-nowrap  [print-color-adjust:exact] text-sm py-1 **:data-flux-badge-icon:me-1.5 rounded-md px-2 text-zinc-700 [&amp;_button]:text-zinc-700! dark:text-zinc-200 dark:[&amp;_button]:text-zinc-200! bg-zinc-400/15 dark:bg-zinc-400/40 [&amp;:is(button)]:hover:bg-zinc-400/25 dark:[button]:hover:bg-zinc-400/50">
        <?php ob_start(); ?><?php echo e($incident->root_cause_category); ?><?php echo trim(ob_get_clean()); ?>

    </div>
<?php echo ltrim(ob_get_clean()); ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($attempt?->is_complete): ?>
                        <?php ob_start(); ?><div data-flux-badge="data-flux-badge" class="inline-flex items-center font-medium whitespace-nowrap  [print-color-adjust:exact] text-sm py-1 **:data-flux-badge-icon:me-1.5 rounded-md px-2 text-zinc-700 [&amp;_button]:text-zinc-700! dark:text-zinc-200 dark:[&amp;_button]:text-zinc-200! bg-zinc-400/15 dark:bg-zinc-400/40 [&amp;:is(button)]:hover:bg-zinc-400/25 dark:[button]:hover:bg-zinc-400/50">
        <?php ob_start(); ?><?php echo e($attempt->score); ?><?php echo trim(ob_get_clean()); ?>

    </div>
<?php echo ltrim(ob_get_clean()); ?>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <?php ob_start(); ?><a class="inline font-medium underline-offset-[6px] hover:decoration-current underline [[data-color]&gt;&amp;]:text-inherit [[data-color]&gt;&amp;]:decoration-current/20 dark:[[data-color]&gt;&amp;]:decoration-current/50 [[data-color]&gt;&amp;]:hover:decoration-current text-[var(--color-accent-content)] decoration-[color-mix(in_oklab,var(--color-accent-content),transparent_80%)] mt-4 inline-block" <?php if (($__blazeAttr = route('senior.incidents.show', $incident)) !== false && !is_null($__blazeAttr)): ?>href="<?php echo e($__blazeAttr === true ? 'href' : $__blazeAttr); ?>"<?php endif; unset($__blazeAttr); ?> wire:navigate="" data-flux-link ><?php ob_start(); ?>
                    Open war room
                <?php echo trim(ob_get_clean()); ?></a><?php echo ltrim(ob_get_clean()); ?>
            <?php echo trim(ob_get_clean()); ?>

</div>
<?php echo ltrim(ob_get_clean()); ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            <p>No incidents seeded.</p>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div><?php /**PATH C:\wamp64\www\php_tutorials\storage\framework\views/livewire/views/1e0a66e7.blade.php ENDPATH**/ ?>