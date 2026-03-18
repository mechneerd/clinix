<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'icon', 'is_active', 'is_core'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_core' => 'boolean',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('is_active')->withTimestamps();
    }
}
