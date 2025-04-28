<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class CheckMissingColumns extends Command
{
    protected $signature = 'check:missing-columns';
    protected $description = 'Check for missing columns in the publications table';

    public function handle()
    {
        $table = 'publications';

        $intended = [
            'contributing_unit', 'type_of_publication', 'title_of_publication', 'co_authors',
            'research_conference_publisher_details', 'study_research_project', 'journal_book_conference',
            'publisher_organizer', 'type_of_publisher', 'location_of_publisher', 'editors',
            'volume_issue', 'date_published', 'conference_start_date', 'conference_end_date',
            'conference_venue', 'doi_or_link', 'isbn_issn', 'collection_database', 'web_science',
            'scopus', 'science_direct', 'pubmed', 'ched_journals', 'other_reputable_collection',
            'citations', 'pdf_proof_1', 'pdf_proof_2', 'received_award', 'award_title',
            'date_awarded', 'user_id', 'updated_at', 'created_at'
        ];

        if (!Schema::hasTable($table)) {
            $this->error("Table '$table' does not exist.");
            return 1;
        }

        $actual = Schema::getColumnListing($table);
        $missing = array_diff($intended, $actual);

        if (empty($missing)) {
            $this->info("✅ No missing columns in '$table'.");
        } else {
            $this->warn("🚫 Missing columns in '$table':");
            foreach ($missing as $column) {
                $this->line(" - $column");
            }
        }

        return 0;
    }
}
