@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('メールアドレスの確認') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('新しい確認リンクがあなたのメールアドレスに送信されました。') }}
                        </div>
                    @endif

                    <p>{{ __('ご登録ありがとうございます！始める前に、メールで送信されたリンクをクリックしてメールアドレスを確認してください。メールが届いていない場合は、再送信いたします。') }}</p>

                    @if (session('status') == 'verification-link-sent')
                        <div class="alert alert-success" role="alert">
                            {{ __('新しい確認リンクが登録時に提供されたメールアドレスに送信されました。') }}
                        </div>
                    @endif
                    <div class="mt-4 d-flex justify-content-between">
                        <form method="POST" action="{{ route('verification.send') }}">
                            @csrf
                            <button type="submit" class="btn btn-primary">
                                {{ __('確認メールを再送信') }}
                            </button>
                        </form>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-link">
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