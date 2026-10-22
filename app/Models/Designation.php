<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    protected $table = 'designations';

    protected $fillable = [
        'designation_id',
        'designation_name',
        'is_active'
    ];
}
