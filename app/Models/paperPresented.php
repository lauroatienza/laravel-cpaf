<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class paperPresented extends Model
{
    use HasFactory;

    protected $fillable = [
        "faculty_id",
        "paper_title",
        "date_presented",
    ];

}
