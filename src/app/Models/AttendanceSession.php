<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'work_start_time',
        'work_end_time',
        'break_duration',
        'work_duration',
    ];

    protected $casts = [
        'work_start_time' => 'datetime',
        'work_end_time' => 'datetime',
        'break_duration' => 'integer',
        'work_duration' => 'integer',
    ];

        public function user()
    {
        return $this->belongsTo(User::class);
    }


    // フォーマットされた休憩時間を取得
    public function getFormattedBreakDurationAttribute()
    {
        return gmdate('H:i:s', $this->break_duration);
    }

    // フォーマットされた勤務時間を取得
    public function getFormattedWorkDurationAttribute()
    {
        return gmdate('H:i:s', $this->work_duration);
    }

}
