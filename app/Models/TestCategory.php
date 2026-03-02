<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class TestCategory extends Model
{
    protected $fillable = ['lab_id','name','description','sort_order','is_active'];
    protected $casts = ['is_active'=>'boolean'];
    public function lab()   { return $this->belongsTo(Lab::class); }
    public function tests() { return $this->hasMany(LabTest::class,'category_id'); }
}
