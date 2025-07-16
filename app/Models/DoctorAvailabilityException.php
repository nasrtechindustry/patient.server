<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DoctorAvailabilityException extends Model
{
    use HasFactory;

    protected $fillable = ['doctor_id', 'date', 'is_available', 'reason'];

    protected $casts = [
        'date' => 'date', 
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
