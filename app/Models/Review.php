<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'clinic_id','doctor_id','patient_id','appointment_id',
        'rating','review','categories','is_verified','is_visible',
        'reply','replied_at','replied_by',
    ];
    protected $casts = ['categories'=>'array','is_verified'=>'boolean','is_visible'=>'boolean','replied_at'=>'datetime'];
    public function patient() { return $this->belongsTo(User::class,'patient_id'); }
    public function doctor()  { return $this->belongsTo(User::class,'doctor_id'); }
    public function clinic()  { return $this->belongsTo(Clinic::class); }
}
