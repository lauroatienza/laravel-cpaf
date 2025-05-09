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
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (Auth::check()) {
                $model->user_id = Auth::id(); 
            }
        });
    }
    
}


