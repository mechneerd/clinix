<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabNormalRange extends Model
{
    protected $fillable = [
        'lab_test_id', 'gender', 'min_age_days', 
        'max_age_days', 'min_value', 'max_value', 
        'unit', 'interpretation_notes'
    ];

    public function labTest() { return $this->belongsTo(LabTest::class); }
}
