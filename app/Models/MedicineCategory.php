<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class MedicineCategory extends Model
{
    protected $fillable = ['pharmacy_id','name','description','sort_order','is_active'];
    protected $casts = ['is_active'=>'boolean'];
    public function pharmacy()  { return $this->belongsTo(Pharmacy::class); }
    public function medicines() { return $this->hasMany(Medicine::class,'category_id'); }
}
