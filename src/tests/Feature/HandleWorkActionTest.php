<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use App\Models\User;
use App\Models\AttendanceRecord;

class HandleWorkActionTest extends TestCase
{
    use RefreshDatabase,WithoutMiddleware; // ここに WithoutMiddleware を追加

    public function testHandleWorkActions()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // 勤務開始
        $response = $this->post(route('work.action'), ['start_work' => true]);
        $response->assertRedirect();
        $this->assertDatabaseHas('attendance_records', [
            'user_id' => $user->id,
            'work_start_time' => now()->toDateTimeString(),
        ]);

        // 勤務終了
        $response = $this->post(route('work.action'), ['end_work' => true]);
        $response->assertRedirect();
        $this->assertDatabaseHas('attendance_records', [
            'user_id' => $user->id,
            'work_end_time' => now()->toDateTimeString(),
        ]);

        // 休憩開始
        $response = $this->post(route('work.action'), ['start_break' => true]);
        $response->assertRedirect();
        $this->assertDatabaseHas('attendance_records', [
            'user_id' => $user->id,
            'break_start_time' => now()->toDateTimeString(),
        ]);

        // 休憩終了
        $response = $this->post(route('work.action'), ['end_break' => true]);
        $response->assertRedirect();
        $this->assertDatabaseHas('attendance_records', [
            'user_id' => $user->id,
            'break_end_time' => now()->toDateTimeString(),
        ]);
    }
}