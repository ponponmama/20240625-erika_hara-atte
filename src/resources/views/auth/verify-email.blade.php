@extends('layouts.app')

@section('content')
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
        margin-bottom: 20px; /* フォーム間のマージンを設定 */
    }
</style>
<div class="container" style="margin-left: 10%; margin-right: 10%;">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header" style="font-weight: bold;">{{ __('メールアドレスの確認') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('新しい確認リンクがあなたのメールアドレスに送信されました。') }}
                        </div>
                    @endif

                    <p style="font-weight: bold;">{{ __('ご登録ありがとうございます！') }}<br>
                    {{ __('始める前に、メールで送信されたリンクをクリックしてメールアドレスを確認してください。') }}<br>
                    {{ __('メールが届いていない場合は、再送いたします。') }}</p>

                    @if (session('status') == 'verification-link-sent')
                        <div class="alert alert-success" role="alert">
                            {{ __('新しい確認リンクが登録時に提供されたメールアドレスに送信されました。') }}
                        </div>
                    @endif
                    <div class="center-buttons">
                        <form method="POST" action="{{ route('verification.send') }}" class="form-spacing">
                            @csrf
                            <button type="submit" class="btn-custom">
                                {{ __('確認メールを再送信') }}
                            </button>
                        </form>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn-custom">
                                {{ __('ログアウト') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection