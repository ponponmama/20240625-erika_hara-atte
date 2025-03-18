<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\AttendanceRecord;
use App\Models\BreakTime;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AttendanceController extends Controller
{
    public function showAttendanceIndex()
    {
        $today = Carbon::now()->toDateString();
        return redirect()->route('attendance.show', ['date' => $today]);
    }

    public function index()
    {
        $user = Auth::user();
        $today = Carbon::now()->toDateString();
        $attendance = $this->getCurrentAttendance($user->id, $today);

        $status = $this->getStatus($attendance);
        return view('index', compact('status'));
    }

    private function getStatus($attendance)
    {
        if (!$attendance) {
            session()->put('action', '勤務未開始');
            return 0;
        }

        if ($attendance->work_end_time) {
            session()->put('action', '勤務終了');
            return 3;
        }

        $currentBreak = $attendance->breakTimes()
            ->whereNull('break_end_time')
            ->first();

        if ($currentBreak) {
            session()->put('action', '休憩中');
            return 2;
        }

        session()->put('action', '勤務中');
        return 1;
    }

    public function startWork()
    {
        try {
            $user = Auth::user();
            $today = Carbon::now()->toDateString();

            Log::info('勤務開始処理開始', [
                'user_id' => $user->id,
                'date' => $today
            ]);

            if ($this->getCurrentAttendance($user->id, $today)) {
                Log::warning('既に勤務開始済み', [
                    'user_id' => $user->id,
                    'date' => $today
                ]);
                return back()->with('error', '既に勤務が開始されています。');
            }

            $record = AttendanceRecord::create([
                'user_id' => $user->id,
                'date' => $today,
                'work_start_time' => Carbon::now()
            ]);

            Log::info('勤務開始レコード作成成功', [
                'record_id' => $record->id,
                'user_id' => $record->user_id,
                'date' => $record->date,
                'work_start_time' => $record->work_start_time
            ]);

            return back()->with('status', '勤務を開始しました。');
        } catch (\Exception $e) {
            Log::error('勤務開始処理でエラー発生', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', '勤務開始処理でエラーが発生しました。');
        }
    }

    public function endWork()
    {
        $user = Auth::user();
        $today = Carbon::now()->toDateString();
        $attendance = $this->getCurrentAttendance($user->id, $today);

        if (!$attendance || $attendance->work_end_time) {
            return back()->with('error', '勤務が開始されていないか、既に終了しています。');
        }

        $attendance->work_end_time = Carbon::now();
        $attendance->save();

        return back()->with('status', '勤務を終了しました。');
    }

    public function startBreak()
    {
        $user = Auth::user();
        $today = Carbon::now()->toDateString();
        $attendance = $this->getCurrentAttendance($user->id, $today);

        if (!$attendance || $attendance->work_end_time) {
            return back()->with('error', '勤務が開始されていないか、既に終了しています。');
        }

        if ($attendance->breakTimes()->whereNull('break_end_time')->exists()) {
            return back()->with('error', '既に休憩中です。');
        }

        BreakTime::create([
            'attendance_record_id' => $attendance->id,
            'break_start_time' => Carbon::now()
        ]);

        return back()->with('status', '休憩を開始しました。');
    }

    public function endBreak()
    {
        $user = Auth::user();
        $today = Carbon::now()->toDateString();
        $attendance = $this->getCurrentAttendance($user->id, $today);

        if (!$attendance || $attendance->work_end_time) {
            return back()->with('error', '勤務が開始されていないか、既に終了しています。');
        }

        $break = $attendance->breakTimes()
            ->whereNull('break_end_time')
            ->latest()
            ->first();

        if (!$break) {
            return back()->with('error', '開始されている休憩がありません。');
        }

        $break->break_end_time = Carbon::now();
        $break->save();

        return back()->with('status', '休憩を終了しました。');
    }

    private function getCurrentAttendance($userId, $date)
    {
        Log::info('getCurrentAttendance呼び出し', [
            'user_id' => $userId,
            'date' => $date
        ]);

        $record = AttendanceRecord::where('user_id', $userId)
            ->whereDate('date', $date)
            ->latest()
            ->first();

        Log::info('getCurrentAttendance結果', [
            'found' => $record ? 'yes' : 'no',
            'record' => $record
        ]);

        return $record;
    }

    public function show($date)
    {
        Log::info('show method called with date:', ['date' => $date]);

        // AttendanceRecordから直接データを取得
        $attendances = AttendanceRecord::whereDate('date', $date)
            ->with(['user', 'breakTimes'])
            ->orderBy('work_start_time', 'desc')
            ->paginate(5);

        Log::info('Retrieved attendance records:', [
            'count' => $attendances->count(),
            'total' => $attendances->total(),
            'items' => $attendances->items()
        ]);

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

            Log::info('Processed attendance record:', [
                'id' => $attendance->id,
                'user' => $attendance->user->name,
                'work_start' => $attendance->work_start_time,
                'work_end' => $attendance->work_end_time,
                'break_duration' => $attendance->break_duration,
                'work_duration' => $attendance->work_duration,
                'is_breaking' => $attendance->is_breaking
            ]);
        }

        return view('attendance', compact('attendances', 'date'));
    }
}