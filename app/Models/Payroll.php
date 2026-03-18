<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    protected $fillable = [
        'staff_id', 'month_year', 'basic_salary', 
        'allowances', 'deductions', 'net_salary', 
        'payment_status', 'paid_at'
    ];

    protected $casts = [
        'paid_at' => 'date',
    ];

    public function staff() { return $this->belongsTo(Staff::class); }
}
