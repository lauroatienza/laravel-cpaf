<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherNotableAwards extends Model
{
    use HasFactory;
    
    protected $fillable = [
        "award_title",
        "award_desc",
        "faculty_id",
        "date_awarded",
    ];
}
