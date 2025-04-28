<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DocumentResource\Pages;
use App\Models\Document;
use Filament\Forms;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\DateColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\UrlColumn;
use League\Csv\Writer;
use SplTempFileObject;
use Illuminate\Support\Collection;

class DocumentResource extends Resource
{
    protected static ?string $model = Document::class;
    protected static ?string $navigationGroup = 'Other Documents';
    protected static ?string $navigationLabel = 'MOU and MOA';
    protected static ?int $navigationSort = 4;
    protected static ?string $navigationIcon = 'heroicon-o-document';
    
    public static function getNavigationBadge(): ?string
    {
        return Document::count();
    }
    
    public static function getNavigationBadgeColor(): string
    {
        return 'secondary'; 
    }
    
    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Select::make('contributing_unit')
                ->options([
                    'CSPPS' => 'CSPPS',
                    'CISC' => 'CISC',
                    'IGRD' => 'IGRD',
                    'CPAf' => 'CPAf',
                ])
                ->required(),

            Select::make('partnership_type')
                ->options([
                    'Memorandum of Agreement (MOA)' => 'Memorandum of Agreement (MOA)',
                    'Memorandum of Understanding (MOU)' => 'Memorandum of Understanding (MOU)',
                ])
                ->required()
                ->label('Type of Partnership Agreement'),

            TextInput::make('extension_title')->required(),
            TextInput::make('partner_stakeholder')->required(),
            DatePicker::make('start_date')->required(),
            DatePicker::make('end_date')->required(),

            Select::make('training_courses')
            ->options([
                'Yes' => 'Yes',
                'No' => 'No',
            ])
            ->label('Training Courses (non-degree and non-degree)'),

        Select::make('technical_advisory_service')
            ->options([
                'Yes' => 'Yes',
                'No' => 'No',
            ])
            ->label('Technical/Advisory Service for external clients'),

        Select::make('information_dissemination')
            ->options([
                'Yes' => 'Yes',
                'No' => 'No',
            ])
            ->label('Information Dissemination/Communication through mass media'),

        Select::make('consultancy')
            ->options([
                'Yes' => 'Yes',
                'No' => 'No',
            ])
            ->label('Consultancy for external clients'),

        Select::make('community_outreach')
            ->options([
                'Yes' => 'Yes',
                'No' => 'No',
            ])
            ->label('Community Outreach or Public Service'),

        Select::make('technology_transfer')
            ->options([
                'Yes' => 'Yes',
                'No' => 'No',
            ])
            ->label('Technology or Knowledge Transfer to Target user/adopters in industry or the community'),
        
        Select::make('organizing_events')
            ->options([
                'Yes' => 'Yes',
                'No' => 'No',
            ])
            ->label('Organizing such as symposium, forum, exhibit, performance, conference'),

            Textarea::make('scope_of_work')->nullable(),
            TextInput::make('pdf_file_url')->label('PDF File URL')->url(),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('contributing_unit')
                    ->sortable()
                    ->label('Contributing Unit')
                    ->searchable(),
                    
                BadgeColumn::make('partnership_type')
                    ->label('Partnership Type')
                    ->sortable(),
                    
