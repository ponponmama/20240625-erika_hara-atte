<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/attendance.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header_nav'); ?>
    <a class="header__logo" href="/">
        ホーム
    </a>
    <a href="/attendance">日付一覧</a>
    <a href="<?php echo e(route('employees.index')); ?>">ユーザー一覧</a>
    <form action="<?php echo e(route('logout')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <button class="logout_button" type="submit">
            ログアウト
        </button>
    </form>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="table-container">
    <p class="user_list">ユーザー 一覧</p>
    <table class="data-table">
        <thead>
            <tr>
                <th class="name-column">名前</th>
                <th>勤怠詳細</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td class="name-column"><?php echo e($employee->name); ?></td>
                <td><a href="<?php echo e(route('employee.attendance.show', ['userId' => $employee->id, 'date' => now()->format('Y-m-d')])); ?>">勤怠表</a></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    <div class="pagination custom-count-pagination">
        <?php echo e($employees->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/EmployeeDirectory.blade.php ENDPATH**/ ?>