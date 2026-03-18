<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LedgerAccount extends Model
{
    protected $fillable = ['clinic_id', 'name', 'code', 'type', 'balance'];

    public function clinic() { return $this->belongsTo(Clinic::class); }
    public function transactions() { return $this->hasMany(FinancialTransaction::class); }
}
