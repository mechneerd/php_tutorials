<?php
if (!function_exists('__116e1fb7a6e96269223d025d69305877')):
function __116e1fb7a6e96269223d025d69305877($__blaze, $__data = [], $__slots = [], $__bound = [], $__keys = [], $__this = null) {
$__env = $__blaze->env;
$__slots['slot'] ??= new \Illuminate\View\ComponentSlot('');
if (($__data['attributes'] ?? null) instanceof \Illuminate\View\ComponentAttributeBag) { $__data = $__data + $__data['attributes']->all(); unset($__data['attributes']); }
extract($__slots, EXTR_SKIP); unset($__slots);
extract($__data, EXTR_SKIP);
$attributes = \Livewire\Blaze\Runtime\BlazeAttributeBag::make($__data, $__bound, $__keys);
unset($__data, $__bound, $__keys);
ob_start();
?>


<div <?php echo e($attributes->class('text-sm')); ?> data-slot="text">
    <?php echo e($slot); ?>

</div>
<?php
echo $__blaze->processPassthroughContent('ltrim', ltrim(ob_get_clean()));
} endif; ?><?php /**PATH C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/callout/text.blade.php ENDPATH**/ ?>