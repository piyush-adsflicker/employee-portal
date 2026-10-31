<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeExperience extends Model
{
    protected $table = 'employee_experiences';

    protected $fillable = ['employee_id', 'company_name', 'job_role', 'from_date', 'to_date', 'description'];

    protected function casts(): array
    {
        return ['from_date' => 'date', 'to_date' => 'date'];
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
