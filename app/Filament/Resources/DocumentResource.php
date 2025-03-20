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

class DocumentResource extends Resource
{
    protected static ?string $model = Document::class;
    protected static ?string $navigationGroup = 'Documents';
    protected static ?string $navigationLabel = 'MOU and MOA';
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
                    'MOU' => 'Memorandum of Understanding (MOU)',
                    'MOA' => 'Memorandum of Agreement (MOA)',
                ])
                ->required(),

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
        return $table->columns([
            TextColumn::make('contributing_unit')->sortable(),
            BadgeColumn::make('partnership_type')->sortable(),
            TextColumn::make('extension_title')->searchable(),
            TextColumn::make('partner_stakeholder')->searchable(),
            TextColumn::make('start_date')->sortable()->date(), 
            TextColumn::make('end_date')->sortable()->date(), 
            BadgeColumn::make('training_courses')
                ->colors(['success' => 'Yes', 'danger' => 'No']),
                BadgeColumn::make('technical_advisory_service')
                ->colors(['success' => 'Yes', 'danger' => 'No'])
                ->label('Technical/Advisory Service'),
            BadgeColumn::make('information_dissemination')
                ->colors(['success' => 'Yes', 'danger' => 'No'])
                ->label('Information Dissemination'),
            BadgeColumn::make('consultancy')
                ->colors(['success' => 'Yes', 'danger' => 'No'])
                ->label('Consultancy'),
            BadgeColumn::make('community_outreach')
                ->colors(['success' => 'Yes', 'danger' => 'No'])
                ->label('Community Outreach'),
            BadgeColumn::make('technology_transfer')
                ->colors(['success' => 'Yes', 'danger' => 'No'])
                ->label('Technology Transfer'),
            BadgeColumn::make('organizing_events')
                ->colors(['success' => 'Yes', 'danger' => 'No'])
                ->label('Organizing Events'),
            TextColumn::make('pdf_file_url')
                ->label('PDF File')
                ->formatStateUsing(fn ($state) => "<a href='{$state}' target='_blank' style='color: blue; text-decoration: underline;'>View PDF</a>")
                ->html(),
        ])
        ->filters([])
        ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
        ->bulkActions([Tables\Actions\DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDocuments::route('/'),
            'create' => Pages\CreateDocument::route('/create'),
            'edit' => Pages\EditDocument::route('/{record}/edit'),
        ];
    }

    
    
}

