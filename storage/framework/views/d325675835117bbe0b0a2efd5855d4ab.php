<?php
if (!function_exists('_d325675835117bbe0b0a2efd5855d4ab')):
function _d325675835117bbe0b0a2efd5855d4ab($__blaze, $__data = [], $__slots = [], $__bound = [], $__keys = [], $__this = null) {
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
    'name',
    'descriptionTrailing',
    'description',
    'label',
    'badge',
]));
?>

<?php $descriptionTrailing = $descriptionTrailing ??= $attributes->pluck('description:trailing'); ?>

<?php
$__defaults = [
    'name' => $attributes->whereStartsWith('wire:model')->first(),
    'descriptionTrailing' => null,
    'description' => null,
    'label' => null,
    'badge' => null,
];
$name ??= $attributes['name'] ?? $__defaults['name']; unset($attributes['name']);
$descriptionTrailing ??= $attributes['description-trailing'] ?? $attributes['descriptionTrailing'] ?? $__defaults['descriptionTrailing']; unset($attributes['descriptionTrailing'], $attributes['description-trailing']);
$description ??= $attributes['description'] ?? $__defaults['description']; unset($attributes['description']);
$label ??= $attributes['label'] ?? $__defaults['label']; unset($attributes['label']);
$badge ??= $attributes['badge'] ?? $__defaults['badge']; unset($attributes['badge']);
unset($__defaults);
?>

<?php if (isset($label) || isset($description) || isset($descriptionTrailing)): ?>
    <?php

        $fieldAttributes = Flux::attributesAfter('field:', $attributes, []);
        $labelAttributes = Flux::attributesAfter('label:', $attributes, ['badge' => $badge]);
        $descriptionAttributes = Flux::attributesAfter('description:', $attributes, []);
        $errorAttributes = Flux::attributesAfter('error:', $attributes, ['name' => $name]);
    ?>
    <?php if (!function_exists('_e821fb85391f4930d986978f78fcbd3e')) { $__blaze->compile('C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/field.blade.php', $__blaze->compiledPath.'/e821fb85391f4930d986978f78fcbd3e.php'); require $__blaze->compiledPath.'/e821fb85391f4930d986978f78fcbd3e.php'; } ?>
<?php if (isset($__slotse821fb85391f4930d986978f78fcbd3e)) { $__slotsStacke821fb85391f4930d986978f78fcbd3e[] = $__slotse821fb85391f4930d986978f78fcbd3e; } ?>
<?php if (isset($__attrse821fb85391f4930d986978f78fcbd3e)) { $__attrsStacke821fb85391f4930d986978f78fcbd3e[] = $__attrse821fb85391f4930d986978f78fcbd3e; } ?>
<?php $__attrse821fb85391f4930d986978f78fcbd3e = ['attributes' => $fieldAttributes]; ?>
<?php $__slotse821fb85391f4930d986978f78fcbd3e = []; ?>
<?php $__blaze->pushData($__attrse821fb85391f4930d986978f78fcbd3e); ?>
<?php ob_start(); ?>
        <?php if (isset($label)): ?>
            <?php if (!function_exists('_39e64a406346055a837b9fd08d5022f8')) { $__blaze->compile('C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/label.blade.php', $__blaze->compiledPath.'/39e64a406346055a837b9fd08d5022f8.php'); require $__blaze->compiledPath.'/39e64a406346055a837b9fd08d5022f8.php'; } ?>
