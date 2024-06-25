<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\AttendanceRecord;
use Carbon\Carbon;
use App\Http\Controllers\AttendanceController;

class BreakEndButtonTest extends TestCase
{
    use RefreshDatabase;

    public function testBreakEndButtonIsClickableAfterCrossDay()
    {
        // 日を跨いだ後の状態をシミュレートするためのダミーデータを作成
        $user = User::factory()->create();
        $record = AttendanceRecord::factory()->create([
            'user_id' => $user->id,
            'date' => Carbon::yesterday()->format('Y-m-d'),
            'work_start_time' => Carbon::yesterday()->setHour(10)->setMinute(0)->setSecond(0),
        ]);
        $endTime = Carbon::now()->setHour(3)->setMinute(0)->setSecond(0);

        // 日を跨いだ後の状態に切り替える
        $controller = new AttendanceController();
        $controller->handleCrossDay($record, $endTime, 'work');

        // 休憩終了ボタンが押せるかどうかをテスト
        $response = $this->followingRedirects()->post('/end-break', [
            // ここに適切なリクエストデータを追加
        ]);

        $response->assertStatus(200); // リダイレクトをフォローしてステータスコードを確認
    }
}