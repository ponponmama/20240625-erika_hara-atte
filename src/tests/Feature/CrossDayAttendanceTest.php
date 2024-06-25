<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\AttendanceRecord;
use Carbon\Carbon;

class CrossDayAttendanceTest extends TestCase
{
    use RefreshDatabase;

    public function testCrossDayWorkHandling()
    {
        // テスト用のユーザーを作成し、認証
        $user = User::factory()->create();
        $this->actingAs($user);

        // 前日の日付で勤務開始の記録を作成
        $yesterday = Carbon::yesterday()->format('Y-m-d');
        AttendanceRecord::create([
            'user_id' => $user->id,
            'date' => $yesterday,
            'work_start_time' => Carbon::yesterday()->setHour(20),
        ]);

        // 日付を跨いで勤務終了の操作を行う
        Carbon::setTestNow(Carbon::today()->setHour(1));  // 現在時刻をテスト日の1時に設定

        $response = $this->post('/work/action', ['end_work' => '1']);

        // 新しい記録が作成されていることを確認
        $today = Carbon::today()->format('Y-m-d');
        $expectedEndTime = Carbon::now()->format('Y-m-d H:i:00');  // 秒を00に設定して比較
        $this->assertDatabaseHas('attendance_records', [
            'user_id' => $user->id,
            'date' =>  $today,  // 注意: 日を跨いだ場合の日付の扱いを確認
            'work_end_time' => $expectedEndTime,
        ]);

        $response->assertRedirect('/');  // リダイレクトの確認
    }
}