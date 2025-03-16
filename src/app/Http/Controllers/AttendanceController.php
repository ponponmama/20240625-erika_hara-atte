<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AttendanceRecord;
use App\Models\AttendanceSession;
use App\Models\BreakTime;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AttendanceController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $now = Carbon::now();
        $today = $now->format('Y-m-d');

        $currentRecord = $this->getCurrentAttendanceRecord($user, $today);
        $status = 0; // デフォルトは「勤務未開始」

        if ($currentRecord) {
        if ($currentRecord->date != $today) {
                $this->handleCrossDay($currentRecord, $now, $currentRecord->work_end_time ? 'work' : 'break');
            } else {
                // 休憩中かどうかを確認
                $currentBreak = $currentRecord->breakTimes()->whereNull('break_end_time')->first();
                if ($currentRecord->work_start_time && !$currentRecord->work_end_time) {
                    if ($currentBreak) {
                        $status = 2; // 休憩中
                        session()->put('action', '休憩中'); // セッションに「休憩中」を設定
                    } else {
                        $status = 1; // 勤務中
                        session()->put('action', '勤務中'); // セッションに「勤務中」を設定
                    }
                } elseif ($currentRecord->work_end_time) {
                    $status = 3; // 勤務終了
                    session()->put('action', '勤務終了'); // セッションに「勤務終了」を設定
                }
            }
        } else {
            session()->put('action', '勤務未開始'); // セッションに「勤務未開始」を設定
        }

        return view('index', compact('status'));
    }

    public function startWork(Request $request)
    {
        $user = Auth::user();
        $now = Carbon::now();
        $today = $now->toDateString();

        $record = $this->getCurrentAttendanceRecord($user, $today);
        if (!$record) {
            $record = new AttendanceRecord([
                'user_id' => $user->id,
                'date' => $today,
                'work_start_time' => $now,
            ]);
            $record->save();
            $this->updateAttendanceSession($record);
            session()->forget('action');
            session()->put('action', '勤務中');

            return back()->with('status', '勤務を開始しました。');
        } else {
            return back()->with('error', '既に勤務が開始されています。');
        }
    }

    public function endWork(Request $request)
    {
        $user = Auth::user();
        $now = Carbon::now();
        $today = $now->toDateString();

        $record = $this->getCurrentAttendanceRecord($user, $today);
        if ($record) {
            if (!$record->work_end_time) {
                if ($now->toDateString() > $record->work_start_time->toDateString()) {
                    $this->handleCrossDay($record, $now, 'work');
                    $record->refresh();
                } else {
                    $record->work_end_time = $now;
                    $record->save();
                    $this->updateAttendanceSession($record);
                }
                session()->forget('action');
                session()->put('action', '勤務終了');
                return back()->with('status', '勤務を終了しました。');
            } else {
                return back()->with('error', '勤務終了時間は既に設定されています。');
            }
        } else {
            return back()->with('error', '勤務が開始されていないか、既に終了しています。');
        }
    }

    public function startBreak(Request $request)
    {
        $user = Auth::user();
        $now = Carbon::now();
        $today = $now->toDateString();

        $record = $this->getCurrentAttendanceRecord($user, $today);
        if ($record && !$record->work_end_time) {
            $break = new BreakTime([
                'attendance_record_id' => $record->id,
                'break_start_time' => $now,
            ]);
            $break->save();
            session()->forget('action');
            session()->put('action', '休憩中');

            return back()->with('status', '休憩を開始しました。');
        } else {
            return back()->with('error', '勤務が開始されていないか、既に終了しています。');
        }
    }

    public function endBreak(Request $request)
    {
        $user = Auth::user();
        $now = Carbon::now();
        $today = $now->toDateString();

        $record = $this->getCurrentAttendanceRecord($user, $today);
        if ($record && !$record->work_end_time) {
            $break = BreakTime::where('attendance_record_id', $record->id)
                            ->whereNull('break_end_time')
                            ->latest()
                            ->first();
            if ($break) {
                if ($now->toDateString() > $break->break_start_time->toDateString()) {
                    $this->handleCrossDay($record, $now, 'break');
                    $break->refresh();
                } else {
                    $break->break_end_time = $now;
                    $break->save();
                }
                session()->forget('action');
                session()->put('action', '勤務中');

                return back()->with('status', '休憩を終了しました。');
            } else {
                return back()->with('error', '開始されている休憩がありません。');
            }
        } else {
            return back()->with('error', '勤務が開始されていないか、既に終了しています。');
        }
    }


    private function getCurrentAttendanceRecord($user, $date) {
        return AttendanceRecord::where('user_id', $user->id)
                            ->whereDate('date', $date)
                            ->latest()
                            ->first();
    }


    public function handleCrossDay($record, $endTime, $type)
    {
        // 勤務時間が日を跨いでいるか確認
        if ($endTime->isNextDay($record->work_start_time)) {
            // 前日の勤務終了時間を23:59:59に設定
            $record->work_end_time = $record->work_start_time->copy()->endOfDay();
            $record->save();

            // 新しい日の勤務記録を作成
            $newRecord = new AttendanceRecord([
                'user_id' => $record->user_id,
                'date' => $endTime->toDateString(),
                'work_start_time' => $endTime->copy()->startOfDay(),
                $type === 'work' ? 'work_end_time' : 'work_start_time' => $endTime
            ]);
            $newRecord->save();
        }

        // 休憩時間が日を跨いでいるか確認
        foreach ($record->breakTimes as $breakTime) {
            if ($breakTime->break_end_time && $breakTime->break_end_time->isNextDay($breakTime->break_start_time)) {
                $breakTime->break_end_time = $breakTime->break_start_time->copy()->endOfDay();
                $breakTime->save();

                $newBreakTime = new BreakTime([
                    'attendance_record_id' => $record->id,
                    'break_start_time' => $endTime->copy()->startOfDay(),
                    'break_end_time' => $breakTime->break_end_time
                ]);
                $newBreakTime->save();
            }
        }

        return response()->json(['message' => 'Handled cross day successfully']);
    }

    public function updateAttendanceSession($record)
    {
        Log::info("updateAttendanceSession called with record: {$record->id}");

        if (!$record) {
            Log::info("No record found for updating session.");
            return;
        }

        $session = AttendanceSession::firstOrNew([
            'user_id' => $record->user_id,
            'date' => $record->date,
        ]);

        $session->work_start_time = $record->work_start_time;
        $session->work_end_time = $record->work_end_time;

        $totalBreakDuration = $record->breakTimes()->get()->reduce(function ($carry, $breakTime) {
            $start = Carbon::parse($breakTime->break_start_time);
            $end = $breakTime->break_end_time ? Carbon::parse($breakTime->break_end_time) : Carbon::now();
            return $carry + $end->diffInSeconds($start);
        }, 0);

        $session->break_duration = $totalBreakDuration;

        if ($session->work_start_time && $session->work_end_time) {
            $workStart = Carbon::parse($session->work_start_time);
            $workEnd = Carbon::parse($session->work_end_time);

            if ($workEnd->lessThan($workStart)) {
                $workEnd->addDay();  // 翌日の同時刻として扱う
            }

            $totalWorkDuration = $workEnd->diffInSeconds($workStart);
            $session->work_duration = $totalWorkDuration - $totalBreakDuration;
        } else {
            $session->work_duration = 0;
        }

        $session->save();  // セッションを保存
        Log::info("Session updated: {$session->id}, Work Duration: {$session->work_duration}, Break Duration: {$session->break_duration}");
    }

    public function showAttendanceIndex()
    {
        $date = Carbon::today()->format('Y-m-d');
        return redirect()->route('attendance.show', ['date' => $date]);
    }

    public function show($date)
    {
        $dateBasedSessions = AttendanceSession::where(function ($query) use ($date) {
            $query->whereDate('work_start_time', '<=', $date)
                ->whereDate('work_end_time', '>=', $date);
        })->paginate(5);

        return view('attendance', compact('dateBasedSessions', 'date'));
    }
}