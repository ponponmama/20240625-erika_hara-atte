@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance.css') }}">
@endsection

@section('header_nav')
    <a class="header__logo" href="/">
        ホーム
    </a>
    <a href="/attendance">日付一覧</a>
    <a href="{{ route('employees.index') }}">ユーザー一覧</a>
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button class="logout_button" type="submit">
            ログアウト
        </button>
    </form>
@endsection

@section('content')
<div class="table-container">
    <p class="user_list">ユーザー 一覧</p>
    <table class="data-table">
        <thead>
            <tr>
                <th class="name-column">名前</th>
                <th>勤怠詳細</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($employees as $employee)
            <tr>
                <td class="name-column">{{ $employee->name }}</td>
                <td><a href="{{ route('employee.attendance.show', ['userId' => $employee->id, 'date' => now()->format('Y-m-d')]) }}">勤怠表</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="pagination custom-count-pagination">
        {{ $employees->links() }}
    </div>
</div>
@endsection
