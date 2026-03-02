<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class PharmacySale extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'invoice_number','pharmacy_id','clinic_id','patient_id','sale_type',
        'prescription_id','subtotal','discount','tax_amount','total_amount',
        'amount_paid','amount_due','payment_method','payment_status','sold_by','notes',
    ];
    protected $casts = ['total_amount'=>'decimal:2','amount_paid'=>'decimal:2'];
    public function pharmacy()    { return $this->belongsTo(Pharmacy::class); }
    public function patient()     { return $this->belongsTo(User::class,'patient_id'); }
    public function items()       { return $this->hasMany(PharmacySaleItem::class,'sale_id'); }
    public function soldBy()      { return $this->belongsTo(User::class,'sold_by'); }
    public function prescription(){ return $this->belongsTo(Prescription::class); }
}
