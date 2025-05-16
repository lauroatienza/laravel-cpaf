<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Extension extends Model
{
    use HasFactory;
    

    protected $table = 'extensionnew'; 

    protected $fillable = [
        'start_date',
        'created_at',
        'event_title',
        'extension_involvement',
        'extensiontype',
        'full_name',
        'end_date',
        'id',
        'location',
        'name',
        'updated_at',
        'user_id',
        'venue',
        'activity_date',
    ];

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
    {
        static::creating(function ($model) {
            if (Auth::check()) {
                $model->user_id = Auth::id(); 
            }
        });
    }
}

}
