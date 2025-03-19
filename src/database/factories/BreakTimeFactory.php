<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\AttendanceRecord;
use Carbon\Carbon;

class BreakTimeFactory extends Factory
{
    public function definition()
    {
        return [
            'attendance_record_id' => null,
            'break_start_time' => Carbon::now()->subHours(rand(1, 8)),
            'break_end_time' => Carbon::now()->subHours(rand(1, 7)),
        ];
    }
}