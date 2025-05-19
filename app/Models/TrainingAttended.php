<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class TrainingAttended extends Model
{
    use HasFactory;

    protected static function booted()
    {
        static::creating(function ($model) {
            // Set user_id if not already set
            if (Auth::check() && empty($model->user_id)) {
                $model->user_id = Auth::id();
            }

            // Normalize name using input value
            $model->full_name = self::normalizeName($model->full_name);
        });

        static::updating(function ($model) {
            $model->full_name = self::normalizeName($model->full_name);
        });
    }

    protected static function normalizeName($name)
    {
        $titles = ['Dr.', 'Prof.', 'Engr.', 'Sir', 'Ms.', 'Mr.', 'Mrs.'];
        $name = str_ireplace($titles, '', $name);
        $name = preg_replace('/\s+/', ' ', trim($name));
        return $name;
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