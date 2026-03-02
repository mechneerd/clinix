<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Clinic extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'owner_id','user_subscription_id','name','slug','description',
        'logo','banner_image','primary_color','secondary_color',
        'email','phone','alternate_phone','website',
        'address','city','state','country','postal_code',
        'latitude','longitude','working_hours','appointment_duration',
        'is_active','is_verified','is_featured','show_on_public_listing',
    ];

    protected $casts = [
        'working_hours'           => 'array',
        'is_active'               => 'boolean',
        'is_verified'             => 'boolean',
        'is_featured'             => 'boolean',
        'show_on_public_listing'  => 'boolean',
        'featured_until'          => 'datetime',
        'latitude'                => 'decimal:8',
        'longitude'               => 'decimal:8',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($clinic) {
            $clinic->slug = $clinic->slug ?? static::generateSlug($clinic->name);
        });
    }

    public static function generateSlug(string $name): string
    {
        $slug = Str::slug($name);
        $count = static::where('slug', 'like', "{$slug}%")->count();
        return $count ? "{$slug}-{$count}" : $slug;
    }

    public function owner()        { return $this->belongsTo(User::class, 'owner_id'); }
    public function subscription() { return $this->belongsTo(UserSubscription::class, 'user_subscription_id'); }
    public function departments()  { return $this->hasMany(Department::class); }
    public function staff()        { return $this->hasMany(StaffProfile::class); }
    public function labs()         { return $this->hasMany(Lab::class); }
    public function pharmacies()   { return $this->hasMany(Pharmacy::class); }
    public function appointments() { return $this->hasMany(Appointment::class); }
    public function settings()     { return $this->hasMany(ClinicSetting::class); }
    public function roles()        { return $this->hasMany(ClinicRole::class); }

    public function getLogoUrlAttribute(): ?string
    {
        return $this->logo ? asset('storage/' . $this->logo) : null;
    }

    public function doctors()
    {
        return User::whereHas('roles', fn($q) => $q->whereIn('name', ['doctor']))
            ->whereHas('staffProfile', fn($q) => $q->where('clinic_id', $this->id));
    }
}
