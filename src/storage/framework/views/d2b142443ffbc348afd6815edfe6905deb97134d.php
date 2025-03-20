<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Atte</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/sanitize.css')); ?>">
    <link href="<?php echo e(asset('css/common.css')); ?>" rel="stylesheet">
    <?php echo $__env->yieldContent('css'); ?>
</head>

<body>
    <header class="header">
        <div class="header__inner">
            <h2 class="top_logo">Atte</h2>
            <div class="header_nav">
                <?php echo $__env->yieldContent('header_nav'); ?>
            </div>
        </div>
    </header>

    <main class="main_section">
        <?php echo $__env->yieldContent('content'); ?>
    </main>
    <footer class="footer">
        ©Atte,inc.
    </footer>
</body>
</html><?php /**PATH /var/www/resources/views/layouts/app.blade.php ENDPATH**/ ?>