<?php if (isset($__slots39e64a406346055a837b9fd08d5022f8)) { $__slotsStack39e64a406346055a837b9fd08d5022f8[] = $__slots39e64a406346055a837b9fd08d5022f8; } ?>
<?php if (isset($__attrs39e64a406346055a837b9fd08d5022f8)) { $__attrsStack39e64a406346055a837b9fd08d5022f8[] = $__attrs39e64a406346055a837b9fd08d5022f8; } ?>
<?php $__attrs39e64a406346055a837b9fd08d5022f8 = ['attributes' => $labelAttributes]; ?>
<?php $__slots39e64a406346055a837b9fd08d5022f8 = []; ?>
<?php $__blaze->pushData($__attrs39e64a406346055a837b9fd08d5022f8); ?>
<?php ob_start(); ?><?php echo e($label); ?><?php $__slots39e64a406346055a837b9fd08d5022f8['slot'] = new \Illuminate\View\ComponentSlot(trim(ob_get_clean()), []); ?>
<?php $__blaze->pushSlots($__slots39e64a406346055a837b9fd08d5022f8); ?>
<?php _39e64a406346055a837b9fd08d5022f8($__blaze, $__attrs39e64a406346055a837b9fd08d5022f8, $__slots39e64a406346055a837b9fd08d5022f8, ['attributes'], [], $__this ?? (isset($this) ? $this : null)); ?>
<?php if (! empty($__slotsStack39e64a406346055a837b9fd08d5022f8)) { $__slots39e64a406346055a837b9fd08d5022f8 = array_pop($__slotsStack39e64a406346055a837b9fd08d5022f8); } ?>
<?php if (! empty($__attrsStack39e64a406346055a837b9fd08d5022f8)) { $__attrs39e64a406346055a837b9fd08d5022f8 = array_pop($__attrsStack39e64a406346055a837b9fd08d5022f8); } ?>
<?php $__blaze->popData(); ?>
        <?php endif; ?>

        <?php if (isset($description)): ?>
            <?php if (!function_exists('_ccdf26f189d0591b7d1c52775f3bc424')) { $__blaze->compile('C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/description.blade.php', $__blaze->compiledPath.'/ccdf26f189d0591b7d1c52775f3bc424.php'); require $__blaze->compiledPath.'/ccdf26f189d0591b7d1c52775f3bc424.php'; } ?>
<?php if (isset($__slotsccdf26f189d0591b7d1c52775f3bc424)) { $__slotsStackccdf26f189d0591b7d1c52775f3bc424[] = $__slotsccdf26f189d0591b7d1c52775f3bc424; } ?>
<?php if (isset($__attrsccdf26f189d0591b7d1c52775f3bc424)) { $__attrsStackccdf26f189d0591b7d1c52775f3bc424[] = $__attrsccdf26f189d0591b7d1c52775f3bc424; } ?>
<?php $__attrsccdf26f189d0591b7d1c52775f3bc424 = ['attributes' => $descriptionAttributes]; ?>
<?php $__slotsccdf26f189d0591b7d1c52775f3bc424 = []; ?>
<?php $__blaze->pushData($__attrsccdf26f189d0591b7d1c52775f3bc424); ?>
<?php ob_start(); ?><?php echo e($description); ?><?php $__slotsccdf26f189d0591b7d1c52775f3bc424['slot'] = new \Illuminate\View\ComponentSlot(trim(ob_get_clean()), []); ?>
<?php $__blaze->pushSlots($__slotsccdf26f189d0591b7d1c52775f3bc424); ?>
<?php _ccdf26f189d0591b7d1c52775f3bc424($__blaze, $__attrsccdf26f189d0591b7d1c52775f3bc424, $__slotsccdf26f189d0591b7d1c52775f3bc424, ['attributes'], [], $__this ?? (isset($this) ? $this : null)); ?>
<?php if (! empty($__slotsStackccdf26f189d0591b7d1c52775f3bc424)) { $__slotsccdf26f189d0591b7d1c52775f3bc424 = array_pop($__slotsStackccdf26f189d0591b7d1c52775f3bc424); } ?>
<?php if (! empty($__attrsStackccdf26f189d0591b7d1c52775f3bc424)) { $__attrsccdf26f189d0591b7d1c52775f3bc424 = array_pop($__attrsStackccdf26f189d0591b7d1c52775f3bc424); } ?>
<?php $__blaze->popData(); ?>
        <?php endif; ?>

        <?php echo e($slot); ?>


        
        <?php $__getScope = fn($scope = []) => $scope; ?><?php if (isset($scope)) $__scope = $scope; ?><?php $scope = $__getScope(scope: ['attributes' => $errorAttributes->getAttributes()]); ?>
        <?php if (!function_exists('_aa0278b6c13682a2d983ac62d32a8b46')) { $__blaze->compile('C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/error.blade.php', $__blaze->compiledPath.'/aa0278b6c13682a2d983ac62d32a8b46.php'); require $__blaze->compiledPath.'/aa0278b6c13682a2d983ac62d32a8b46.php'; } ?>
