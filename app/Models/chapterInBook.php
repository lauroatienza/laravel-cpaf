<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class chapterInBook extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "title",
        "co-authors",
        "date_publication",
    ];  

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}