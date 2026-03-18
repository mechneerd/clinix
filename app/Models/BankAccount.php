<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    protected $fillable = [
        'clinic_id', 'bank_name', 'account_number', 
        'account_name', 'branch', 'swift_code', 'current_balance'
    ];

    public function clinic() { return $this->belongsTo(Clinic::class); }
}
