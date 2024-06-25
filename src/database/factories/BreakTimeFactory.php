<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\BreakTime;
use App\Models\AttendanceRecord;
use Illuminate\Support\Carbon;

class BreakTimeFactory extends Factory
{

    public function definition()
    {
        return [
            'attendance_record_id' => AttendanceRecord::factory(),
            'break_start_time' => $this->faker->dateTimeThisMonth(),
            'break_end_time' => $this->faker->dateTimeThisMonth(),
        ];
    }
}