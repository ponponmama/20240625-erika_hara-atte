<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        
        return [
            'name' => ['required', 'max:191'],
            'email' => ['required',  'email', 'max:191', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'max:191', 'confirmed', 'regex:/[A-Z]/'],
            'password_confirmation' => ['required'], 
        ];
    }

    public function messages()
    {   
       return [
            'name.required' => 'お名前を入力してください。',
            'name.max' => 'お名前は191文字以内で入力してください。',
            'email.required' => 'メールアドレスを入力してください。',
            'email.email' => '有効なメールアドレスを入力してください。',
            'email.max' => 'メールアドレスは191文字以内で入力してください。',
            'email.unique' => 'このメールアドレスは既に登録されています。',
            'password.required' => 'パスワードを入力してください。',
            'password.min' => 'パスワードは最低8文字必要です。',
            'password.max' => 'パスワードは191文字以内で入力してください。',
            'password.regex' => 'パスワードには少なくとも一つの大文字が含まれている必要があります。',
            'password.confirmed' => 'パスワードが確認用パスワードと一致しません。',
            'password_confirmation.required' => '確認用パスワードを入力してください。' ,
            
        ];
    }
    
}
