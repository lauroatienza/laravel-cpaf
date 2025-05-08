<?php

namespace App\Filament\Resources\PublicationResource\Pages;

use App\Models\Publication;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\PublicationResource;
use Filament\Actions\CreateAction;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use League\Csv\Writer;
use SplTempFileObject;

class ListPublications extends ListRecords
{
    protected static string $resource = PublicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Create New Publication')
                ->color('secondary')
                ->icon('heroicon-o-pencil-square'),

            Action::make('export')
                ->label('Export')
                ->icon('heroicon-o-arrow-down-tray')
                ->action(function () {
                    $publications = Publication::all([
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
                    ]);
            
                    $csv = Writer::createFromFileObject(new SplTempFileObject());
            
                    $csv->insertOne([
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
                    ]);
            
                    foreach ($publications as $publication) {
                        $csv->insertOne([
                            $publication->name,
                            $publication->contributing_unit,
                            $publication->type_of_publication,
                            $publication->other_type,
                            $publication->title_of_publication,
                            $publication->co_authors,
                            $publication->research_conference_publisher_details,
                            $publication->study_research_project,
                            $publication->journal_book_conference,
                            $publication->publisher_organizer,
                            $publication->type_of_publisher,
                            $publication->location_of_publisher,
                            $publication->editors,
                            $publication->volume_issue,
                            optional($publication->date_published)?->format('Y-m-d'),
                            optional($publication->conference_start_date)?->format('Y-m-d'),
                            optional($publication->conference_end_date)?->format('Y-m-d'),
                            $publication->conference_venue,
                            $publication->doi_or_link,
                            $publication->isbn_issn,
                            $publication->collection_database,
                            $publication->web_science,
                            $publication->scopus,
                            $publication->science_direct,
                            $publication->pubmed,
                            $publication->ched_journals,
                            $publication->other_reputable_collection,
                            $publication->citations,
                            $publication->pdf_proof_1,
                            $publication->pdf_proof_2,
                            $publication->received_award,
                            $publication->award_title,
                            optional($publication->date_awarded)?->format('Y-m-d'),
                        ]);
                    }
            
                    return response()->streamDownload(function () use ($csv) {
                        echo $csv->toString();
                    }, 'Publications_' . now()->format('Ymd_His') . '.csv');
                }),
        ];
    }

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()->where('user_id', Auth::id());
    }
}
