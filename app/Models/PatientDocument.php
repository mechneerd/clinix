<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientDocument extends Model
{
    protected $fillable = [
        'patient_id', 'document_type', 'name', 'mime_type', 
        'file_path', 'file_size', 'uploaded_by'
    ];

    public function patient() { return $this->belongsTo(Patient::class); }
    public function uploader() { return $this->belongsTo(User::class, 'uploaded_by'); }
}
