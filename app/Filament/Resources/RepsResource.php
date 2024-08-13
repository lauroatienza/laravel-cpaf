<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RepsResource\Pages;
use App\Filament\Resources\RepsResource\RelationManagers;
use App\Models\Reps;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Forms\Components\TextInput;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RepsResource extends Resource
{
    protected static ?string $model = Reps::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Staff and Faculty';

    public static function getNavigationBadge(): ?string
    {
        return static::$model::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('first_name')->label('First Name')->required(),
                TextInput::make('last_name')->label('Last Name')->required(),
                TextInput::make('middle_name')->label('Middle Name')->required(),
                TextInput::make('designation')->label('Designation')->required(),

                Select::make('employment_status')->label('Employment Status')
                ->options([
                    'permanent' => 'Permanent',
                    'temporary' => 'Temporary',
                ])->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('first_name')->label('First Name'),
                TextColumn::make('last_name')->label('Last Name'),
                TextColumn::make('middle_name')->label('Middle Name'),
                TextColumn::make('designation')->label('Designation'),
                TextColumn::make('employment_status')->label('Employment Status'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListReps::route('/'),
            'create' => Pages\CreateReps::route('/create'),
            'view' => Pages\ViewReps::route('/{record}'),
            'edit' => Pages\EditReps::route('/{record}/edit'),
        ];
    }
}
