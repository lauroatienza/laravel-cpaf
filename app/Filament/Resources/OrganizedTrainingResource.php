<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrganizedTrainingResource\Pages;
use App\Models\OrganizedTraining;
use Filament\Forms;
use Filament\Forms\Components\{TextInput, Select, DatePicker, Textarea, FileUpload, Section, Grid, Placeholder};
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
use App\Models\ExtensionPrime;
use App\Models\Research;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;



class OrganizedTrainingResource extends Resource
{
    protected static ?string $model = OrganizedTraining::class;
    protected static ?string $navigationIcon = 'heroicon-o-puzzle-piece';
    protected static ?string $navigationGroup = 'Accomplishments';
    protected static ?string $navigationLabel = 'Training Organized';
    protected static ?int $navigationSort = 2;
    protected static ?string $label = 'Organized Training';

    public static function getNavigationBadge(): ?string
    {

        $user = Auth::user();

        if ($user->hasRole(['super-admin', 'admin'])) {
            return static::$model::count();
        }

        // Name formats
        $fullName = trim("{$user->name} " . ($user->middle_name ? "{$user->middle_name} " : "") . "{$user->last_name}");
        $fullNameReversed = trim("{$user->last_name}, {$user->name}" . ($user->middle_name ? " {$user->middle_name}" : ""));
        $simpleName = trim("{$user->name} {$user->last_name}");

        // New: Lastname, F.M. format
        $initials = strtoupper(substr($user->name, 0, 1)) . '.';
        if ($user->middle_name) {
            $initials .= strtoupper(substr($user->middle_name, 0, 1)) . '.';
        }
        $reversedInitialsName = "{$user->last_name}, {$initials}";

        // Titles to strip
        $titles = ['Dr.', 'Prof.', 'Engr.', 'Sir', 'Ms.', 'Mr.', 'Mrs.'];

        // Prepare SQL REPLACE chain
        $replacer = 'full_name';
        foreach ($titles as $title) {
            $replacer = "REPLACE($replacer, '$title', '')";
        }

        // Normalizer function
        $normalizeName = function ($name) use ($titles, $user) {
            $nameWithoutTitles = str_ireplace($titles, '', $name);

            if ($user->middle_name) {
                $middleNameInitial = strtoupper(substr($user->middle_name, 0, 1)) . '.';
                $nameWithoutTitles = str_ireplace($user->middle_name, $middleNameInitial, $nameWithoutTitles);
            }

            return preg_replace('/\s+/', ' ', trim($nameWithoutTitles));
        };

        // Normalize all formats
        $normalizedFullName = $normalizeName($fullName);
        $normalizedFullNameReversed = $normalizeName($fullNameReversed);
        $normalizedSimpleName = $normalizeName($simpleName);
        $normalizedReversedInitials = $normalizeName($reversedInitialsName);

        // Final query
        return static::$model::where(function ($query) use ($replacer, $normalizedFullName, $normalizedFullNameReversed, $normalizedSimpleName, $normalizedReversedInitials) {
            $query->whereRaw("LOWER(($replacer)) LIKE LOWER(?)", ["%$normalizedFullName%"])
                ->orWhereRaw("LOWER(($replacer)) LIKE LOWER(?)", ["%$normalizedFullNameReversed%"])
                ->orWhereRaw("LOWER(($replacer)) LIKE LOWER(?)", ["%$normalizedSimpleName%"])
                ->orWhereRaw("LOWER(($replacer)) LIKE LOWER(?)", ["%$normalizedReversedInitials%"]);
        })->count();

    }

