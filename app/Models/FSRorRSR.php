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
        "file_upload",
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
