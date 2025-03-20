<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AwardsRecognitions extends Model
{
    protected $fillable = [
        'award_type',
        'award_title',
        'awardee_name',
        'granting_organization',
        'date_awarded',
    ];
}

