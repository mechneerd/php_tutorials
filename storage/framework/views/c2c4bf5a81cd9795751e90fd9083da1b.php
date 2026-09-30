<?php # [BlazeFolded]:{flux::heading}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/heading.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::badge}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/badge/index.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::callout}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/callout/index.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::text}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/text.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::card}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/card/index.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::button}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/button/index.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::heading}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/heading.blade.php}:{1789433108} ?>
<?php
use App\Models\Checkpoint;
use App\Models\Lesson;
use App\Services\CheckpointEvaluator;
use App\Services\ProgressGate;
use Livewire\Attributes\Title;
use Livewire\Component;
?>

<div class="flex flex-col gap-4">
    <div class="flex flex-wrap items-center justify-between gap-2">
        <?php ob_start(); ?><h2 class="font-medium [:where(&amp;)]:text-zinc-800 [:where(&amp;)]:dark:text-white text-sm [&amp;:has(+[data-flux-subheading])]:mb-2 [[data-flux-subheading]+&amp;]:mt-2" data-flux-heading><?php ob_start(); ?><?php echo e($checkpoint->title); ?><?php echo trim(ob_get_clean()); ?></h2>

        <?php echo ltrim(ob_get_clean()); ?>
        <?php ob_start(); ?><div data-flux-badge="data-flux-badge" class="inline-flex items-center font-medium whitespace-nowrap  [print-color-adjust:exact] text-sm py-1 **:data-flux-badge-icon:me-1.5 rounded-md px-2 text-zinc-700 [&amp;_button]:text-zinc-700! dark:text-zinc-200 dark:[&amp;_button]:text-zinc-200! bg-zinc-400/15 dark:bg-zinc-400/40 [&amp;:is(button)]:hover:bg-zinc-400/25 dark:[button]:hover:bg-zinc-400/50">
        <?php ob_start(); ?>Pass ≥ <?php echo e($checkpoint->pass_score); ?>%<?php echo trim(ob_get_clean()); ?>

    </div>
<?php echo ltrim(ob_get_clean()); ?>
    </div>

    <?php ob_start(); ?><div class="@container p-2 flex border rounded-xl border-(--callout-border) bg-(--callout-background) [&amp;_[data-slot=heading]]:text-(--callout-heading) [&amp;_[data-slot=text]]:text-(--callout-text) [--callout-border:var(--color-zinc-200)] dark:[--callout-border:color-mix(in_oklab,var(--color-white),transparent_95%)] [--callout-background:var(--color-white)] dark:[--callout-background:color-mix(in_oklab,var(--color-zinc-400),transparent_90%)] [--callout-heading:var(--color-zinc-800)] dark:[--callout-heading:var(--color-zinc-200)] [--callout-text:var(--color-zinc-500)] dark:[--callout-text:var(--color-zinc-300)] [--callout-icon:var(--color-zinc-400)] dark:[--callout-icon:var(--color-zinc-400)]" data-flux-callout>
            <div class="ps-2 py-2 pe-0 flex items-baseline">
            <svg class="shrink-0 [:where(&amp;)]:size-5 inline-block size-5 text-[var(--callout-icon)] dark:text-[var(--callout-icon)]" data-flux-icon xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
  <path fill-rule="evenodd" d="M9.664 1.319a.75.75 0 0 1 .672 0 41.059 41.059 0 0 1 8.198 5.424.75.75 0 0 1-.254 1.285 31.372 31.372 0 0 0-7.86 3.83.75.75 0 0 1-.84 0 31.508 31.508 0 0 0-2.08-1.287V9.394c0-.244.116-.463.302-.592a35.504 35.504 0 0 1 3.305-2.033.75.75 0 0 0-.714-1.319 37 37 0 0 0-3.446 2.12A2.216 2.216 0 0 0 6 9.393v.38a31.293 31.293 0 0 0-4.28-1.746.75.75 0 0 1-.254-1.285 41.059 41.059 0 0 1 8.198-5.424ZM6 11.459a29.848 29.848 0 0 0-2.455-1.158 41.029 41.029 0 0 0-.39 3.114.75.75 0 0 0 .419.74c.528.256 1.046.53 1.554.82-.21.324-.455.63-.739.914a.75.75 0 1 0 1.06 1.06c.37-.369.69-.77.96-1.193a26.61 26.61 0 0 1 3.095 2.348.75.75 0 0 0 .992 0 26.547 26.547 0 0 1 5.93-3.95.75.75 0 0 0 .42-.739 41.053 41.053 0 0 0-.39-3.114 29.925 29.925 0 0 0-5.199 2.801 2.25 2.25 0 0 1-2.514 0c-.41-.275-.826-.541-1.25-.797a6.985 6.985 0 0 1-1.084 3.45 26.503 26.503 0 0 0-1.281-.78A5.487 5.487 0 0 0 6 12v-.54Z" clip-rule="evenodd"/>
