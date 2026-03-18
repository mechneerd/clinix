<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InsuranceProvider extends Model
{
    protected $fillable = ['name', 'code', 'email', 'phone', 'address', 'default_coverage_percent', 'status'];

    public function patientInsurances() { return $this->hasMany(PatientInsurance::class); }
}
