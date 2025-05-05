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
        // Remove titles
        $nameWithoutTitles = str_ireplace($titles, '', $name);

        // If the middle name exists and is not already an initial, replace it with the initial
        if ($user->middle_name) {
            $middleNameInitial = strtoupper(substr($user->middle_name, 0, 1)) . '.';
            $nameWithoutTitles = str_ireplace($user->middle_name, $middleNameInitial, $nameWithoutTitles);
        }

        // Replace multiple spaces with a single space
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
                ])->required()->default('On-going'),

                TextInput::make('title')->label('Title')->required(),

                Select::make('faculty_id')
                     ,




                DatePicker::make('extension_date')->label('Extension Date')
                    ->format('Y/m/d')->nullable(),

                RichEditor::make('event_highlight')->columnSpan('full'),

                Select::make('has_gender_component')->label('Has gender component')
                ->options([
                    'yes' => 'Yes',
                    'no' => 'No',
                ])->default('no'),



                RichEditor::make('objectives')->columnSpan('full'),
                RichEditor::make('expected_output')->columnSpan('full'),
                TextInput::make('no_months_orig_timeframe')->default('N/A')->label('Months No. from original timeframe'),
                TextInput::make('name_of_researchers')->required()->placeholder('Use comma to separate names'),

                TextInput::make('source_funding')->required(),
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

                FileUpload::make('pdf_image_1')->preserveFilenames(),
                DatePicker::make('completed_date')->label('Completed Date')
                    ->format('Y/m/d')->nullable(),

                TextInput::make('sdg_theme')->default('N/A')->label('SDG Theme'),
                TextInput::make('agora_theme')->default('N/A')->label('AGORA Theme'),

                Select::make('climate_ccam_initiative')->label('Climate Initiative')
                ->options([
                    'yes' => 'Yes',
                    'no' => 'No',
                ])->required()->default("no"),

                Select::make('disaster_risk_reduction')->label('Disaster Risk Reduction')
                ->options([
                    'yes' => 'Yes',
                    'no' => 'No',
                ])->required()->default("no"),

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
                ])->required()->default("pending"),

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
                    ->color(fn ($state) => match ($state) {
                        'Completed' => 'success',  // Green
                        'On-going' => 'warning',   // Orange
                        default => 'secondary',    // Gray
                    }),

                TextColumn::make('title')->label('Title')
                    ->sortable()->searchable()->limit(18)
                    ->tooltip(fn ($state) => $state),
                TextColumn::make('objectives')->label('Objectives')
                ->sortable()->searchable()->limit(18)
                ->tooltip(fn ($state) => $state),
                TextColumn::make('expected_output')->label('Expected Output')
                    ->sortable()->searchable()->limit(18) // Only show first 20 characters
                    ->tooltip(fn ($state) => $state),
                TextColumn::make('name_of_researchers')->label("Name of Researchers")
                    ->sortable()->searchable()
                    ->limit(10) // Only show first 20 characters
                ->tooltip(fn ($state) => $state),
                TextColumn::make('project_leader')->label("Project Leader")
                    ->sortable()->searchable()
                    ->limit(10) // Only show first 20 characters
                ->tooltip(fn ($state) => $state),
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
                IconColumn::make('pbms_upload_status')
                     ->icon(fn (string $state): string => match ($state) {
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
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Create Research Project')
                    ->color('secondary')
                    ->icon('heroicon-o-pencil-square'),

                Action::make('exportAll')
                    ->label('Export')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(fn () => static::exportData(Research::all())),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function exportData($records)
    {
        if ($records->isEmpty()) {
            return back()->with('error', 'No records selected for export.');
        }

        return response()->streamDownload(function () use ($records) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'Contributing Unit', 'Start Date', 'End Date', 'Status', 'Title', 'Objectives', 'Expected Output', 'Name of Researchers', 'Project Leader', 'Source of Funding', 'Category of Source of Funding', 'Budget', 'Type of Funding', 'SDG Theme', 'Upload Status'
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

    // Build possible name formats
    $fullName = trim("{$user->name} " . ($user->middle_name ? "{$user->middle_name} " : "") . "{$user->last_name}");
    $fullNameReversed = trim("{$user->last_name}, {$user->name}" . ($user->middle_name ? " {$user->middle_name}" : ""));
    $simpleName = trim("{$user->name} {$user->last_name}");

    // List of titles to remove
    $titles = ['Dr.', 'Prof.', 'Engr.', 'Sir', 'Ms.', 'Mr.', 'Mrs.'];

    // Function to normalize names by removing titles and converting middle name to initial
    $normalizeName = function ($name) use ($titles, $user) {
        // Remove titles
        $nameWithoutTitles = str_ireplace($titles, '', $name);

        // If the middle name exists and is not already an initial, replace it with the initial
        if ($user->middle_name) {
            $middleNameInitial = strtoupper(substr($user->middle_name, 0, 1)) . '.';
            $nameWithoutTitles = str_ireplace($user->middle_name, $middleNameInitial, $nameWithoutTitles);
        }

        // Replace multiple spaces with a single space
        return preg_replace('/\s+/', ' ', trim($nameWithoutTitles));
    };

    // Normalize names
    $normalizedFullName = $normalizeName($fullName);
    $normalizedFullNameReversed = $normalizeName($fullNameReversed);
    $normalizedSimpleName = $normalizeName($simpleName);

    return parent::getEloquentQuery()
        ->where(function ($query) use ($normalizedFullName, $normalizedFullNameReversed, $normalizedSimpleName) {
            $query->whereRaw("LOWER(REPLACE(name_of_researchers, 'Dr.', '')) LIKE LOWER(?)", ["%$normalizedFullName%"])
                  ->orWhereRaw("LOWER(REPLACE(name_of_researchers, 'Dr.', '')) LIKE LOWER(?)", ["%$normalizedFullNameReversed%"])
                  ->orWhereRaw("LOWER(REPLACE(name_of_researchers, 'Dr.', '')) LIKE LOWER(?)", ["%$normalizedSimpleName%"]);
        });
}

}
