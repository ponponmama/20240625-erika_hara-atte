<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/index.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header_nav'); ?>
<a class="link header__logo" href="/">
    ホーム
</a>
<a class="link date_list" href="/attendance">
    日付一覧
</a>
<a class="link employee_list" href="<?php echo e(route('employees.index')); ?>">
    ユーザー一覧
</a>
<form action="<?php echo e(route('logout')); ?>" method="POST">
    <?php echo csrf_field(); ?>
    <button class="button logout_button" type="submit">
        ログアウト
    </button>
</form>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="index_edge">
        <p class="greeting">
            <?php echo e(Auth::user()->name); ?>さん&nbsp;お疲れ様です！
        </p>
        <?php if(session('action')): ?>
        <p class="alert alert-info">
            <?php echo e(session('action')); ?>

        </p>
        <?php endif; ?>
        <?php if(session('status')): ?>
        <p class="alert alert-success">
            <?php echo e(session('status')); ?>

        </p>
        <?php endif; ?>
        <?php if(session('error')): ?>
        <p class="alert alert-danger">
            <?php echo e(session('error')); ?>

        </p>
        <?php endif; ?>
        <div class="attendance-records">
            <div class="record_item">
                <form action="<?php echo e(route('work.start')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php if($status == 0): ?>
                        <button class="button start_work attendance-button" type="submit" name="start_work">勤務開始</button>
                    <?php else: ?>
                        <button class="button start_work attendance-button" disabled>勤務開始</button>
                    <?php endif; ?>
                </form>
            </div>
            <div class="record_item">
                <form action="<?php echo e(route('work.end')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php if($status == 1): ?>
                        <button class="button end_work attendance-button" type="submit" name="end_work">勤務終了</button>
                    <?php else: ?>
                        <button class="button end_work attendance-button" type="submit" name="end_work" disabled>勤務終了</button>
                    <?php endif; ?>
                </form>
            </div>
            <div class="record_item">
                <form action="<?php echo e(route('break.start')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php if($status == 1): ?>
                        <button class="button break_records attendance-button" type="submit" name="start_break">休憩開始</button>
                    <?php else: ?>
                        <button class="button break_records attendance-button" type="submit" name="start_break" disabled>休憩開始</button>
                    <?php endif; ?>
                </form>
            </div>
            <div class="record_item">
                <form action="<?php echo e(route('break.end')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php if($status == 2): ?>
                        <button class="button end_break attendance-button" type="submit" name="end_break">休憩終了</button>
                    <?php else: ?>
                        <button class="button end_break attendance-button" type="submit" name="end_break" disabled>休憩終了</button>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/index.blade.php ENDPATH**/ ?>