<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorSchedule extends Model
{
    /**
     * Summary of doctor
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Doctor, DoctorSchedule>
     */
    public function doctor(){
        return $this->belongsTo(Doctor::class);
    }
}
