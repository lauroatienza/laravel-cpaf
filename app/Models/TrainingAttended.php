<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainingAttended extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($training) {
            $training->user_id = \Illuminate\Support\Facades\Auth::id(); // Automatically assign the logged-in user
        });
    }

    protected $fillable = [
        'training_title',
        'full_name',
        'unit_center',
        'start_date',
        'end_date',
        'category',
        'specific_title',
        'highlights',
        'has_gender_component',
        'total_hours',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
