<?php # [BlazeFolded]:{flux::badge}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/badge/index.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::heading}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/heading.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::subheading}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/subheading.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::badge}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/badge/index.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::badge}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/badge/index.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::badge}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/badge/index.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::link}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/link.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::text}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/text.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::heading}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/heading.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::callout}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/callout/index.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::callout}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/callout/index.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::callout}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/callout/index.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::card}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/card/index.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::heading}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/heading.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::text}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/text.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::link}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/link.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::text}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/text.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::link}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/link.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::text}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/text.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::link}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/link.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::link}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/link.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::link}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/link.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::card}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/card/index.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::button}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/button/index.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::button}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/button/index.blade.php}:{1789433108} ?>
<?php
use App\Models\Lesson;
use App\Services\ProgressGate;
use Livewire\Attributes\Title;
use Livewire\Component;
?>

<div class="flex flex-1 flex-col gap-6">
    <div class="flex flex-wrap items-start justify-between gap-3">
        <div>
            <?php ob_start(); ?><div data-flux-badge="data-flux-badge" class="inline-flex items-center font-medium whitespace-nowrap  [print-color-adjust:exact] text-sm py-1 **:data-flux-badge-icon:me-1.5 rounded-md px-2 text-zinc-700 [&amp;_button]:text-zinc-700! dark:text-zinc-200 dark:[&amp;_button]:text-zinc-200! bg-zinc-400/15 dark:bg-zinc-400/40 [&amp;:is(button)]:hover:bg-zinc-400/25 dark:[button]:hover:bg-zinc-400/50">
        <?php ob_start(); ?><?php echo e($this->lesson->stage?->title); ?><?php echo trim(ob_get_clean()); ?>

    </div>
<?php echo ltrim(ob_get_clean()); ?>
            <?php ob_start(); ?><h1 class="font-medium [:where(&amp;)]:text-zinc-800 [:where(&amp;)]:dark:text-white text-2xl [&amp;:has(+[data-flux-subheading])]:mb-2 [[data-flux-subheading]+&amp;]:mt-2 mt-2" data-flux-heading><?php ob_start(); ?><?php echo e($this->lesson->title); ?><?php echo trim(ob_get_clean()); ?></h1>

        <?php echo ltrim(ob_get_clean()); ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($this->lesson->summary): ?>
                <?php ob_start(); ?><div class="text-sm [:where(&amp;)]:text-zinc-500 [:where(&amp;)]:dark:text-white/70" data-flux-subheading>
    <?php ob_start(); ?><?php echo e($this->lesson->summary); ?><?php echo trim(ob_get_clean()); ?>

</div>
<?php echo ltrim(ob_get_clean()); ?>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <div class="mt-2 flex flex-wrap gap-2">
                <?php ob_start(); ?><div data-flux-badge="data-flux-badge" class="inline-flex items-center font-medium whitespace-nowrap  [print-color-adjust:exact] text-sm py-1 **:data-flux-badge-icon:me-1.5 rounded-md px-2 text-zinc-700 [&amp;_button]:text-zinc-700! dark:text-zinc-200 dark:[&amp;_button]:text-zinc-200! bg-zinc-400/15 dark:bg-zinc-400/40 [&amp;:is(button)]:hover:bg-zinc-400/25 dark:[button]:hover:bg-zinc-400/50">
        <?php ob_start(); ?><?php echo e($this->lesson->code); ?><?php echo trim(ob_get_clean()); ?>

    </div>
<?php echo ltrim(ob_get_clean()); ?>
                <?php ob_start(); ?><div data-flux-badge="data-flux-badge" class="inline-flex items-center font-medium whitespace-nowrap  [print-color-adjust:exact] text-sm py-1 **:data-flux-badge-icon:me-1.5 rounded-md px-2 text-zinc-700 [&amp;_button]:text-zinc-700! dark:text-zinc-200 dark:[&amp;_button]:text-zinc-200! bg-zinc-400/15 dark:bg-zinc-400/40 [&amp;:is(button)]:hover:bg-zinc-400/25 dark:[button]:hover:bg-zinc-400/50">
        <?php ob_start(); ?><?php echo e($this->lesson->estimated_minutes); ?> min<?php echo trim(ob_get_clean()); ?>

    </div>
