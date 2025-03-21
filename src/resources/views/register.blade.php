@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endsection

@section('content')
    <div class="custom-form-container">
        <h2 class="custom_title">会員登録</h2>
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="form_input">
                <input type="text" name="name" id="name" placeholder="名前" value="{{ old('name') }}">
                <p class="form__error">
                    @error('name')
                        {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="form_input">
                <input type="email" name="email" id="email" placeholder="メールアドレス" value="{{ old('email') }}">
                <p class="form__error">
                    @error('email')
                        {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="form_input">
                <input type="password" name="password" id="password" placeholder="パスワード">
                <p class="form__error">
                    @error('password')
                        {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="form_input">
                <input type="password" name="password_confirmation" id="password_confirmation" placeholder="確認用パスワード">
                <p class="form__error">
                    @error('password_confirmation')
                        {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="form_input">
                <button type="submit" class="custom-submit-button">
                    会員登録
                </button>
            </div>
        </form>
        <p class="link-text">
            アカウントをお持ちの方はこちらから
        </p>
        <a class="jump_link" href="{{ route('login') }}" class="custom-login-link">
            ログイン
        </a>
    </div>
@endsection
