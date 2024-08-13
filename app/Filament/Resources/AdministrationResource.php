<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdministrationResource\Pages;
use App\Filament\Resources\AdministrationResource\RelationManagers;
use App\Models\Administration;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Forms\Components\TextInput;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AdministrationResource extends Resource
{
    protected static ?string $model = Administration::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Admins';
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
                    'Permanent' => 'Permanent',
                    'Temporary' => 'Temporary',
                ])->required(),

                Select::make('unit')->label('Unit')
                ->options([
                    'DO' => 'DO',
                    'KMO' => 'KMO',
                    'IGRD' => 'IGRD',
                    'CISC' => 'CISC',
                    'CSPPS' => 'CSPPS',
                ])->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('first_name')->label('First Name')->searchable()->sortable(),
                TextColumn::make('last_name')->label('Last Name')->searchable()->sortable(),
                TextColumn::make('middle_name')->label('Middle Name')->searchable()->sortable(),
                TextColumn::make('designation')->label('Designation')->searchable()->sortable(),
                TextColumn::make('employment_status')->label('Employment Status')->searchable()->sortable(),
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
            'index' => Pages\ListAdministrations::route('/'),
            'create' => Pages\CreateAdministration::route('/create'),
            'view' => Pages\ViewAdministration::route('/{record}'),
            'edit' => Pages\EditAdministration::route('/{record}/edit'),
        ];
    }
}