<?php echo ltrim(ob_get_clean()); ?>
                <?php ob_start(); ?><div data-flux-badge="data-flux-badge" class="inline-flex items-center font-medium whitespace-nowrap  [print-color-adjust:exact] text-sm py-1 **:data-flux-badge-icon:me-1.5 rounded-md px-2 text-zinc-700 [&amp;_button]:text-zinc-700! dark:text-zinc-200 dark:[&amp;_button]:text-zinc-200! bg-zinc-400/15 dark:bg-zinc-400/40 [&amp;:is(button)]:hover:bg-zinc-400/25 dark:[button]:hover:bg-zinc-400/50">
        <?php ob_start(); ?><?php echo e($this->lesson->difficulty); ?><?php echo trim(ob_get_clean()); ?>

    </div>
<?php echo ltrim(ob_get_clean()); ?>
            </div>
        </div>

        <div class="flex flex-col items-end gap-2">
            <?php ob_start(); ?><a class="inline font-medium underline-offset-[6px] hover:decoration-current underline [[data-color]&gt;&amp;]:text-inherit [[data-color]&gt;&amp;]:decoration-current/20 dark:[[data-color]&gt;&amp;]:decoration-current/50 [[data-color]&gt;&amp;]:hover:decoration-current text-[var(--color-accent-content)] decoration-[color-mix(in_oklab,var(--color-accent-content),transparent_80%)]" <?php if (($__blazeAttr = route('roadmap')) !== false && !is_null($__blazeAttr)): ?>href="<?php echo e($__blazeAttr === true ? 'href' : $__blazeAttr); ?>"<?php endif; unset($__blazeAttr); ?> wire:navigate="" data-flux-link ><?php ob_start(); ?><?php echo e(__('Back to roadmap')); ?><?php echo trim(ob_get_clean()); ?></a><?php echo ltrim(ob_get_clean()); ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($this->lesson->prerequisites->isNotEmpty()): ?>
                <?php ob_start(); ?><p class="[:where(&amp;)]:font-normal [:where(&amp;)]:text-sm [:where(&amp;)]:text-zinc-500 [:where(&amp;)]:dark:text-white/70 text-sm text-zinc-500" data-flux-text ><?php ob_start(); ?>
                    <?php echo e(__('Prerequisites:')); ?>

                    <?php echo e($this->lesson->prerequisites->pluck('code')->join(', ')); ?>

                <?php echo trim(ob_get_clean()); ?></p><?php echo ltrim(ob_get_clean()); ?>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! $allowed): ?>
        <?php ob_start(); ?><div class="@container p-2 flex border rounded-xl border-(--callout-border) bg-(--callout-background) [&amp;_[data-slot=heading]]:text-(--callout-heading) [&amp;_[data-slot=text]]:text-(--callout-text) [--callout-border:var(--color-yellow-400)] dark:[--callout-border:color-mix(in_oklab,var(--color-yellow-400),transparent_50%)] [--callout-background:var(--color-yellow-50)] dark:[--callout-background:color-mix(in_oklab,var(--color-yellow-400),transparent_90%)] [--callout-heading:var(--color-yellow-600)] dark:[--callout-heading:var(--color-yellow-200)] [--callout-text:var(--color-yellow-700)] dark:[--callout-text:var(--color-yellow-300)] [--callout-icon:var(--color-yellow-500)] dark:[--callout-icon:var(--color-yellow-400)]" data-flux-callout>
            <div class="ps-2 py-2 pe-0 flex items-baseline">
            <svg class="shrink-0 [:where(&amp;)]:size-5 inline-block size-5 text-[var(--callout-icon)] dark:text-[var(--callout-icon)]" data-flux-icon xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
  <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 0 0-4.5 4.5V9H5a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-6a2 2 0 0 0-2-2h-.5V5.5A4.5 4.5 0 0 0 10 1Zm3 8V5.5a3 3 0 1 0-6 0V9h6Z" clip-rule="evenodd"/>
</svg>

                </div>
    
    <div class="ps-2 flex-1 ">
        <div class="flex-1 py-2 pe-3 @md:pe-4 flex flex-col justify-center gap-2" data-slot="content">
            
            
            <?php ob_start(); ?>
            <?php ob_start(); ?><div class="font-medium [:where(&amp;)]:text-zinc-800 [:where(&amp;)]:dark:text-white text-sm [&amp;:has(+[data-flux-subheading])]:mb-2 [[data-flux-subheading]+&amp;]:mt-2" data-flux-heading><?php ob_start(); ?><?php echo e(__('Locked')); ?><?php echo trim(ob_get_clean()); ?></div>
