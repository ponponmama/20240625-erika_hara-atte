<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\AttendanceSession;
use App\Models\AttendanceRecord;
use App\Models\BreakTime;
use Illuminate\Support\Carbon;

class AttendanceSessionFactory extends Factory
{

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'date' => $this->faker->date(), // 'date' カラムを追加
            'work_start_time' => $this->faker->dateTimeThisMonth(), 
            'work_end_time' => $this->faker->dateTimeThisMonth(), 
            'break_duration' => $this->faker->numberBetween(0, 120), // 休憩時間の合計
            'work_duration' => $this->faker->numberBetween(0, 480), // 実働時間の合計
        ];
    }
}
