<?php $__env->startComponent('mail::message'); ?>
# メールアドレスの確認

<?php echo e($user->name); ?> さん！

ご登録ありがとうございます！以下のボタンをクリックしてメールアドレスを確認してください。

<div style="text-align: center; margin-bottom: 20px;">
    <a href="<?php echo e($verificationUrl); ?>" style="background-color: #0000ff; color: white; padding: 10px 20px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px; border-radius: 5px;">
        メールアドレスを確認する
    </a>
</div>

もしこのメールに心当たりがない場合は、このメッセージを無視してください。

よろしくお願いいたします。<br>
<?php echo e(config('app.name')); ?>

<?php echo $__env->renderComponent(); ?><?php /**PATH /var/www/resources/views/emails/verify.blade.php ENDPATH**/ ?>