<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExtensionPrimaryResource\Pages;
use App\Models\ExtensionPrime;
use Doctrine\DBAL\Query\Limit;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Columns\BadgeColumn;

class ExtensionPrimaryResource extends Resource
{
    protected static ?string $model = ExtensionPrime::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationGroup = 'Programs';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationLabel = 'Extension';
    protected static ?string $modelLabel = 'Extension';
    protected static ?string $pluralModelLabel = 'Extensions';

    public static function getNavigationBadge(): ?string
    {
        $user = Auth::user();

        if ($user->hasRole(['super-admin', 'admin'])) {
            return static::$model::count();
        }

        $fullName = trim("{$user->name} " . ($user->middle_name ? "{$user->middle_name} " : "") . "{$user->last_name}");
        $fullNameReversed = trim("{$user->last_name}, {$user->name}" . ($user->middle_name ? " {$user->middle_name}" : ""));
        $simpleName = trim("{$user->name} {$user->last_name}");

        // Format: Lapitan, A.V.
        $initials = strtoupper(substr($user->name, 0, 1)) . '.';
        if ($user->middle_name) {
            $initials .= strtoupper(substr($user->middle_name, 0, 1)) . '.';
        }
        $reversedInitialsName = "{$user->last_name}, {$initials}";

        $titles = ['Dr.', 'Prof.', 'Engr.', 'Sir', 'Ms.', 'Mr.', 'Mrs.'];

        // Normalize function
        $normalizeName = function ($name) use ($titles, $user) {
            $nameWithoutTitles = str_ireplace($titles, '', $name);

            if ($user->middle_name) {
                $middleNameInitial = strtoupper(substr($user->middle_name, 0, 1)) . '.';
                $nameWithoutTitles = str_ireplace($user->middle_name, $middleNameInitial, $nameWithoutTitles);
            }

            return preg_replace('/\s+/', ' ', trim($nameWithoutTitles));
        };

        // Normalized name variations
        $normalizedFullName = $normalizeName($fullName);
        $normalizedFullNameReversed = $normalizeName($fullNameReversed);
        $normalizedSimpleName = $normalizeName($simpleName);
        $normalizedReversedInitials = $normalizeName($reversedInitialsName);

        // SQL-friendly column name replacements
        $columns = ['researcher_names', 'project_leader'];
        $replacers = [];

        foreach ($columns as $column) {
            $colReplacer = $column;
            foreach ($titles as $title) {
                $colReplacer = "REPLACE($colReplacer, '$title', '')";
            }
            $replacers[$column] = $colReplacer;
        }

        // Main query with all normalized formats and both columns
        return static::$model::where(function ($query) use (
            $replacers,
            $normalizedFullName,
            $normalizedFullNameReversed,
            $normalizedSimpleName,
            $normalizedReversedInitials
        ) {
            foreach ($replacers as $column => $replacer) {
                $query->orWhereRaw("LOWER($replacer) LIKE LOWER(?)", ["%$normalizedFullName%"])
                    ->orWhereRaw("LOWER($replacer) LIKE LOWER(?)", ["%$normalizedFullNameReversed%"])
                    ->orWhereRaw("LOWER($replacer) LIKE LOWER(?)", ["%$normalizedSimpleName%"])
                    ->orWhereRaw("LOWER($replacer) LIKE LOWER(?)", ["%$normalizedReversedInitials%"]);
            }
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
                Select::make('contributing_unit')->label('Contributing Unit')
                ->options([
                    'CSPPS' => 'CSPPS',
                    'CISC' => 'CISC',
                    'CPAf' => 'CPAf',
                    'IGRD' => 'IGRD',
                ])->required(),

                DatePicker::make('start_date')
                ->label('Start Date (mm/dd/yyyy)')
                ->format('Y/m/d')->required(),

                DatePicker::make('end_date')
                ->label('End Date based on actual completion')
                ->format('Y/m/d')->required(),

                DatePicker::make('extension_date')
                ->label('Extension Date')
                ->format('Y/m/d'),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        'Completed' => 'Completed',
                        'On-going' => 'On-going',
                    ])->required(),

                TextInput::make('title_of_extension_program')
                ->label('Title of Extension Program')
                ->required(),

                Textarea::make('objectives')
                ->label('Objectives'),

                Textarea::make('expected_output')
                ->label('Expected Output/Scope of Work'),

                TextInput::make('original_timeframe_months')
                ->label('Number of Months in Original Timeframe')
                ->numeric(),

                TextInput::make('researcher_names')
                ->label('Name of Researcher/s or Extensionist')
                ->required(),

                TextInput::make('project_leader')->label('Project Leader'),

                TextInput::make('source_of_funding')->label('Source of Funding'),
                TextInput::make('budget')->label('Budget')->numeric(),
                TextInput::make('type_of_funding')->label('Type of Funding'),
                TextInput::make('fund_code')->label('Fund Code'),

                TextInput::make('pdf_image_file')->label('PDF Image File')->placeholder('Input URL here'),

                Textarea::make('training_courses')->label('Training Courses (non-degree and non-credit)'),
                Textarea::make('technical_service')->label('Technical/Advisory Service for external clients'),
                Textarea::make('info_dissemination')->label('Information Dissemination/Communication through mass media'),
                Textarea::make('consultancy_service')->label('Consultancy for external clients'),
                Textarea::make('community_outreach')->label('Community Outreach or Public Service'),
                Textarea::make('knowledge_transfer')->label('Technology or Knowledge Transfer'),
                Textarea::make('organizing_events')->label('Organizing such as symposium, forum, exhibit, etc.'),

                Textarea::make('benefited_academic_programs')->label('Academic Degree Programs Benefited'),

                TextInput::make('target_beneficiary_count')->label('Number of Target Beneficiary Groups or Persons Served')->numeric(),
                TextInput::make('target_beneficiary_group')->label('Target Beneficiary Group'),
                TextInput::make('funding_source')->label('Source of Majority Share of Funding for this Training'),
                Textarea::make('role_of_unit')->label('Role of Unit and Total Hrs. Spent'),

                TextInput::make('unit_theme')->label('Unit Theme'),
                TextInput::make('sdg_theme')->label('SDG Theme'),
                TextInput::make('agora_theme')->label('AGORA Theme'),
                TextInput::make('cpaf_re_theme')->label('CPAf R&E Theme (GoRABeLS)'),

                Select::make('ccam_initiatives')->label('Change and Mitigation (CCAM) Initiatives (Y/N)')
                    ->options(['Y' => 'Yes', 'N' => 'No']),
                Select::make('drrms')->label('Disaster Risk Reduction and Management Service (DRRMS) (Y/N)')
                    ->options(['Y' => 'Yes', 'N' => 'No']),

                Textarea::make('project_article')->label('Project Article'),
                TextInput::make('pbms_upload_status')->label('PBMS Uploading Status'),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('contributing_unit')->label('Contributing Unit')->sortable()->searchable(),
                TextColumn::make('start_date')->label('Start Date')->sortable(),
                TextColumn::make('end_date')->label('End Date')->sortable(),
                TextColumn::make('extension_date')->label('Extension Date')->sortable(),

                BadgeColumn::make('status')
                    ->label('Status')
                    ->color(fn ($state) => match ($state) {
                        'Completed' => 'success',
                        'On-going' => 'warning',
                        default => 'secondary',
                    }),

                TextColumn::make('title_of_extension_program')->label('Title of Extension Program')->sortable()->searchable()->limit(50)->tooltip(fn ($state) => $state),
                TextColumn::make('objectives')->label('Objectives')->limit(50)->searchable(),
                TextColumn::make('expected_output')->label('Expected Output/Scope of Work')->limit(50)->searchable(),

                TextColumn::make('original_timeframe_months')->label('Number of Months')->sortable(),

                TextColumn::make('researcher_names')->label('Name of Researcher/s')->sortable()->searchable()->limit(50)->tooltip(fn ($state) => $state),
                TextColumn::make('project_leader')->label('Project Leader')->sortable()->searchable(),

                TextColumn::make('source_of_funding')->label('Source of Funding')->sortable(),
                TextColumn::make('budget')->label('Budget')->sortable(),
                TextColumn::make('type_of_funding')->label('Type of Funding')->sortable(),
                TextColumn::make('fund_code')->label('Fund Code')->sortable(),

                TextColumn::make('pbms_upload_status')->label('PBMS Uploading Status')->sortable(),
            ])
            ->filters([])

            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Create Extension Program')
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
                    ->action(fn (array $data) => static::exportData(ExtensionPrime::all(), $data['format'])),
            ])

            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])

            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),

                BulkAction::make('exportBulk')
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
            ]);
    }

    public static function exportData($records, $format)
    {
        if ($records->isEmpty()) {
            return back()->with('error', 'No records selected for export.');
        }

        if ($format === 'csv') {
            return response()->streamDownload(function () use ($records) {
                $handle = fopen('php://output', 'w');
                fputcsv($handle, [
                    'Contributing Unit',
                    'Start Date',
                    'End Date',
                    'Extension Date',
                    'Status',
                    'Title of Extension Program',
                    'Objectives',
                    'Expected Output/Scope of Work',
                    'Number of Months',
                    'Name of Researchers',
                    'Project Leader',
                    'Source of Funding',
                    'Budget',
                    'Type of Funding',
                    'Fund Code',
                    'PBMS Upload Status',
                ]);

                foreach ($records as $record) {
                    fputcsv($handle, [
                        $record->contributing_unit,
                        $record->start_date,
                        $record->end_date,
                        $record->extension_date,
                        $record->status,
                        $record->title_of_extension_program,
                        $record->objectives,
                        $record->expected_output,
                        $record->original_timeframe_months,
                        $record->researcher_names,
                        $record->project_leader,
                        $record->source_of_funding,
                        $record->budget,
                        $record->type_of_funding,
                        $record->fund_code,
                        $record->pbms_upload_status,
                    ]);
                }

                fclose($handle);
            }, 'extension_programs.csv');
        }

        if ($format === 'pdf') {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('exports.extensionprimary', ['records' => $records]);
            return response()->streamDownload(fn () => print($pdf->output()), 'extensionprimary.pdf');
        }
    }


    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExtensionPrimaries::route('/'),
            'create' => Pages\CreateExtensionPrimary::route('/create'),
            'edit' => Pages\EditExtensionPrimary::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $user = Auth::user();

