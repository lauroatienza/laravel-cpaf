<?php

namespace App\Filament\Resources;

use App\Models\Publication;
use App\Filament\Resources\PublicationResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Placeholder;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Illuminate\Support\Facades\Auth;

class PublicationResource extends Resource
{
    protected static ?string $model = Publication::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Accomplishments';
    protected static ?string $label = 'Publication';
    protected static ?int $navigationSort = 1;

public static function getNavigationBadge(): ?string
{
    $user = Auth::user();

    // Check if the user is a super-admin or admin
    if ($user->hasRole(['super-admin', 'admin'])) {
        return (string) \App\Models\Publication::count();
    }

    if ($user) {
        return (string) \App\Models\Publication::where('user_id', $user->id)->count();
    }

    return null;  
    }

    public static function getNavigationBadgeColor(): string
    {
        return 'secondary';  
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Section 1 of 5')
                    ->schema([
                        Radio::make('contributing_unit')
                            ->label('Contributing Unit')
                            ->options([
                                'CISC' => 'CISC',
                                'CSPPS' => 'CSPPS',
                                'CPAF' => 'CPAF',
                                'IGRD' => 'IGRD',
                            ])
                            ->required(),

                        Radio::make('type_of_publication')
                            ->label('Type of Publication')
                            ->options([
                                'Book/Monograph' => 'Book/Monograph',
                                'Book Chapter (Edited/Peer-Reviewed)' => 'Book Chapter (Edited/Peer-Reviewed)',
                                'Paper Publication (Peer-Reviewed/Refereed)' => 'Paper Publication (Peer-Reviewed/Refereed)',
                                'Paper Publication (Indexed Journal)' => 'Paper Publication (Indexed Journal)',
                                'Journal Article (Peer-Reviewed)' => 'Journal Article (Peer-Reviewed)',
                                'Other' => 'Other...',
                            ])
                            ->required(),

                        TextInput::make('other_type')
                            ->label('Other Type (if applicable)')
                            ->placeholder('Specify other type')
                            ->maxLength(255)
                            ->hidden(fn ($get) => $get('type_of_publication') !== 'Other'),

                        TextInput::make('title_of_publication')
                            ->label('Title of Publication')
                            ->placeholder('Enter the title of the publication')
                            ->required()
                            ->maxLength(255),

                        Textarea::make('co_authors')
                            ->label('Co-author(s)')
                            ->placeholder('Specify the lead author first. Separate co-authors with semi-colons.')
                            ->helperText('Example: John Doe; Jane Smith; Alex Johnson')
                            ->required()
                            ->rows(3),
                    ]),

                    Section::make('Section 2 of 5')
                    ->schema([
                        Textarea::make('research_conference_publisher_details')
                            ->label('Research/Conference/Publisher details')
                            ->placeholder('Description (optional)')
                            ->helperText('Do not leave the box blank. Please put "NA" if you have no answers. Thank you!')
                            ->required(),

                        Textarea::make('study_research_project')
                            ->label('Study/Research Project where the publication resulted from')
                            ->placeholder('Enter details')
                            ->rows(3),

                        Textarea::make('journal_book_conference')
                            ->label('Name of Journal/Book/Conference Publication')
                            ->placeholder('Enter the name')
                            ->rows(3),

                        TextInput::make('publisher_organizer')
                            ->label('Publisher/Name of Organizer')
                            ->placeholder('Enter the name')
                            ->maxLength(255),

                        Radio::make('type_of_publisher')
                            ->label('Type of Publisher')
                            ->options([
                                'Commercial' => 'Commercial',
                                'Learned Society and Association' => 'Learned Society and Association',
                                'University Press' => 'University Press',
                            ])
                            ->required(),

                        Radio::make('location_of_publisher')
                            ->label('Location of Publisher')
                            ->options([
                                'Local' => 'Local',
                                'International' => 'International',
                            ])
                            ->required(),

                        Textarea::make('editors')
                            ->label('Name of Editor(s)')
                            ->placeholder('Separate editors\' names with semi-colons.')
                            ->helperText('Example: John Doe; Jane Smith; Alex Johnson')
                            ->rows(3),

                        TextInput::make('volume_issue')
                            ->label('Volume No. and Issue No.')
                            ->placeholder('Enter volume and issue number')
                            ->maxLength(255),

                        DatePicker::make('date_published')
                            ->label('Date Published or Accepted for Publication')
                            ->placeholder('Select date')
                            ->required(),

                        DatePicker::make('conference_start_date')
                            ->label('Conference START Date')
                            ->placeholder('Select start date'),

                        DatePicker::make('conference_end_date')
                            ->label('Conference END Date')
                            ->placeholder('Select end date'),

                        Textarea::make('conference_venue')
                            ->label('Conference Venue, City, and Country')
                            ->placeholder('Enter location details')
                            ->rows(3),

                        TextInput::make('doi_or_link')
                            ->label('DOI if any or Link')
                            ->placeholder('Specify DOI or URL')
                            ->maxLength(255),

                        TextInput::make('isbn_issn')
                            ->label('ISBN or ISSN')
                            ->placeholder('Enter ISBN or ISSN')
                            ->maxLength(255),
                    ]),

                Section::make('Section 3 of 5')
                    ->schema([
                        Textarea::make('collection_database')
                            ->helperText('Indicate the Collection/Database where this Journal/Book/Conference Publication has been indexed/catalogued/recognized:
                                    (Do not leave the box blank. Please put "NA" if you have no answers. Thank you)')
                            ->placeholder('Description (optional)')
                            ->required(),

                        Radio::make('web_science')
                            ->label('Web Science (formerly ISI)')
                            ->options([
                                'YES' => 'YES',
                                'NO' => 'NO',
                            ])
                            ->required(),

                        Radio::make('scopus')
                            ->label("Elsevier's Scopus")
                            ->options([
                                'YES' => 'YES',
                                'NO' => 'NO',
                            ])
                            ->required(),

                        Radio::make('science_direct')
                            ->label("Elsevier's ScienceDirect")
                            ->options([
                                'YES' => 'YES',
                                'NO' => 'NO',
                            ])
                            ->required(),

                        Radio::make('pubmed')
                            ->label('PubMed/MEDLINE')
                            ->options([
                                'YES' => 'YES',
                                'NO' => 'NO',
                            ])
                            ->required(),

                        Radio::make('ched_journals')
                            ->label('CHED-Recognized Journals')
                            ->options([
                                'YES' => 'YES',
                                'NO' => 'NO',
                            ])
                            ->required(),

                        Textarea::make('other_database')
                            ->label('Other Reputable Collection/Database')
                            ->placeholder('Leave blank if there is no such other database.'),

                        TextInput::make('citations')
                            ->label('Number of Citations')
                            ->numeric()
                            ->placeholder('If none, please put zero (0).')
                            ->required(),
                    ]),

                Section::make('Section 4 of 5')
                    ->schema([
                        Placeholder::make('proofs_instruction')
                            ->label('Proofs Instruction')
                            ->helperText('Kindly input the link to the proofs below.
                                       Do not leave the box blank. Please put "NA" if you have no answers. Thank you!')
                            ->columnSpan('full'),

                        TextInput::make('pdf_proof_1')
                            ->label('PDF Image File 1 (Proof of Publication)')
                            ->placeholder('Input the link to the proof')
                            ->required(),

                        TextInput::make('pdf_proof_2')
                            ->label('PDF Image File 2 (Proof of Utilization)')
                            ->placeholder('Input the link to the proof (if applicable)'),
                    ]),

                Section::make('Section 5 of 5')
                    ->schema([
                        Placeholder::make('awards_instruction')
                            ->label('Awards Instruction')
                            ->helperText('Do not leave the box blank.
                                       Please put "NA" if you have no answers. Thank you!')
                            ->columnSpan('full'),

                        Radio::make('received_award')
                            ->label('Received Award')
                            ->options([
                                'YES' => 'YES',
                                'NO' => 'NO',
                            ])
                            ->required(),

                        Textarea::make('award_title')
                            ->label('Award Title')
                            ->placeholder('Enter the award title')
                            ->nullable(),

                        DatePicker::make('date_awarded')
                            ->label('Date Awarded')
                            ->nullable(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('collection_database')->label('Collection Database')->searchable(),
                TextColumn::make('citations')->label('Citations')->sortable(),

                BadgeColumn::make('received_award')
                    ->label('Received Award')
                    ->colors([
                        'success' => 'YES',
                        'danger' => 'NO',
                    ])
                    ->formatStateUsing(fn ($state) => $state === 'YES' ? 'Yes' : 'No'),

                TextColumn::make('date_awarded')->label('Date Awarded')->date(),
            ])
            ->filters([])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
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
