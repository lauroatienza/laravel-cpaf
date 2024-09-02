<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingAttended extends Model
{
    use HasFactory;

    protected $fillable = [
        "training_title",
        "num_hours",
        "start_date",
        "end_date",
        "faculty_id",
        "venue",
    ];
}
