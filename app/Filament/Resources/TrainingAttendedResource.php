<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TrainingAttendedResource\Pages;
use App\Models\TrainingAttended;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TrainingAttendedResource extends Resource
{
    protected static ?string $model = TrainingAttended::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationLabel = 'Training Attended';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationGroup = 'Programs';

    public static function getNavigationBadge(): ?string
    {
        return static::$model::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('training_title')
                    ->label('Attended Training/Seminar/Workshop/Conference Title')
                    ->required(),

                Forms\Components\TextInput::make('full_name')
                    ->label('Full Name')
                    ->required(),

                Forms\Components\Select::make('unit_center')
                    ->label('Unit/Center')
                    ->options([
                        'CSPPS' => 'CSPPS',
                        'CISC' => 'CISC',
                        'IGRD' => 'IGRD',
                        'CPAF' => 'CPAF',
                    ])
                    ->required(),

                Forms\Components\DatePicker::make('start_date')->required(),
                Forms\Components\DatePicker::make('end_date')->required(),

                Forms\Components\Select::make('category')
                    ->label('Category')
                    ->options([
                        'Workshop' => 'Workshop',
                        'Training' => 'Training',
                        'Conference' => 'Conference',
                        'Seminar' => 'Seminar',
                        'Forum' => 'Forum',
                        'Symposium' => 'Symposium',
                        'Other' => 'Other',
                    ])
                    ->live(),

                Forms\Components\TextInput::make('specific_title')
                    ->label('Specific Title')
                    ->visible(fn ($get) => $get('category') === 'Other'),

                Forms\Components\Textarea::make('highlights')
                    ->label('Highlights of Event')
                    ->rows(4)
                    ->nullable(),

                Forms\Components\Radio::make('has_gender_component')
                    ->label('Has Gender Component?')
                    ->options([
                        true => 'Yes',
                        false => 'No',
                    ])
                    ->required(),

                Forms\Components\TextInput::make('total_hours')
                    ->label('Total Hrs. Spent')
                    ->numeric()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('training_title')
                    ->label('Training Title')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('full_name')
                    ->label('Full Name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('unit_center')
                    ->label('Unit/Center')
                    ->sortable(),

                Tables\Columns\TextColumn::make('category')
                    ->sortable(),

                Tables\Columns\TextColumn::make('start_date')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('end_date')
                    ->date()
                    ->sortable(),

                Tables\Columns\BooleanColumn::make('has_gender_component')
                    ->label('Gender Component'),

                Tables\Columns\TextColumn::make('total_hours')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTrainingAttendeds::route('/'),
            'create' => Pages\CreateTrainingAttended::route('/create'),
            // 'view' => Pages\ViewTrainingAttended::route('/{record}'),
            'edit' => Pages\EditTrainingAttended::route('/{record}/edit'),
        ];
    }
}