<?php $__blaze->pushData(['attributes' => new \Illuminate\View\ComponentAttributeBag($scope['attributes'])]); ?>
<?php _aa0278b6c13682a2d983ac62d32a8b46($__blaze, ['attributes' => new \Illuminate\View\ComponentAttributeBag($scope['attributes'])], [], ['attributes'], [], $__this ?? (isset($this) ? $this : null)); ?>
<?php $__blaze->popData(); ?>
        <?php if (isset($__scope)) { $scope = $__scope; unset($__scope); } ?>

        <?php if (isset($descriptionTrailing)): ?>
            <?php if (!function_exists('_ccdf26f189d0591b7d1c52775f3bc424')) { $__blaze->compile('C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/description.blade.php', $__blaze->compiledPath.'/ccdf26f189d0591b7d1c52775f3bc424.php'); require $__blaze->compiledPath.'/ccdf26f189d0591b7d1c52775f3bc424.php'; } ?>
<?php if (isset($__slotsccdf26f189d0591b7d1c52775f3bc424)) { $__slotsStackccdf26f189d0591b7d1c52775f3bc424[] = $__slotsccdf26f189d0591b7d1c52775f3bc424; } ?>
<?php if (isset($__attrsccdf26f189d0591b7d1c52775f3bc424)) { $__attrsStackccdf26f189d0591b7d1c52775f3bc424[] = $__attrsccdf26f189d0591b7d1c52775f3bc424; } ?>
<?php $__attrsccdf26f189d0591b7d1c52775f3bc424 = ['attributes' => $descriptionAttributes]; ?>
<?php $__slotsccdf26f189d0591b7d1c52775f3bc424 = []; ?>
<?php $__blaze->pushData($__attrsccdf26f189d0591b7d1c52775f3bc424); ?>
<?php ob_start(); ?><?php echo e($descriptionTrailing); ?><?php $__slotsccdf26f189d0591b7d1c52775f3bc424['slot'] = new \Illuminate\View\ComponentSlot(trim(ob_get_clean()), []); ?>
<?php $__blaze->pushSlots($__slotsccdf26f189d0591b7d1c52775f3bc424); ?>
<?php _ccdf26f189d0591b7d1c52775f3bc424($__blaze, $__attrsccdf26f189d0591b7d1c52775f3bc424, $__slotsccdf26f189d0591b7d1c52775f3bc424, ['attributes'], [], $__this ?? (isset($this) ? $this : null)); ?>
<?php if (! empty($__slotsStackccdf26f189d0591b7d1c52775f3bc424)) { $__slotsccdf26f189d0591b7d1c52775f3bc424 = array_pop($__slotsStackccdf26f189d0591b7d1c52775f3bc424); } ?>
<?php if (! empty($__attrsStackccdf26f189d0591b7d1c52775f3bc424)) { $__attrsccdf26f189d0591b7d1c52775f3bc424 = array_pop($__attrsStackccdf26f189d0591b7d1c52775f3bc424); } ?>
<?php $__blaze->popData(); ?>
        <?php endif; ?>
    <?php $__slotse821fb85391f4930d986978f78fcbd3e['slot'] = new \Illuminate\View\ComponentSlot(trim(ob_get_clean()), []); ?>
<?php $__blaze->pushSlots($__slotse821fb85391f4930d986978f78fcbd3e); ?>
<?php _e821fb85391f4930d986978f78fcbd3e($__blaze, $__attrse821fb85391f4930d986978f78fcbd3e, $__slotse821fb85391f4930d986978f78fcbd3e, ['attributes'], [], $__this ?? (isset($this) ? $this : null)); ?>
<?php if (! empty($__slotsStacke821fb85391f4930d986978f78fcbd3e)) { $__slotse821fb85391f4930d986978f78fcbd3e = array_pop($__slotsStacke821fb85391f4930d986978f78fcbd3e); } ?>
<?php if (! empty($__attrsStacke821fb85391f4930d986978f78fcbd3e)) { $__attrse821fb85391f4930d986978f78fcbd3e = array_pop($__attrsStacke821fb85391f4930d986978f78fcbd3e); } ?>
<?php $__blaze->popData(); ?>
<?php else: ?>
    <?php echo e($slot); ?>

<?php endif; ?>
<?php
echo ltrim(ob_get_clean());
} endif; ?><?php /**PATH C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/with-field.blade.php ENDPATH**/ ?>