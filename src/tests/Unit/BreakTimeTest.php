<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\BreakTime;
use App\Models\AttendanceRecord;
use App\Models\User;

class BreakTimeTest extends TestCase
{
    use RefreshDatabase;

    public function testBreakTimeBelongsToAttendanceRecord()
    {
        $user = User::factory()->create();
        $attendanceRecord = AttendanceRecord::factory()->create([
            'user_id' => $user->id
        ]);

        $breakTime = new BreakTime([
            'attendance_record_id' => $attendanceRecord->id,
            'break_start_time' => now(),
            'break_end_time' => now()->addHours(1)
        ]);
        $breakTime->save();

        $this->assertInstanceOf(AttendanceRecord::class, $breakTime->attendanceRecord);
        $this->assertEquals($attendanceRecord->id, $breakTime->attendanceRecord->id);
    }
}