<?php echo ltrim(ob_get_clean()); ?>
            <ul class="mt-1 list-inside list-disc">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $lockReasons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reason): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                    <li><?php echo e($reason); ?></li>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
            </ul>
        <?php echo trim(ob_get_clean()); ?>

        </div>

            </div>

    </div>
<?php echo ltrim(ob_get_clean()); ?>
    <?php else: ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('status')): ?>
            <?php ob_start(); ?><div class="@container p-2 flex border rounded-xl border-(--callout-border) bg-(--callout-background) [&amp;_[data-slot=heading]]:text-(--callout-heading) [&amp;_[data-slot=text]]:text-(--callout-text) [--callout-border:var(--color-green-300)] dark:[--callout-border:color-mix(in_oklab,var(--color-green-400),transparent_50%)] [--callout-background:var(--color-green-50)] dark:[--callout-background:color-mix(in_oklab,var(--color-green-400),transparent_90%)] [--callout-heading:var(--color-green-600)] dark:[--callout-heading:var(--color-green-200)] [--callout-text:var(--color-green-600)] dark:[--callout-text:var(--color-green-300)] [--callout-icon:var(--color-green-500)] dark:[--callout-icon:var(--color-green-400)]" data-flux-callout>
            <div class="ps-2 py-2 pe-0 flex items-baseline">
            <svg class="shrink-0 [:where(&amp;)]:size-5 inline-block size-5 text-[var(--callout-icon)] dark:text-[var(--callout-icon)]" data-flux-icon xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
  <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd"/>
</svg>

                </div>
    
    <div class="ps-2 flex-1 ">
        <div class="flex-1 py-2 pe-3 @md:pe-4 flex flex-col justify-center gap-2" data-slot="content">
            
            
            <?php ob_start(); ?><?php echo e(session('status')); ?><?php echo trim(ob_get_clean()); ?>

        </div>

            </div>

    </div>
<?php echo ltrim(ob_get_clean()); ?>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
            <?php ob_start(); ?><div class="@container p-2 flex border rounded-xl border-(--callout-border) bg-(--callout-background) [&amp;_[data-slot=heading]]:text-(--callout-heading) [&amp;_[data-slot=text]]:text-(--callout-text) [--callout-border:var(--color-red-200)] dark:[--callout-border:color-mix(in_oklab,var(--color-red-400),transparent_50%)] [--callout-background:var(--color-red-50)] dark:[--callout-background:color-mix(in_oklab,var(--color-red-400),transparent_90%)] [--callout-heading:var(--color-red-700)] dark:[--callout-heading:var(--color-red-200)] [--callout-text:var(--color-red-700)] dark:[--callout-text:var(--color-red-300)] [--callout-icon:var(--color-red-400)] dark:[--callout-icon:var(--color-red-400)]" data-flux-callout>
            <div class="ps-2 py-2 pe-0 flex items-baseline">
            <svg class="shrink-0 [:where(&amp;)]:size-5 inline-block size-5 text-[var(--callout-icon)] dark:text-[var(--callout-icon)]" data-flux-icon xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon">
  <path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-8-5a.75.75 0 0 1 .75.75v4.5a.75.75 0 0 1-1.5 0v-4.5A.75.75 0 0 1 10 5Zm0 10a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd"/>
</svg>

                </div>
    
    <div class="ps-2 flex-1 ">
        <div class="flex-1 py-2 pe-3 @md:pe-4 flex flex-col justify-center gap-2" data-slot="content">
            
            
            <?php ob_start(); ?><?php echo e(session('error')); ?><?php echo trim(ob_get_clean()); ?>

        </div>

            </div>

    </div>
<?php echo ltrim(ob_get_clean()); ?>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php ob_start(); ?><div class="border [:where(&amp;)]:bg-white [:where(&amp;)]:border-zinc-200 dark:[:where(&amp;)]:bg-white/10 dark:[:where(&amp;)]:border-white/10 [:where(&amp;)]:p-6 [:where(&amp;)]:rounded-xl [--flux-bleed:1.5rem] prose prose-invert max-w-none dark:prose-invert" data-flux-card>
    <?php ob_start(); ?>
            <?php echo $this->lesson->body_html; ?>

        <?php echo trim(ob_get_clean()); ?>

</div>
<?php echo ltrim(ob_get_clean()); ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($this->lesson->exercises->isNotEmpty()): ?>
            <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('pages::exercise-panel', ['exercises' => $this->lesson->exercises,'lesson' => $this->lesson]);

