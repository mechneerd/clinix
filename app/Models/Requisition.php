<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Requisition extends Model
{
    protected $fillable = ['clinic_id', 'requesting_department_id', 'requisition_number', 'status', 'requested_by'];

    public function clinic() { return $this->belongsTo(Clinic::class); }
    public function department() { return $this->belongsTo(Department::class, 'requesting_department_id'); }
    public function requester() { return $this->belongsTo(User::class, 'requested_by'); }
    public function items() { return $this->hasMany(RequisitionItem::class); }
}
