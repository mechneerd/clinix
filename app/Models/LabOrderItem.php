<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabOrderItem extends Model
{
    protected $fillable = [
        'lab_order_id','lab_test_id','price','status',
        'result_value','result_unit','result_status','remarks',
        'notes_for_patient','conducted_by','conducted_at','verified_by','verified_at',
    ];

    protected $casts = ['conducted_at' => 'datetime', 'verified_at' => 'datetime', 'price' => 'decimal:2'];

    public function labOrder() { return $this->belongsTo(LabOrder::class); }
    public function labTest()  { return $this->belongsTo(LabTest::class); }
}
