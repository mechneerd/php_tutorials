<?php # [BlazeFolded]:{flux::heading}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/heading.blade.php}:{1789433108} ?>
<?php # [BlazeFolded]:{flux::button}:{C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/button/index.blade.php}:{1789433108} ?>
<?php
use App\Concerns\PasswordValidationRules;
use Flux\Flux;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;
use Laravel\Fortify\Features;
use Laravel\Fortify\Fortify;
use Livewire\Attributes\Title;
use Livewire\Component;
?>

<section class="w-full">
    <?php echo $__env->make('partials.settings-heading', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <?php ob_start(); ?><h2 class="font-medium [:where(&amp;)]:text-zinc-800 [:where(&amp;)]:dark:text-white text-sm [&amp;:has(+[data-flux-subheading])]:mb-2 [[data-flux-subheading]+&amp;]:mt-2 sr-only" data-flux-heading><?php ob_start(); ?><?php echo e(__('Security settings')); ?><?php echo trim(ob_get_clean()); ?></h2>

        <?php echo ltrim(ob_get_clean()); ?>

    <?php if (isset($component)) { $__componentOriginal47c6e5d793050babb6edb764210472f1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal47c6e5d793050babb6edb764210472f1 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'af6a29d55d306249cfe5b80ece79872b::settings.layout','data' => ['heading' => __('Update password'),'subheading' => __('Ensure your account is using a long, random password to stay secure')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('pages::settings.layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['heading' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Update password')),'subheading' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('Ensure your account is using a long, random password to stay secure'))]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

        <form method="POST" wire:submit="updatePassword" class="mt-6 space-y-6">
            <?php if (!function_exists('_2683f27394d53853c2e2b74551666d57')) { $__blaze->compile('C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/input/index.blade.php', $__blaze->compiledPath.'/2683f27394d53853c2e2b74551666d57.php'); require $__blaze->compiledPath.'/2683f27394d53853c2e2b74551666d57.php'; } ?>
<?php $__blaze->pushData(['wire:model' => 'current_password','label' => __('Current password'),'type' => 'password','required' => true,'autocomplete' => 'current-password','viewable' => true]); ?>
<?php _2683f27394d53853c2e2b74551666d57($__blaze, ['wire:model' => 'current_password','label' => __('Current password'),'type' => 'password','required' => true,'autocomplete' => 'current-password','viewable' => true], [], ['label', 'required', 'viewable'], [], $__this ?? (isset($this) ? $this : null)); ?>
<?php $__blaze->popData(); ?>
            <?php if (!function_exists('_2683f27394d53853c2e2b74551666d57')) { $__blaze->compile('C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/input/index.blade.php', $__blaze->compiledPath.'/2683f27394d53853c2e2b74551666d57.php'); require $__blaze->compiledPath.'/2683f27394d53853c2e2b74551666d57.php'; } ?>
<?php $__blaze->pushData(['wire:model' => 'password','label' => __('New password'),'type' => 'password','required' => true,'autocomplete' => 'new-password','passwordrules' => e(\Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString()),'viewable' => true]); ?>
<?php _2683f27394d53853c2e2b74551666d57($__blaze, ['wire:model' => 'password','label' => __('New password'),'type' => 'password','required' => true,'autocomplete' => 'new-password','passwordrules' => e(\Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString()),'viewable' => true], [], ['label', 'required', 'viewable'], [], $__this ?? (isset($this) ? $this : null)); ?>
<?php $__blaze->popData(); ?>
            <?php if (!function_exists('_2683f27394d53853c2e2b74551666d57')) { $__blaze->compile('C:\wamp64\www\php_tutorials\vendor\livewire\flux\src/../stubs/resources/views/flux/input/index.blade.php', $__blaze->compiledPath.'/2683f27394d53853c2e2b74551666d57.php'); require $__blaze->compiledPath.'/2683f27394d53853c2e2b74551666d57.php'; } ?>
<?php $__blaze->pushData(['wire:model' => 'password_confirmation','label' => __('Confirm password'),'type' => 'password','required' => true,'autocomplete' => 'new-password','passwordrules' => e(\Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString()),'viewable' => true]); ?>
<?php _2683f27394d53853c2e2b74551666d57($__blaze, ['wire:model' => 'password_confirmation','label' => __('Confirm password'),'type' => 'password','required' => true,'autocomplete' => 'new-password','passwordrules' => e(\Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString()),'viewable' => true], [], ['label', 'required', 'viewable'], [], $__this ?? (isset($this) ? $this : null)); ?>
<?php $__blaze->popData(); ?>

            <div class="flex items-center gap-4">
                <?php ob_start(); ?><button type="submit" class="relative items-center font-medium justify-center whitespace-nowrap disabled:opacity-50 dark:disabled:opacity-50 disabled:cursor-default disabled:pointer-events-none disabled:shadow-none justify-center h-10 text-sm rounded-lg gap-2 ps-4 pe-4 inline-flex  bg-[var(--color-accent)] hover:bg-[color-mix(in_oklab,_var(--color-accent),_transparent_10%)] text-[var(--color-accent-foreground)] border border-black/10 dark:border-0 shadow-[inset_0px_1px_--theme(--color-white/.2)] [[data-flux-button-group]_&amp;]:border-e-0 [:is([data-flux-button-group]&gt;&amp;:last-child,_[data-flux-button-group]_:last-child&gt;&amp;)]:border-e-[1px] dark:[:is([data-flux-button-group]&gt;&amp;:last-child,_[data-flux-button-group]_:last-child&gt;&amp;)]:border-e-0 [:is([data-flux-button-group]&gt;&amp;:not(:first-child),_[data-flux-button-group]_:not(:first-child)&gt;&amp;)]:border-s-[1px]! [:is([data-flux-button-group]&gt;&amp;:not(:first-child),_[data-flux-button-group]_:not(:first-child)&gt;&amp;)]:border-s-[color-mix(in_srgb,var(--color-accent-foreground),transparent_85%)] *:transition-opacity [&amp;[disabled]&gt;:not([data-flux-loading-indicator])]:opacity-0 [&amp;[disabled]&gt;[data-flux-loading-indicator]]:opacity-100 [&amp;[disabled]]:pointer-events-none" data-flux-button="data-flux-button" data-flux-group-target="data-flux-group-target" data-test="update-password-button">
        <div class="absolute inset-0 flex items-center justify-center opacity-0" data-flux-loading-indicator>
                <svg class="shrink-0 [:where(&amp;)]:size-4 animate-spin" data-flux-icon xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true" data-slot="icon">
    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
</svg>
                    </div>
        
        
                    
            
            <span><?php ob_start(); ?>
                    <?php echo e(__('Save')); ?>

                <?php echo trim(ob_get_clean()); ?></span>
    </button>
<?php echo ltrim(ob_get_clean()); ?>
            </div>
        </form>


     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal47c6e5d793050babb6edb764210472f1)): ?>
<?php $attributes = $__attributesOriginal47c6e5d793050babb6edb764210472f1; ?>
<?php unset($__attributesOriginal47c6e5d793050babb6edb764210472f1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal47c6e5d793050babb6edb764210472f1)): ?>
<?php $component = $__componentOriginal47c6e5d793050babb6edb764210472f1; ?>
<?php unset($__componentOriginal47c6e5d793050babb6edb764210472f1); ?>
<?php endif; ?>

</section><?php /**PATH C:\wamp64\www\php_tutorials\storage\framework\views/livewire/views/b8e3059b.blade.php ENDPATH**/ ?>