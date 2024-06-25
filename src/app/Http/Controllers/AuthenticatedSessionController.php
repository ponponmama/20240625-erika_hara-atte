<?php

namespace App\Http\Controllers;

use App\Http\Requests\AtteLoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    public function create()
    {
        return view('login');
    }

    public function store(AtteLoginRequest $request)
    {
        $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        // メール認証が完了しているか確認
        if (!Auth::user()->hasVerifiedEmail()) {
            Auth::logout();
            return back()->withErrors([
                'email' => 'メールアドレスが認証されていません。',
            ]);
        }

            return redirect()->intended('/');
        }

        return back()->withErrors([
        'email' => '指定された認証情報が記録と一致しません。',
        ]);
    }

    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
