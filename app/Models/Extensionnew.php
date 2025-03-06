<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
class Extensionnew extends Model
{
    use HasFactory;

    protected $table = 'extensionnew'; // Ensures it uses the correct table name

    protected $fillable = [
        'name', 
        'extension_involvement', 
        'event_title',
        'activity_date',
        'venue',
        'location',
        
    ];

    protected static function boot()
{
    parent::boot();

    static::creating(function ($model) {
        if (auth()->check()) {
            $model->user_id = auth()->id();
            $model->name = auth()->user()->name . ' ' . auth()->user()->last_name;
        }
    });
}


}


