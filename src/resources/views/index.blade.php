@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('header_nav')
<a class="link header__logo" href="/">
    ホーム
</a>
<a class="link date_list" href="/attendance">
    日付一覧
</a>
<a class="link employee_list" href="{{ route('employees.index') }}">
    ユーザー一覧
</a>
<form action="{{ route('logout') }}" method="POST">
    @csrf
    <button class="button logout_button" type="submit">
        ログアウト
    </button>
</form>
@endsection

@section('content')
    <div class="index_edge">
        <p class="greeting">
            {{ Auth::user()->name }}さん&nbsp;お疲れ様です！
        </p>
        @if (session('action'))
        <p class="alert alert-info">
            {{ session('action') }}
        </p>
        @endif
        @if (session('status'))
        <p class="alert alert-success">
            {{ session('status') }}
        </p>
        @endif
        @if (session('error'))
        <p class="alert alert-danger">
            {{ session('error') }}
        </p>
        @endif
        <div class="attendance-records">
            <div class="record_item">
                <form action="{{ route('work.start') }}" method="POST">
                    @csrf
                    @if($status == 0)
                        <button class="button start_work attendance-button" type="submit" name="start_work">勤務開始</button>
                    @else
                        <button class="button start_work attendance-button" disabled>勤務開始</button>
                    @endif
                </form>
            </div>
            <div class="record_item">
                <form action="{{ route('work.end') }}" method="POST">
                    @csrf
                    @if($status == 1)
                        <button class="button end_work attendance-button" type="submit" name="end_work">勤務終了</button>
                    @else
                        <button class="button end_work attendance-button" type="submit" name="end_work" disabled>勤務終了</button>
                    @endif
                </form>
            </div>
            <div class="record_item">
                <form action="{{ route('break.start') }}" method="POST">
                    @csrf
                    @if($status == 1)
                        <button class="button break_records attendance-button" type="submit" name="start_break">休憩開始</button>
                    @else
                        <button class="button break_records attendance-button" type="submit" name="start_break" disabled>休憩開始</button>
                    @endif
                </form>
            </div>
            <div class="record_item">
                <form action="{{ route('break.end') }}" method="POST">
                    @csrf
                    @if($status == 2)
                        <button class="button end_break attendance-button" type="submit" name="end_break">休憩終了</button>
                    @else
                        <button class="button end_break attendance-button" type="submit" name="end_break" disabled>休憩終了</button>
                    @endif
                </form>
            </div>
        </div>
    </div>
@endsection