// If the user is an admin, return all records
if ($user->hasRole(['super-admin', 'admin'])) {
    return parent::getEloquentQuery();
}

// Build name variations
$fullName = trim("{$user->name} " . ($user->middle_name ? "{$user->middle_name} " : "") . "{$user->last_name}");
$fullNameReversed = trim("{$user->last_name}, {$user->name}" . ($user->middle_name ? " {$user->middle_name}" : ""));
$simpleName = trim("{$user->name} {$user->last_name}");
$initials = strtoupper(substr($user->name, 0, 1)) . '.';
if ($user->middle_name) {
    $initials .= strtoupper(substr($user->middle_name, 0, 1)) . '.';
}
$reversedInitialsName = "{$user->last_name}, {$initials}";

// Titles to strip from name columns
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

// Normalize name formats
$normalizedFullName = $normalizeName($fullName);
$normalizedFullNameReversed = $normalizeName($fullNameReversed);
$normalizedSimpleName = $normalizeName($simpleName);
$normalizedReversedInitials = $normalizeName($reversedInitialsName);

// Columns to check
$columns = ['researcher_names', 'project_leader'];

// Build dynamic title-free column expressions
$replacers = [];
foreach ($columns as $column) {
    $cleanedColumn = $column;
    foreach ($titles as $title) {
        $cleanedColumn = "REPLACE($cleanedColumn, '$title', '')";
    }
    $replacers[$column] = $cleanedColumn;
}

// Apply filters to both columns with all name formats
return parent::getEloquentQuery()
    ->where(function ($query) use ($replacers, $normalizedFullName, $normalizedFullNameReversed, $normalizedSimpleName, $normalizedReversedInitials) {
        foreach ($replacers as $columnExpr) {
            $query->orWhereRaw("LOWER($columnExpr) LIKE LOWER(?)", ["%$normalizedFullName%"])
                  ->orWhereRaw("LOWER($columnExpr) LIKE LOWER(?)", ["%$normalizedFullNameReversed%"])
                  ->orWhereRaw("LOWER($columnExpr) LIKE LOWER(?)", ["%$normalizedSimpleName%"])
                  ->orWhereRaw("LOWER($columnExpr) LIKE LOWER(?)", ["%$normalizedReversedInitials%"]);
        }
    });

    }
}
