<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class NewAppointment extends Model
{
    use HasFactory;

    public static function booted()
{
    static::creating(function ($model) {
        $model->full_name = self::normalizeName($model->full_name);
    });

    static::updating(function ($model) {
        $model->full_name = self::normalizeName($model->full_name);
    });
}

protected static function normalizeName($name)
{
    $titles = ['Dr.', 'Prof.', 'Engr.', 'Sir', 'Ms.', 'Mr.', 'Mrs.'];

    // Remove titles and normalize spacing
    $name = str_ireplace($titles, '', $name);
    $name = preg_replace('/\s+/', ' ', trim($name));

    return $name;
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
