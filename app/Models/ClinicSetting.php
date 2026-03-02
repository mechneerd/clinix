<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ClinicSetting extends Model
{
    protected $fillable = ['clinic_id','group','key','value','type'];
    public function clinic() { return $this->belongsTo(Clinic::class); }
    public static function get(int $clinicId, string $key, $default = null)
    {
        $setting = static::where('clinic_id',$clinicId)->where('key',$key)->first();
        return $setting ? $setting->value : $default;
    }
    public static function set(int $clinicId, string $key, $value, string $group='general'): void
    {
        static::updateOrCreate(
            ['clinic_id'=>$clinicId,'key'=>$key],
            ['value'=>$value,'group'=>$group]
        );
    }
}
