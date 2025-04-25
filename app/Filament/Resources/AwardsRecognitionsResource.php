<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AwardsRecognitionsResource\Pages;
use App\Models\AwardsRecognitions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Auth;
use League\Csv\Writer;
use SplTempFileObject;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AwardsRecognitionsExport;
use Barryvdh\DomPDF\Facade\Pdf;


class AwardsRecognitionsResource extends Resource
{
    protected static ?string $model = AwardsRecognitions::class;

    protected static ?string $navigationLabel = 'Awards/Recognitions';

    protected static ?string $navigationGroup = 'Accomplishments';
    protected static ?string $navigationIcon = 'heroicon-o-trophy';
    protected static ?int $navigationSort = 5;


    public static function getNavigationBadgeColor(): string
    {
        return 'secondary';
    }

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
        $replacer = 'name';
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

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Select::make('award_type')
                    ->label('Type of Award')
                    ->required()
                    ->options([
                        'International Publication Awards' => 'International Publication Awards',
                        'Other Notable Awards' => 'Other Notable Awards',
                    ])
                    ->reactive(),

                TextInput::make('award_title')
                    ->label('Title of Paper or Award')
                    ->helperText('Please include title if Publication or Presentation')
                    ->required()
                    ->maxLength(255),

                TextInput::make('name')
                    ->label('Name(s) of Awardee/Recipient')
                    ->required()
                    ->maxLength(255),

                TextInput::make('granting_organization')
                    ->label('Granting Organization')
                    ->required()
                    ->maxLength(255),

                DatePicker::make('date_awarded')
                    ->label('Date Awarded')
                    ->required(),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('award_type')
                    ->label('Type of Award')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('award_title')
                    ->label('Title of Paper or Award')
                    ->sortable()
                    ->searchable()
                    ->limit(20)
                    ->tooltip(fn($state) => $state),

                TextColumn::make('name')
                    ->label('Name(s) of Awardee/Recipient')
                    ->sortable()
                    ->searchable()
                    ->limit(20)
                    ->tooltip(fn($state) => $state),

                TextColumn::make('granting_organization')
                    ->label('Granting Organization')
                    ->sortable()
                    ->searchable()
                    ->limit(20)
                ->tooltip(fn ($state) => $state),

                    ->tooltip(fn($state) => $state),

                TextColumn::make('date_awarded')
                    ->label('Date Awarded')
                    ->date()
                    ->sortable()
                    ->placeholder('N/A'),
            ])
            ->filters([

            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Create Awards/Recognitions')
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
                    ->action(fn (array $data) => static::exportData(\App\Models\AwardsRecognitions::all(), $data['format'])),
            ])
            ->bulkActions([
                BulkAction::make('Delete Selected')
                    ->label('Delete Selected')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->action(fn ($records) => $records->each->delete()),

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
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('New Awards/Recognitions')->icon('heroicon-o-pencil-square')->color('secondary'),
                Tables\Actions\Action::make('export')
                    ->label('Export')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function () {
                        // Fetch all Awards/Recognitions
                        $awardsrecognitions = AwardsRecognitions::all([
                            'award_type',
                            'award_title',
                            'name',
                            'granting_organization',
                            'date_awarded',
                        ]);

                        // Create CSV writer
                        $csv = Writer::createFromFileObject(new SplTempFileObject());

                        // Add CSV headers
                        $csv->insertOne(['award_type', 'award_title', 'name', 'granting_organization', 'date_awarded']);

                        // Add data rows
                        foreach ($awardsrecognitions as $award) {
                            $csv->insertOne([
                                $award->award_type,
                                $award->award_title,
                                $award->name,
                                $award->granting_organization,
                                $award->date_awarded
                            ]);
                        }


                        // Return CSV
                        return response()->streamDownload(function () use ($csv) {
                            echo $csv->toString();
                        }, 'awards_recognitions_export_' . now()->format('Ymd_His') . '.csv');
                    }),
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
                fputcsv($handle, ['Type of Award', 'Title of Paper or Award', 'Name(s) of Awardee/Recipient', 'Granting Organization', 'Date Awarded']);

                foreach ($records as $record) {
                    fputcsv($handle, [
                        $record->award_type,
                        $record->award_title,
                        $record->name,
                        $record->granting_organization,
                        $record->date_awarded,
                    ]);
                }

                fclose($handle);
            }, 'awards_and_recognitions.csv');
        }

        if ($format === 'pdf') {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('exports.awards_recognitions', ['records' => $records]);
            return response()->streamDownload(fn () => print($pdf->output()), 'awards_and_recognitions.pdf');
        }
    }


    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAwardsRecognitions::route('/'),
            'create' => Pages\CreateAwardsRecognitions::route('/create'),
            'edit' => Pages\EditAwardsRecognitions::route('/{record}/edit'),
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
    $replacer = 'name';
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
