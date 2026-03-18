<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClinicExpense extends Model
{
    protected $fillable = [
        'clinic_id', 'category', 'amount', 'description', 
        'expense_date', 'recorded_by'
    ];

    protected $casts = ['expense_date' => 'date'];

    public function clinic() { return $this->belongsTo(Clinic::class); }
    public function recorder() { return $this->belongsTo(User::class, 'recorded_by'); }
}
