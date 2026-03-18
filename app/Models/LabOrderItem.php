<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'lab_order_id', 'lab_test_id', 'result_value', 'result_status', 'remarks'
    ];

    public function labOrder()
    {
        return $this->belongsTo(LabOrder::class);
    }

    public function labTest()
    {
        return $this->belongsTo(LabTest::class);
    }
}
