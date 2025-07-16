<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Doctor extends Authenticatable
{
    use HasFactory;

    protected $guard = 'patient';
    protected $fillable = ['name', 'email', 'password'];
    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'working_hours' => 'array',
    ];


    public function departments()
    {
        return $this->belongsToMany(Department::class);
    }

    public function schedules()
    {
        return $this->hasMany(DoctorSchedule::class);
    }

    public function appointmentTypes()
    {
        return $this->belongsToMany(AppointmentType::class, 'doctor_appointment_types')
            ->withPivot('weekday');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
    public function exceptions()
{
    return $this->hasMany(DoctorAvailabilityException::class);
}
}
