<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use Carbon\Carbon;

class AttendanceRecordFactory extends Factory
{
    public function definition()
    {
        $date = $this->faker->date();
        $startHour = $this->faker->numberBetween(9, 21);
        $startTime = Carbon::parse($date)->setTime($startHour, 0, 0);
        $endTime = (clone $startTime)->addHours($this->faker->numberBetween(6, 8));

        return [
            'user_id' => User::factory(),
            'date' => $date,
            'work_start_time' => $startTime,
            'work_end_time' => $endTime,
        ];
    }
}