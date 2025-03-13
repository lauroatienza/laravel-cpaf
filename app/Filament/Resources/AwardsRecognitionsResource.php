<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AwardsRecognitionsResource\Pages;
use App\Models\AwardsRecognitions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Select;

class AwardsRecognitionsResource extends Resource
{
    protected static ?string $model = \App\Models\AwardsRecognitions::class;

    protected static ?string $navigationLabel = 'Awards/Recognitions';

    protected static ?string $navigationGroup = 'Awards';
    protected static ?string $navigationIcon = 'heroicon-o-trophy';
    protected static ?int $navigationSort = 3;

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
    ->schema([
        Select::make('award_type')
            ->label('Type of Award')
            ->required()
            ->options([
                'International Publication Awards' => 'International Publication Awards',
                'Other Notable Awards' => 'Other Notable Awards',
            ])
            ->reactive(),

        TextInput::make('award_title')
            ->label('Title of Paper or Award')
            ->helperText('Please include title if Publication or Presentation')
            ->required()
            ->maxLength(255),

        TextInput::make('awardee_name')
            ->label('Name(s) of Awardee/Recipient')
            ->required()
            ->maxLength(255),

        TextInput::make('granting_organization')
            ->label('Granting Organization')
            ->required()
            ->maxLength(255),

        DatePicker::make('date_awarded')
            ->label('Date Awarded')
            ->required(),
    ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('award_type')
                    ->label('Type of Award')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('award_title')
                    ->label('Title of Paper or Award')
                    ->sortable()
                    ->searchable()
                    ->limit(15) // Only show first 20 characters
                ->tooltip(fn ($state) => $state),

                TextColumn::make('awardee_name')
                    ->label('Name(s) of Awardee/Recipient')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('date_awarded')
                    ->label('Date Awarded')
                    ->date()
                    ->sortable()
                    ->placeholder('N/A'),
            ])
            ->filters([
                // Add filters if needed
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAwardsRecognitions::route('/'),
            'create' => Pages\CreateAwardsRecognitions::route('/create'),
            'edit' => Pages\EditAwardsRecognitions::route('/{record}/edit'),
        ];
    }
}
