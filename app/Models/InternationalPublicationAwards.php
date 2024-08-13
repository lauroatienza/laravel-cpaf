<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternationalPublicationAwards extends Model
{
    use HasFactory;

    protected $fillable = [
        "title",
        "faculty_id",
        "date_awarded",
        "date_published",
    ];
}
