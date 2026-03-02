<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class PharmacySaleItem extends Model
{
    protected $fillable = ['sale_id','medicine_id','medicine_name','quantity','unit_price','discount','total_price','batch_number','expiry_date'];
    protected $casts = ['unit_price'=>'decimal:2','total_price'=>'decimal:2','expiry_date'=>'date'];
    public function sale()     { return $this->belongsTo(PharmacySale::class,'sale_id'); }
    public function medicine() { return $this->belongsTo(Medicine::class); }
}
