@component('mail::message')
# メールアドレスの確認

{{ $user->name }} さん、

ご登録ありがとうございます！以下のボタンをクリックしてメールアドレスを確認してください。

@component('mail::button', ['url' => $verificationUrl])
メールアドレスを確認する
@endcomponent

もしこのメールに心当たりがない場合は、このメッセージを無視してください。

よろしくお願いいたします。<br>
{{ config('app.name') }}
@endcomponent