</svg>

                </div>
    
    <div class="ps-2 flex-1 ">
        <div class="flex-1 py-2 pe-3 @md:pe-4 flex flex-col justify-center gap-2" data-slot="content">
            
            
            <?php ob_start(); ?>
        <?php echo e(__('Gate: you cannot complete this lesson until you pass. Failed attempt → remediation (re-read lesson), then retry.')); ?>

    <?php echo trim(ob_get_clean()); ?>

        </div>

            </div>

    </div>
<?php echo ltrim(ob_get_clean()); ?>

    <form wire:submit="submit" class="flex flex-col gap-5">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $checkpoint->questions ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
            <?php ob_start(); ?><div class="border [:where(&amp;)]:bg-white [:where(&amp;)]:border-zinc-200 dark:[:where(&amp;)]:bg-white/10 dark:[:where(&amp;)]:border-white/10 [:where(&amp;)]:p-6 [:where(&amp;)]:rounded-xl [--flux-bleed:1.5rem] flex flex-col gap-3" data-flux-card>
    <?php ob_start(); ?>
                <div class="flex items-start justify-between gap-2">
                    <strong>Q<?php echo e($index + 1); ?>.</strong>
                    <?php ob_start(); ?><p class="[:where(&amp;)]:font-normal [:where(&amp;)]:text-sm [:where(&amp;)]:text-zinc-500 [:where(&amp;)]:dark:text-white/70 flex-1" data-flux-text ><?php ob_start(); ?><?php echo e($question['prompt']); ?><?php echo trim(ob_get_clean()); ?></p><?php echo ltrim(ob_get_clean()); ?>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(($question['type'] ?? 'text') === 'mcq'): ?>
                    <div class="flex flex-col gap-2">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $question['options'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <label class="flex items-center gap-2 text-sm">
                                <input
                                    type="radio"
                                    wire:model="answers.<?php echo e($question['id']); ?>"
                                    value="<?php echo e($option); ?>"
                                />
                                <span><?php echo e($option); ?></span>
                            </label>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    </div>
                <?php else: ?>
                    <textarea
                        wire:model="answers.<?php echo e($question['id']); ?>"
                        rows="2"
                        class="w-full rounded border border-zinc-700 bg-zinc-900 p-2 text-sm"
                        placeholder="<?php echo e(__('Your answer')); ?>"
                    ></textarea>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php echo trim(ob_get_clean()); ?>

</div>
<?php echo ltrim(ob_get_clean()); ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>

        <div>
            <?php ob_start(); ?><button type="submit" class="relative items-center font-medium justify-center whitespace-nowrap disabled:opacity-50 dark:disabled:opacity-50 disabled:cursor-default disabled:pointer-events-none disabled:shadow-none justify-center h-10 text-sm rounded-lg gap-2 ps-4 pe-4 inline-flex  bg-[var(--color-accent)] hover:bg-[color-mix(in_oklab,_var(--color-accent),_transparent_10%)] text-[var(--color-accent-foreground)] border border-black/10 dark:border-0 shadow-[inset_0px_1px_--theme(--color-white/.2)] [[data-flux-button-group]_&amp;]:border-e-0 [:is([data-flux-button-group]&gt;&amp;:last-child,_[data-flux-button-group]_:last-child&gt;&amp;)]:border-e-[1px] dark:[:is([data-flux-button-group]&gt;&amp;:last-child,_[data-flux-button-group]_:last-child&gt;&amp;)]:border-e-0 [:is([data-flux-button-group]&gt;&amp;:not(:first-child),_[data-flux-button-group]_:not(:first-child)&gt;&amp;)]:border-s-[1px]! [:is([data-flux-button-group]&gt;&amp;:not(:first-child),_[data-flux-button-group]_:not(:first-child)&gt;&amp;)]:border-s-[color-mix(in_srgb,var(--color-accent-foreground),transparent_85%)] *:transition-opacity [&amp;[disabled]&gt;:not([data-flux-loading-indicator])]:opacity-0 [&amp;[disabled]&gt;[data-flux-loading-indicator]]:opacity-100 [&amp;[disabled]]:pointer-events-none" data-flux-button="data-flux-button" data-flux-group-target="data-flux-group-target" data-test="submit-checkpoint">
        <div class="absolute inset-0 flex items-center justify-center opacity-0" data-flux-loading-indicator>
                <svg class="shrink-0 [:where(&amp;)]:size-4 animate-spin" data-flux-icon xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true" data-slot="icon">
    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
</svg>
                    </div>
        
        
                    
            
            <span><?php ob_start(); ?>
                <?php echo e(__('Submit checkpoint')); ?>

            <?php echo trim(ob_get_clean()); ?></span>
    </button>
<?php echo ltrim(ob_get_clean()); ?>
        </div>
    </form>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($result): ?>
        <?php if (!function_exists('_4de0ac57123305c2f2dda8b4a02af66f')) { $__blaze->compile('C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/callout/index.blade.php', $__blaze->compiledPath.'/4de0ac57123305c2f2dda8b4a02af66f.php'); require $__blaze->compiledPath.'/4de0ac57123305c2f2dda8b4a02af66f.php'; } ?>
<?php if (isset($__slots4de0ac57123305c2f2dda8b4a02af66f)) { $__slotsStack4de0ac57123305c2f2dda8b4a02af66f[] = $__slots4de0ac57123305c2f2dda8b4a02af66f; } ?>
<?php if (isset($__attrs4de0ac57123305c2f2dda8b4a02af66f)) { $__attrsStack4de0ac57123305c2f2dda8b4a02af66f[] = $__attrs4de0ac57123305c2f2dda8b4a02af66f; } ?>
<?php $__attrs4de0ac57123305c2f2dda8b4a02af66f = ['variant' => e($result['passed'] ? 'success' : 'danger'),'icon' => e($result['passed'] ? 'check-circle' : 'x-circle')]; ?>
<?php $__slots4de0ac57123305c2f2dda8b4a02af66f = []; ?>
<?php $__blaze->pushData($__attrs4de0ac57123305c2f2dda8b4a02af66f); ?>
<?php ob_start(); ?>
            <?php ob_start(); ?><div class="font-medium [:where(&amp;)]:text-zinc-800 [:where(&amp;)]:dark:text-white text-sm [&amp;:has(+[data-flux-subheading])]:mb-2 [[data-flux-subheading]+&amp;]:mt-2" data-flux-heading><?php ob_start(); ?>
                <?php echo e($result['passed'] ? __('Passed') : __('Not passed')); ?> — <?php echo e($result['score']); ?>%
            <?php echo trim(ob_get_clean()); ?></div>
<?php echo ltrim(ob_get_clean()); ?>
            <ul class="mt-2 list-inside list-disc text-sm">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $result['feedback'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <li class="<?php echo e($row['correct'] ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'); ?>">
                        <?php echo e($row['correct'] ? '✓' : '✗'); ?>

                        <?php echo e($row['correct'] ? 'Correct' : 'Incorrect'); ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! $row['correct'] && filled($row['expected'])): ?>
                            — expected contains: <?php echo e($row['expected']); ?>

                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </li>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </ul>
        <?php $__slots4de0ac57123305c2f2dda8b4a02af66f['slot'] = new \Illuminate\View\ComponentSlot(trim(ob_get_clean()), []); ?>
<?php $__blaze->pushSlots($__slots4de0ac57123305c2f2dda8b4a02af66f); ?>
<?php _4de0ac57123305c2f2dda8b4a02af66f($__blaze, $__attrs4de0ac57123305c2f2dda8b4a02af66f, $__slots4de0ac57123305c2f2dda8b4a02af66f, [], [], $__this ?? (isset($this) ? $this : null)); ?>
<?php if (! empty($__slotsStack4de0ac57123305c2f2dda8b4a02af66f)) { $__slots4de0ac57123305c2f2dda8b4a02af66f = array_pop($__slotsStack4de0ac57123305c2f2dda8b4a02af66f); } ?>
<?php if (! empty($__attrsStack4de0ac57123305c2f2dda8b4a02af66f)) { $__attrs4de0ac57123305c2f2dda8b4a02af66f = array_pop($__attrsStack4de0ac57123305c2f2dda8b4a02af66f); } ?>
<?php $__blaze->popData(); ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div><?php /**PATH C:\wamp64\www\php_tutorials\storage\framework\views/livewire/views/20554f6b.blade.php ENDPATH**/ ?>