<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrganizedTrainingResource\Pages;
use App\Models\OrganizedTraining;
use Filament\Forms;
use Filament\Forms\Components\{TextInput, Select, DatePicker, Textarea, FileUpload, Section, Grid};
use Filament\Resources\Resource;
use Filament\Tables;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Columns\{TextColumn, BadgeColumn};
use Filament\Tables\Actions;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OrganizedTrainingResource extends Resource
{
    protected static ?string $model = OrganizedTraining::class;
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup = 'Accomplishments';
    protected static ?string $navigationLabel = 'Training Organized';
    protected static ?int $navigationSort = 2;

    public static function getNavigationBadge(): ?string
{
    $user = Auth::user();

    // If the user is an admin, show the total count
    if ($user->hasRole(['super-admin', 'admin'])) {
        return static::$model::count();
    }

    // Build possible name formats
    $fullName = trim("{$user->name} " . ($user->middle_name ? "{$user->middle_name} " : "") . "{$user->last_name}");
    $fullNameReversed = trim("{$user->last_name}, {$user->name}" . ($user->middle_name ? " {$user->middle_name}" : ""));
    $simpleName = trim("{$user->name} {$user->last_name}");

    // List of titles to remove
    $titles = ['Dr.', 'Prof.', 'Engr.', 'Sir', 'Ms.', 'Mr.', 'Mrs.'];

    // Function to normalize names by removing titles and extra spaces
    $normalizeName = function ($name) use ($titles) {
        return preg_replace('/\s+/', ' ', trim(str_ireplace($titles, '', $name)));
    };

    // Normalize names
    $normalizedFullName = $normalizeName($fullName);
    $normalizedFullNameReversed = $normalizeName($fullNameReversed);
    $normalizedSimpleName = $normalizeName($simpleName);

    return static::$model::where(function ($query) use ($user, $normalizedFullName, $normalizedFullNameReversed, $normalizedSimpleName) {
        $query->whereRaw("LOWER(CONCAT(TRIM(first_name), ' ', TRIM(middle_name), ' ', TRIM(last_name))) LIKE LOWER(?)", ["%$normalizedFullName%"])
              ->orWhereRaw("LOWER(CONCAT(TRIM(last_name), ', ', TRIM(first_name), ' ', TRIM(middle_name))) LIKE LOWER(?)", ["%$normalizedFullNameReversed%"])
              ->orWhereRaw("LOWER(CONCAT(TRIM(first_name), ' ', TRIM(last_name))) LIKE LOWER(?)", ["%$normalizedSimpleName%"]);
    })->count();
}

    public static function getNavigationBadgeColor(): string
    {
        return 'secondary'; 
    }

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Section::make('Training Details')
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('first_name')->label('First Name')->required(),
                            TextInput::make('middle_name')->label('Middle Name'),
                            TextInput::make('last_name')->label('Last Name')->required(),
                        ]),
                        Select::make('contributing_unit')->label('Contributing Unit')
                            ->options([
                                'CSPPS' => 'CSPPS',
                                'CISC' => 'CISC',
                                'IGRD' => 'IGRD',
                                'CPAf' => 'CPAf',
                            ])
                            ->required(),
                        TextInput::make('title')->label('Title of the Event')->required(),
                        Grid::make(2)->schema([
                            DatePicker::make('start_date')->label('Start Date')->required(),
                            DatePicker::make('end_date')->label('End Date')->required(),
                        ]),
                        Textarea::make('special_notes')->label('Special Notes'),
                        Textarea::make('resource_persons')->label('Resource Person(s)')
                            ->nullable(),
                        Select::make('activity_category')->label('Activity Category')
                            ->options([
                                'Training/Workshop' => 'Training/Workshop',
                                'Seminar/Forum/Round Table' => 'Seminar/Forum/Round Table',
                            ])
                            ->required(),
                        TextInput::make('venue')->label('Venue')->required(),
                    ]),

                Section::make('Trainee Details')
                    ->schema([
                        TextInput::make('total_trainees')->label('Total Trainees')->numeric()->required(),
                        TextInput::make('weighted_trainees')->label('Weighted Trainees')->numeric(), // No longer required
                        TextInput::make('training_hours')->label('Training Hours')->numeric()->required(),
                        Select::make('funding_source')->label('Funding Source')
                            ->options([
                                'UP Entity' => 'UP Entity',
                                'RP Government Entity or Public Sector Entity' => 'RP Government Entity or Public Sector Entity',
                                'RP Private Sector Entity' => 'RP Private Sector Entity',
                                'Foreign or Non-Domestic Entity' => 'Foreign or Non-Domestic Entity',
                            ])
                            ->required(),
                    ]),

                Section::make('Survey Responses')
                    ->schema([
                        TextInput::make('sample_size')->label('Sample Size')->numeric(),
                        TextInput::make('responses_poor')->label('Number of Responses - Poor/Below Fair')->numeric(),
                        TextInput::make('responses_fair')->label('Number of Responses - Fair')->numeric(),
                        TextInput::make('responses_satisfactory')->label('Number of Responses - Satisfactory')->numeric(),
                        TextInput::make('responses_very_satisfactory')->label('Number of Responses - Very Satisfactory')->numeric(),
                        TextInput::make('responses_outstanding')->label('Number of Responses - Outstanding')->numeric(),
                    ]),

                    Section::make('Supporting Documents')
                    ->schema([
                        TextInput::make('related_extension_program')->label('Related Extension Program, if applicable'),
                        TextInput::make('pdf_file_1')
                            ->label('PDF File 1 (Link)')
                            ->placeholder('Enter PDF link')
                            ->url(),
                        TextInput::make('pdf_file_2')
                            ->label('PDF File 2 (Link)')
                            ->placeholder('Enter PDF link')
                            ->url(),
                        TextInput::make('documents_link')->label('Documents Link'),
                        TextInput::make('project_title')->label('Project Title'),
                    ]),
                
            ]);
    }
    
