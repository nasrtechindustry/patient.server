<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Patient extends Authenticatable
{
    use HasFactory;
    protected $guard = 'patient';
    protected $fillable = ['full_name', 'email', 'password' ,'phone'] ;
    protected $hidden = ['password', 'remember_token'];
}
