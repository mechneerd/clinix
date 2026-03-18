<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerformanceReview extends Model
{
    protected $fillable = ['staff_id', 'reviewer_id', 'review_date', 'rating', 'comments'];

    protected $casts = [
        'review_date' => 'date',
    ];

    public function staff() { return $this->belongsTo(Staff::class); }
    public function reviewer() { return $this->belongsTo(User::class, 'reviewer_id'); }
}
