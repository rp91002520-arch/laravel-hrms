<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [

        'employee_code',
        'name',
        'email',
        'phone',
        'department',
        'joining_date',
        'paid_leave_balance',
        'sick_leave_balance',
        'casual_leave_balance'
    ];

    public function leaves()
    {
        return $this->hasMany(LeaveApplication::class);
    }
}
