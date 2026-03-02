<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class StockMovement extends Model
{
    protected $fillable = [
        'medicine_id','pharmacy_id','type','quantity',
        'stock_before','stock_after','reference_type','reference_id','reason','created_by',
    ];
    public function medicine()   { return $this->belongsTo(Medicine::class); }
    public function createdBy()  { return $this->belongsTo(User::class,'created_by'); }
}
