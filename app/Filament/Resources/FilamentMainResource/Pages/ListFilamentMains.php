<?php

namespace App\Filament\Resources\FilamentMainResource\Pages;

use App\Filament\Resources\FilamentMainResource;
use App\Models\Publication;
use Filament\Actions\CreateAction;
use Filament\Actions\Action;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;
use League\Csv\Writer;
use SplTempFileObject;

class ListFilamentMains extends ListRecords
{
    protected static string $resource = FilamentMainResource::class;
    
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
                    $user = Auth::user();
    
                    $query = Publication::query();
    
                    if (!$user->hasRole(['super-admin', 'admin'])) {
                        $fullName = trim("{$user->name} " . ($user->middle_name ? "{$user->middle_name} " : "") . "{$user->last_name}");
                        $fullNameReversed = trim("{$user->last_name}, {$user->name}" . ($user->middle_name ? " {$user->middle_name}" : ""));
                        $simpleName = trim("{$user->name} {$user->last_name}");
    
                        $initials = strtoupper(substr($user->name, 0, 1)) . '.';
                        if ($user->middle_name) {
                            $initials .= strtoupper(substr($user->middle_name, 0, 1)) . '.';
                        }
                        $reversedInitialsName = "{$user->last_name}, {$initials}";
    
                        $titles = ['Dr.', 'Prof.', 'Engr.', 'Sir', 'Ms.', 'Mr.', 'Mrs.'];
    
                        $normalize = function ($name) use ($titles, $user) {
                            $name = str_ireplace($titles, '', $name);
                            if ($user->middle_name) {
                                $name = str_ireplace($user->middle_name, strtoupper(substr($user->middle_name, 0, 1)) . '.', $name);
                            }
                            return preg_replace('/\s+/', ' ', trim($name));
                        };
    
                        $normalizedNames = [
                            strtolower($normalize($fullName)),
                            strtolower($normalize($fullNameReversed)),
                            strtolower($normalize($simpleName)),
                            strtolower($normalize($reversedInitialsName)),
                        ];
    
                        $publications = $query->get()->filter(function ($pub) use ($normalize, $normalizedNames) {
                            return in_array(strtolower($normalize($pub->name)), $normalizedNames);
                        });
                    } else {
                        $publications = $query->get();
                    }
    
                    $csv = Writer::createFromFileObject(new SplTempFileObject());
    
                    $csv->insertOne([
                        'Name',
                        'Contributing Unit',
                        'Type of Publication',
                        'Other Type',
                        'Title of Publication',
                        'Co-author(s)',
                        'Research/Conference/Publisher Details',
                        'Study/Research Project',
                        'Journal/Book/Conference Name',
                        'Publisher/Organizer',
                        'Type of Publisher',
                        'Location of Publisher',
                        'Editor(s)',
                        'Volume/Issue',
                        'Date Published',
                        'Conference Start',
                        'Conference End',
                        'Conference Venue',
                        'DOI/Link',
                        'ISBN/ISSN',
                        'Collection Database',
                        'Web Science',
                        'Scopus',
                        'Science Direct',
                        'PubMed/MEDLINE',
                        'CHED-Recognized Journals',
                        'Other Reputable Collection/Database',
                        'Citations',
                        'PDF Proof 1',
                        'PDF Proof 2',
                        'Received Award',
                        'Award Title',
                        'Date Awarded',
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
    
}
