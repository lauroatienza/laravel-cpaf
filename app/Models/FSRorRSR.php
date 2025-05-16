<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class FSRorRSR extends Model
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

    protected $fillable = [
        'user_id',
        'year',
        'sem',
        'drive_link',
        'full_name',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
