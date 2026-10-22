<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected $fillable = [
        'asset_code',
        'asset_name',
        'category',
        'serial_number',
        'employee_id',
        'issue_date',
        'return_date',
        'condition',
        'status',
        'notes'
    ];

    protected function casts(): array
    {
        return [
            'issue_date' => 'date',
            'return_date' => 'date',
        ];
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}