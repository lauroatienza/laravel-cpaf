<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdministrationResource\Pages;
use App\Filament\Resources\AdministrationResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Forms\Components\TextInput;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AdministrationResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-bookmark-square';
    protected static ?string $navigationLabel = 'Admins';
    protected static ?string $navigationGroup = 'Staff and Faculty';

    public static function getNavigationBadge(): ?string
    {
        return static::$model::where('role','admin')->count();
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('role', 'admin');
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
            ]);
    }
    public static function canCreate(): bool
{
    return false;
}
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('First Name')->searchable()->sortable(),
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
            ->headerActions([
                Action::make('Export')
                ->action(function () {
                    $users = User::where('role', 'admin')->get();
                    
                    $pdf = Pdf::loadView('exports.faculty', compact('users'));

                    return response()->streamDownload(
                        fn () => print($pdf->output()),
                        'faculty_list.pdf'
                    );
                })
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
