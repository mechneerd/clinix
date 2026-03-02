<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ClinicRole extends Model
{
    protected $fillable = ['clinic_id','name','guard_name','permissions','description','is_system_role'];
    protected $casts = ['permissions'=>'array','is_system_role'=>'boolean'];
    public function clinic()    { return $this->belongsTo(Clinic::class); }
    public function userRoles() { return $this->hasMany(ClinicUserRole::class); }
}
