@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/attendance.css') }}">
@endsection

@section('header_nav')
    <a class="header__logo" href="/">
        HOME
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
<div class="date_table">
    <div class="pagination custom-date-pagination">
        <a href="{{ route('attendance.show', ['date' => \Carbon\Carbon::parse($date)->subDay()->format('Y-m-d')]) }}"><</a>
        <span class="date_span">{{ $date }}</span>
        <a href="{{ route('attendance.show', ['date' => \Carbon\Carbon::parse($date)->addDay()->format('Y-m-d')]) }}">></a>
    </div>
    <div class="date_table_list">
        <table>
            <thead>
                <tr>
                    <th class="date_list_name">名前</th>
                    <th>勤務開始</th>
                    <th>勤務終了</th>
                    <th>休憩時間</th>
                    <th>勤務時間</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dateBasedSessions as $session)
                <tr>
                    <td class="date_list_name">{{ $session->user->name }}</td>
                    <td>{{ \Carbon\Carbon::parse($session->work_start_time)->format('H:i:s') }}</td>
                    <td>{{ \Carbon\Carbon::parse($session->work_end_time)->format('H:i:s') }}</td>
                    <td>{{ gmdate('H:i:s', $session->break_duration) }}</td>
                    <td>{{ gmdate('H:i:s', $session->work_duration) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="pagination custom-count-pagination">
        {{ $dateBasedSessions->links() }}
    </div>
</div>
@endsection