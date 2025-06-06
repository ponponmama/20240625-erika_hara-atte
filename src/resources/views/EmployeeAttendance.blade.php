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
<div class="table-container">
    <div class="pagination custom-date-pagination">
        <a href="{{ route('employee.attendance.show', ['userId' => $user->id, 'date' => \Carbon\Carbon::parse($date)->subDay()->format('Y-m-d')]) }}"><</a>
        <span class="date_span">{{ $date }}</span>
        <a href="{{ route('employee.attendance.show', ['userId' => $user->id, 'date' => \Carbon\Carbon::parse($date)->addDay()->format('Y-m-d')]) }}">></a>
    </div>
    <table class="data-table">
        <thead>
            <tr>
                <th class="name-column">名前</th>
                <th>勤務開始</th>
                <th>勤務終了</th>
                <th>休憩時間</th>
                <th>勤務時間</th>
            </tr>
        </thead>
        <tbody>
            @if($attendances->count() > 0)
                @foreach($attendances as $attendance)
                <tr>
                    <td class="name-column">{{ $user->name }}</td>
                    <td>{{ $attendance->work_start_time ? \Carbon\Carbon::parse($attendance->work_start_time)->format('H:i:s') : '' }}</td>
                    <td>
                        @if ($attendance->work_end_time)
                            {{ \Carbon\Carbon::parse($attendance->work_end_time)->format('H:i:s') }}
                        @elseif ($attendance->is_breaking)
                            休憩中
                        @else
                            勤務中
                        @endif
                    </td>
                    <td>{{ \Carbon\CarbonInterval::seconds($attendance->break_duration)->cascade()->format('%H:%I:%S') }}</td>
                    <td>{{ \Carbon\CarbonInterval::seconds($attendance->work_duration)->cascade()->format('%H:%I:%S') }}</td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="5" class="text-center">この日の勤怠記録はありません</td>
                </tr>
            @endif
        </tbody>
    </table>
    <div class="pagination custom-count-pagination">
        {{ $attendances->links() }}
    </div>
</div>
@endsection
