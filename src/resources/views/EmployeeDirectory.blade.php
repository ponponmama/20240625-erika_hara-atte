@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/attendance.css') }}">
@endsection

@section('header_nav')
    <a class="link header__logo" href="/">
        ホーム
    </a>
    <a href="/attendance" class="link date_list">日付一覧</a>
    <a href="{{ route('employees.index') }}" class="link employee_list">ユーザー一覧</a>
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button class="button logout_button" type="submit">
            ログアウト
        </button>
    </form>
@endsection

@section('content')
    <div class="table-container user-list-container">
        <p class="user_list">ユーザー 一覧</p>
        <table class="data-table">
            <thead>
                <tr class="data-table-header">
                    <th class="data-table-header-item name-column">名前</th>
                    <th class="data-table-header-item">勤怠詳細</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($employees as $employee)
                    <tr class="data-table-row">
                        <td class="data-table-row-item name-column">{{ str_replace([' ', '　'], '', $employee->name) }}</td>
                        <td class="data-table-row-item link"><a class="text-link" href="{{ route('employee.attendance.show', ['userId' => $employee->id, 'date' => now()->format('Y-m-d')]) }}">勤怠表</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="pagination custom-count-pagination">
            {{ $employees->links() }}
        </div>
    </div>
@endsection
