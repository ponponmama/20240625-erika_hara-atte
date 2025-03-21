<?php $__env->startSection('content'); ?>
<style>
    .btn-custom {
        background-color: blue;
        color: white;
        padding: 12px;
        border-radius: 4px;
        font-size: 0.7rem;
        border: none;
        cursor: pointer;
        font-weight: bold;
    }
    .btn-custom:hover {
        background-color: #8cbff5;
    }
    .center-buttons {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-top: 20px;
    }
    .form-spacing {
        margin-bottom: 30px;
    }
    .card-container {
        max-width: 600px;
        margin: 0 auto;
    }
</style>

<main class="card-container">
    <section class="card">
        <header class="card-header" style="font-weight: bold;">
            <?php echo e(__('メールアドレスの確認')); ?>

        </header>
        <div class="card-body">
            <?php if(session('resent')): ?>
                <p class="alert alert-success">
                    <?php echo e(__('新しい確認リンクがあなたのメールアドレスに送信されました。')); ?>

                </p>
            <?php endif; ?>
            <p class="verification-message" style="font-weight: bold;"><?php echo e(__('ご登録ありがとうございます！')); ?><br>
            <?php echo e(__('メールで送信されたリンクをクリックしてメールアドレスを確認してください。')); ?><br>
            <?php echo e(__('メールの再送')); ?></p>
            <?php if(session('status') == 'verification-link-sent'): ?>
                <p class="alert alert-success">
                    <?php echo e(__('新しい確認リンクが登録のメールアドレスに送信されました。')); ?>

                </p>
            <?php endif; ?>
            <div class="center-buttons">
                <form method="POST" action="<?php echo e(route('verification.send')); ?>" class="form-spacing">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn-custom">
                        <?php echo e(__('確認メールを再送信')); ?>

                    </button>
                </form>
                <form method="POST" action="<?php echo e(route('logout')); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn-custom">
                        <?php echo e(__('ログアウト')); ?>

                    </button>
                </form>
            </div>
        </div>
    </section>
</main>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/auth/verify-email.blade.php ENDPATH**/ ?>