<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'sidebar' => false,
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'sidebar' => false,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($sidebar): ?>
    <?php if (!function_exists('_52b8982c9c7c1573012fc8017db900c8')) { $__blaze->compile('C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/sidebar/brand.blade.php', $__blaze->compiledPath.'/52b8982c9c7c1573012fc8017db900c8.php'); require $__blaze->compiledPath.'/52b8982c9c7c1573012fc8017db900c8.php'; } ?>
<?php if (isset($__slots52b8982c9c7c1573012fc8017db900c8)) { $__slotsStack52b8982c9c7c1573012fc8017db900c8[] = $__slots52b8982c9c7c1573012fc8017db900c8; } ?>
<?php if (isset($__attrs52b8982c9c7c1573012fc8017db900c8)) { $__attrsStack52b8982c9c7c1573012fc8017db900c8[] = $__attrs52b8982c9c7c1573012fc8017db900c8; } ?>
<?php $__attrs52b8982c9c7c1573012fc8017db900c8 = ['name' => config('app.name', 'Laravel'),'attributes' => $attributes]; ?>
<?php $__slots52b8982c9c7c1573012fc8017db900c8 = []; ?>
<?php $__blaze->pushData($__attrs52b8982c9c7c1573012fc8017db900c8); ?>
<?php ob_start(); ?>
         <?php ob_start(); ?>
            <?php if (isset($component)) { $__componentOriginal159d6670770cb479b1921cea6416c26c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal159d6670770cb479b1921cea6416c26c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.app-logo-icon','data' => ['class' => 'size-5 fill-current text-white dark:text-black']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-logo-icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'size-5 fill-current text-white dark:text-black']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal159d6670770cb479b1921cea6416c26c)): ?>
<?php $attributes = $__attributesOriginal159d6670770cb479b1921cea6416c26c; ?>
<?php unset($__attributesOriginal159d6670770cb479b1921cea6416c26c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal159d6670770cb479b1921cea6416c26c)): ?>
<?php $component = $__componentOriginal159d6670770cb479b1921cea6416c26c; ?>
<?php unset($__componentOriginal159d6670770cb479b1921cea6416c26c); ?>
<?php endif; ?>
        <?php $__slots52b8982c9c7c1573012fc8017db900c8['logo'] = new \Illuminate\View\ComponentSlot(trim(ob_get_clean()), ['class' => 'flex aspect-square size-8 items-center justify-center rounded-md bg-accent-content text-accent-foreground']); ?>
    <?php $__slots52b8982c9c7c1573012fc8017db900c8['slot'] = new \Illuminate\View\ComponentSlot(trim(ob_get_clean()), []); ?>
<?php $__blaze->pushSlots($__slots52b8982c9c7c1573012fc8017db900c8); ?>
<?php _52b8982c9c7c1573012fc8017db900c8($__blaze, $__attrs52b8982c9c7c1573012fc8017db900c8, $__slots52b8982c9c7c1573012fc8017db900c8, ['name', 'attributes'], [], $__this ?? (isset($this) ? $this : null)); ?>
<?php if (! empty($__slotsStack52b8982c9c7c1573012fc8017db900c8)) { $__slots52b8982c9c7c1573012fc8017db900c8 = array_pop($__slotsStack52b8982c9c7c1573012fc8017db900c8); } ?>
<?php if (! empty($__attrsStack52b8982c9c7c1573012fc8017db900c8)) { $__attrs52b8982c9c7c1573012fc8017db900c8 = array_pop($__attrsStack52b8982c9c7c1573012fc8017db900c8); } ?>
<?php $__blaze->popData(); ?>
<?php else: ?>
    <?php if (!function_exists('_c3e1e5055c6167608989d3e37dd33ddb')) { $__blaze->compile('C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/brand.blade.php', $__blaze->compiledPath.'/c3e1e5055c6167608989d3e37dd33ddb.php'); require $__blaze->compiledPath.'/c3e1e5055c6167608989d3e37dd33ddb.php'; } ?>
<?php if (isset($__slotsc3e1e5055c6167608989d3e37dd33ddb)) { $__slotsStackc3e1e5055c6167608989d3e37dd33ddb[] = $__slotsc3e1e5055c6167608989d3e37dd33ddb; } ?>
<?php if (isset($__attrsc3e1e5055c6167608989d3e37dd33ddb)) { $__attrsStackc3e1e5055c6167608989d3e37dd33ddb[] = $__attrsc3e1e5055c6167608989d3e37dd33ddb; } ?>
<?php $__attrsc3e1e5055c6167608989d3e37dd33ddb = ['name' => config('app.name', 'Laravel'),'attributes' => $attributes]; ?>
<?php $__slotsc3e1e5055c6167608989d3e37dd33ddb = []; ?>
<?php $__blaze->pushData($__attrsc3e1e5055c6167608989d3e37dd33ddb); ?>
<?php ob_start(); ?>
         <?php ob_start(); ?>
            <?php if (isset($component)) { $__componentOriginal159d6670770cb479b1921cea6416c26c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal159d6670770cb479b1921cea6416c26c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.app-logo-icon','data' => ['class' => 'size-5 fill-current text-white dark:text-black']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-logo-icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'size-5 fill-current text-white dark:text-black']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal159d6670770cb479b1921cea6416c26c)): ?>
<?php $attributes = $__attributesOriginal159d6670770cb479b1921cea6416c26c; ?>
<?php unset($__attributesOriginal159d6670770cb479b1921cea6416c26c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal159d6670770cb479b1921cea6416c26c)): ?>
<?php $component = $__componentOriginal159d6670770cb479b1921cea6416c26c; ?>
<?php unset($__componentOriginal159d6670770cb479b1921cea6416c26c); ?>
<?php endif; ?>
        <?php $__slotsc3e1e5055c6167608989d3e37dd33ddb['logo'] = new \Illuminate\View\ComponentSlot(trim(ob_get_clean()), ['class' => 'flex aspect-square size-8 items-center justify-center rounded-md bg-accent-content text-accent-foreground']); ?>
    <?php $__slotsc3e1e5055c6167608989d3e37dd33ddb['slot'] = new \Illuminate\View\ComponentSlot(trim(ob_get_clean()), []); ?>
<?php $__blaze->pushSlots($__slotsc3e1e5055c6167608989d3e37dd33ddb); ?>
<?php _c3e1e5055c6167608989d3e37dd33ddb($__blaze, $__attrsc3e1e5055c6167608989d3e37dd33ddb, $__slotsc3e1e5055c6167608989d3e37dd33ddb, ['name', 'attributes'], [], $__this ?? (isset($this) ? $this : null)); ?>
<?php if (! empty($__slotsStackc3e1e5055c6167608989d3e37dd33ddb)) { $__slotsc3e1e5055c6167608989d3e37dd33ddb = array_pop($__slotsStackc3e1e5055c6167608989d3e37dd33ddb); } ?>
<?php if (! empty($__attrsStackc3e1e5055c6167608989d3e37dd33ddb)) { $__attrsc3e1e5055c6167608989d3e37dd33ddb = array_pop($__attrsStackc3e1e5055c6167608989d3e37dd33ddb); } ?>
<?php $__blaze->popData(); ?>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH C:\wamp64\www\php_tutorials\resources\views/components/app-logo.blade.php ENDPATH**/ ?>