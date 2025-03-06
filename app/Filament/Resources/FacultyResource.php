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
    protected static ?string $navigationLabel = 'Admin, Faculty, and REPS';
    protected static ?string $navigationGroup = 'Staff and Faculty';

    // ✅ Fix: Correct badge count for all roles
    public static function getNavigationBadge(): ?string
    {
        return static::$model::whereIn('staff', ['faculty', 'representative', 'admin'])->count();
    }

    // ✅ Fix: Show records for admin, faculty, and representatives
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereIn('staff', ['faculty', 'representative', 'admin']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Define form fields here if needed
            ]);
    }

    public static function canCreate(): bool
    {
        return false;
    }
    public static function infolist(Infolist $infolist): Infolist
{
    return $infolist
        ->schema([
            TextEntry::make('name')->label('First Name'),
            TextEntry::make('last_name')->label('Last Name'),
            TextEntry::make('email')->label('Email'),
            TextEntry::make('staff')->label('Staff Type'),
        ]);
}


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('First Name')->sortable(),
                TextColumn::make('last_name')->label('Last Name')->sortable(),
                TextColumn::make('email')->sortable(),
                TextColumn::make('staff')->label('Staff')->sortable(),
            ])
            ->filters([
                // Add filters if needed
            ])
            ->actions([
                // Define actions if needed
            ])
            ->headerActions([
                // ✅ Fix: Pass correct data for export
                Action::make('Export')
                    ->action(function () {
                        $users = User::whereIn('staff', ['faculty', 'representative', 'admin'])->get();
                        $pdf = Pdf::loadView('exports.faculty', compact('users'));

                        return response()->streamDownload(
                            fn () => print($pdf->output()),
                            'faculty_list.pdf'
                        );
                    }),            ])
            ->bulkActions([
                // Define bulk actions if needed
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFaculties::route('/'),
            'view' => Pages\ViewFaculty::route('/{record}'),
        ];
    }
}
