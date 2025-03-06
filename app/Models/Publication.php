<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publication extends Model
{
    use HasFactory;

    protected $fillable = [
        'collection_database',
        'web_science', 'scopus', 'science_direct', 'pubmed', 'ched_journals',
        'other_reputable_collection', 'citations',
        'pdf_proof_1', 'pdf_proof_2',
        'received_award', 'award_title', 'date_awarded'
    ];

    protected $casts = [
        'date_awarded' => 'date',
    ];
}
