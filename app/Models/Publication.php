<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Publication extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'contributing_unit',
        'type_of_publication',
        'other_type',
        'title_of_publication',
        'co_authors',

        'research_conference_publisher_details',
        'study_research_project',
        'journal_book_conference',
        'publisher_organizer',
        'type_of_publisher',
        'location_of_publisher',
        'editors',
        'volume_issue',
        'date_published',
        'research_conference_publisher_details',
        'conference_start_date',
        'conference_end_date',
        'conference_venue',
        'doi_or_link',
        'isbn_issn',

        'collection_database',
        'web_science',
        'scopus',
        'science_direct',
        'pubmed',
        'ched_journals',
        'other_reputable_collection',
        'citations',

        'pdf_proof_1',
        'pdf_proof_2',

        'received_award',
        'award_title',
        'date_awarded',

    ];


    protected $casts = [
        'date_awarded' => 'date',
        'date_published' => 'date',
        'conference_start_date' => 'date',
        'conference_end_date' => 'date',
    ];


    /**
     * Automatically assign the authenticated user's ID when creating a new publication.
     */
    protected static function booted()
    {
        static::creating(function ($publication) {
            if (Auth::check() && !$publication->user_id) {
                $publication->user_id = Auth::id();
            }
        });
    }
}
