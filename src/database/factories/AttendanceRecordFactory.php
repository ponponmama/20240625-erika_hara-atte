<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\AttendanceRecord;
use App\Models\User;
use Illuminate\Support\Carbon;

class AttendanceRecordFactory extends Factory
{
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'date' => $this->faker->date(),
            'work_start_time' => $this->faker->time(),
            'work_end_time' => $this->faker->time(),
        ];
    }
}