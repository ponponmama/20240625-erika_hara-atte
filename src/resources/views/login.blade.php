@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/common-auth-styles.css') }}">
@endsection

    @section('content')
    <div class="custom-container">
        <div class="container">
            <div class="custom-form-container">
                <h2 class="custom_title">
                    ログイン
                </h2>
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="form_input">
                        <input type="email" name="email" id="email" placeholder="メールアドレス" class="form_text" value="{{ old('email') }}">
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
                    <div class="form_button">
                        <button type="submit" class="custom-submit-button">ログイン</button>
                    </div>
                    <div class="text_link">
                        <p class="login-link-text">アカウントをお持ちでない方はこちらから</p>
                        <a href="{{ route('register') }}" class="custom-login-link">会員登録</a>
                    </div>        
                </form>    
            </div>
    @endsection