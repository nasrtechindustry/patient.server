<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'appointment_type_id',
        'date',
        'start_time',
        'end_time',
        'status',
        'reschedule_count',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function appointmentType()
    {
        return $this->belongsTo(AppointmentType::class);
    }

    public function doctor() {
        return $this->belongsTo(Doctor::class);
    }
}
