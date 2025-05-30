<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ResearchResource\Pages;
use App\Filament\Resources\ResearchResource\RelationManagers;
use App\Models\Research;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Columns\IconColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Model;
class ResearchResource extends Resource
{
    protected static ?string $model = Research::class;
    protected static ?string $navigationIcon = 'heroicon-o-beaker';
    protected static ?string $navigationGroup = 'Programs';
    protected static ?int $navigationSort = 1;
    protected static ?string $recordTitleAttribute = 'title';
    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'name_of_researchers', 'poject_leader'];
    }

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

        $normalizeName = function ($name) use ($titles, $user) {
        $nameWithoutTitles = str_ireplace($titles, '', $name);

        if ($user->middle_name) {
        // Split middle name into parts (e.g., Dela Torres → [Dela, Torres])
        $middleNameParts = explode(' ', $user->middle_name);

        // Build initials (e.g., D + T)
        $middleNameInitials = '';
        foreach ($middleNameParts as $part) {
            $middleNameInitials .= strtoupper(substr($part, 0, 1));
        }

        // Add period after initials
        $middleNameInitials .= '.';

        // Replace full middle name in the name string
        $nameWithoutTitles = str_ireplace($user->middle_name, $middleNameInitials, $nameWithoutTitles);
        }

        // Clean up extra spaces
        return preg_replace('/\s+/', ' ', trim($nameWithoutTitles));
        };

        // Normalize names
        $normalizedFullName = $normalizeName($fullName);
        $normalizedFullNameReversed = $normalizeName($fullNameReversed);
        $normalizedSimpleName = $normalizeName($simpleName);

        return static::$model::where(function ($query) use ($normalizedFullName, $normalizedFullNameReversed, $normalizedSimpleName) {
            $query->whereRaw("LOWER(REPLACE(name_of_researchers, 'Dr.', '')) LIKE LOWER(?)", ["%$normalizedFullName%"])
                ->orWhereRaw("LOWER(REPLACE(name_of_researchers, 'Dr.', '')) LIKE LOWER(?)", ["%$normalizedFullNameReversed%"])
                ->orWhereRaw("LOWER(REPLACE(name_of_researchers, 'Dr.', '')) LIKE LOWER(?)", ["%$normalizedSimpleName%"]);
        })->count();
    }

    public static function getNavigationBadgeColor(): string
    {
        return 'primary';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('contributing_unit')->label('Contributing unit')
                    ->options([
                        'CSPPS' => 'CSPPS',
                        'CISC' => 'CISC',
                        'CPAf' => 'CPAf',
                        'IGRD' => 'IGRD',
                    ])->required()->default('CPAf'),

                DatePicker::make('start_date')->label('Start Date')
                    ->format('Y/m/d')->required(),

                DatePicker::make('end_date')->label('End Date')
                    ->format('Y/m/d')->required(),

                Select::make('status')->label('Status')
                    ->options([
                        'Completed' => 'Completed',
                        'On-going' => 'On-going',
                    ])->required(),

                TextInput::make('title')->label('Title')->required(),

                DatePicker::make('extension_date')->label('Extension Date')
                    ->format('Y/m/d')->nullable(),

                RichEditor::make('event_highlight')->columnSpan('full')->label('Event Highlight')->nullable(),

                Select::make('has_gender_component')->label('Has Gender Component')
                ->options([
                    'yes' => 'Yes',
                    'no' => 'No',
                ])->default('no')->nullable(),


                RichEditor::make('objectives')->columnSpan('full')->nullable(),
                RichEditor::make('expected_output')->columnSpan('full')->label('Expected Output')->nullable(),
                TextInput::make('no_months_orig_timeframe')->label('Months No. from Original Timeframe'),
                TextInput::make('poject_leader')->required()->label('Project Leader'),
                TextInput::make('name_of_researchers')->required()->label('Name of Researchers')->placeholder('Use comma to separate names'),

                TextInput::make('source_funding')->required()->label('Source Funding'),
                Select::make('category_source_funding')->label('Source of Funding Category')
                    ->options([
                        'UP Entity' => 'UP Entity',
                        'RP Gov' => 'RP Government Entity or Public Sector Entity',
                        'RP Priv' => 'RP Private Sector Entity',
                        'Foreign Non-Dom' => 'Foreign or Non-Domestic Entity',
                    ])->required(),

                TextInput::make('budget')->numeric()->label('Budget (in Philippine Peso)'),
                Select::make('type_funding')->label('Type of Funding')
                    ->options([
                        'Externally Funded' => 'Externally Funded',
                        'UPLB Basic Research' => 'UPLB Basic Research',
                        'UP System' => 'UP System',
                        'In-house' => 'In-house',
                    ])->required(),

                FileUpload::make('pdf_image_1')->preserveFilenames()->label('PDF Image 1'),
                DatePicker::make('completed_date')->label('Completed Date')
                    ->format('Y/m/d')->nullable(),

                TextInput::make('sdg_theme')->default('N/A')->label('SDG Theme'),
                TextInput::make('agora_theme')->default('N/A')->label('AGORA Theme'),

                Select::make('climate_ccam_initiative')->label('Climate Initiative')
                    ->options([
                        'yes' => 'Yes',
                        'no' => 'No',
                    ])->required(),

                Select::make('disaster_risk_reduction')->label('Disaster Risk Reduction')
                    ->options([
                        'yes' => 'Yes',
                        'no' => 'No',
                    ])->required(),

                Select::make('flagship_theme')->label('UP Flagship Theme')
                    ->options([
                        'FP1' => 'FP1: Academic Excellence',
                        'FP2' => 'FP2: Inclusive University Admissions',
                        'FP3' => 'FP3: Innovation Hubs, S&T Parks',
                        'FP4' => 'FP4: ODeL',
                        'FP5' => 'FP5: Archipelagic & Ocean Virtual University',
                        'FP6' => 'FP6: Active and Collaborative Partnerships',
                        'FP7' => 'FP7: Arts and Culture',
                        'FP8' => 'FP8: Expansion of Public Service',
                        'FP9' => 'FP9: QMSQA',
                        'FP10' => 'FP10: Digital Transformation',
                    ])->required()->nullable(),

                Select::make('pbms_upload_status')->label('PBMS Upload Status')
                    ->options([
                        'uploaded' => 'Uploaded',
                        'pending' => 'Pending',
                    ])->required(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                BadgeColumn::make('contributing_unit')->label('Contributing Unit')
                    ->sortable()->searchable(),
                TextColumn::make('start_date')
                    ->sortable()->searchable(),
                TextColumn::make('end_date')
                    ->sortable()->searchable(),
                BadgeColumn::make('status')
                    ->sortable()
                    ->searchable()
                    ->color(fn($state) => match ($state) {
                        'Completed' => 'success',  // Green
                        'On-going' => 'warning',   // Orange
                        default => 'secondary',    // Gray
                    }),

                TextColumn::make('title')->label('Title')
                    ->sortable()->searchable()->limit(18)
                    ->tooltip(fn ($state) => $state),
                TextColumn::make('objectives')
                    ->label('Objectives')
                    ->formatStateUsing(fn ($state) => strip_tags($state))
                    ->tooltip(fn ($record) => strip_tags($record->objectives))
                    ->limit(18),
                TextColumn::make('expected_output')
                    ->label('Expected Output')
                    ->formatStateUsing(fn ($state) => strip_tags($state))
                    ->tooltip(fn ($record) => strip_tags($record->expected_output))
                    ->limit(18),
                TextColumn::make('name_of_researchers')->label("Name of Researchers")
                    ->sortable()->searchable()
                    ->limit(10) // Only show first 20 characters
                    ->tooltip(fn($state) => $state),
                TextColumn::make('poject_leader')->label("Project Leader")
                    ->sortable()->searchable()
                    ->limit(10) // Only show first 20 characters
                    ->tooltip(fn($state) => $state),
                TextColumn::make('source_funding')->label('Source of Funding')
                    ->sortable()->searchable(),
                BadgeColumn::make('category_source_funding')->label('Category of Source of Funding')
                    ->sortable()->searchable()->color('gray'),
                TextColumn::make('budget')->label('Budget')
                    ->sortable()->searchable(),
                TextColumn::make('type_funding')->label('Type of Funding')
                    ->sortable()->searchable(),
                TextColumn::make('sdg_theme')->label('Year Completed') //the column go into sdg theme sorry huhu
                    ->sortable()->searchable(),
                IconColumn::make('pbms_upload_status')->label('PMBS Upload Status')
                    ->icon(fn(string $state): string => match ($state) {
                        'uploaded' => 'heroicon-o-check-badge',
                        'pending' => 'heroicon-o-clock',
                    })


                //  $table->foreignId('faculty_id');
                //    $table->foreignId('reps_id');

                //       $table->time('extension_date');
                //      $table->text('event_highlight');
                //     $table->boolean('has_gender_component');
                //    $table->text('status');
                //   $table->text('objectives');
                //       $table->text('expected_output');
                //      $table->text('no_months_orig_timeframe');
                //     $table->text('name_of_researchers');
                //    $table->text('source_funding');
                //   $table->text('category_source_funding');
                //  $table->integer('budget');
                //   $table->text('type_funding');
                //  $table->text('pdf_image_1');
                //     $table->time('completed_date');
                //   $table->text('sdg_theme');
                //     $table->text('agora_theme');
                //     $table->boolean('climate_ccam_initiative');
                //     $table->boolean('disaster_risk_reduction');
                //     $table->text('flagship_theme');
                //    $table->text('pbms_upload_status');
                //   $table->timestamps();
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('contributing_unit')
                    ->options([
                        'CSPPS' => 'CSPPS',
                        'CISC' => 'CISC',
                        'CPAf' => 'CPAf',
                        'IGRD' => 'IGRD',
                    ]),

                Filter::make('date_range')
                    ->form([
                        DatePicker::make('start')->label('From'),
                        DatePicker::make('end')->label('Until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['start'], fn ($q, $date) => $q->whereDate('start_date', '>=', $date))
                            ->when($data['end'], fn ($q, $date) => $q->whereDate('end_date', '<=', $date));
                     }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                    BulkAction::make('exportBulk')
                        ->label('Export Selected')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->requiresConfirmation()
                        ->action(fn(array $data, $records) => static::exportData($records)),
                ]),
            ]);
    }

    public static function exportData($records)
    {
        $user = Auth::user();
    
        // Restrict non-admins to their own records
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
    
            $records = $records->filter(function ($record) use ($nameVariants, $normalizeName) {
                $researchers = $normalizeName($record->name_of_researchers ?? '');
                $leader = $normalizeName($record->project_leader ?? '');
    
                foreach ($nameVariants as $variant) {
                    if (
                        stripos($researchers, $variant) !== false ||
                        stripos($leader, $variant) !== false
                    ) {
                        return true;
                    }
                }
                return false;
            });
        }
    
        if ($records->isEmpty()) {
            return back()->with('error', 'No records selected for export.');
        }
    
        return response()->streamDownload(function () use ($records) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'Contributing Unit', 'Start Date', 'End Date', 'Status', 'Title', 'Objectives', 'Expected Output',
                'Name of Researchers', 'Project Leader', 'Source of Funding', 'Category of Source of Funding',
                'Budget', 'Type of Funding', 'SDG Theme', 'Upload Status'
            ]);
    
            foreach ($records as $record) {
                fputcsv($handle, [
                    $record->contributing_unit,
                    $record->start_date,
                    $record->end_date,
                    $record->status,
                    $record->title,
                    $record->objectives,
                    $record->expected_output,
                    $record->name_of_researchers,
                    $record->project_leader,
                    $record->source_funding,
                    $record->category_source_funding,
                    $record->budget,
                    $record->type_funding,
                    $record->sdg_theme,
                    $record->pbms_upload_status
                ]);
            }
    
            fclose($handle);
        }, 'research_data.csv');
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
            'index' => Pages\ListResearch::route('/'),
            'create' => Pages\CreateResearch::route('/create'),
            'view' => Pages\ViewResearch::route('/{record}'),
            'edit' => Pages\EditResearch::route('/{record}/edit'),
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
        // Split middle name into parts (e.g., Dela Torres → [Dela, Torres])
        $middleNameParts = explode(' ', $user->middle_name);

        // Build initials (e.g., D + T)
        $middleNameInitials = '';
        foreach ($middleNameParts as $part) {
            $middleNameInitials .= strtoupper(substr($part, 0, 1));
        }

        // Add period after initials
        $middleNameInitials .= '.';

        // Replace full middle name in the name string
        $nameWithoutTitles = str_ireplace($user->middle_name, $middleNameInitials, $nameWithoutTitles);
        }

        // Clean up extra spaces
        return preg_replace('/\s+/', ' ', trim($nameWithoutTitles));
    };

        // Normalize name formats
        $normalizedFullName = $normalizeName($fullName);
        $normalizedFullNameReversed = $normalizeName($fullNameReversed);
        $normalizedSimpleName = $normalizeName($simpleName);
        $normalizedReversedInitials = $normalizeName($reversedInitialsName);

        // Columns to check
        $columns = ['poject_leader', 'name_of_researchers'];

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
