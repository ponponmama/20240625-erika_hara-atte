<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\AttendanceSession;
use App\Models\AttendanceRecord;
use App\Models\BreakTime;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;


class DatabaseSeeder extends Seeder
{
    public function run()
    {
        User::factory(100)->create()->each(function ($user) {
            for ($i = 0; $i < 3; $i++) {
                $date = Carbon::today()->subDays($i);
                // 10%の確率で日を跨ぐ勤務時間を設定
                if (rand(1, 100) <= 10) {
                    $workStartHour = rand(22, 23); // 夜遅くに設定
                    $workDurationHours = rand(9, 12); // 長時間勤務
                } else {
                    $workStartHour = rand(9, 21);
                    $workDurationHours = rand(6, 8);
                }
                $workStartTime = Carbon::createFromTime($workStartHour, 0, 0, 'Asia/Tokyo')->subDays($i);
                $workEndTime = (clone $workStartTime)->addHours($workDurationHours);

                if ($workEndTime->day != $workStartTime->day) {
                    $currentDayWorkEndTime = Carbon::createFromTime(0, 0, 0, 'Asia/Tokyo')
                        ->setDate($workStartTime->year, $workStartTime->month, $workStartTime->day)
                        ->addDay()
                        ->subSecond(1);
                    $nextDayWorkStartTime = (clone $currentDayWorkEndTime)->addSecond();
                    $nextDayWorkEndTime = clone $workEndTime;

                    // 当日の勤務セッション
                    $this->createAttendanceSession($user, $date, $workStartTime, $currentDayWorkEndTime);

                    // 翌日の勤務セッション、日付を正しく設定
                    $nextDayDate = $nextDayWorkStartTime->toDateString();
                    $this->createAttendanceSession($user, $nextDayDate, $nextDayWorkStartTime, $nextDayWorkEndTime);
                } else {
                    $this->createAttendanceSession($user, $date, $workStartTime, $workEndTime);
                }
            }
        });
    }

    private function createAttendanceSession($user, $date, $startTime, $endTime)
    {
        $session = AttendanceSession::factory()->create([
            'user_id' => $user->id,
            'date' => $date,
            'work_start_time' => $startTime,
            'work_end_time' => $endTime,
        ]);

        $record = AttendanceRecord::factory()->create([
            'user_id' => $user->id,
            'date' => $date,
            'work_start_time' => $startTime,
            'work_end_time' => $endTime,
        ]);

        $totalBreakMinutes = 0;
        $breaksCount = intdiv($endTime->diffInMinutes($startTime), 60);
        for ($j = 0; $j < $breaksCount; $j++) {
            $breakStart = (clone $startTime)->addMinutes($j * 60 + 50);
            $breakEnd = (clone $breakStart)->addMinutes(10);
            $totalBreakMinutes += 10;

            BreakTime::factory()->create([
                'attendance_record_id' => $record->id,
                'break_start_time' => $breakStart,
                'break_end_time' => $breakEnd,
            ]);
        }

        // 日をまたぐ場合の勤務時間計算
        $workDurationMinutes = $endTime->diffInMinutes($startTime);
        if ($endTime->lessThan($startTime)) {
            Log::info("Work spans over midnight. Adjusting times.");
            $workDurationMinutes += 1440; // 24時間分の分を加算
        }
        // 休憩時間を勤務時間から差し引く
        $workDurationMinutes -= $totalBreakMinutes;

        $session->update([
            'break_duration' => $totalBreakMinutes,
            'work_duration' => $workDurationMinutes,
        ]);
    }
}