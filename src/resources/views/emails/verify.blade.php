@component('mail::message')
# メールアドレスの確認

{{ $user->name }} さん！

ご登録ありがとうございます！<br>
以下のボタンをクリックしてメールアドレスを確認してください。<br>

<div style="text-align: center; margin-bottom: 20px;">
    <a href="{{ $verificationUrl }}" style="width: 70%; margin: 0 auto; background-color: #0000ff; color: white; padding: 10px 20px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px; border-radius: 5px;">
        メールアドレスを確認する
    </a>
</div>

もしこのメールに心当たりがない場合は、このメッセージを無視してください。

よろしくお願いいたします。<br>
{{ config('app.name') }}
@endcomponent
