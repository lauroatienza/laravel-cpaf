<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExtensionPrimaryResource\Pages;
use App\Models\ExtensionPrime;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables;
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
        return 'secondary'; 
    }    
        public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
        
                TextInput::make('contributing_unit')->label('Contributing Unit'),

                DatePicker::make('start_date')->label('Start Date (mm/dd/yyyy)'),
                DatePicker::make('end_date')->label('End Date based on actual completion (mm/dd/yyyy)'),
                DatePicker::make('extension_date')->label('Extension Date'),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        'Completed' => 'Completed',
                        'On-going' => 'On-going',
                    ]),

                TextInput::make('title_of_extension_program')->label('Title of Extension Program'),
                Textarea::make('objectives')->label('Objectives'),
                Textarea::make('expected_output')->label('Expected Output/Scope of Work'),

                TextInput::make('original_timeframe_months')->label('Number of Months in Original Timeframe'),

                TextInput::make('researcher_names')->label('Name of Researcher/s or Extensionist'),
                TextInput::make('project_leader')->label('Project Leader'),

                TextInput::make('source_of_funding')->label('Source of Funding'),
                TextInput::make('budget')->label('Budget')->numeric(),
                TextInput::make('type_of_funding')->label('Type of Funding'),
                TextInput::make('fund_code')->label('Fund Code'),

                FileUpload::make('pdf_image_file')->label('PDF Image File'),

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
                TextColumn::make('id_no')->label('ID No.')->sortable(),
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

                TextColumn::make('title_of_extension_program')->label('Title of Extension Program')->sortable()->searchable(),
                TextColumn::make('objectives')->label('Objectives')->limit(50)->searchable(),
                TextColumn::make('expected_output')->label('Expected Output/Scope of Work')->limit(50)->searchable(),

                TextColumn::make('original_timeframe_months')->label('Number of Months')->sortable(),

                TextColumn::make('researcher_names')->label('Name of Researcher/s')->sortable()->searchable(),
                TextColumn::make('project_leader')->label('Project Leader')->sortable()->searchable(),

                TextColumn::make('source_of_funding')->label('Source of Funding')->sortable(),
                TextColumn::make('budget')->label('Budget')->sortable(),
                TextColumn::make('type_of_funding')->label('Type of Funding')->sortable(),
                TextColumn::make('fund_code')->label('Fund Code')->sortable(),

                TextColumn::make('pbms_upload_status')->label('PBMS Uploading Status')->sortable(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
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
