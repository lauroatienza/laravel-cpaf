<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class journalArticle extends Model
{
    use HasFactory;
   
    protected $fillable = [
        "faculty_id",
        "authors",
        "article_title",
        "journal_name",
        "date_published",
    ];
}
