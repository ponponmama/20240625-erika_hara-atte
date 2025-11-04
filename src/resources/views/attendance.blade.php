@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/attendance.css') }}">
@endsection

@section('header_nav')
    <a class="link header__logo" href="/">
        HOME
    </a>
    <a href="/attendance" class="link">日付一覧</a>
    <a href="{{ route('employees.index') }}" class="link">ユーザー一覧</a>
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
            <a href="{{ route('attendance.show', ['date' => \Carbon\Carbon::parse($date)->subDay()->format('Y-m-d')]) }}"
                class="custom-date-pagination-link">＜</a>
            <span class="date_span">{{ $date }}</span>
            <a href="{{ route('attendance.show', ['date' => \Carbon\Carbon::parse($date)->addDay()->format('Y-m-d')]) }}" class="custom-date-pagination-link">＞</a>
        </div>
        <table class="data-table">
            <thead>
                <tr class="data-table-header">
                    <th class="data-table-header-item name-column">名前</th>
                    <th class="data-table-header-item">勤務開始</th>
                    <th class="data-table-header-item">勤務終了</th>
                    <th class="data-table-header-item">休憩時間</th>
                    <th class="data-table-header-item">勤務時間</th>
                </tr>
            </thead>
            <tbody>
                @if ($attendances->count() > 0)
                    @foreach ($attendances as $attendance)
                        <tr class="data-table-row">
                            <td class="data-table-row-item name-column">
                                {{ $attendance->user ? str_replace([' ', '　'], '', $attendance->user->name) : 'Unknown' }}
                            </td>
                            <td class="data-table-row-item">{{ $attendance->work_start_time ? \Carbon\Carbon::parse($attendance->work_start_time)->format('H:i:s') : '' }}
                            </td>
                            <td class="data-table-row-item">
                                @if ($attendance->work_end_time)
                                    {{ \Carbon\Carbon::parse($attendance->work_end_time)->format('H:i:s') }}
                                @elseif ($attendance->is_breaking)
                                    休憩中
                                @else
                                    勤務中
                                @endif
                            </td>
                            <td class="data-table-row-item">{{ \Carbon\CarbonInterval::seconds($attendance->break_duration)->cascade()->format('%H:%I:%S') }}
                            </td>
                            <td class="data-table-row-item">{{ \Carbon\CarbonInterval::seconds($attendance->work_duration)->cascade()->format('%H:%I:%S') }}
                            </td>
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
