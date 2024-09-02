<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TrainingOrganizeResource\Pages;
use App\Filament\Resources\TrainingOrganizeResource\RelationManagers;
use App\Models\TrainingOrganize;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TrainingOrganizeResource extends Resource
{
    protected static ?string $model = TrainingOrganize::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Training Conducted';

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
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
            'index' => Pages\ListTrainingOrganizes::route('/'),
            'create' => Pages\CreateTrainingOrganize::route('/create'),
            'view' => Pages\ViewTrainingOrganize::route('/{record}'),
            'edit' => Pages\EditTrainingOrganize::route('/{record}/edit'),
        ];
    }
}
