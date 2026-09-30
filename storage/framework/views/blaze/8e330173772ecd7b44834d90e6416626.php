<?php
if (!function_exists('__8e330173772ecd7b44834d90e6416626')):
function __8e330173772ecd7b44834d90e6416626($__blaze, $__data = [], $__slots = [], $__bound = [], $__keys = [], $__this = null) {
$__env = $__blaze->env;
$__livewire = $__env->shared('__livewire');
$__slots['slot'] ??= new \Illuminate\View\ComponentSlot('');
if (($__data['attributes'] ?? null) instanceof \Illuminate\View\ComponentAttributeBag) { $__data = $__data + $__data['attributes']->all(); unset($__data['attributes']); }
extract($__slots, EXTR_SKIP); unset($__slots);
extract($__data, EXTR_SKIP);
$attributes = \Livewire\Blaze\Runtime\BlazeAttributeBag::make($__data, $__bound, $__keys);
unset($__data, $__bound, $__keys);
ob_start();
?>


<?php
$__defaults = [
    'dismissible' => null,
    'escapable' => null,
    'position' => null,
    'closable' => null,
    'trigger' => null,
    'variant' => null,
    'scroll' => null,
    'flyout' => null,
    'name' => null,
];
$dismissible ??= $attributes['dismissible'] ?? $__defaults['dismissible']; unset($attributes['dismissible']);
$escapable ??= $attributes['escapable'] ?? $__defaults['escapable']; unset($attributes['escapable']);
$position ??= $attributes['position'] ?? $__defaults['position']; unset($attributes['position']);
$closable ??= $attributes['closable'] ?? $__defaults['closable']; unset($attributes['closable']);
$trigger ??= $attributes['trigger'] ?? $__defaults['trigger']; unset($attributes['trigger']);
$variant ??= $attributes['variant'] ?? $__defaults['variant']; unset($attributes['variant']);
$scroll ??= $attributes['scroll'] ?? $__defaults['scroll']; unset($attributes['scroll']);
$flyout ??= $attributes['flyout'] ?? $__defaults['flyout']; unset($attributes['flyout']);
$name ??= $attributes['name'] ?? $__defaults['name']; unset($attributes['name']);
unset($__defaults);
?>

<?php
// Blaze doesn't support View::share, this supplements it...
$__livewire = $__env->shared('__livewire');

if ($variant === 'flyout') {
    $flyout = true;
    $variant = null;
}

$closable ??= $variant === 'bare' ? false : true;
$overflow = $scroll === 'body' && ! $flyout;

if ($flyout) {
    $classes = Flux::classes()
        ->add(match ($variant) {
            default => match($position) {
                // For bottom flyout we intentionally use 100% instead of 100vw because Firefox includes scrollbar gutter in vw...
                'bottom' => 'fixed m-0 p-8 min-w-[100%] overflow-y-auto mt-auto [--flux-flyout-translate:translateY(50px)] border-t',
                'left' => 'fixed m-0 p-8 max-h-dvh min-h-dvh md:[:where(&)]:min-w-[25rem] overflow-y-auto mr-auto [--flux-flyout-translate:translateX(-50px)] border-e rtl:mr-0 rtl:ml-auto rtl:[--flux-flyout-translate:translateX(50px)]',
                default => 'fixed m-0 p-8 max-h-dvh min-h-dvh md:[:where(&)]:min-w-[25rem] overflow-y-auto ml-auto [--flux-flyout-translate:translateX(50px)] border-s rtl:ml-0 rtl:mr-auto rtl:[--flux-flyout-translate:translateX(-50px)]',
            },
            'floating' => match($position) {
                // For bottom flyout we intentionally use 100% instead of 100vw because Firefox includes scrollbar gutter in vw...
                'bottom' => 'fixed m-2 p-8 min-w-[calc(100%-1rem)] overflow-y-auto mt-auto [--flux-flyout-translate:translateY(50px)]',
                'left' => 'fixed m-2 p-8 max-h-[calc(100dvh-1rem)] min-h-[calc(100dvh-1rem)] md:[:where(&)]:min-w-[25rem] overflow-y-auto mr-auto [--flux-flyout-translate:translateX(-50px)] rtl:mr-0 rtl:ml-auto rtl:[--flux-flyout-translate:translateX(50px)]',
                default => 'fixed m-2 p-8 max-h-[calc(100dvh-1rem)] min-h-[calc(100dvh-1rem)] md:[:where(&)]:min-w-[25rem] overflow-y-auto ml-auto [--flux-flyout-translate:translateX(50px)] rtl:ml-0 rtl:mr-auto rtl:[--flux-flyout-translate:translateX(-50px)]',
            },
            'bare' => '',
        })
        ->add(match ($variant) {
            default => 'bg-white dark:bg-zinc-800 border-transparent dark:border-zinc-700',
            'floating' => 'bg-white dark:bg-zinc-800 ring ring-black/5 dark:ring-zinc-700 shadow-lg rounded-xl',
            'bare' => 'bg-transparent',
        });
} elseif ($overflow) {
    $classes = Flux::classes();

    $contentClasses = Flux::classes()
        ->add('relative')
        ->add(match ($variant) {
            default => 'p-6 [:where(&)]:max-w-xl [:where(&)]:min-w-xs shadow-lg rounded-xl',
            'bare' => '',
        })
        ->add(match ($variant) {
            default => 'bg-white dark:bg-zinc-800 ring ring-black/5 dark:ring-zinc-700 shadow-lg rounded-xl',
            'bare' => 'bg-transparent',
        });
} else {
    $classes = Flux::classes()
        ->add(match ($variant) {
            default => 'p-6 [:where(&)]:max-w-xl [:where(&)]:min-w-xs shadow-lg rounded-xl',
            'bare' => '',
        })
        ->add(match ($variant) {
            default => 'bg-white dark:bg-zinc-800 ring ring-black/5 dark:ring-zinc-700 shadow-lg rounded-xl',
            'bare' => 'bg-transparent',
        });
}

// Support adding the .self modifier to the wire:model directive...
if (($wireModel = $attributes->wire('model')) && $wireModel->directive && ! $wireModel->hasModifier('self')) {
    unset($attributes[$wireModel->directive]);

    $wireModel->directive .= '.self';

    $attributes = $attributes->merge([$wireModel->directive => $wireModel->value]);
}

if ($attributes['@close'] ?? null) {
    $attributes['wire:close'] = $attributes['@close'];

    unset($attributes['@close']);
}

if ($attributes['@cancel'] ?? null) {
    $attributes['wire:cancel'] = $attributes['@cancel'];

    unset($attributes['@cancel']);
}

if ($dismissible === false) {
    $attributes = $attributes->merge(['disable-click-outside' => '']);
}

if ($escapable === false) {
    $attributes = $attributes->merge(['disable-escape' => '']);
}

[ $contentAttributes, $attributes ] = Flux::splitAttributes($attributes, ['autofocus', 'class', 'style']);
[ $dialogAttributes, $attributes ] = Flux::splitAttributes($attributes, ['wire:close', 'x-on:close', 'wire:cancel', 'x-on:cancel']);

if (! $overflow) {
    $dialogAttributes = $dialogAttributes->merge($contentAttributes->getAttributes());
}
?>

<ui-modal <?php echo e($attributes); ?> data-flux-modal>
    <?php if ($trigger): ?>
        <?php echo e($trigger); ?>

    <?php endif; ?>

    <dialog
        wire:ignore.self 
        <?php echo e($dialogAttributes->class($classes)); ?>

        <?php if ($name): ?> data-modal="<?php echo e($name); ?>" <?php endif; ?>
        <?php if ($flyout): ?> data-flux-flyout <?php endif; ?>
        <?php if ($overflow): ?> data-flux-modal-overflow <?php endif; ?>
        [STARTCOMPILEDUNBLAZE:nKyppDJcMZ]<?php \Livewire\Blaze\Unblaze::storeScope("nKyppDJcMZ", scope: ['name' => $name]) ?><?php \Livewire\Blaze\Unblaze::storeReplacement("nKyppDJcMZ", "CiAgICAgICAgeC1kYXRhPSJmbHV4TW9kYWwoQGpzKCRzY29wZVsnbmFtZSddKSwgQGpzKGlzc2V0KCRfX2xpdmV3aXJlKSA/ICRfX2xpdmV3aXJlLT5nZXRJZCgpIDogbnVsbCkpIgogICAgICAgIA==") ?>[ENDCOMPILEDUNBLAZE:nKyppDJcMZ]
        x-on:modal-show.document="handleShow($event)"
        x-on:modal-close.document="handleClose($event)"
    >
        <?php if ($overflow): ?>
            <div class="flex min-h-full items-center justify-center p-4 sm:p-6">
                <div <?php echo e($contentAttributes->class($contentClasses)); ?> data-flux-modal-content>
                    <?php echo e($slot); ?>


                    <?php if ($closable): ?>
                        <div class="absolute top-0 end-0 mt-4 me-4">
                            <?php if (!function_exists('__360680d04f04e188185494e516df98ad')) { $__blaze->compile('C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/modal/close.blade.php', $__blaze->compiledPath.'/360680d04f04e188185494e516df98ad.php'); require $__blaze->compiledPath.'/360680d04f04e188185494e516df98ad.php'; } ?>
<?php if (isset($__slots360680d04f04e188185494e516df98ad)) { $__slotsStack360680d04f04e188185494e516df98ad[] = $__slots360680d04f04e188185494e516df98ad; } ?>
<?php if (isset($__attrs360680d04f04e188185494e516df98ad)) { $__attrsStack360680d04f04e188185494e516df98ad[] = $__attrs360680d04f04e188185494e516df98ad; } ?>
<?php $__attrs360680d04f04e188185494e516df98ad = []; ?>
<?php $__slots360680d04f04e188185494e516df98ad = []; ?>
<?php $__blaze->pushData($__attrs360680d04f04e188185494e516df98ad); ?>
<?php ob_start(); ?>
                                <?php if (!function_exists('__edde4785f0f34069385094b7fab7b02b')) { $__blaze->compile('C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/button/index.blade.php', $__blaze->compiledPath.'/edde4785f0f34069385094b7fab7b02b.php'); require $__blaze->compiledPath.'/edde4785f0f34069385094b7fab7b02b.php'; } ?>
<?php if (isset($__slotsedde4785f0f34069385094b7fab7b02b)) { $__slotsStackedde4785f0f34069385094b7fab7b02b[] = $__slotsedde4785f0f34069385094b7fab7b02b; } ?>
<?php if (isset($__attrsedde4785f0f34069385094b7fab7b02b)) { $__attrsStackedde4785f0f34069385094b7fab7b02b[] = $__attrsedde4785f0f34069385094b7fab7b02b; } ?>
<?php $__attrsedde4785f0f34069385094b7fab7b02b = ['variant' => 'ghost','icon' => 'x-mark','size' => 'sm','ariaLabel' => e(__('Close modal')),'class' => 'text-zinc-400! hover:text-zinc-800! dark:text-zinc-500! dark:hover:text-white!']; ?>
<?php $__slotsedde4785f0f34069385094b7fab7b02b = []; ?>
<?php $__blaze->pushData($__attrsedde4785f0f34069385094b7fab7b02b); ?>
<?php ob_start(); ?><?php $__slotsedde4785f0f34069385094b7fab7b02b['slot'] = new \Illuminate\View\ComponentSlot($__blaze->processPassthroughContent('trim', trim(ob_get_clean())), []); ?>
<?php $__blaze->pushSlots($__slotsedde4785f0f34069385094b7fab7b02b); ?>
<?php __edde4785f0f34069385094b7fab7b02b($__blaze, $__attrsedde4785f0f34069385094b7fab7b02b, $__slotsedde4785f0f34069385094b7fab7b02b, [], ['ariaLabel' => 'aria-label'], $__this ?? (isset($this) ? $this : null)); ?>
<?php if (! empty($__slotsStackedde4785f0f34069385094b7fab7b02b)) { $__slotsedde4785f0f34069385094b7fab7b02b = array_pop($__slotsStackedde4785f0f34069385094b7fab7b02b); } ?>
<?php if (! empty($__attrsStackedde4785f0f34069385094b7fab7b02b)) { $__attrsedde4785f0f34069385094b7fab7b02b = array_pop($__attrsStackedde4785f0f34069385094b7fab7b02b); } ?>
<?php $__blaze->popData(); ?>
                            <?php $__slots360680d04f04e188185494e516df98ad['slot'] = new \Illuminate\View\ComponentSlot($__blaze->processPassthroughContent('trim', trim(ob_get_clean())), []); ?>
<?php $__blaze->pushSlots($__slots360680d04f04e188185494e516df98ad); ?>
<?php __360680d04f04e188185494e516df98ad($__blaze, $__attrs360680d04f04e188185494e516df98ad, $__slots360680d04f04e188185494e516df98ad, [], [], $__this ?? (isset($this) ? $this : null)); ?>
<?php if (! empty($__slotsStack360680d04f04e188185494e516df98ad)) { $__slots360680d04f04e188185494e516df98ad = array_pop($__slotsStack360680d04f04e188185494e516df98ad); } ?>
<?php if (! empty($__attrsStack360680d04f04e188185494e516df98ad)) { $__attrs360680d04f04e188185494e516df98ad = array_pop($__attrsStack360680d04f04e188185494e516df98ad); } ?>
<?php $__blaze->popData(); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php else: ?>
            <?php echo e($slot); ?>


            <?php if ($closable): ?>
                <div class="absolute top-0 end-0 mt-4 me-4">
                    <?php if (!function_exists('__360680d04f04e188185494e516df98ad')) { $__blaze->compile('C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/modal/close.blade.php', $__blaze->compiledPath.'/360680d04f04e188185494e516df98ad.php'); require $__blaze->compiledPath.'/360680d04f04e188185494e516df98ad.php'; } ?>
<?php if (isset($__slots360680d04f04e188185494e516df98ad)) { $__slotsStack360680d04f04e188185494e516df98ad[] = $__slots360680d04f04e188185494e516df98ad; } ?>
<?php if (isset($__attrs360680d04f04e188185494e516df98ad)) { $__attrsStack360680d04f04e188185494e516df98ad[] = $__attrs360680d04f04e188185494e516df98ad; } ?>
<?php $__attrs360680d04f04e188185494e516df98ad = []; ?>
<?php $__slots360680d04f04e188185494e516df98ad = []; ?>
<?php $__blaze->pushData($__attrs360680d04f04e188185494e516df98ad); ?>
<?php ob_start(); ?>
                        <?php if (!function_exists('__edde4785f0f34069385094b7fab7b02b')) { $__blaze->compile('C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/button/index.blade.php', $__blaze->compiledPath.'/edde4785f0f34069385094b7fab7b02b.php'); require $__blaze->compiledPath.'/edde4785f0f34069385094b7fab7b02b.php'; } ?>
<?php if (isset($__slotsedde4785f0f34069385094b7fab7b02b)) { $__slotsStackedde4785f0f34069385094b7fab7b02b[] = $__slotsedde4785f0f34069385094b7fab7b02b; } ?>
<?php if (isset($__attrsedde4785f0f34069385094b7fab7b02b)) { $__attrsStackedde4785f0f34069385094b7fab7b02b[] = $__attrsedde4785f0f34069385094b7fab7b02b; } ?>
<?php $__attrsedde4785f0f34069385094b7fab7b02b = ['variant' => 'ghost','icon' => 'x-mark','size' => 'sm','ariaLabel' => e(__('Close modal')),'class' => 'text-zinc-400! hover:text-zinc-800! dark:text-zinc-500! dark:hover:text-white!']; ?>
<?php $__slotsedde4785f0f34069385094b7fab7b02b = []; ?>
<?php $__blaze->pushData($__attrsedde4785f0f34069385094b7fab7b02b); ?>
<?php ob_start(); ?><?php $__slotsedde4785f0f34069385094b7fab7b02b['slot'] = new \Illuminate\View\ComponentSlot($__blaze->processPassthroughContent('trim', trim(ob_get_clean())), []); ?>
<?php $__blaze->pushSlots($__slotsedde4785f0f34069385094b7fab7b02b); ?>
<?php __edde4785f0f34069385094b7fab7b02b($__blaze, $__attrsedde4785f0f34069385094b7fab7b02b, $__slotsedde4785f0f34069385094b7fab7b02b, [], ['ariaLabel' => 'aria-label'], $__this ?? (isset($this) ? $this : null)); ?>
<?php if (! empty($__slotsStackedde4785f0f34069385094b7fab7b02b)) { $__slotsedde4785f0f34069385094b7fab7b02b = array_pop($__slotsStackedde4785f0f34069385094b7fab7b02b); } ?>
<?php if (! empty($__attrsStackedde4785f0f34069385094b7fab7b02b)) { $__attrsedde4785f0f34069385094b7fab7b02b = array_pop($__attrsStackedde4785f0f34069385094b7fab7b02b); } ?>
<?php $__blaze->popData(); ?>
                    <?php $__slots360680d04f04e188185494e516df98ad['slot'] = new \Illuminate\View\ComponentSlot($__blaze->processPassthroughContent('trim', trim(ob_get_clean())), []); ?>
<?php $__blaze->pushSlots($__slots360680d04f04e188185494e516df98ad); ?>
<?php __360680d04f04e188185494e516df98ad($__blaze, $__attrs360680d04f04e188185494e516df98ad, $__slots360680d04f04e188185494e516df98ad, [], [], $__this ?? (isset($this) ? $this : null)); ?>
<?php if (! empty($__slotsStack360680d04f04e188185494e516df98ad)) { $__slots360680d04f04e188185494e516df98ad = array_pop($__slotsStack360680d04f04e188185494e516df98ad); } ?>
<?php if (! empty($__attrsStack360680d04f04e188185494e516df98ad)) { $__attrs360680d04f04e188185494e516df98ad = array_pop($__attrsStack360680d04f04e188185494e516df98ad); } ?>
<?php $__blaze->popData(); ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </dialog>
</ui-modal>
<?php
echo $__blaze->processPassthroughContent('ltrim', ltrim(ob_get_clean()));
} endif; ?><?php /**PATH C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/modal/index.blade.php ENDPATH**/ ?>