<?php

namespace App\Http\Controllers;

use App\Models\AttendanceRecord;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Support\Facades\Log;

class EmployeeAttendanceController extends Controller
{
    public function show($userId, $date)
    {
        Log::info('Employee attendance show method called:', [
            'user_id' => $userId,
            'date' => $date
        ]);

        $user = User::findOrFail($userId);

        // AttendanceRecordから直接データを取得
        $attendances = AttendanceRecord::where('user_id', $userId)
            ->whereDate('date', $date)
            ->with(['breakTimes'])
            ->orderBy('work_start_time', 'desc')
            ->paginate(5);

        $totalWorkDuration = 0;
        $totalBreakDuration = 0;

        foreach ($attendances as $attendance) {
            // 現在進行中の休憩を取得
            $currentBreak = $attendance->breakTimes()
                ->whereNull('break_end_time')
                ->first();

            // 終了した休憩の合計時間を計算
            $breakDuration = $attendance->breakTimes()
                ->whereNotNull('break_end_time')
                ->get()
                ->sum(function ($break) {
                    return Carbon::parse($break->break_end_time)
                        ->diffInSeconds(Carbon::parse($break->break_start_time));
                });

            // 現在進行中の休憩時間を加算
            if ($currentBreak) {
                $breakStart = Carbon::parse($currentBreak->break_start_time);
                $breakDuration += Carbon::now()->diffInSeconds($breakStart);
            }

            // 勤務時間の計算
            if ($attendance->work_start_time) {
                $workStart = Carbon::parse($attendance->work_start_time);
                $workEnd = $attendance->work_end_time
                    ? Carbon::parse($attendance->work_end_time)
                    : Carbon::now();

                $workDuration = $workEnd->diffInSeconds($workStart);
                $attendance->work_duration = max(0, $workDuration - $breakDuration);
            } else {
                $attendance->work_duration = 0;
            }

            $attendance->break_duration = $breakDuration;
            $attendance->is_breaking = $currentBreak !== null;

            // 合計時間に加算
            $totalWorkDuration += $attendance->work_duration;
            $totalBreakDuration += $breakDuration;

            Log::info('Processed employee attendance record:', [
                'id' => $attendance->id,
                'work_start' => $attendance->work_start_time,
                'work_end' => $attendance->work_end_time,
                'break_duration' => $breakDuration,
                'work_duration' => $attendance->work_duration,
                'is_breaking' => $attendance->is_breaking
            ]);
        }

        // フォーマットされた合計時間の計算
        $totalWorkDurationFormatted = CarbonInterval::seconds($totalWorkDuration)->cascade()->format('%H:%I:%S');
        $totalBreakDurationFormatted = CarbonInterval::seconds($totalBreakDuration)->cascade()->format('%H:%I:%S');

        return view('EmployeeAttendance', compact('user', 'attendances', 'date', 'totalWorkDurationFormatted', 'totalBreakDurationFormatted'));
    }
}