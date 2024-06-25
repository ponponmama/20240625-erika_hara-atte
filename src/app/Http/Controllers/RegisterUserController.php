<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;


class RegisterUserController extends Controller
{
    //register表示
    public function create()
    {
        return view('register');
    }
    //register登録処理
    public function store(RegisterRequest $request)
    {
        //登録後にメール認証
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));
        Auth::login($user);

        // メール認証通知を送信
        //$user->sendEmailVerificationNotification();

        return redirect()->route('verification.notice');
    }
}
