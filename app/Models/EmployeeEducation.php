<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeEducation extends Model
{
    protected $table = 'employee_educations';
    
    protected $fillable = ['employee_id', 'level', 'institution', 'specialization', 'start_year', 'end_year'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
