<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Employee extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'emp_id',
        'emp_name',
        'email',
        'phone_number',
        'department_id',
        'designation_id',
        'shift_id',
        'joining_date',
        'employment_type',
        'salary_ctc',
        'skills',
        'previous_experience',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relation',
        'is_active',
        'exit_date',
        'profile_photo',
        'password',
        'pan_number',
        'aadhaar_number',
        'pf_uan',
        'bank_name',
        'bank_account_no',
        'bank_ifsc',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'joining_date' => 'date',
            'exit_date' => 'date',
            'is_active' => 'boolean',
            'salary_ctc' => 'decimal:2',
            'password' => 'hashed',
        ];
    }

    // Master Relationships
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }
    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    // 360° CRM Relationships
    public function assets()
    {
        return $this->hasMany(Asset::class);
    }
    public function documents()
    {
        return $this->hasMany(EmployeeDocument::class)->latest();
    }
    public function timelines()
    {
        return $this->hasMany(EmployeeTimeline::class)->orderBy('event_date', 'desc');
    }
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }
    public function leaveBalances()
    {
        return $this->hasMany(LeaveBalance::class);
    }
    public function educations()
    {
        return $this->hasMany(EmployeeEducation::class)->orderBy('end_year', 'desc');
    }
    public function experiences()
    {
        return $this->hasMany(EmployeeExperience::class)->orderBy('from_date', 'desc');
    }
}
