<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeLeave extends Model
{
    protected $fillable = ['staff_id', 'leave_type', 'start_date', 'end_date', 'reason', 'status', 'approved_by'];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function staff() { return $this->belongsTo(Staff::class); }
    public function approver() { return $this->belongsTo(User::class, 'approved_by'); }
}
