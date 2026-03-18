<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SafetyReport extends Model
{
    protected $fillable = ['clinic_id', 'report_title', 'summary', 'recommendations', 'report_date', 'prepared_by'];

    protected $casts = [
        'report_date' => 'date',
    ];

    public function clinic() { return $this->belongsTo(Clinic::class); }
    public function preparer() { return $this->belongsTo(User::class, 'prepared_by'); }
}
