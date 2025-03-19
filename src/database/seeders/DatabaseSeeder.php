<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\AttendanceRecord;
use App\Models\BreakTime;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // 固定の日付（3/19）を設定（2025年に変更）
        $baseDate = Carbon::create(2025, 3, 19, 0, 0, 0, 'Asia/Tokyo');

        // ユーザーを作成
        User::factory(10)->create()->each(function ($user) use ($baseDate) {
            // 各ユーザーに対して過去3日分の勤怠記録を作成
            for ($i = 0; $i < 3; $i++) {
                $date = $baseDate->copy()->subDays($i);

                // 10%の確率で日を跨ぐ勤務時間を設定
                if (rand(1, 100) <= 10) {
                    // 日を跨ぐ勤務の場合
                    $startHour = rand(22, 23); // 夜遅くに設定
                    $workDurationHours = rand(6, 8); // 6-8時間の勤務

                    $workStartTime = Carbon::createFromTime($startHour, 0, 0, 'Asia/Tokyo')
                        ->setDate($date->year, $date->month, $date->day);
                    $workEndTime = (clone $workStartTime)->addHours($workDurationHours);

                    if ($workEndTime->day != $workStartTime->day) {
                        // 当日の勤務を23:59:59で終了
                        $currentDayWorkEndTime = Carbon::createFromTime(0, 0, 0, 'Asia/Tokyo')
                            ->setDate($workStartTime->year, $workStartTime->month, $workStartTime->day)
                            ->addDay()
                            ->subSecond(1);

                        // 当日の勤務記録を作成
                        $this->createAttendanceRecord($user, $date, $workStartTime, $currentDayWorkEndTime);

                        // 翌日の勤務記録を作成（00:00:00から開始）
                        $nextDayDate = $workEndTime->toDateString();
                        $nextDayWorkStartTime = Carbon::createFromTime(0, 0, 0, 'Asia/Tokyo')
                            ->setDate($nextDayDate, $workEndTime->month, $workEndTime->day);
                        $nextDayWorkEndTime = clone $workEndTime;

                        // 翌日の勤務記録を作成（日付は翌日）
                        $this->createAttendanceRecord($user, $nextDayDate, $nextDayWorkStartTime, $nextDayWorkEndTime);
                    } else {
                        $this->createAttendanceRecord($user, $date, $workStartTime, $workEndTime);
                    }
                } else {
                    // 通常の勤務時間（6-8時間）
                    $workDurationHours = rand(6, 8);
                    $startHour = rand(9, 17); // 開始時間を9-17時に制限

                    $workStartTime = Carbon::createFromTime($startHour, 0, 0, 'Asia/Tokyo')
                        ->setDate($date->year, $date->month, $date->day);
                    $workEndTime = (clone $workStartTime)->addHours($workDurationHours);

                    // 終了時間が午前0時以降の場合、日付を翌日に設定
                    if ($workEndTime->hour < 6) { // 午前6時までは前日の勤務として扱う
                        $displayDate = $workEndTime->copy()->subDay()->toDateString();
                    } else {
                        $displayDate = $date;
                    }

                    $this->createAttendanceRecord($user, $displayDate, $workStartTime, $workEndTime);
                }
            }
        });
    }

    private function createAttendanceRecord($user, $date, $startTime, $endTime)
    {
        $attendanceRecord = AttendanceRecord::create([
            'user_id' => $user->id,
            'date' => $date,
            'work_start_time' => $startTime,
            'work_end_time' => $endTime,
        ]);

        // 休憩時間を作成（1-3回）
        $breakCount = rand(1, 3);
        for ($j = 0; $j < $breakCount; $j++) {
            $breakStart = Carbon::parse($attendanceRecord->work_start_time)
                ->addMinutes(rand(30, 240));
            $breakEnd = (clone $breakStart)->addMinutes(10);

            BreakTime::create([
                'attendance_record_id' => $attendanceRecord->id,
                'break_start_time' => $breakStart,
                'break_end_time' => $breakEnd,
            ]);
        }
    }
}