$__keyOuter = $__key ?? null;

$__key = null;
$__componentSlots = [];

$__key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-2210352243-0', $__key);

$__html = app('livewire')->mount($__name, $__params, $__key, $__componentSlots);

echo $__html;

unset($__html);
unset($__key);
$__key = $__keyOuter;
unset($__keyOuter);
unset($__name);
unset($__params);
unset($__componentSlots);
unset($__split);
?>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($this->lesson->checkpoints->isNotEmpty()): ?>
            <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('pages::checkpoint-panel', ['checkpoint' => $this->lesson->checkpoints->first(),'lesson' => $this->lesson]);

$__keyOuter = $__key ?? null;

$__key = null;
$__componentSlots = [];

$__key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-2210352243-1', $__key);

$__html = app('livewire')->mount($__name, $__params, $__key, $__componentSlots);

echo $__html;

unset($__html);
unset($__key);
$__key = $__keyOuter;
unset($__keyOuter);
unset($__name);
unset($__params);
unset($__componentSlots);
unset($__split);
?>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php ob_start(); ?><div class="border [:where(&amp;)]:bg-white [:where(&amp;)]:border-zinc-200 dark:[:where(&amp;)]:bg-white/10 dark:[:where(&amp;)]:border-white/10 [:where(&amp;)]:p-6 [:where(&amp;)]:rounded-xl [--flux-bleed:1.5rem]" data-test="knowledge-graph" data-flux-card>
    <?php ob_start(); ?>
            <?php ob_start(); ?><h3 class="font-medium [:where(&amp;)]:text-zinc-800 [:where(&amp;)]:dark:text-white text-sm [&amp;:has(+[data-flux-subheading])]:mb-2 [[data-flux-subheading]+&amp;]:mt-2" data-flux-heading><?php ob_start(); ?><?php echo e(__('Knowledge graph')); ?><?php echo trim(ob_get_clean()); ?></h3>

        <?php echo ltrim(ob_get_clean()); ?>
            <div class="mt-3 grid gap-4 text-sm md:grid-cols-3">
                <div>
                    <?php ob_start(); ?><p class="[:where(&amp;)]:font-normal [:where(&amp;)]:text-sm [:where(&amp;)]:text-zinc-500 [:where(&amp;)]:dark:text-white/70 font-medium text-zinc-400" data-flux-text ><?php ob_start(); ?><?php echo e(__('Requires')); ?><?php echo trim(ob_get_clean()); ?></p><?php echo ltrim(ob_get_clean()); ?>
                    <ul class="mt-1 flex flex-col gap-1">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $this->lesson->prerequisites; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pre): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <li>
                                <?php ob_start(); ?><a class="inline font-medium underline-offset-[6px] hover:decoration-current underline [[data-color]&gt;&amp;]:text-inherit [[data-color]&gt;&amp;]:decoration-current/20 dark:[[data-color]&gt;&amp;]:decoration-current/50 [[data-color]&gt;&amp;]:hover:decoration-current text-[var(--color-accent-content)] decoration-[color-mix(in_oklab,var(--color-accent-content),transparent_80%)]" <?php if (($__blazeAttr = route('lessons.show', $pre)) !== false && !is_null($__blazeAttr)): ?>href="<?php echo e($__blazeAttr === true ? 'href' : $__blazeAttr); ?>"<?php endif; unset($__blazeAttr); ?> wire:navigate="" data-flux-link ><?php ob_start(); ?>
                                    <?php echo e($pre->code); ?> — <?php echo e($pre->title); ?>

                                <?php echo trim(ob_get_clean()); ?></a><?php echo ltrim(ob_get_clean()); ?>
                            </li>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            <li class="text-zinc-500"><?php echo e(__('Entry point')); ?></li>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </ul>
                </div>
                <div>
                    <?php ob_start(); ?><p class="[:where(&amp;)]:font-normal [:where(&amp;)]:text-sm [:where(&amp;)]:text-zinc-500 [:where(&amp;)]:dark:text-white/70 font-medium text-zinc-400" data-flux-text ><?php ob_start(); ?><?php echo e(__('Unlocks')); ?><?php echo trim(ob_get_clean()); ?></p><?php echo ltrim(ob_get_clean()); ?>
                    <ul class="mt-1 flex flex-col gap-1">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $unlocks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unlock): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <li>
                                <?php ob_start(); ?><a class="inline font-medium underline-offset-[6px] hover:decoration-current underline [[data-color]&gt;&amp;]:text-inherit [[data-color]&gt;&amp;]:decoration-current/20 dark:[[data-color]&gt;&amp;]:decoration-current/50 [[data-color]&gt;&amp;]:hover:decoration-current text-[var(--color-accent-content)] decoration-[color-mix(in_oklab,var(--color-accent-content),transparent_80%)]" <?php if (($__blazeAttr = route('lessons.show', $unlock)) !== false && !is_null($__blazeAttr)): ?>href="<?php echo e($__blazeAttr === true ? 'href' : $__blazeAttr); ?>"<?php endif; unset($__blazeAttr); ?> wire:navigate="" data-flux-link ><?php ob_start(); ?>
                                    <?php echo e($unlock->code); ?> — <?php echo e($unlock->title); ?>

                                <?php echo trim(ob_get_clean()); ?></a><?php echo ltrim(ob_get_clean()); ?>
                            </li>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            <li class="text-zinc-500"><?php echo e(__('End of chain')); ?></li>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </ul>
                </div>
                <div>
                    <?php ob_start(); ?><p class="[:where(&amp;)]:font-normal [:where(&amp;)]:text-sm [:where(&amp;)]:text-zinc-500 [:where(&amp;)]:dark:text-white/70 font-medium text-zinc-400" data-flux-text ><?php ob_start(); ?><?php echo e(__('Related')); ?><?php echo trim(ob_get_clean()); ?></p><?php echo ltrim(ob_get_clean()); ?>
                    <ul class="mt-1 flex flex-col gap-1">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $related; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoopIteration(); ?><?php endif; ?>
                            <li>
                                <?php ob_start(); ?><a class="inline font-medium underline-offset-[6px] hover:decoration-current underline [[data-color]&gt;&amp;]:text-inherit [[data-color]&gt;&amp;]:decoration-current/20 dark:[[data-color]&gt;&amp;]:decoration-current/50 [[data-color]&gt;&amp;]:hover:decoration-current text-[var(--color-accent-content)] decoration-[color-mix(in_oklab,var(--color-accent-content),transparent_80%)]" <?php if (($__blazeAttr = route('lessons.show', $rel)) !== false && !is_null($__blazeAttr)): ?>href="<?php echo e($__blazeAttr === true ? 'href' : $__blazeAttr); ?>"<?php endif; unset($__blazeAttr); ?> wire:navigate="" data-flux-link ><?php ob_start(); ?>
                                    <?php echo e($rel->code); ?> — <?php echo e($rel->title); ?>

                                <?php echo trim(ob_get_clean()); ?></a><?php echo ltrim(ob_get_clean()); ?>
                            </li>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                            <li class="text-zinc-500"><?php echo e(__('No lateral links yet')); ?></li>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </ul>
                </div>
            </div>
            <div class="mt-4 flex flex-wrap justify-between gap-2 border-t border-zinc-800 pt-3">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($prev): ?>
                    <?php ob_start(); ?><a class="inline font-medium underline-offset-[6px] hover:decoration-current underline [[data-color]&gt;&amp;]:text-inherit [[data-color]&gt;&amp;]:decoration-current/20 dark:[[data-color]&gt;&amp;]:decoration-current/50 [[data-color]&gt;&amp;]:hover:decoration-current text-[var(--color-accent-content)] decoration-[color-mix(in_oklab,var(--color-accent-content),transparent_80%)]" <?php if (($__blazeAttr = route('lessons.show', $prev)) !== false && !is_null($__blazeAttr)): ?>href="<?php echo e($__blazeAttr === true ? 'href' : $__blazeAttr); ?>"<?php endif; unset($__blazeAttr); ?> wire:navigate="" data-flux-link ><?php ob_start(); ?>
                        ← <?php echo e($prev->code); ?> <?php echo e($prev->title); ?>

                    <?php echo trim(ob_get_clean()); ?></a><?php echo ltrim(ob_get_clean()); ?>
                <?php else: ?>
                    <span></span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($next): ?>
                    <?php ob_start(); ?><a class="inline font-medium underline-offset-[6px] hover:decoration-current underline [[data-color]&gt;&amp;]:text-inherit [[data-color]&gt;&amp;]:decoration-current/20 dark:[[data-color]&gt;&amp;]:decoration-current/50 [[data-color]&gt;&amp;]:hover:decoration-current text-[var(--color-accent-content)] decoration-[color-mix(in_oklab,var(--color-accent-content),transparent_80%)]" <?php if (($__blazeAttr = route('lessons.show', $next)) !== false && !is_null($__blazeAttr)): ?>href="<?php echo e($__blazeAttr === true ? 'href' : $__blazeAttr); ?>"<?php endif; unset($__blazeAttr); ?> wire:navigate="" data-flux-link ><?php ob_start(); ?>
                        <?php echo e($next->code); ?> <?php echo e($next->title); ?> →
                    <?php echo trim(ob_get_clean()); ?></a><?php echo ltrim(ob_get_clean()); ?>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        <?php echo trim(ob_get_clean()); ?>

