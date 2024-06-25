<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\AttendanceRecord;
use Carbon\Carbon;

class IndexMethodTest extends TestCase
{
    use RefreshDatabase;

    public function testIndexMethod()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // 勤務未開始の場合
        $response = $this->get(route('HOME'));
        $response->assertViewHas('status', 0);

        // 勤務中の場合
        AttendanceRecord::create([
            'user_id' => $user->id,
            'date' => Carbon::today()->toDateString(),
            'work_start_time' => Carbon::now()->subHour(1),
            'work_end_time' => null
        ]);
        $response = $this->get(route('HOME'));
        $response->assertViewHas('status', 1);

        // 休憩中の場合
        $record = AttendanceRecord::latest()->first();
        $record->break_start_time = Carbon::now()->subMinutes(30);
        $record->save();
        $response = $this->get(route('HOME'));
        $response->assertViewHas('status', 2);

        // 勤務終了の場合
        $record->work_end_time = Carbon::now();
        $record->save();
        $response = $this->get(route('HOME'));
        $response->assertViewHas('status', 3);
    }
}