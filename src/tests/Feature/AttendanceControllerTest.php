<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Http\Controllers\AttendanceController;
use App\Models\User;
use App\Models\AttendanceRecord;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class AttendanceControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testHandleCrossDay()
    {
        $controller = new AttendanceController();
        $user = User::factory()->create(); // factory 関数を User モデルの factory メソッドに変更
        $record = AttendanceRecord::factory()->create([ // factory 関数を AttendanceRecord モデルの factory メソッドに変更
            'user_id' => $user->id,
            'date' => Carbon::yesterday()->format('Y-m-d'),
            'work_start_time' => Carbon::yesterday()->setHour(10)->setMinute(0)->setSecond(0),
        ]);
        $endTime = Carbon::now()->setHour(3)->setMinute(0)->setSecond(0);

        $controller->handleCrossDay($record, $endTime, 'work');

        $this->assertDatabaseHas('attendance_records', [
            'user_id' => $user->id,
            'date' => $endTime->toDateString(),
            'work_start_time' => $endTime->copy()->startOfDay(),
            'work_end_time' => $endTime,
        ]);
    }
}