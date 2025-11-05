@extends('layouts.app')

@section('content')
    <style>
        .btn-custom {
            width: 100%;
            background-color: rgba(3, 24, 251, 0.772);
            color: white;
            padding: 4px;
            border-radius: 4px;
            font-size: 18px;
            line-height: 22px
            border: none;
            cursor: pointer;
            font-weight: bold;
        }

        .btn-custom:hover {
            background-color: #8cbff5;
        }

        .center-buttons {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 20px;
        }

        .form-spacing {
            width: 100%;
            margin-bottom: 30px;
        }

        .card-container {
            max-width: 600px;
            margin: 0 auto;
        }

        .card-header {
            font-weight: bold;
            font-size: 22px;
            line-height: 26px;
            margin: 20px auto;
        }

        .verification-message {
            font-weight: bold;
            font-size: 20px;
            line-height: 22px;
            margin: 20px auto;
        }

        .verification-text-message {
            text-align: center;
            font-size: 18px;
            line-height: 22px;
            margin: 20px auto;
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
                <p class="verification-message">{{ __('ご登録ありがとうございます！') }}<br>
                    {{ __('メールで送信されたリンクをクリックしてメールアドレスを確認してください。') }}</p>
                    <p class="verification-text-message">{{ __('メールの再送') }}</p>
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
                </div>
            </div>
        </section>
    </main>
@endsection
