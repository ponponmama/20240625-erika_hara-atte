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
<div class="date_table">
        <div class="pagination custom-date-pagination">
        <a href="<?php echo e(route('employee.attendance.show', ['userId' => $user->id, 'date' => \Carbon\Carbon::parse($date)->subDay()->format('Y-m-d')])); ?>"><</a>
        <span class="date_span"><?php echo e($date); ?></span>
        <a href="<?php echo e(route('employee.attendance.show', ['userId' => $user->id, 'date' => \Carbon\Carbon::parse($date)->addDay()->format('Y-m-d')])); ?>">></a>
    </div>
    <div class="date_table_list">
        <table>
            <thead>
                <tr>
                    <th class="date_list_name">名前</th>
                    <th>勤務開始</th>
                    <th>勤務終了</th>
                    <th>休憩時間</th>
                    <th>勤務時間</th>
                </tr>
            </thead>
            <tbody>
                <?php if($attendances->count() > 0): ?>
                    <?php $__currentLoopData = $attendances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attendance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class="date_list_name"><?php echo e($user->name); ?></td>
                        <td><?php echo e($attendance->work_start_time ? \Carbon\Carbon::parse($attendance->work_start_time)->format('H:i:s') : ''); ?></td>
                        <td>
                            <?php if($attendance->work_end_time): ?>
                                <?php echo e(\Carbon\Carbon::parse($attendance->work_end_time)->format('H:i:s')); ?>

                            <?php elseif($attendance->is_breaking): ?>
                                休憩中
                            <?php else: ?>
                                勤務中
                            <?php endif; ?>
                        </td>
                        <td><?php echo e(\Carbon\CarbonInterval::seconds($attendance->break_duration)->cascade()->format('%H:%I:%S')); ?></td>
                        <td><?php echo e(\Carbon\CarbonInterval::seconds($attendance->work_duration)->cascade()->format('%H:%I:%S')); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">この日の勤怠記録はありません</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="pagination custom-count-pagination">
        <?php echo e($attendances->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/EmployeeAttendance.blade.php ENDPATH**/ ?>