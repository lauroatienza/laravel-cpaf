<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reps extends Model
{
    use HasFactory;

    protected $fillable = [
        "first_name",
        "research_id",
        "last_name",
        "middle_name",
        "designation",
        "employment_status",
    ];

}
