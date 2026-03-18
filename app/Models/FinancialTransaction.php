<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinancialTransaction extends Model
{
    protected $fillable = [
        'clinic_id', 'ledger_account_id', 'debit', 
        'credit', 'description', 'reference_type', 
        'reference_id', 'transaction_date', 'recorded_by'
    ];

    protected $casts = [
        'transaction_date' => 'datetime',
    ];

    public function clinic() { return $this->belongsTo(Clinic::class); }
    public function account() { return $this->belongsTo(LedgerAccount::class, 'ledger_account_id'); }
    public function recorder() { return $this->belongsTo(User::class, 'recorded_by'); }
}
