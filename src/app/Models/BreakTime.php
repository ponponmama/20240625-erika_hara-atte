<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BreakTime extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendance_record_id',
        'break_start_time',
        'break_end_time'
    ];

    public function attendanceRecord()
    {
        return $this->belongsTo(AttendanceRecord::class);
    }

    protected $dates = ['break_start_time', 'break_end_time'];
}