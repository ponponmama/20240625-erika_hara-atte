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
            {{ __('メールアドレスの確認') }}
        </header>
        <div class="card-body">
            @if (session('resent'))
                <p class="alert alert-success">
                    {{ __('新しい確認リンクがあなたのメールアドレスに送信されました。') }}
                </p>
            @endif
            <p class="verification-message" style="font-weight: bold;">{{ __('ご登録ありがとうございます！') }}<br>
            {{ __('メールで送信されたリンクをクリックしてメールアドレスを確認してください。') }}<br>
            {{ __('メールの再送') }}</p>
            @if (session('status') == 'verification-link-sent')
                <p class="alert alert-success">
                    {{ __('新しい確認リンクが登録のメールアドレスに送信されました。') }}
                </p>
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
    </section>
</main>
@endsection