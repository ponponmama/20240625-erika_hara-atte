@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endsection

@section('content')
    <div class="custom-form-container">
        <h2 class="custom_title">
            ログイン
        </h2>
        <form method="POST" action="{{ route('login') }}">
            @csrf
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
            <div class="form_button">
                <button type="submit" class="custom-submit-button">
                    ログイン
                </button>
            </div>
        </form>
        <p class="link-text">アカウントをお持ちでない方はこちらから</p>
        <a href="{{ route('register') }}" class="jump_link">会員登録</a>
    </div>
@endsection
