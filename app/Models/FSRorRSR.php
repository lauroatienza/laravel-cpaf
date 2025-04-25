<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FSRorRSR extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "year",
        "sem",
        "drive_link",
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