public static function table(Tables\Table $table): Tables\Table
{
    return $table
        ->columns([
            TextColumn::make('first_name')->label('First Name')->searchable(),
            TextColumn::make('last_name')->label('Last Name')->searchable(),
            TextColumn::make('title')->label('Title')->searchable()
                ->limit(20)
                ->tooltip(fn ($state) => $state),
            TextColumn::make('start_date')->label('Start Date')->date('Y-m-d'),
            TextColumn::make('end_date')->label('End Date')->date('Y-m-d'),
            BadgeColumn::make('contributing_unit')->label('Contributing Unit'),
        ])
        ->filters([])
        ->headerActions([
            // Custom create button
            Tables\Actions\CreateAction::make()
                ->label('Create Organized Training')
                ->color('secondary')
                ->icon('heroicon-o-pencil-square'),
        
            Tables\Actions\Action::make('exportAll')
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
                ->action(fn (array $data) => static::exportData(OrganizedTraining::all(), $data['format'])),
        ])
        
        ->actions([
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ])
        ->bulkActions([
            Actions\DeleteBulkAction::make(),
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
    
        if ($format === 'csv') {
            return response()->streamDownload(function () use ($records) {
                $handle = fopen('php://output', 'w');
                fputcsv($handle, ['First Name', 'Last Name', 'Title', 'Start Date', 'End Date']);
    
                foreach ($records as $record) {
                    fputcsv($handle, [
                        $record->first_name,
                        $record->last_name,
                        $record->title,
                        $record->start_date,
                        $record->end_date,
                    ]);
                }
    
                fclose($handle);
            }, 'organized_trainings.csv');
        }
    
        if ($format === 'pdf') {
            $pdf = Pdf::loadView('exports.organized_trainings', ['records' => $records]);
            return response()->streamDownload(fn () => print($pdf->output()), 'organized_trainings.pdf');
        }
    }    


    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrganizedTrainings::route('/'),
            'create' => Pages\CreateOrganizedTraining::route('/create'),
            'edit' => Pages\EditOrganizedTraining::route('/{record}/edit'),
        ];
    }
    public static function getEloquentQuery(): Builder
{
    $user = Auth::user();

    // If the user is an admin, return all records
    if ($user->hasRole(['super-admin', 'admin'])) {
        return parent::getEloquentQuery();
    }

    // Build possible name formats
    $fullName = trim("{$user->name} " . ($user->middle_name ? "{$user->middle_name} " : "") . "{$user->last_name}");
    $fullNameReversed = trim("{$user->last_name}, {$user->name}" . ($user->middle_name ? " {$user->middle_name}" : ""));
    $simpleName = trim("{$user->name} {$user->last_name}");

    // List of titles to remove
    $titles = ['Dr.', 'Prof.', 'Engr.', 'Sir', 'Ms.', 'Mr.', 'Mrs.'];

    // Function to normalize names by removing titles and extra spaces
    $normalizeName = function ($name) use ($titles) {
        return preg_replace('/\s+/', ' ', trim(str_ireplace($titles, '', $name)));
    };

    // Normalize names
    $normalizedFullName = $normalizeName($fullName);
    $normalizedFullNameReversed = $normalizeName($fullNameReversed);
    $normalizedSimpleName = $normalizeName($simpleName);

    return parent::getEloquentQuery()
        ->where(function ($query) use ($user, $normalizedFullName, $normalizedFullNameReversed, $normalizedSimpleName) {
            $query->whereRaw("LOWER(CONCAT(TRIM(first_name), ' ', TRIM(middle_name), ' ', TRIM(last_name))) LIKE LOWER(?)", ["%$normalizedFullName%"])
                  ->orWhereRaw("LOWER(CONCAT(TRIM(last_name), ', ', TRIM(first_name), ' ', TRIM(middle_name))) LIKE LOWER(?)", ["%$normalizedFullNameReversed%"])
                  ->orWhereRaw("LOWER(CONCAT(TRIM(first_name), ' ', TRIM(last_name))) LIKE LOWER(?)", ["%$normalizedSimpleName%"]);
        });
}

    
    
}
