<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ClinicUserRole extends Model
{
    protected $fillable = ['user_id','clinic_id','clinic_role_id','assigned_by','expires_at','is_active'];
    protected $casts = ['is_active'=>'boolean','expires_at'=>'datetime'];
    public function user()       { return $this->belongsTo(User::class); }
    public function clinic()     { return $this->belongsTo(Clinic::class); }
    public function clinicRole() { return $this->belongsTo(ClinicRole::class); }
}