                TextColumn::make('extension_title')
                    ->searchable()
                    ->label('Extension Title')
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 30) {
                            return null;
                        }
                        return $state;
                    })
                    ->formatStateUsing(fn ($state) => strlen($state) > 30 ? substr($state, 0, 30).'...' : $state),
                    
                TextColumn::make('partner_stakeholder')
                    ->label('Partner Stakeholder')
                    ->searchable(),
                    
                TextColumn::make('start_date')
                    ->date('Y-m-d')
                    ->sortable(),
                    
                TextColumn::make('end_date')
                    ->date('Y-m-d')
                    ->sortable(),
                    
                BadgeColumn::make('training_courses')
                    ->label('Training Courses')
                    ->colors(['success' => 'Yes', 'danger' => 'No']),
                    
                BadgeColumn::make('technical_advisory_service')
                    ->label('Technical/Advisory Service')
                    ->colors(['success' => 'Yes', 'danger' => 'No']),
                    
                BadgeColumn::make('information_dissemination')
                    ->label('Info Dissemination')
                    ->colors(['success' => 'Yes', 'danger' => 'No']),
                    
                BadgeColumn::make('consultancy')
                    ->label('Consultancy')
                    ->colors(['success' => 'Yes', 'danger' => 'No']),
                    
                BadgeColumn::make('community_outreach')
                    ->label('Community Outreach')
                    ->colors(['success' => 'Yes', 'danger' => 'No']),
                    
                BadgeColumn::make('technology_transfer')
                    ->label('Technology Transfer')
                    ->colors(['success' => 'Yes', 'danger' => 'No']),
                    
                BadgeColumn::make('organizing_events')
                    ->label('Orginizing Events')
                    ->colors(['success' => 'Yes', 'danger' => 'No']),
                    
                TextColumn::make('pdf_file_url')
                    ->label('PDF')
                    ->formatStateUsing(fn ($state) => $state ? 
                        "<a href='{$state}' target='_blank' class='text-primary-600 hover:underline'>View</a>" : '')
                    ->html(),
            ])
            ->defaultSort('start_date', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('partnership_type')
                    ->options([
                        'Memorandum of Agreement (MOA)' => 'MOA',
                        'Memorandum of Understanding (MOU)' => 'MOU',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->headerActions([
                Tables\Actions\Action::make('export')
                    ->label('Export')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function (array $data) {
                        $query = Document::query();
                        
                        if ($data['type'] === 'MOA') {
                            $query->where('partnership_type', 'Memorandum of Agreement (MOA)');
                        } elseif ($data['type'] === 'MOU') {
                            $query->where('partnership_type', 'Memorandum of Understanding (MOU)');
                        }
                        
                        $records = $query->get();
                        
                        return response()->streamDownload(function () use ($records) {
                            $csv = Writer::createFromFileObject(new SplTempFileObject());
                            
                            $csv->insertOne([
                                'Unit', 'Type', 'Title', 'Partner', 
                                'Start Date', 'End Date', 'Training',
                                'Tech Service', 'Info Dissemination',
                                'Consultancy', 'Community Outreach',
                                'Tech Transfer', 'Organizing Events',
                                'Scope of Work', 'PDF URL'
                            ]);
                            
                            foreach ($records as $record) {
                                $csv->insertOne([
                                    $record->contributing_unit,
                                    str_contains($record->partnership_type, 'MOA') ? 'MOA' : 'MOU',
                                    $record->extension_title,
                                    $record->partner_stakeholder,
                                    $record->start_date,
                                    $record->end_date,
                                    $record->training_courses,
                                    $record->technical_advisory_service,
                                    $record->information_dissemination,
                                    $record->consultancy,
                                    $record->community_outreach,
                                    $record->technology_transfer,
                                    $record->organizing_events,
                                    $record->scope_of_work,
                                    $record->pdf_file_url,
                                ]);
                            }
                            
                            echo $csv->toString();
                        }, 'documents_export_' . now()->format('Ymd_His') . '.csv');
                    })
                    ->form([
                        Select::make('type')
                            ->options([
                                'ALL' => 'All Documents',
                                'MOA' => 'MOA',
                                'MOU' => 'MOU',
                            ])
                            ->default('ALL'),
                    ]),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\BulkAction::make('export')
                    ->label('Export Selected')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function (Collection $records, array $data) {
                        $filteredRecords = $records;
                        
                        if ($data['type'] === 'MOA') {
                            $filteredRecords = $records->where('partnership_type', 'Memorandum of Agreement (MOA)');
                        } elseif ($data['type'] === 'MOU') {
                            $filteredRecords = $records->where('partnership_type', 'Memorandum of Understanding (MOU)');
                        }
                        
                        return response()->streamDownload(function () use ($filteredRecords) {
                            $csv = Writer::createFromFileObject(new SplTempFileObject());
                            
                            $csv->insertOne([
                                'Unit', 'Type', 'Title', 'Partner', 
                                'Start Date', 'End Date', 'Training',
                                'Tech Service', 'Info Dissemination',
                                'Consultancy', 'Community Outreach',
                                'Tech Transfer', 'Organizing Events',
                                'Scope of Work', 'PDF URL'
                            ]);
                            
                            foreach ($filteredRecords as $record) {
                                $csv->insertOne([
                                    $record->contributing_unit,
                                    str_contains($record->partnership_type, 'MOA') ? 'MOA' : 'MOU',
                                    $record->extension_title,
                                    $record->partner_stakeholder,
                                    $record->start_date,
                                    $record->end_date,
                                    $record->training_courses,
                                    $record->technical_advisory_service,
                                    $record->information_dissemination,
                                    $record->consultancy,
                                    $record->community_outreach,
                                    $record->technology_transfer,
                                    $record->organizing_events,
                                    $record->scope_of_work,
                                    $record->pdf_file_url,
                                ]);
                            }
                            
                            echo $csv->toString();
                        }, 'selected_documents_' . now()->format('Ymd_His') . '.csv');
                    })
                    ->form([
                        Select::make('type')
                            ->options([
                                'ALL' => 'All Selected',
                                'MOA' => 'MOA',
                                'MOU' => 'MOU',
                            ])
                            ->default('ALL'),
                    ])
                    ->deselectRecordsAfterCompletion(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDocuments::route('/'),
            'create' => Pages\CreateDocument::route('/create'),
            'edit' => Pages\EditDocument::route('/{record}/edit'),
            'view' => Pages\ViewDocument::route('/{record}'),
        ];
    }
}