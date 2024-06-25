<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    // 例：勤務開始のテスト
    public function testStartWork()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // リクエスト前に時間範囲の開始を設定
        $startTime = now();

        $response = $this->withSession([])->post('/work/action', [
            'start_work' => '1',
            '_token' => csrf_token(),
        ]);

        $response->assertRedirect('/');

        // リクエスト後に時間範囲の終了を設定
        $endTime = now()->addSeconds(2);

        $exists = \DB::table('attendance_records')
            ->where('user_id', $user->id)
            ->whereBetween('work_start_time', [$startTime, $endTime])
            ->exists();

        $this->assertTrue($exists);
    }
}
