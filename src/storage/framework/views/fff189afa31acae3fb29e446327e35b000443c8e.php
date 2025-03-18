<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('css/common-auth-styles.css')); ?>">
<?php $__env->stopSection(); ?>

    <?php $__env->startSection('content'); ?>
    <div class="custom-container">
        <div class="container">
            <div class="custom-form-container">
                <h2 class="custom_title_register">会員登録</h2>
                <form method="POST" action="<?php echo e(route('register')); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="form_input">
                        <input type="text" name="name" id="name" placeholder="名前" value="<?php echo e(old('name')); ?>" class="form_text">
                    </div>
                    <div class="form__error">
                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <?php echo e($message); ?>

                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="form_input">
                        <input type="email" name="email" id="email" placeholder="メールアドレス" value="<?php echo e(old('email')); ?>" class="form_text">
                    </div>
                    <div class="form__error">
                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <?php echo e($message); ?>

                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="form_input">
                        <input type="password" name="password" id="password" placeholder="パスワード" class="form_text">
                    </div>
                    <div class="form__error">
                        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <?php echo e($message); ?>

                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="form_input">
                        <input type="password" name="password_confirmation" id="password_confirmation" placeholder="確認用パスワード" class="form_text">
                    </div>
                    <div class="form__error">
                        <?php $__errorArgs = ['password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <?php echo e($message); ?>

                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="form_input">
                        <button type="submit" class="custom-submit-button">会員登録</button>
                    </div>
                </form>
                <div class="text_link">
                    <p class="login-link-text">アカウントをお持ちの方はこちらから</p>
                    <a href="<?php echo e(route('login')); ?>" class="custom-login-link">ログイン</a>
                </div>
            </div>
        </div>
    </div>
    <?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/register.blade.php ENDPATH**/ ?>