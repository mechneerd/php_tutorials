<?php
if (!function_exists('_2ccf264c8d08f12c8898c94d8c6d344e')):
function _2ccf264c8d08f12c8898c94d8c6d344e($__blaze, $__data = [], $__slots = [], $__bound = [], $__keys = [], $__this = null) {
$__env = $__blaze->env;

if (($__data['attributes'] ?? null) instanceof \Illuminate\View\ComponentAttributeBag) { $__data = $__data + $__data['attributes']->all(); unset($__data['attributes']); }
extract($__slots, EXTR_SKIP); unset($__slots);
extract($__data, EXTR_SKIP);
$attributes = \Livewire\Blaze\Runtime\BlazeAttributeBag::make($__data, $__bound, $__keys);
unset($__data, $__bound, $__keys);
ob_start();
?>


<?php
$__defaults = [
    'iconVariant' => 'mini',
    'size' => null,
];
$iconVariant ??= $attributes['icon-variant'] ?? $attributes['iconVariant'] ?? $__defaults['iconVariant']; unset($attributes['iconVariant'], $attributes['icon-variant']);
$size ??= $attributes['size'] ?? $__defaults['size']; unset($attributes['size']);
unset($__defaults);
?>

<?php
$attributes = $attributes->merge([
    'variant' => 'subtle',
    'class' => '-me-1',
    'square' => true,
    'size' => null,
]);
?>

<?php if (!function_exists('_edde4785f0f34069385094b7fab7b02b')) { $__blaze->compile('C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/button/index.blade.php', $__blaze->compiledPath.'/edde4785f0f34069385094b7fab7b02b.php'); require $__blaze->compiledPath.'/edde4785f0f34069385094b7fab7b02b.php'; } ?>
<?php if (isset($__slotsedde4785f0f34069385094b7fab7b02b)) { $__slotsStackedde4785f0f34069385094b7fab7b02b[] = $__slotsedde4785f0f34069385094b7fab7b02b; } ?>
<?php if (isset($__attrsedde4785f0f34069385094b7fab7b02b)) { $__attrsStackedde4785f0f34069385094b7fab7b02b[] = $__attrsedde4785f0f34069385094b7fab7b02b; } ?>
<?php $__attrsedde4785f0f34069385094b7fab7b02b = ['attributes' => $attributes,'size' => $size === 'sm' || $size === 'xs' ? 'xs' : 'sm','xData' => 'fluxInputViewable','xOn:click' => 'toggle()','xBind:dataViewableOpen' => 'open','ariaLabel' => e(__('Toggle password visibility'))]; ?>
<?php $__slotsedde4785f0f34069385094b7fab7b02b = []; ?>
<?php $__blaze->pushData($__attrsedde4785f0f34069385094b7fab7b02b); ?>
<?php ob_start(); ?>
    <?php if (!function_exists('_ed7b85622bf842e7d4af75c2c048e46d')) { $__blaze->compile('C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/icon/eye-slash.blade.php', $__blaze->compiledPath.'/ed7b85622bf842e7d4af75c2c048e46d.php'); require $__blaze->compiledPath.'/ed7b85622bf842e7d4af75c2c048e46d.php'; } ?>
<?php $__blaze->pushData(['variant' => $iconVariant,'class' => 'hidden [[data-viewable-open]>&]:block']); ?>
<?php _ed7b85622bf842e7d4af75c2c048e46d($__blaze, ['variant' => $iconVariant,'class' => 'hidden [[data-viewable-open]>&]:block'], [], ['variant'], [], $__this ?? (isset($this) ? $this : null)); ?>
<?php $__blaze->popData(); ?>
    <?php if (!function_exists('_13353735b312a3f00f62ab7f9917846e')) { $__blaze->compile('C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/icon/eye.blade.php', $__blaze->compiledPath.'/13353735b312a3f00f62ab7f9917846e.php'); require $__blaze->compiledPath.'/13353735b312a3f00f62ab7f9917846e.php'; } ?>
<?php $__blaze->pushData(['variant' => $iconVariant,'class' => 'block [[data-viewable-open]>&]:hidden']); ?>
<?php _13353735b312a3f00f62ab7f9917846e($__blaze, ['variant' => $iconVariant,'class' => 'block [[data-viewable-open]>&]:hidden'], [], ['variant'], [], $__this ?? (isset($this) ? $this : null)); ?>
<?php $__blaze->popData(); ?>
<?php $__slotsedde4785f0f34069385094b7fab7b02b['slot'] = new \Illuminate\View\ComponentSlot(trim(ob_get_clean()), []); ?>
<?php $__blaze->pushSlots($__slotsedde4785f0f34069385094b7fab7b02b); ?>
<?php _edde4785f0f34069385094b7fab7b02b($__blaze, $__attrsedde4785f0f34069385094b7fab7b02b, $__slotsedde4785f0f34069385094b7fab7b02b, ['attributes', 'size'], ['xData' => 'x-data', 'xOn:click' => 'x-on:click', 'xBind:dataViewableOpen' => 'x-bind:data-viewable-open', 'ariaLabel' => 'aria-label'], $__this ?? (isset($this) ? $this : null)); ?>
<?php if (! empty($__slotsStackedde4785f0f34069385094b7fab7b02b)) { $__slotsedde4785f0f34069385094b7fab7b02b = array_pop($__slotsStackedde4785f0f34069385094b7fab7b02b); } ?>
<?php if (! empty($__attrsStackedde4785f0f34069385094b7fab7b02b)) { $__attrsedde4785f0f34069385094b7fab7b02b = array_pop($__attrsStackedde4785f0f34069385094b7fab7b02b); } ?>
<?php $__blaze->popData(); ?>
<?php
echo ltrim(ob_get_clean());
} endif; ?><?php /**PATH C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/input/viewable.blade.php ENDPATH**/ ?>