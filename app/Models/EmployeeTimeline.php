<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeTimeline extends Model
{
    protected $fillable = [
        'employee_id',
        'event_type',
        'title',
        'description',
        'event_date'
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
        ];
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
