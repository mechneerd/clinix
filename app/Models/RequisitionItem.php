<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequisitionItem extends Model
{
    protected $fillable = ['requisition_id', 'item_type', 'item_id', 'quantity_requested', 'quantity_fulfilled'];

    public function requisition() { return $this->belongsTo(Requisition::class); }
    public function item() { return $this->morphTo(); }
}
