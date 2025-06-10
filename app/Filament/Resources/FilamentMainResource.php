<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FilamentMainResource\Pages;
use App\Filament\Resources\FilamentMainResource\RelationManagers;
use App\Models\FilamentMain;
use Filament\Forms;
use Filament\Forms\Form;
use App\Models\Publication;
use Filament\Tables\Filters\Filter;
use Illuminate\Support\Str;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Placeholder;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\BadgeColumn;
use League\Csv\Writer;
use SplTempFileObject;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Contracts\View\View;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Tables\Filters\SelectFilter;

use Illuminate\Database\Eloquent\SoftDeletingScope;

class FilamentMainResource extends Resource
{
    protected static ?string $model = Publication::class;
    protected static ?string $navigationIcon = 'heroicon-o-bookmark-square';
    protected static ?string $navigationGroup = 'Accomplishments';
    protected static ?string $label = 'Publication';
    protected static ?int $navigationSort = 1;
    
    public static function getNavigationBadge(): ?string
    {
        $user = Auth::user();

        if ($user->hasRole(['super-admin', 'admin'])) {
            return static::$model::count();
        }

        // Name formats
        $fullName = trim("{$user->name} " . ($user->middle_name ? "{$user->middle_name} " : "") . "{$user->last_name}");
        $fullNameReversed = trim("{$user->last_name}, {$user->name}" . ($user->middle_name ? " {$user->middle_name}" : ""));
        $simpleName = trim("{$user->name} {$user->last_name}");

        // New: Lastname, F.M. format
        $initials = strtoupper(substr($user->name, 0, 1)) . '.';
        if ($user->middle_name) {
            $initials .= strtoupper(substr($user->middle_name, 0, 1)) . '.';
        }
        $reversedInitialsName = "{$user->last_name}, {$initials}";

        // Titles to strip
        $titles = ['Dr.', 'Prof.', 'Engr.', 'Sir', 'Ms.', 'Mr.', 'Mrs.'];

        // Prepare SQL REPLACE chain
        $replacer = 'name';
        foreach ($titles as $title) {
            $replacer = "REPLACE($replacer, '$title', '')";
        }

        // Normalizer function
        $normalizeName = function ($name) use ($titles, $user) {
            $nameWithoutTitles = str_ireplace($titles, '', $name);

            if ($user->middle_name) {
                $middleNameInitial = strtoupper(substr($user->middle_name, 0, 1)) . '.';
                $nameWithoutTitles = str_ireplace($user->middle_name, $middleNameInitial, $nameWithoutTitles);
            }

            return preg_replace('/\s+/', ' ', trim($nameWithoutTitles));
        };

        // Normalize all formats
        $normalizedFullName = $normalizeName($fullName);
        $normalizedFullNameReversed = $normalizeName($fullNameReversed);
        $normalizedSimpleName = $normalizeName($simpleName);
        $normalizedReversedInitials = $normalizeName($reversedInitialsName);

        // Final query
        return static::$model::where(function ($query) use ($replacer, $normalizedFullName, $normalizedFullNameReversed, $normalizedSimpleName, $normalizedReversedInitials) {
            $query->whereRaw("LOWER(($replacer)) LIKE LOWER(?)", ["%$normalizedFullName%"])
                ->orWhereRaw("LOWER(($replacer)) LIKE LOWER(?)", ["%$normalizedFullNameReversed%"])
                ->orWhereRaw("LOWER(($replacer)) LIKE LOWER(?)", ["%$normalizedSimpleName%"])
                ->orWhereRaw("LOWER(($replacer)) LIKE LOWER(?)", ["%$normalizedReversedInitials%"]);
        })->count();

    }
    public static function form(Form $form): Form
    {
        return $form
                ->schema([
                    Section::make('Section 1')
                    ->schema([
                        TextInput::make('name')->label('Full Name')->placeholder('First Name, Middle Initial, Last Name')->required(),
                        Select::make('contributing_unit')->label('Contributing Unit')->options([
                            'CISC' => 'CISC',
                            'CSPPS' => 'CSPPS',
                            'CPAF' => 'CPAF',
                            'IGRD' => 'IGRD',
                        ])->required(),

                        Select::make('type_of_publication')
                            ->label('Type of Publication')
                            ->options([
                                'Book/Monograph' => 'Book/Monograph',
                                'Book Chapter (Edited/Peer-Reviewed)' => 'Book Chapter (Edited/Peer-Reviewed)',
                                'Paper Publication (Peer-Reviewed/Refereed)' => 'Paper Publication (Peer-Reviewed/Refereed)',
                                'Paper Publication (Indexed Journal)' => 'Paper Publication (Indexed Journal)',
                                'Journal Article (Peer-Reviewed)' => 'Journal Article (Peer-Reviewed)',
                                'Other' => 'Other...',
                            ])
                            ->live()
                            ->required()
                            ->afterStateUpdated(function (Set $set, $state) {
                                if ($state !== 'Other') {
                                    $set('other_type', null);
                                }
                            }),

                        TextInput::make('other_type')
                            ->label('Other (please specify)')
                            ->maxLength(255)
                            ->visible(fn (Get $get) => $get('type_of_publication') === 'Other')
                            ->afterStateUpdated(function (Set $set, $state, Get $get) {
                                if ($get('type_of_publication') === 'Other') {
                                    $set('type_of_publication', $state);
                                }
                            }),

                        TextInput::make('title_of_publication')->label('Title of Publication')->placeholder('Enter the title of the publication')->required()->maxLength(255),
                        Textarea::make('co_authors')->label('Co-author(s)')->placeholder("Specify the lead author first. Separate co-authors with semi-colons.\nExample: John Doe; Jane Smith; Alex Johnson")->helperText('')->required()->rows(3),
                    ])->columns(2),

                    Section::make('Section 2')
                        ->schema([
                            Textarea::make('research_conference_publisher_details')->label('Research/Conference/Publisher details')->placeholder("Do not leave the box blank. Please put 'NA' if you have no answers. Thank you!")->required()->rows(2),
                            Textarea::make('study_research_project')->label('Study/Research Project where the publication resulted from')->placeholder('Enter details')->rows(2),
                            Textarea::make('journal_book_conference')->label('Name of Journal/Book/Conference Publication')->placeholder('Enter the name')->rows(2),
                            Textarea::make('publisher_organizer')->label('Publisher/Name of Organizer')->placeholder('Enter the name')->maxLength(255)->rows(2),
                            Select::make('type_of_publisher')->label('Type of Publisher')->options([
                                'Commercial' => 'Commercial',
                                'Learned Society and Association' => 'Learned Society and Association',
                                'University Press' => 'University Press',
                            ])->required(),
                            Select::make('location_of_publisher')->label('Location of Publisher')->options([
                                'Local' => 'Local',
                                'International' => 'International',
                            ])->required(),
                            Textarea::make('editors')->label('Name of Editor(s)')->placeholder("Separate editors' names with semi-colons.\nExample: John Doe; Jane Smith; Alex Johnson ")->rows(3),
                            TextInput::make('volume_issue')->label('Volume No. and Issue No.')->placeholder('Ex: Volume 1 Issue 3')->maxLength(255),
                            Grid::make(3)->schema([
                                DatePicker::make('date_published')->label('Date Published or Accepted')->placeholder('Select date')->required(),
                                DatePicker::make('conference_start_date')->label('Conference START Date')->placeholder('Select start date'),
                                DatePicker::make('conference_end_date')->label('Conference END Date')->placeholder('Select end date'),
                            ]),
                            Textarea::make('conference_venue')->label('Conference Venue, City, and Country')->placeholder('Enter location details')->rows(2),
                            TextInput::make('doi_or_link')->label('DOI or Link')->placeholder('Specify DOI or URL')->url()->required(),

                            Select::make('isbn_issn')->label('ISBN or ISSN')->options([
                                'ISBN' => 'ISBN',
                                'ISSN' => 'ISSN']),
                            ])->columns(2),

                    Section::make('Section 3')
                        ->description('Indicate the Collection/Database where this Journal/Book/Conference Publication has been indexed/catalogued/recognized.')
                        ->schema([

                            // Textarea::make('collection_database')->placeholder('Indicate the Collection/Database where this Journal/Book/Conference Publication has been indexed/catalogued/recognized')->helperText('Do not leave the box blank. Please put "NA" if you have no answers. Thank you!')->required(),
                            
                            Radio::make('web_science')->label('Web Science (formerly ISI)')->options(['YES' => 'YES', 'NO' => 'NO'])->inline()->required(),
                            Radio::make('scopus')->label("Elsevier's Scopus")->options(['YES' => 'YES', 'NO' => 'NO'])->inline()->required(),
                            Radio::make('science_direct')->label("Elsevier's ScienceDirect")->options(['YES' => 'YES', 'NO' => 'NO'])->inline()->required(),
                            Radio::make('pubmed')->label('PubMed/MEDLINE')->options(['YES' => 'YES', 'NO' => 'NO'])->inline()->required(),
                            Radio::make('ched_journals')->label('CHED-Recognized Journals')->options(['YES' => 'YES', 'NO' => 'NO'])->inline()->required(),
                            Textarea::make('other_reputable_collection')->label('Other Reputable Collection/Database')->placeholder('Leave blank if there is no such other database.'),
                            TextInput::make('citations')->label('Number of Citations')->numeric()->placeholder('If none, please put zero (0).')->required(),
                        ])->columns(1),

                    Section::make('Section 4')
                        ->schema([
                            Placeholder::make('proofs_instruction')->label('Kindly input the link to the proofs below. Do not leave the box blank. Please put "NA" if you have no answers. Thank you!')->columnSpan('full'),
                            TextInput::make('pdf_proof_1')->label('Proof of Publication 1')->placeholder('Input the Google Drive link to the proof')->helperText('Proof of Publication or Accepted for Publication such as cover, title and bibliographic information pages, publishers acceptance letter or preprint, etc.')->required(),
                            TextInput::make('pdf_proof_2')->label('Proof of Publication 2')->placeholder('Input the Google Drive link to the proof (if applicable)')->helperText('Proof of Utilization, if applicable, such as citation evidence, number of sales, UP International Publication Award, or was published in indexed/catalogued/recognized publications or by reputable publishers, etc., must be properly endorsed by the Dean/Head of Unit.'),
                        ])
                        ->columns(2),

                    Section::make('Section 5')
                        ->schema([
                            Radio::make('received_award')->label('Received Award')->options(['YES' => 'YES', 'NO' => 'NO'])->required(),
                            Textarea::make('award_title')->label('Award Title')->placeholder("Do not leave the box blank. Please put 'NA' if you have no answers. Thank you!")->nullable(),
                            Grid::make(5)->schema([DatePicker::make('date_awarded')->label('Date Awarded')->nullable()
                        ]),
                        ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // SECTION 1 of 5: Author and Publication Info
                TextColumn::make('name')->label('Name')->sortable()->searchable(),
                BadgeColumn::make('contributing_unit')->label('Contributing Unit')->searchable()->sortable()->limit(20),
                TextColumn::make('type_of_publication')->label('Type of Publication')->searchable()->sortable()->limit(20)->tooltip(fn($state) => $state),
                TextColumn::make('title_of_publication')->label('Title of Publication')->searchable()->sortable()->limit(20)->tooltip(fn($state) => $state),
                TextColumn::make('co_authors')->label('Co-author(s)')->searchable()->sortable()->limit(20)->tooltip(fn($state) => $state),

                // SECTION 2 of 5: Journal & Publisher Info
                TextColumn::make('research_conference_publisher_details')->label('Research/Conference/Publisher Details')->searchable()->limit(20)->sortable()->tooltip(fn($state) => $state),
                TextColumn::make('study_research_project')->label('Study/Research Project')->searchable()->limit(20)->sortable()->tooltip(fn($state) => $state),
                TextColumn::make('journal_book_conference')->label('Journal/Book/Conference Name')->sortable()->searchable()->limit(20)->tooltip(fn($state) => $state),
                TextColumn::make('publisher_organizer')->label('Publisher/Organizer')->sortable()->searchable()->limit(20)->tooltip(fn($state) => $state),
                TextColumn::make('type_of_publisher')->label('Type of Publisher')->sortable()->searchable()->limit(20)->tooltip(fn($state) => $state),
                TextColumn::make('location_of_publisher')->label('Location of Publisher')->sortable()->searchable()->limit(20)->tooltip(fn($state) => $state),
                TextColumn::make('editors')->label('Editor(s)')->sortable()->searchable()->limit(20)->tooltip(fn($state) => $state),
                TextColumn::make('volume_issue')->label('Volume/Issue')->sortable()->searchable()->limit(20)->tooltip(fn($state) => $state),
                TextColumn::make('date_published')->label('Date Published')->date()->sortable()->limit(20),
                TextColumn::make('conference_start_date')->label('Conference Start')->date()->sortable(),
                TextColumn::make('conference_end_date')->label('Conference End')->date()->sortable(),
                TextColumn::make('conference_venue')->label('Conference Venue')->searchable()->sortable()->limit(20)->tooltip(fn($state) => $state),

                //COPY DOI OR LINK TO OTHER LINKS FROM SECTION 4
                TextColumn::make('doi_or_link')
                    ->label('DOI / Link')
                    ->url(
                        fn($record) =>
                        str_starts_with($record->doi_or_link, 'http')
                        ? $record->doi_or_link
                        : 'https://doi.org/' . ltrim($record->doi_or_link, '/')
                    )
                    ->openUrlInNewTab()
                    ->limit(30)
                    ->sortable()
                    ->searchable()
                    ->tooltip(fn($state) => $state),
                //

                TextColumn::make('isbn_issn')->label('ISBN/ISSN')->searchable()->sortable(),

                // SECTION 3 of 5: Indexing and Citations
                // TextColumn::make('collection_database')->label('Collection Database')->sortable()->searchable()->limit(20)->tooltip(fn($state) => $state),
                TextColumn::make('web_science')->label('Web Science')->sortable()->alignCenter()->searchable()->badge()->formatStateUsing(fn($state) => $state === 'YES' ? 'Yes' : 'No')->color(fn($state) => $state === 'YES' ? 'success' : 'danger'),
                TextColumn::make('scopus')->label('Scopus')->alignCenter()->sortable()->searchable()->badge()->formatStateUsing(fn($state) => $state === 'YES' ? 'Yes' : 'No')->color(fn($state) => $state === 'YES' ? 'success' : 'danger'),
                TextColumn::make('science_direct')->label('Science Direct')->sortable()->alignCenter()->searchable()->badge()->formatStateUsing(fn($state) => $state === 'YES' ? 'Yes' : 'No')->color(fn($state) => $state === 'YES' ? 'success' : 'danger'),
                TextColumn::make('pubmed')->label('PubMed/MEDLINE')->sortable()->alignCenter()->searchable()->badge()->formatStateUsing(fn($state) => $state === 'YES' ? 'Yes' : 'No')->color(fn($state) => $state === 'YES' ? 'success' : 'danger'),
                TextColumn::make('ched_journals')->label('CHED-Recognized Journals')->sortable()->alignCenter()->searchable()->badge()->formatStateUsing(fn($state) => $state === 'YES' ? 'Yes' : 'No')->color(fn($state) => $state === 'YES' ? 'success' : 'danger'),
                TextColumn::make('other_reputable_collection')->label('Other Reputable Collection/Database')->sortable()->searchable()->limit(20)->tooltip(fn($state) => $state),
                TextColumn::make('citations')->label('Citations')->sortable()->sortable()->limit(20)->tooltip(fn($state) => $state)->alignCenter(),

                // SECTION 4 of 5: Proofs
                TextColumn::make('pdf_proof_1')
                    ->label('PDF Proof 1')
                    ->sortable()
                    ->url(fn($record) =>
                        str_starts_with($record->pdf_proof_1, 'http')
                        ? $record->pdf_proof_1
                        : 'https://drive.google.com/' . ltrim($record->pdf_proof_1, '/'))
                    ->openUrlInNewTab()
                    ->limit(20)
                    ->searchable()
                    ->tooltip(fn($state) => $state),

                TextColumn::make('pdf_proof_2')
                    ->label('PDF Proof 2')
                    ->sortable()
                    ->url(
                        fn($record) =>
                        str_starts_with($record->pdf_proof_2, 'http')
                        ? $record->pdf_proof_2
                        : 'https://drive.google.com/' . ltrim($record->pdf_proof_2, '/')
                    )
                    ->openUrlInNewTab()
                    ->limit(30)
                    ->searchable()
                    ->tooltip(fn($state) => $state),

                // SECTION 5 of 5: Awards
                TextColumn::make('received_award')->sortable()->label('Received Award')->badge()->formatStateUsing(fn($state) => $state === 'YES' ? 'Yes' : 'No')->color(fn($state) => $state === 'YES' ? 'success' : 'danger')->alignCenter(),
                TextColumn::make('award_title')->sortable()->label('Award Title')->searchable()->tooltip(fn($state) => $state),
                TextColumn::make('date_awarded')->sortable()->label('Date Awarded')->date(),
            ])
            ->filters([
                Filter::make('date_published')
                    ->form([
                        DatePicker::make('from')->label('From'),
                        DatePicker::make('until')->label('Until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'], fn ($query, $date) => $query->whereDate('date_published', '>=', $date))
                            ->when($data['until'], fn ($query, $date) => $query->whereDate('date_published', '<=', $date));
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\BulkAction::make('exportSelected')
                    ->label('Export Selected')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function ($records) {
                        $csv = Writer::createFromFileObject(new SplTempFileObject());

                        // Insert header
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

        foreach ($records as $publication) {
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
    })
    ->requiresConfirmation(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFilamentMains::route('/'),
            'create' => Pages\CreateFilamentMain::route('/create'),
            'edit' => Pages\EditFilamentMain::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
{
    $user = Auth::user();

    // If the user is an admin, return all records
    if ($user->hasRole(['super-admin', 'admin'])) {
        return parent::getEloquentQuery();
    }

    // Build possible name formats
    $fullName = trim("{$user->name} " . ($user->middle_name ? "{$user->middle_name} " : "") . "{$user->last_name}");
    $fullNameReversed = trim("{$user->last_name}, {$user->name}" . ($user->middle_name ? " {$user->middle_name}" : ""));
    $simpleName = trim("{$user->name} {$user->last_name}");

    // New format: Lastname, F.M.
    $initials = strtoupper(substr($user->name, 0, 1)) . '.';
    if ($user->middle_name) {
        $initials .= strtoupper(substr($user->middle_name, 0, 1)) . '.';
    }
    $reversedInitialsName = "{$user->last_name}, {$initials}";

    // Titles to remove
    $titles = ['Dr.', 'Prof.', 'Engr.', 'Sir', 'Ms.', 'Mr.', 'Mrs.'];

    // Function to normalize names
    $normalizeName = function ($name) use ($titles, $user) {
        $nameWithoutTitles = str_ireplace($titles, '', $name);

        if ($user->middle_name) {
            $middleNameInitial = strtoupper(substr($user->middle_name, 0, 1)) . '.';
            $nameWithoutTitles = str_ireplace($user->middle_name, $middleNameInitial, $nameWithoutTitles);
        }

        return preg_replace('/\s+/', ' ', trim($nameWithoutTitles));
    };

    // Normalize each name variant
    $normalizedFullName = $normalizeName($fullName);
    $normalizedFullNameReversed = $normalizeName($fullNameReversed);
    $normalizedSimpleName = $normalizeName($simpleName);
    $normalizedReversedInitials = $normalizeName($reversedInitialsName);

    // Create full REPLACE chain for SQL title-stripping
    $replacer = 'name';
    foreach ($titles as $title) {
        $replacer = "REPLACE($replacer, '$title', '')";
    }

    return parent::getEloquentQuery()
        ->where(function ($query) use (
            $replacer,
            $normalizedFullName,
            $normalizedFullNameReversed,
            $normalizedSimpleName,
            $normalizedReversedInitials
        ) {
            $query->whereRaw("LOWER($replacer) LIKE LOWER(?)", ["%$normalizedFullName%"])
                  ->orWhereRaw("LOWER($replacer) LIKE LOWER(?)", ["%$normalizedFullNameReversed%"])
                  ->orWhereRaw("LOWER($replacer) LIKE LOWER(?)", ["%$normalizedSimpleName%"])
                  ->orWhereRaw("LOWER($replacer) LIKE LOWER(?)", ["%$normalizedReversedInitials%"]);
        });


}
}
