<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AttendanceSession;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;

class EmployeeAttendanceController extends Controller
{
    public function show($userId, $date)
    {
        $user = User::findOrFail($userId);
        $dateBasedSessions = AttendanceSession::where('user_id', $userId)
            ->whereDate('date', $date)
            ->paginate(5);

        // 合計時間の計算
        $totalWorkDuration = $dateBasedSessions->sum('work_duration');
        $totalBreakDuration = $dateBasedSessions->sum('break_duration');

        // フォーマットされた合計時間の計算
        $totalWorkDurationFormatted = gmdate('H:i:s', $totalWorkDuration);
        $totalBreakDurationFormatted = gmdate('H:i:s', $totalBreakDuration);

        return view('EmployeeAttendance', compact('user', 'dateBasedSessions', 'date', 'totalWorkDurationFormatted', 'totalBreakDurationFormatted'));
    }
}
