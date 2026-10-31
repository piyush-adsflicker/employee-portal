<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganizationFile extends Model
{
    protected $fillable = [
        'title', 'description', 'file_path', 'category', 'visibility_type', 'is_active',
    ];

    // Get the departments associated with this file
    public function departments()
    {
        return $this->belongsToMany(Department::class, 'organization_file_departments', 'organization_file_id', 'department_id');
    }

    // Get the designations associated with this file
    public function designations()
    {
        return $this->belongsToMany(Designation::class, 'organization_file_designations', 'organization_file_id', 'designation_id');
    }
}