</div>
<?php echo ltrim(ob_get_clean()); ?>

        <div class="flex flex-wrap items-center gap-3">
            <?php ob_start(); ?><button type="button" class="relative items-center font-medium justify-center whitespace-nowrap disabled:opacity-50 dark:disabled:opacity-50 disabled:cursor-default disabled:pointer-events-none disabled:shadow-none justify-center h-10 text-sm rounded-lg gap-2 ps-4 pe-4 inline-flex  bg-[var(--color-accent)] hover:bg-[color-mix(in_oklab,_var(--color-accent),_transparent_10%)] text-[var(--color-accent-foreground)] border border-black/10 dark:border-0 shadow-[inset_0px_1px_--theme(--color-white/.2)] [[data-flux-button-group]_&amp;]:border-e-0 [:is([data-flux-button-group]&gt;&amp;:last-child,_[data-flux-button-group]_:last-child&gt;&amp;)]:border-e-[1px] dark:[:is([data-flux-button-group]&gt;&amp;:last-child,_[data-flux-button-group]_:last-child&gt;&amp;)]:border-e-0 [:is([data-flux-button-group]&gt;&amp;:not(:first-child),_[data-flux-button-group]_:not(:first-child)&gt;&amp;)]:border-s-[1px]! [:is([data-flux-button-group]&gt;&amp;:not(:first-child),_[data-flux-button-group]_:not(:first-child)&gt;&amp;)]:border-s-[color-mix(in_srgb,var(--color-accent-foreground),transparent_85%)] *:transition-opacity [&amp;[data-loading]&gt;:not([data-flux-loading-indicator])]:opacity-0 [&amp;[data-flux-loading]&gt;:not([data-flux-loading-indicator])]:opacity-0 [&amp;[data-loading]&gt;[data-flux-loading-indicator]]:opacity-100 [&amp;[data-flux-loading]&gt;[data-flux-loading-indicator]]:opacity-100 data-loading:pointer-events-none data-flux-loading:pointer-events-none" data-flux-button="data-flux-button" data-flux-group-target="data-flux-group-target" wire:target="markComplete" wire:loading.attr="data-flux-loading" wire:click="markComplete" data-test="complete-lesson-button">
        <div class="absolute inset-0 flex items-center justify-center opacity-0" data-flux-loading-indicator>
                <svg class="shrink-0 [:where(&amp;)]:size-4 animate-spin" data-flux-icon xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true" data-slot="icon">
    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
</svg>
                    </div>
        
        
                    
            
            <span><?php ob_start(); ?>
                <?php echo e(__('Mark lesson complete')); ?>

            <?php echo trim(ob_get_clean()); ?></span>
    </button>
<?php echo ltrim(ob_get_clean()); ?>
            <?php ob_start(); ?><a href="<?php echo e(route('mentor')); ?>" data-flux-button="data-flux-button" class="relative items-center font-medium justify-center whitespace-nowrap disabled:opacity-50 dark:disabled:opacity-50 disabled:cursor-default disabled:pointer-events-none disabled:shadow-none justify-center h-10 text-sm rounded-lg gap-2 ps-4 pe-4 inline-flex  bg-transparent hover:bg-zinc-800/5 dark:hover:bg-white/15 text-zinc-800 dark:text-white" wire:navigate="">
        <?php ob_start(); ?>
                <?php echo e(__('Ask AI mentor')); ?>

            <?php echo trim(ob_get_clean()); ?>

    </a>
<?php echo ltrim(ob_get_clean()); ?>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div><?php /**PATH C:\wamp64\www\php_tutorials\storage\framework\views/livewire/views/16b34a44.blade.php ENDPATH**/ ?>