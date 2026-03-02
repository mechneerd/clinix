<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationLog extends Model
{
    protected $fillable = [
        'user_id','notifiable_type','notifiable_id','channel',
        'template_used','content','recipient_contact','status',
        'error_message','sent_at','delivered_at','read_at',
    ];
    protected $casts = ['sent_at'=>'datetime','delivered_at'=>'datetime','read_at'=>'datetime'];
    public function user() { return $this->belongsTo(User::class); }
}
