<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\AttendanceRecord;
use App\Models\User;

class AttendanceRecordFactoryTest extends TestCase
{
    use RefreshDatabase;

    public function testAttendanceRecordCreation()
    {
        // ユーザーを生成
        $user = User::factory()->create();

        // 勤務記録を生成
        $attendanceRecord = AttendanceRecord::factory()->create([
            'user_id' => $user->id
        ]);

        // データベースに記録が存在することを確認
        $this->assertDatabaseHas('attendance_records', [
            'user_id' => $user->id,
            'date' => $attendanceRecord->date,
            'work_start_time' => $attendanceRecord->work_start_time,
            'work_end_time' => $attendanceRecord->work_end_time
        ]);
    }

    public function testSpansTwoDays()
    {
        // ユーザーを生成
        $user = User::factory()->create();

        // 日を跨ぐ勤務記録を生成
        $attendanceRecord = AttendanceRecord::factory()->spansTwoDays()->create([
            'user_id' => $user->id
        ]);

        // 勤務終了時間が日を跨いでいるか確認
        $this->assertNotEquals(
            $attendanceRecord->work_start_time->toDateString(),
            $attendanceRecord->work_end_time->toDateString()
        );
    }
}