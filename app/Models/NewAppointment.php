<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class NewAppointment extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($appointment) {
            $appointment->full_name = Auth::user()->name . ' ' . Auth::user()->last_name;
        });
        
    }

    protected $table = 'new_appointments'; 

    protected $fillable = [
        'created_at',   
        'updated_at',
        'type_of_appointments',
        'position',
        'appointment',
        'appointment_effectivity_date',
        'full_name',
        'photo_url', //This is not used 
        'time_stamp',
        'created_at',
        'updated_at',
        'new_appointment_file_path',
    ];
}
