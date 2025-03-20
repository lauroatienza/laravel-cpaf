<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AwardsRecognitions extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "award_title",
        "award_desc",
        "award_type",
        "faculty_id",
        "date_awarded",
    ];
}
