<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AttendanceRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'work_start_time',
        'work_end_time',
    ];

    protected $dates = [
        'work_start_time',
        'work_end_time'
    ];

    protected $casts = [
        'work_start_time' => 'datetime',
        'work_end_time' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function breakTimes()
    {
        return $this->hasMany(BreakTime::class, 'attendance_record_id');
    }

    public function getTotalBreakDuration()
    {
        return $this->breakTimes()->get()->reduce(function ($carry, $break) {
            $start = Carbon::parse($break->break_start_time);
            $end = $break->break_end_time ? Carbon::parse($break->break_end_time) : Carbon::now();
            return $carry + $end->diffInSeconds($start);
        }, 0);
    }
}