<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class chapterInBook extends Model
{
    use HasFactory;

    protected $fillable = [
        "first_name",
        "title",
        "co-authors",
        "date_publication",
    ];
}
