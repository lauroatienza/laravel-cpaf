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

class PublicationResource extends Resource
{
    protected static ?string $model = Publication::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Programs';
    protected static ?string $label = 'Publication';
    protected static ?int $navigationSort = 6;

    public static function getNavigationBadge(): ?string
    {
        return static::$model::count(); 
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Section 3 of 5')
                    ->schema([
                        Textarea::make('collection_database')
                            ->label('Indicate the Collection/Database where this Journal/Book/Conference Publication has been indexed/catalogued/recognized:
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
                            ->content('Kindly input the link to the proofs below. 
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
                            ->content('Do not leave the box blank. 
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
