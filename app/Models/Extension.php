<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Extension extends Model
{
    use HasFactory;

    protected $table = 'extensionnew'; // Ensures it uses the correct table name
  
    protected $fillable = [
        'activity_date',
        'created_at',
        'date_end',
        'event_title',
        'extension_involvement',
        'extensiontype',
        'full_name',
        'id',
        'location',
        'name',
        'updated_at',
        'user_id',
        'venue',
    ];

    protected static function boot()
{
    parent::boot();

    static::creating(function ($model) {
        if (auth()->check()) {
            $model->id_no = auth()->id();
           
        }
    });
}


}


