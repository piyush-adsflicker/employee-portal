<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'employee_id',
        'attendance_date',
        'check_in_time',
        'check_in_ip',
        'check_in_device',
        'check_in_location',
        'check_in_coordinates',
        'check_out_time',
        'check_out_ip',
        'check_out_device',
        'check_out_location',
        'check_out_coordinates',
        'shift_id',
        'working_hours',
        'late_minutes',
        'early_logout_minutes',
        'current_break_start',
        'total_break_minutes',
        'status'
    ];

    protected function casts(): array
    {
        return [
            'attendance_date' => 'date',
            'check_in_time' => 'datetime',
            'check_out_time' => 'datetime',
            'current_break_start' => 'datetime',
        ];
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }
}
