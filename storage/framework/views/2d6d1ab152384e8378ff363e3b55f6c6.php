<?php
if (!function_exists('_2d6d1ab152384e8378ff363e3b55f6c6')):
function _2d6d1ab152384e8378ff363e3b55f6c6($__blaze, $__data = [], $__slots = [], $__bound = [], $__keys = [], $__this = null) {
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
$__defaults = [
    'interactive' => null,
    'position' => 'top',
    'align' => 'center',
    'content' => null,
    'kbd' => null,
    'toggleable' => null,
];
$interactive ??= $attributes['interactive'] ?? $__defaults['interactive']; unset($attributes['interactive']);
$position ??= $attributes['position'] ?? $__defaults['position']; unset($attributes['position']);
$align ??= $attributes['align'] ?? $__defaults['align']; unset($attributes['align']);
$content ??= $attributes['content'] ?? $__defaults['content']; unset($attributes['content']);
$kbd ??= $attributes['kbd'] ?? $__defaults['kbd']; unset($attributes['kbd']);
$toggleable ??= $attributes['toggleable'] ?? $__defaults['toggleable']; unset($attributes['toggleable']);
unset($__defaults);
?>

<?php
// Support adding the .self modifier to the wire:model directive...
if (($wireModel = $attributes->wire('model')) && $wireModel->directive && ! $wireModel->hasModifier('self')) {
    unset($attributes[$wireModel->directive]);

    $wireModel->directive .= '.self';

    $attributes = $attributes->merge([$wireModel->directive => $wireModel->value]);
}
?>

<?php if ($toggleable): ?>
    <ui-dropdown position="<?php echo e($position); ?> <?php echo e($align); ?>" <?php echo e($attributes); ?> data-flux-tooltip>
        <?php echo e($slot); ?>


        <?php if ($content !== null): ?>
            <?php if (!function_exists('_b71b52111992a6c42fa5f99cb9f0c449')) { $__blaze->compile('C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/tooltip/content.blade.php', $__blaze->compiledPath.'/b71b52111992a6c42fa5f99cb9f0c449.php'); require $__blaze->compiledPath.'/b71b52111992a6c42fa5f99cb9f0c449.php'; } ?>
<?php if (isset($__slotsb71b52111992a6c42fa5f99cb9f0c449)) { $__slotsStackb71b52111992a6c42fa5f99cb9f0c449[] = $__slotsb71b52111992a6c42fa5f99cb9f0c449; } ?>
<?php if (isset($__attrsb71b52111992a6c42fa5f99cb9f0c449)) { $__attrsStackb71b52111992a6c42fa5f99cb9f0c449[] = $__attrsb71b52111992a6c42fa5f99cb9f0c449; } ?>
<?php $__attrsb71b52111992a6c42fa5f99cb9f0c449 = ['kbd' => $kbd]; ?>
<?php $__slotsb71b52111992a6c42fa5f99cb9f0c449 = []; ?>
<?php $__blaze->pushData($__attrsb71b52111992a6c42fa5f99cb9f0c449); ?>
<?php ob_start(); ?><?php echo e($content); ?><?php $__slotsb71b52111992a6c42fa5f99cb9f0c449['slot'] = new \Illuminate\View\ComponentSlot(trim(ob_get_clean()), []); ?>
<?php $__blaze->pushSlots($__slotsb71b52111992a6c42fa5f99cb9f0c449); ?>
<?php _b71b52111992a6c42fa5f99cb9f0c449($__blaze, $__attrsb71b52111992a6c42fa5f99cb9f0c449, $__slotsb71b52111992a6c42fa5f99cb9f0c449, ['kbd'], [], $__this ?? (isset($this) ? $this : null)); ?>
<?php if (! empty($__slotsStackb71b52111992a6c42fa5f99cb9f0c449)) { $__slotsb71b52111992a6c42fa5f99cb9f0c449 = array_pop($__slotsStackb71b52111992a6c42fa5f99cb9f0c449); } ?>
<?php if (! empty($__attrsStackb71b52111992a6c42fa5f99cb9f0c449)) { $__attrsb71b52111992a6c42fa5f99cb9f0c449 = array_pop($__attrsStackb71b52111992a6c42fa5f99cb9f0c449); } ?>
<?php $__blaze->popData(); ?>
        <?php endif; ?>
    </ui-dropdown>
<?php else: ?>
    <ui-tooltip position="<?php echo e($position); ?> <?php echo e($align); ?>" <?php echo e($attributes); ?> data-flux-tooltip <?php if($interactive): ?> interactive <?php endif; ?>>
        <?php echo e($slot); ?>


        <?php if ($content !== null): ?>
            <?php if (!function_exists('_b71b52111992a6c42fa5f99cb9f0c449')) { $__blaze->compile('C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/tooltip/content.blade.php', $__blaze->compiledPath.'/b71b52111992a6c42fa5f99cb9f0c449.php'); require $__blaze->compiledPath.'/b71b52111992a6c42fa5f99cb9f0c449.php'; } ?>
<?php if (isset($__slotsb71b52111992a6c42fa5f99cb9f0c449)) { $__slotsStackb71b52111992a6c42fa5f99cb9f0c449[] = $__slotsb71b52111992a6c42fa5f99cb9f0c449; } ?>
<?php if (isset($__attrsb71b52111992a6c42fa5f99cb9f0c449)) { $__attrsStackb71b52111992a6c42fa5f99cb9f0c449[] = $__attrsb71b52111992a6c42fa5f99cb9f0c449; } ?>
<?php $__attrsb71b52111992a6c42fa5f99cb9f0c449 = ['kbd' => $kbd]; ?>
<?php $__slotsb71b52111992a6c42fa5f99cb9f0c449 = []; ?>
<?php $__blaze->pushData($__attrsb71b52111992a6c42fa5f99cb9f0c449); ?>
<?php ob_start(); ?><?php echo e($content); ?><?php $__slotsb71b52111992a6c42fa5f99cb9f0c449['slot'] = new \Illuminate\View\ComponentSlot(trim(ob_get_clean()), []); ?>
<?php $__blaze->pushSlots($__slotsb71b52111992a6c42fa5f99cb9f0c449); ?>
<?php _b71b52111992a6c42fa5f99cb9f0c449($__blaze, $__attrsb71b52111992a6c42fa5f99cb9f0c449, $__slotsb71b52111992a6c42fa5f99cb9f0c449, ['kbd'], [], $__this ?? (isset($this) ? $this : null)); ?>
<?php if (! empty($__slotsStackb71b52111992a6c42fa5f99cb9f0c449)) { $__slotsb71b52111992a6c42fa5f99cb9f0c449 = array_pop($__slotsStackb71b52111992a6c42fa5f99cb9f0c449); } ?>
<?php if (! empty($__attrsStackb71b52111992a6c42fa5f99cb9f0c449)) { $__attrsb71b52111992a6c42fa5f99cb9f0c449 = array_pop($__attrsStackb71b52111992a6c42fa5f99cb9f0c449); } ?>
<?php $__blaze->popData(); ?>
        <?php endif; ?>
    </ui-tooltip>
<?php endif; ?>
<?php
echo ltrim(ob_get_clean());
} endif; ?><?php /**PATH C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/tooltip/index.blade.php ENDPATH**/ ?>