    public static function getNavigationBadgeColor(): string
    {
        return 'primary';
    }

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Section::make('Training Details')
                    ->schema([
                        TextInput::make('full_name')
                            ->label('Full Name (First Name MI. Last Name)')
                            ->default(function () {
                                $name = Auth::user()->name . ' ' . Auth::user()->last_name;
                                $titles = ['Dr.', 'Prof.', 'Engr.', 'Sir', 'Ms.', 'Mr.', 'Mrs.'];
                                $cleaned = str_ireplace($titles, '', $name);
                                return preg_replace('/\s+/', ' ', trim($cleaned));
                            })
                            ->formatStateUsing(fn ($state) => preg_replace('/\s+/', ' ', trim($state)))
                            ->dehydrated(),

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
                        TextInput::make('total_trainees')->label('Total Trainees')->helperText('Formula: Total Number of Trainees X Weight Value.
                    Weight Value: (<8 hours = 0.5; 8 hours (1 day) = 1, 3-4 days = 1.5; 5 days or (discontinued)')->numeric(),//->required(),
                        TextInput::make('weighted_trainees')->label('Weighted Trainees')->numeric(),
                        TextInput::make('training_hours')->label('Training Hours')->numeric(),//->required(),
                        Select::make('funding_source')->label('Funding Source')
                            ->options([
                                'UP Entity' => 'UP Entity',
                                'RP Government Entity or Public Sector Entity' => 'RP Government Entity or Public Sector Entity',
                                'RP Private Sector Entity' => 'RP Private Sector Entity',
                                'Foreign or Non-Domestic Entity' => 'Foreign or Non-Domestic Entity',
                            ])
                        //->required(),
                    ]),

                Section::make('Survey Responses')
                    ->schema([
                        TextInput::make('sample_size')->label('Sample Size')->numeric(),
                        Grid::make('5')->schema([
                            TextInput::make('responses_poor')->label('Poor/Below Fair')->numeric(),
                            TextInput::make('responses_fair')->label('Fair')->numeric(),
                            TextInput::make('responses_satisfactory')->label('Satisfactory')->numeric(),
                            TextInput::make('responses_very_satisfactory')->label('Very Satisfactory')->numeric(),
                            TextInput::make('responses_outstanding')->label('Outstanding')->numeric(),
                        ])
                    ]),


                Section::make('Supporting Documents')
                    ->schema([
                        Select::make('related_extension_program')
                        ->label('Extension Program')
                        ->options(function (?Model $record) {
                            if (!$record) {
                                // No record yet, maybe during creation — return all or empty
                                return [];
                            }
            
                            return ExtensionPrime::where('contributing_unit', $record->contributing_unit)
                                ->pluck('title_of_extension_program', 'id');
                        })
                        ->searchable(),
    
                        TextInput::make('pdf_file_1')->label('PDF File 1')->placeholder('Proof of conduct of training such as schedule, attendance sheets and activity reports must be properly endorsed by the Dean/Head of Unit.'),
                        TextInput::make('pdf_file_2')->label('PDF File 2')->placeholder('In a single PDF file, maximum of ten (10) survey questionnaires with actual responses or feedback.'),
                        TextInput::make('relevant_documents')->label('Documents Link'),
                        TextInput::make('project_title')
                            ->label('Project Title')

                    ]),

                    // Select::make('related_research_program')
                  //  ->label('Related Research Program')
                  //  ->options(function (?Model $record) {
                 //       if (!$record) {
                            // No record yet, maybe during creation — return all or empty
                    //        return [];
                    //    }
        //
                 //       return Research::where('contributing_unit', $record->contributing_unit)
                 //           ->pluck('title', 'id');
                  //  })
                //    ->searchable(),
                

            ]);
    }


    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                //TextColumn::make('full_name')->label('Full Name')->searchable()->tooltip(fn($state) => $state),
                BadgeColumn::make('contributing_unit')->label('Contributing Unit')->alignCenter()->sortable(),
                TextColumn::make('title')->label('Title')->searchable()->limit(30)->sortable()->tooltip(fn($state) => $state),
                TextColumn::make('start_date')->label('Start Date')->date('Y-m-d')->sortable(),
                TextColumn::make('end_date')->label('End Date')->date('Y-m-d')->sortable(),
                

            ])
            ->filters([
                SelectFilter::make('contributing_unit')
                    ->label('User Classification')
                    ->options([
                        'CISC' => 'CISC',
                        'CPAf' => 'CPAf',
                        'CSPPS' => 'CSPPS',
                        'IGRD' => 'IGRD',
                    ])
                    ->query(function ($query, $data) {
                        if ($data['value'] === 'CISC') {
                            return $query->where('contributing_unit', 'CISC');
                        } elseif ($data['value'] === 'CPAf') {
                            return $query->where('contributing_unit', 'CPAf');
                        } elseif ($data['value'] === 'CSPPS') {
                            return $query->where('contributing_unit', 'CSPPS');
                        }
                        elseif ($data['value'] === 'IGRD') {
                            return $query->where('contributing_unit', 'IGRD');
                        }
                        return $query;
                    }),
            ])
            ->actions([
                Actions\ViewAction::make(),
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
                    ->action(fn(array $data, $records) => static::exportData($records, $data['format'])),
            ])
            ->selectable();

    }


    public static function exportData($records, $format)
    {
        $user = Auth::user();
    
        if (!$user->hasRole(['super-admin', 'admin'])) {
            $fullName = trim("{$user->name} " . ($user->middle_name ? "{$user->middle_name} " : "") . "{$user->last_name}");
            $fullNameReversed = trim("{$user->last_name}, {$user->name}" . ($user->middle_name ? " {$user->middle_name}" : ""));
            $simpleName = trim("{$user->name} {$user->last_name}");
            $initials = strtoupper(substr($user->name, 0, 1)) . '.';
            if ($user->middle_name) {
                $initials .= strtoupper(substr($user->middle_name, 0, 1)) . '.';
            }
            $reversedInitialsName = "{$user->last_name}, {$initials}";
    
            $titles = ['Dr.', 'Prof.', 'Engr.', 'Sir', 'Ms.', 'Mr.', 'Mrs.'];
            $normalizeName = function ($name) use ($titles, $user) {
                $nameWithoutTitles = str_ireplace($titles, '', $name);
                if ($user->middle_name) {
                    $middleInitial = strtoupper(substr($user->middle_name, 0, 1)) . '.';
                    $nameWithoutTitles = str_ireplace($user->middle_name, $middleInitial, $nameWithoutTitles);
                }
                return preg_replace('/\s+/', ' ', trim($nameWithoutTitles));
            };
    
            $nameVariants = [
                $normalizeName($fullName),
                $normalizeName($fullNameReversed),
                $normalizeName($simpleName),
                $normalizeName($reversedInitialsName),
            ];

            $records = $records->filter(function ($record) use ($nameVariants, $titles, $normalizeName) {
                $recordName = $normalizeName(str_ireplace($titles, '', $record->full_name ?? ''));
                foreach ($nameVariants as $variant) {
                    if (stripos($recordName, $variant) !== false) {
                        return true;
                    }
                }
                return false;
            });
        }
    
        if ($records->isEmpty()) {
            return back()->with('error', 'No records selected for export.');
        }
    
        if ($format === 'csv') {
            return response()->streamDownload(function () use ($records) {
                $handle = fopen('php://output', 'w');
                fputcsv($handle, ['Full Name', 'Title', 'Start Date', 'End Date', 'Contributing Unit']);
    
                foreach ($records as $record) {
                    fputcsv($handle, [
                        $record->full_name,
                        $record->title,
                        $record->start_date,
                        $record->end_date,
                        $record->contributing_unit,
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

    // New format: Lastname, F.M.
    $initials = strtoupper(substr($user->name, 0, 1)) . '.';
    if ($user->middle_name) {
        $initials .= strtoupper(substr($user->middle_name, 0, 1)) . '.';
    }
    $reversedInitialsName = "{$user->last_name}, {$initials}";

    // Titles to remove
    $titles = ['Dr.', 'Prof.', 'Engr.', 'Sir', 'Ms.', 'Mr.', 'Mrs.'];

    // Function to normalize names
    $normalizeName = function ($name) use ($titles, $user) {
        $nameWithoutTitles = str_ireplace($titles, '', $name);

        if ($user->middle_name) {
            $middleNameInitial = strtoupper(substr($user->middle_name, 0, 1)) . '.';
            $nameWithoutTitles = str_ireplace($user->middle_name, $middleNameInitial, $nameWithoutTitles);
        }

        return preg_replace('/\s+/', ' ', trim($nameWithoutTitles));
    };

    // Normalize each name variant
    $normalizedFullName = $normalizeName($fullName);
    $normalizedFullNameReversed = $normalizeName($fullNameReversed);
    $normalizedSimpleName = $normalizeName($simpleName);
    $normalizedReversedInitials = $normalizeName($reversedInitialsName);

    // Create full REPLACE chain for SQL title-stripping
    $replacer = 'full_name';
    foreach ($titles as $title) {
        $replacer = "REPLACE($replacer, '$title', '')";
    }

    return parent::getEloquentQuery()
        ->where(function ($query) use (
            $replacer,
            $normalizedFullName,
            $normalizedFullNameReversed,
            $normalizedSimpleName,
            $normalizedReversedInitials
        ) {
            $query->whereRaw("LOWER($replacer) LIKE LOWER(?)", ["%$normalizedFullName%"])
                  ->orWhereRaw("LOWER($replacer) LIKE LOWER(?)", ["%$normalizedFullNameReversed%"])
                  ->orWhereRaw("LOWER($replacer) LIKE LOWER(?)", ["%$normalizedSimpleName%"])
                  ->orWhereRaw("LOWER($replacer) LIKE LOWER(?)", ["%$normalizedReversedInitials%"]);
        });


}



}
