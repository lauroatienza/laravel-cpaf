<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JournalArticle extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "authors",
        "article_title",
        "journal_name",
        "date_published",
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
