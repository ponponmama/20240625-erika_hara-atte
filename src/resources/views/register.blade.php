@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/common-auth-styles.css') }}">
@endsection

    @section('content')
    <div class="custom-container">
        <div class="container">
            <div class="custom-form-container">
                <h2 class="custom_title_register">会員登録</h2>
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="form_input">
                        <input type="text" name="name" id="name" placeholder="名前" value="{{ old('name') }}" class="form_text">
                    </div>
                    <div class="form__error">
                        @error('name')
                            {{ $message }}
                        @enderror
                    </div>
                    <div class="form_input">
                        <input type="email" name="email" id="email" placeholder="メールアドレス" value="{{ old('email') }}" class="form_text">
                    </div>
                    <div class="form__error">
                        @error('email')
                            {{ $message }}
                        @enderror
                    </div>
                    <div class="form_input">
                        <input type="password" name="password" id="password" placeholder="パスワード" class="form_text">
                    </div>
                    <div class="form__error">
                        @error('password')
                            {{ $message }}
                        @enderror
                    </div>
                    <div class="form_input">
                        <input type="password" name="password_confirmation" id="password_confirmation" placeholder="確認用パスワード" class="form_text">
                    </div>
                    <div class="form__error">
                        @error('password_confirmation')
                            {{ $message }}
                        @enderror
                    </div>
                    <div class="form_input">
                        <button type="submit" class="custom-submit-button">会員登録</button>
                    </div>
                </form>
                <div class="text_link">
                    <p class="login-link-text">アカウントをお持ちの方はこちらから</p>
                    <a href="{{ route('login') }}" class="custom-login-link">ログイン</a>
                </div>
            </div>
        </div>
    </div>
    @endsection
