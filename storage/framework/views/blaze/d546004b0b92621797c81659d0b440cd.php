<?php
if (!function_exists('__d546004b0b92621797c81659d0b440cd')):
function __d546004b0b92621797c81659d0b440cd($__blaze, $__data = [], $__slots = [], $__bound = [], $__keys = [], $__this = null) {
$__env = $__blaze->env;
$__slots['slot'] ??= new \Illuminate\View\ComponentSlot('');
if (($__data['attributes'] ?? null) instanceof \Illuminate\View\ComponentAttributeBag) { $__data = $__data + $__data['attributes']->all(); unset($__data['attributes']); }
extract($__slots, EXTR_SKIP); unset($__slots);
extract($__data, EXTR_SKIP);
$attributes = \Livewire\Blaze\Runtime\BlazeAttributeBag::make($__data, $__bound, $__keys);
unset($__data, $__bound, $__keys);
ob_start();
?>


<?php
extract(Flux::forwardedAttributes($attributes, [
    'tooltipPosition',
    'tooltipKbd',
    'tooltip',
]));
?>

<?php $tooltipPosition = $tooltipPosition ??= $attributes->pluck('tooltip:position'); ?>
<?php $tooltipKbd = $tooltipKbd ??= $attributes->pluck('tooltip:kbd'); ?>
<?php $tooltip = $tooltip ??= $attributes->pluck('tooltip'); ?>

<?php
$__defaults = [
    'tooltipPosition' => 'top',
    'tooltipKbd' => null,
    'tooltip' => null,
];
$tooltipPosition ??= $attributes['tooltip-position'] ?? $attributes['tooltipPosition'] ?? $__defaults['tooltipPosition']; unset($attributes['tooltipPosition'], $attributes['tooltip-position']);
$tooltipKbd ??= $attributes['tooltip-kbd'] ?? $attributes['tooltipKbd'] ?? $__defaults['tooltipKbd']; unset($attributes['tooltipKbd'], $attributes['tooltip-kbd']);
$tooltip ??= $attributes['tooltip'] ?? $__defaults['tooltip']; unset($attributes['tooltip']);
unset($__defaults);
?>

<?php if ($tooltip): ?>
    <?php if (!function_exists('__2d6d1ab152384e8378ff363e3b55f6c6')) { $__blaze->compile('C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/tooltip/index.blade.php', $__blaze->compiledPath.'/2d6d1ab152384e8378ff363e3b55f6c6.php'); require $__blaze->compiledPath.'/2d6d1ab152384e8378ff363e3b55f6c6.php'; } ?>
<?php if (isset($__slots2d6d1ab152384e8378ff363e3b55f6c6)) { $__slotsStack2d6d1ab152384e8378ff363e3b55f6c6[] = $__slots2d6d1ab152384e8378ff363e3b55f6c6; } ?>
<?php if (isset($__attrs2d6d1ab152384e8378ff363e3b55f6c6)) { $__attrsStack2d6d1ab152384e8378ff363e3b55f6c6[] = $__attrs2d6d1ab152384e8378ff363e3b55f6c6; } ?>
<?php $__attrs2d6d1ab152384e8378ff363e3b55f6c6 = ['class' => 'inline-flex','content' => $tooltip,'position' => $tooltipPosition,'kbd' => $tooltipKbd]; ?>
<?php $__slots2d6d1ab152384e8378ff363e3b55f6c6 = []; ?>
<?php $__blaze->pushData($__attrs2d6d1ab152384e8378ff363e3b55f6c6); ?>
<?php ob_start(); ?>
        <?php echo e($slot); ?>

    <?php $__slots2d6d1ab152384e8378ff363e3b55f6c6['slot'] = new \Illuminate\View\ComponentSlot($__blaze->processPassthroughContent('trim', trim(ob_get_clean())), []); ?>
<?php $__blaze->pushSlots($__slots2d6d1ab152384e8378ff363e3b55f6c6); ?>
<?php __2d6d1ab152384e8378ff363e3b55f6c6($__blaze, $__attrs2d6d1ab152384e8378ff363e3b55f6c6, $__slots2d6d1ab152384e8378ff363e3b55f6c6, ['content', 'position', 'kbd'], [], $__this ?? (isset($this) ? $this : null)); ?>
<?php if (! empty($__slotsStack2d6d1ab152384e8378ff363e3b55f6c6)) { $__slots2d6d1ab152384e8378ff363e3b55f6c6 = array_pop($__slotsStack2d6d1ab152384e8378ff363e3b55f6c6); } ?>
<?php if (! empty($__attrsStack2d6d1ab152384e8378ff363e3b55f6c6)) { $__attrs2d6d1ab152384e8378ff363e3b55f6c6 = array_pop($__attrsStack2d6d1ab152384e8378ff363e3b55f6c6); } ?>
<?php $__blaze->popData(); ?>
<?php else: ?>
    <?php echo e($slot); ?>

<?php endif; ?>
<?php
echo $__blaze->processPassthroughContent('ltrim', ltrim(ob_get_clean()));
} endif; ?><?php /**PATH C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/with-tooltip.blade.php ENDPATH**/ ?>