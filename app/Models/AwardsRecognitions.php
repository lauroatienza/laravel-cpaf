<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AwardsRecognitions extends Model
{
    protected $fillable = [
<<<<<<< HEAD
        'award_type',
        'award_title',
        'awardee_name',
        'granting_organization',
        'date_awarded',
=======
        "name",
        "award_title",
        "award_desc",
        "award_type",
        "faculty_id",
        "date_awarded",
>>>>>>> 6e39f4dbe850b99802fb69cd1a3a93d93bbe9157
    ];
}

