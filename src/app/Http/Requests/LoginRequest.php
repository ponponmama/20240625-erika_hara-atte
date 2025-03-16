<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class LoginRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        Log::info('Validating login request', $this->all());
        return [
            'email' => ['required', 'email', 'max:191'],
            'password' => ['required', 'string', 'min:8', 'max:191', 'regex:/[A-Z]/'],
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'メールアドレスを入力してください。',
            'email.email' => '有効なメールアドレスを入力してください。',
            'email.max' => 'メールアドレスは191文字以内で入力してください。',
            'password.required' => 'パスワードを入力してください。',
            'password.min' => 'パスワードは最低8文字必要です。',
            'password.max' => 'パスワードは191文字以内で入力してください。',
            'password.regex' => 'パスワードには少なくとも一つの大文字が含まれている必要があります。',
        ];
    }
}