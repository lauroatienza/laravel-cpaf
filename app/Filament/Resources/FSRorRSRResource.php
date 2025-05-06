<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FSRorRSRResource\Pages;
use App\Models\FSRorRSR;
use Filament\Forms;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Hidden;
use Barryvdh\DomPDF\Facade\Pdf;

class FSRorRSRResource extends Resource
{
    protected static ?string $model = FSRorRSR::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Other Documents';
    protected static ?string $navigationLabel = 'FSR/RSR Attachments';
    protected static ?string $modelLabel = 'FSR/RSR Attachments';
    protected static ?int $navigationSort = 4;
    protected static ?string $pluralModelLabel = 'FSR/RSR Attachments';
    protected static ?string $slug = 'fsr-or-rsr';

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->where('user_id', Auth::id());
        

        // Admins see everything
        if ($user->hasRole(['super-admin', 'admin'])) {
            return parent::getEloquentQuery();
        }
    }

    public static function getNavigationBadge(): ?string
    {
        $user = Auth::user();

        if ($user->hasRole(['super-admin', 'admin'])) {
            return static::$model::count();
        }

        // Only return records belonging to the logged-in user
        return static::$model::where('user_id', $user->id)->count();

    }

    public static function getNavigationBadgeColor(): string
    {
        return 'primary';
    }

    public static function resolveQueryUsing($query)
    {
        return $query->where('user_id', Auth::id());
    }

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Hidden::make('user_id')
                    ->default(Auth::id()),

                TextInput::make('full_name')
                    ->label('Full Name')
                    ->default(Auth::user()->name . ' ' . Auth::user()->last_name)
                    ->disabled()
                    ->required(),

                TextInput::make('year')
                    ->label('Year')
                    ->required(),

                Select::make('sem')
                    ->label('Semester')
                    ->options([
                        '1st' => '1st Semester',
                        '2nd' => '2nd Semester',
                        "Inter" => 'Inter Semester',
                    ])
                    ->required(),


                TextInput::make('drive_link')
                    ->label('Google Drive Shared Link')
                    ->placeholder('https://drive.google.com/...')
                    ->url()
                    ->required()
                    ->maxLength(500),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Name')
                    ->sortable()
                    ->searchable()
                    ->getStateUsing(fn($record) => $record->user->name . ' ' . $record->user->last_name),

                BadgeColumn::make('year')
                    ->sortable()
                    ->searchable(),

                BadgeColumn::make('sem')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('drive_link')
                    ->label('File')
                    ->formatStateUsing(fn ($record) => $record->drive_link ? '🔗 View File' : 'None')
                    ->url(fn ($record) => $record->drive_link, true)
                    ->openUrlInNewTab()
                    ->color('primary'),
                
                
            ])
            ->filters([])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Create FSR/RSR Attachment')
                    ->color('secondary')
                    ->icon('heroicon-o-pencil-square'),

                Action::make('exportAll')
                    ->label('Export')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->form([
                        Forms\Components\Select::make('format')
                            ->options([
                                'csv' => 'CSV',
                                'pdf' => 'PDF',
                            ])
                            ->label('Export Format')
                            ->required(),
                    ])
                    ->action(fn (array $data) => static::exportData(FSRorRSR::all(), $data['format'])),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('delete')
                    ->label('Delete Selected')
                    ->icon('heroicon-o-trash')
                    ->requiresConfirmation()
                    ->color('danger')
                    ->action(fn ($records) => $records->each->delete()),
            
                Tables\Actions\BulkAction::make('exportBulk')
                    ->label('Export Selected')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->requiresConfirmation()
                    ->form([
                        Forms\Components\Select::make('format')
                            ->options([
                                'csv' => 'CSV',
                                'pdf' => 'PDF',
                            ])
                            ->label('Export Format')
                            ->required(),
                    ])
                    ->action(fn (array $data, $records) => static::exportData($records, $data['format'])),
            ])
            
            ->selectable(); 
    }

    public static function exportData($records, $format)
    {
        if ($records->isEmpty()) {
            return back()->with('error', 'No records selected for export.');
        }
    
        $records = $records->load('user');
    
        if ($format === 'csv') {
            return response()->streamDownload(function () use ($records) {
                $handle = fopen('php://output', 'w');
                fputcsv($handle, ['Full Name', 'Year', 'Semester', 'Uploaded File']);
    
                foreach ($records as $record) {
                    fputcsv($handle, [
                        $record->user ? $record->user->name . ' ' . $record->user->last_name : 'N/A', 
                        $record->year,
                        $record->sem,
                        asset('storage/' . $record->file_upload),
                    ]);
                }
    
                fclose($handle);
            }, 'fsr_or_rsr_attachments.csv');
        }
    
        if ($format === 'pdf') {
            $pdf = Pdf::loadView('exports.fsr_or_rsr_attachments', ['records' => $records]);
            return response()->streamDownload(fn () => print($pdf->output()), 'fsr_or_rsr_attachments.pdf');
        }
    }    
    

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFSRorRSRs::route('/'),
            'create' => Pages\CreateFSRorRSR::route('/create'),
            'edit' => Pages\EditFSRorRSR::route('/{record}/edit'),
        ];
    }
}


