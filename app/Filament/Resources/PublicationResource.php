<?php

namespace App\Filament\Resources;

use App\Models\Publication;
use App\Filament\Resources\PublicationResource\Pages;
use Illuminate\Support\Str;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\Resource;
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
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\BadgeColumn;
use League\Csv\Writer;
use SplTempFileObject;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Get;
use Filament\Forms\Set;

class PublicationResource extends Resource
{
    protected static ?string $model = Publication::class;
    protected static ?string $navigationIcon = 'heroicon-o-bookmark-square';
    protected static ?string $navigationGroup = 'Accomplishments';
    protected static ?string $label = 'Publication';
    protected static ?int $navigationSort = 1;

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
{
    $user = Auth::user();

    // Admins see everything
    if ($user && $user->hasRole(['super-admin', 'admin'])) {
        return parent::getEloquentQuery();
    }

    // Non-admins see only their own
    return parent::getEloquentQuery()->where('user_id', $user?->id);
}


    public static function getNavigationBadge(): ?string
    {
        $user = Auth::user();

        if ($user->hasRole(['super-admin', 'admin'])) {
            return static::$model::count();
        }

        // Only return records belonging to the logged-in user
        return static::$model::where('user_id', $user->id)->count();

    }

    public static function getNavigationBadgeColor(): string
    {
        return 'primary';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Section 1 of 5')
                ->schema([
                    TextInput::make('name')->label('Full Name')->required(),
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
                    Textarea::make('co_authors')->label('Co-author(s)')->placeholder('Specify the lead author first. Separate co-authors with semi-colons.')->helperText('Example: John Doe; Jane Smith; Alex Johnson')->required()->rows(3),
                ]),

                Section::make('Section 2 of 5')
                    ->schema([
                        Textarea::make('research_conference_publisher_details')->label('Research/Conference/Publisher details')->placeholder('Description (optional)')->helperText('Do not leave the box blank. Please put "NA" if you have no answers. Thank you!')->required(),
                        Textarea::make('study_research_project')->label('Study/Research Project where the publication resulted from')->placeholder('Enter details')->rows(3),
                        Textarea::make('journal_book_conference')->label('Name of Journal/Book/Conference Publication')->placeholder('Enter the name')->rows(3),
                        TextInput::make('publisher_organizer')->label('Publisher/Name of Organizer')->placeholder('Enter the name')->maxLength(255),
                        Select::make('type_of_publisher')->label('Type of Publisher')->options([
                            'Commercial' => 'Commercial',
                            'Learned Society and Association' => 'Learned Society and Association',
                            'University Press' => 'University Press',
                        ])->required(),
                        Select::make('location_of_publisher')->label('Location of Publisher')->options([
                            'Local' => 'Local',
                            'International' => 'International',
                        ])->required(),
                        Textarea::make('editors')->label('Name of Editor(s)')->placeholder("Separate editors' names with semi-colons.")->helperText('Example: John Doe; Jane Smith; Alex Johnson')->rows(3),
                        TextInput::make('volume_issue')->label('Volume No. and Issue No.')->placeholder('Enter volume and issue number')->maxLength(255),
                        Grid::make(3)->schema([
                            DatePicker::make('date_published')->label('Date Published or Accepted for Publication')->placeholder('Select date')->required(),
                            DatePicker::make('conference_start_date')->label('Conference START Date')->placeholder('Select start date'),
                            DatePicker::make('conference_end_date')->label('Conference END Date')->placeholder('Select end date'),
                        ]),
                        Textarea::make('conference_venue')->label('Conference Venue, City, and Country')->placeholder('Enter location details')->rows(3),
                        //TextInput::make('doi_or_link')->label('DOI if any or Link')->placeholder('Specify DOI or URL')->limit(20),
                        TextInput::make('doi_or_link')->label('DOI or Link')->placeholder('Specify DOI or URL')->url()->required(),

                        Select::make('isbn_issn')->label('ISBN or ISSN')->options([
                            'ISBN' => 'ISBN',
                            'ISSN' => 'ISSN']),
                    ]),

                Section::make('Section 3 of 5')
                    ->schema([

                        Textarea::make('collection_database')->helperText('Indicate the Collection/Database where this Journal/Book/Conference Publication has been indexed/catalogued/recognized:(Do not leave the box blank. Please put "NA" if you have no answers. Thank you)')->placeholder('Description')->required(),
                        Radio::make('web_science')->label('Web Science (formerly ISI)')->options(['YES' => 'YES', 'NO' => 'NO'])->inline()->required(),
                        Radio::make('scopus')->label("Elsevier's Scopus")->options(['YES' => 'YES', 'NO' => 'NO'])->inline()->required(),
                        Radio::make('science_direct')->label("Elsevier's ScienceDirect")->options(['YES' => 'YES', 'NO' => 'NO'])->inline()->required(),
                        Radio::make('pubmed')->label('PubMed/MEDLINE')->options(['YES' => 'YES', 'NO' => 'NO'])->inline()->required(),
                        Radio::make('ched_journals')->label('CHED-Recognized Journals')->options(['YES' => 'YES', 'NO' => 'NO'])->inline()->required(),
                        Textarea::make('other_database')->label('Other Reputable Collection/Database')->placeholder('Leave blank if there is no such other database.'),
                        TextInput::make('citations')->label('Number of Citations')->numeric()->placeholder('If none, please put zero (0).')->required(),
                    ]),

                Section::make('Section 4 of 5')
                    ->schema([
                        Placeholder::make('proofs_instruction')->label('Proofs Instruction')->helperText('Kindly input the link to the proofs below. Do not leave the box blank. Please put "NA" if you have no answers. Thank you!')->columnSpan('full'),
                        TextInput::make('pdf_proof_1')->label('PDF Image File 1 (Proof of Publication)')->placeholder('Input the link to the proof')->required(),
                        TextInput::make('pdf_proof_2')->label('PDF Image File 2 (Proof of Utilization)')->placeholder('Input the link to the proof (if applicable)'),
                    ]),

                Section::make('Section 5 of 5')
                    ->schema([
                        Placeholder::make('awards_instruction')->label('Awards Instruction')->helperText('Do not leave the box blank. Please put "NA" if you have no answers. Thank you!')->columnSpan('full'),
                        Radio::make('received_award')->label('Received Award')->options(['YES' => 'YES', 'NO' => 'NO'])->required(),
                        Textarea::make('award_title')->label('Award Title')->placeholder('Enter the award title')->nullable(),
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
            TextColumn::make('type_of_publication')->label('Type of Publication')->searchable()->sortable()->limit(20)->tooltip(fn ($record) => $record->type_of_publication),
            //TextColumn::make('other_type')->label('Other Type')->searchable()->sortable()->limit(20)->tooltip(fn ($record) => $record->other_type),
            TextColumn::make('title_of_publication')->label('Title of Publication')->searchable()->sortable()->limit(20)->tooltip(fn ($record) => $record->title_of_publication),
            TextColumn::make('co_authors')->label('Co-author(s)')->searchable()->sortable()->limit(20)->tooltip(fn ($record) => $record->co_authors),

            // SECTION 2 of 5: Journal & Publisher Info
            TextColumn::make('research_conference_publisher_details')->label('Research/Conference/Publisher Details')->searchable()->limit(20)->sortable()->tooltip(fn ($record) => $record->research_conference_publisher_details),
            TextColumn::make('study_research_project')->label('Study/Research Project')->searchable()->limit(20)->sortable()->tooltip(fn ($record) => $record->study_research_project),
            TextColumn::make('journal_book_conference')->label('Journal/Book/Conference Name')->sortable()->searchable()->limit(20)->tooltip(fn ($record) => $record->journal_book_conference),
            TextColumn::make('publisher_organizer')->label('Publisher/Organizer')->sortable()->searchable()->limit(20)->tooltip(fn ($record) => $record->publisher_organizer),
            TextColumn::make('type_of_publisher')->label('Type of Publisher')->sortable()->searchable()->limit(20)->tooltip(fn ($record) => $record->type_of_publisher),
            TextColumn::make('location_of_publisher')->label('Location of Publisher')->sortable()->searchable()->limit(20)->tooltip(fn ($record) => $record->location_of_publisher),
            TextColumn::make('editors')->label('Editor(s)')->sortable()->searchable()->limit(20)->tooltip(fn ($record) => $record->other_type),
            TextColumn::make('volume_issue')->label('Volume/Issue')->sortable()->searchable()->limit(20),
            TextColumn::make('date_published')->label('Date Published')->date()->sortable()->limit(20),
            TextColumn::make('conference_start_date')->label('Conference Start')->date()->sortable()->limit(20),
            TextColumn::make('conference_end_date')->label('Conference End')->date()->sortable(),
            TextColumn::make('conference_venue')->label('Conference Venue')->searchable()->sortable()->limit(20)->tooltip(fn ($record) => $record->conference_venue),

            //COPY DOI OR LINK TO OTHER LINKS FROM SECTION 4
            TextColumn::make('doi_or_link')
                ->label('DOI / Link')
                ->url(fn ($record) =>
                    str_starts_with($record->doi_or_link, 'http')
                        ? $record->doi_or_link
                        : 'https://doi.org/' . ltrim($record->doi_or_link, '/')
                )
                ->openUrlInNewTab()
                ->limit(30)
                ->sortable()
                ->searchable(),
            //

            TextColumn::make('isbn_issn')->label('ISBN/ISSN')->searchable()->sortable(),

            // SECTION 3 of 5: Indexing and Citations
            TextColumn::make('collection_database')->label('Collection Database')->sortable()->searchable()->limit(20)->tooltip(fn ($record) => $record->collection_database),
            TextColumn::make('web_science')->label('Web Science')->sortable()->alignCenter()->searchable()->badge()->formatStateUsing(fn ($state) => $state === 'YES' ? 'Yes' : 'No')->color(fn ($state) => $state === 'YES' ? 'success' : 'danger'),
            TextColumn::make('scopus')->label('Scopus')->alignCenter()->sortable()->searchable()->badge()->formatStateUsing(fn ($state) => $state === 'YES' ? 'Yes' : 'No')->color(fn ($state) => $state === 'YES' ? 'success' : 'danger'),
            TextColumn::make('science_direct')->label('Science Direct')->sortable()->alignCenter()->searchable()->badge()->formatStateUsing(fn ($state) => $state === 'YES' ? 'Yes' : 'No')->color(fn ($state) => $state === 'YES' ? 'success' : 'danger'),
            TextColumn::make('pubmed')->label('PubMed/MEDLINE')->sortable()->alignCenter()->searchable()->badge()->formatStateUsing(fn ($state) => $state === 'YES' ? 'Yes' : 'No')->color(fn ($state) => $state === 'YES' ? 'success' : 'danger'),
            TextColumn::make('ched_journals')->label('CHED-Recognized Journals')->sortable()->alignCenter()->searchable()->badge()->formatStateUsing(fn ($state) => $state === 'YES' ? 'Yes' : 'No')->color(fn ($state) => $state === 'YES' ? 'success' : 'danger'),
            TextColumn::make('other_reputable_collection')->label('Other Reputable Collection/Database')->sortable()->searchable()->limit(20)->tooltip(fn ($record) => $record->other_reputable_collection),
            TextColumn::make('citations')->label('Citations')->sortable()->sortable()->limit(20)->tooltip(fn ($record) => $record->citations)->alignCenter(),

            // SECTION 4 of 5: Proofs
            TextColumn::make('pdf_proof_1')
                ->label('PDF Proof 1')
                ->sortable()
                ->url(fn ($record) =>
                    str_starts_with($record->pdf_proof_1, 'http')
                        ? $record->pdf_proof_1
                        : 'https://drive.google.com/' . ltrim($record->pdf_proof_1, '/')
                )
                ->openUrlInNewTab()
                ->limit(30)
                ->searchable(),

                TextColumn::make('pdf_proof_2')
                ->label('PDF Proof 2')
                ->sortable()
                ->url(fn ($record) =>
                    str_starts_with($record->pdf_proof_2, 'http')
                        ? $record->pdf_proof_2
                        : 'https://drive.google.com/' . ltrim($record->pdf_proof_2, '/')
                )
                ->openUrlInNewTab()
                ->limit(30)
                ->searchable(),

            // SECTION 5 of 5: Awards
            TextColumn::make('received_award')->sortable()->label('Received Award')->badge()->formatStateUsing(fn ($state) => $state === 'YES' ? 'Yes' : 'No')->color(fn ($state) => $state === 'YES' ? 'success' : 'danger')->alignCenter(),
            TextColumn::make('award_title')->sortable()->label('Award Title')->searchable(),
            TextColumn::make('date_awarded')->sortable()->label('Date Awarded')->date(),
        ])
        ->filters([])
        ->actions([
            ViewAction::make(),
            EditAction::make(),
            DeleteAction::make(),
        ])
        ->bulkActions([
            DeleteBulkAction::make(),
        ])
        ->headerActions([
            Tables\Actions\CreateAction::make()
                ->label('New Publication')
                ->icon('heroicon-o-pencil-square')
                ->color('secondary'),

            Tables\Actions\Action::make('export')
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
                            $publication->date_published,
                            $publication->research_conference_publisher_details,
                            $publication->conference_start_date,
                            $publication->conference_end_date,
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
                            $publication->date_awarded,

                        ]);
                    }

                    return response()->streamDownload(function () use ($csv) {
                        echo $csv->toString();
                    }, 'publications_export_' . now()->format('Ymd_His') . '.csv');
                }),
        ]);
}
public static function getRelations(): array
    {
        return [];
    }
public static function getPages(): array
{
    return [
        'index' => Pages\ListPublications::route('/'),
        'create' => Pages\CreatePublication::route('/create'),
        'edit' => Pages\EditPublication::route('/{record}/edit'),
    ];
    }
}

