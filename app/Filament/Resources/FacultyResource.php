<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FacultyResource\Pages;
use App\Filament\Resources\FacultyResource\RelationManagers;
use App\Filament\Exports\FacultyExporter;
use App\Models\Faculty;
use Filament\Forms;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms\Form;
use Filament\Tables\Actions\Action;
use Filament\Resources\Resource;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\ExportBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FacultyResource extends Resource
{
    protected static ?string $model = User::class;
    
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Faculty and REPS';
    protected static ?string $navigationGroup = 'Staff and Faculty';

    public static function getNavigationBadge(): ?string
    {
        $facultyCount = static::$model::where('staff', 'faculty')->count();
        $staffCount = static::$model::where('staff', 'staff')->count();
    
        return $facultyCount + $staffCount;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('staff', 'faculty');
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
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('last_name'),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('role'),
            ])
            ->filters([
             


            ])
            ->actions([
              
            ])
            ->headerActions([
                Action::make('Export')
                ->action(function () {
                    $users = User::where('staff', 'faculty')->get();
                    
                    $pdf = Pdf::loadView('exports.faculty', compact('users'));

                    return response()->streamDownload(
                        fn () => print($pdf->output()),
                        'faculty_list.pdf'
                    );
                })
            ])
            ->bulkActions([
                
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
            'index' => Pages\ListFaculties::route('/'),
            //'create' => Pages\CreateFaculty::route('/create'),
            'view' => Pages\ViewFaculty::route('/{record}'),
            //'edit' => Pages\EditFaculty::route('/{record}/edit'),
        ];
    }
}
