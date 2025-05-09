<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExtensionResource\Pages;
use App\Filament\Resources\ExtensionResource\RelationManagers;
use App\Models\Extension;
use App\Models\Users;
use Doctrine\DBAL\Schema\Column;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Radio;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use League\Csv\Writer;
use SplTempFileObject;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Tables\Filters\SelectFilter;


class ExtensionResource extends Resource
{
    protected static ?string $model = Extension::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Accomplishments';

    protected static ?string $navigationLabel = 'Extension Involvements';
    protected static ?int $navigationSort = 4;
    protected static ?string $pluralLabel = 'Extension Involvements';
    public static function getNavigationBadge(): ?string
    {
        $user = Auth::user();


        if ($user->hasRole(['super-admin', 'admin'])) {
            return static::$model::count();
        }


        $fullName = trim("{$user->name} " . ($user->middle_name ? "{$user->middle_name} " : "") . "{$user->last_name}");
        $fullNameReversed = trim("{$user->last_name}, {$user->name}" . ($user->middle_name ? " {$user->middle_name}" : ""));
        $simpleName = trim("{$user->name} {$user->last_name}");


        $titles = ['Dr.', 'Prof.', 'Engr.', 'Sir', 'Ms.', 'Mr.', 'Mrs.'];

        $replacer = 'name';
        foreach ($titles as $title) {
            $replacer = "REPLACE($replacer, '$title', '')";
        }

        $normalizeName = function ($name) use ($titles, $user) {

            $nameWithoutTitles = str_ireplace($titles, '', $name);


            if ($user->middle_name) {
                $middleNameInitial = strtoupper(substr($user->middle_name, 0, 1)) . '.';
                $nameWithoutTitles = str_ireplace($user->middle_name, $middleNameInitial, $nameWithoutTitles);
            }


            return preg_replace('/\s+/', ' ', trim($nameWithoutTitles));
        };


        $normalizedFullName = $normalizeName($fullName);
        $normalizedFullNameReversed = $normalizeName($fullNameReversed);
        $normalizedSimpleName = $normalizeName($simpleName);

        return static::$model::where(function ($query) use ($replacer, $normalizedFullName, $normalizedFullNameReversed, $normalizedSimpleName) {
            $query->whereRaw("LOWER(($replacer)) LIKE LOWER(?)", ["%$normalizedFullName%"])
                ->orWhereRaw("LOWER(($replacer)) LIKE LOWER(?)", ["%$normalizedFullNameReversed%"])
                ->orWhereRaw("LOWER(($replacer)) LIKE LOWER(?)", ["%$normalizedSimpleName%"]);
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
            Hidden::make('user_id')
                ->default(Auth::id()),

            TextInput::make('name')
                ->label('Full Name')
                ->default(Auth::user()->name . ' ' . Auth::user()->last_name)
                ->required(),

            Select::make('extension_involvement')
                ->label('Type of Extension Involvement')
                ->options([
                    'Resource Person' => 'Resource Person',
                    'Seminar Speaker' => 'Seminar Speaker',
                    'Reviewer' => 'Reviewer',
                    'Evaluator' => 'Evaluator',
                    'Moderator' => 'Moderator',
                    'Session Chair' => 'Session Chair',
                    'Editor' => 'Editor',
                    'Examiner' => 'Examiner',
                    'Other' => 'Other (Specify)',
                ])
                ->reactive()
                ->required(),

            TextInput::make('custom_involvement')
                ->label('Specify Other Involvement')
                ->hidden(fn($get) => $get('extension_involvement') !== 'Other')
                ->required(fn($get) => $get('extension_involvement') === 'Other')
                ->maxLength(255),

            Select::make('location')
                ->label('Type of Extension')
                ->options([
                    'Training' => 'Training',
                    'Conference' => 'Conference',
                    'Editorial Team/Board' => 'Editorial Team/Board',
                    'Workshop' => 'Workshop',
                    'Other' => 'Other (Specify)',
                ])
                ->reactive()
                ->required(),

            TextInput::make('custom_location')
                ->label('Specify Other Type of Extension')
                ->hidden(fn($get) => $get('location') !== 'Other')
                ->required(fn($get) => $get('location') === 'Other')
                ->maxLength(255),

            TextInput::make('event_title')
                ->label("Event Title")
                ->required(),

            TextInput::make('venue')
                ->label("Venue and Location")
                ->required(),

                Section::make('Activity Date')
                ->schema([
                    Forms\Components\DatePicker::make('start_date'),
                    Forms\Components\DatePicker::make('end_date'),
                ])->columns(2),
        ])->columns(2);
}


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                /*TextColumn::make('contributing_unit')->label('Contributing Unit')
                ->sortable()->searchable(),
                TextColumn::make('title')->label('Title')
                ->sortable()->searchable(),
                TextColumn::make('faculty.first_name')->label("Project Leader")
                ->sortable()->searchable(),
                TextColumn::make('start_date')
                ->sortable()->searchable(),
                TextColumn::make('end_date')
                ->sortable()->searchable(),
                */
                // IconColumn::make('pbms_upload_status')
                // ->icon(fn (string $state): string => match ($state) {
                //   'uploaded' => 'heroicon-o-check-badge',
                //  'pending' => 'heroicon-o-clock',

                //  })
                /*TextColumn::make('activity_date')->label('Timestamp')
                ->sortable()->searchable() ->date('F d, Y'),*/
                TextColumn::make('name')->label('Full Name')
                    ->sortable()
                    ->searchable()
                    ->limit(40)
                    ->tooltip(fn($state) => $state),
                TextColumn::make('extension_involvement')->label('Type of Extension Involvement')
                    ->sortable()->searchable(),
                TextColumn::make('event_title')->label('Event Title')
                    ->sortable()->searchable()
                    ->limit(20)
                    ->tooltip(fn($state) => $state),
                TextColumn::make('created_at')->label('Start Date')
                    ->sortable()->searchable(),
                TextColumn::make('extensiontype')->label('Type of Extension')
                    ->sortable()->searchable(),
                TextColumn::make('venue')->label('Event Venue')
                    ->sortable()->searchable()
                    ->limit(20)
                    ->tooltip(fn($state) => $state),
                TextColumn::make('date_end')->label('End Date')
                    ->sortable()->searchable(),


            ])
            ->filters([

            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),

                    Tables\Actions\BulkAction::make('exportSelected')
                        ->label('Export Selected')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->form([
                            Select::make('format')
                                ->label('Select Export Format')
                                ->options([
                                    'csv' => 'CSV',
                                    'pdf' => 'PDF',
                                ])
                                ->required(),
                        ])
                        ->action(fn(array $data, $records) =>
                            static::exportData($records, $data['format'])
                        ),
                ]),
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
                fputcsv($handle, ['Full Name', 'Type of Extension Involvement', 'Event Title', 'Start Date', 'End Date', 'Extension Type', 'Venue']);  // CSV headers

                foreach ($records as $record) {
                    fputcsv($handle, [
                        $record->name,
                        $record->extension_involvement,
                        $record->event_title,
                        $record->created_at,
                        $record->date_end,
                        $record->extensiontype,
                        $record->venue,
                    ]);
                }

                fclose($handle);
            }, 'extension_involvements.csv');
        }

        if ($format === 'pdf') {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('exports.extension_involvements', ['records' => $records]);  // Your PDF view
            return response()->streamDownload(fn() => print($pdf->output()), 'extension_involvements.pdf');
        }
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
            'index' => Pages\ListExtensions::route('/'),
            'create' => Pages\CreateExtension::route('/create'),
            //'view' => Pages\ViewExtension::route('/{record}'),
            'edit' => Pages\EditExtension::route('/{record}/edit'),
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
            ->where(function ($query) use ($replacer, $normalizedFullName, $normalizedFullNameReversed, $normalizedSimpleName, $normalizedReversedInitials) {
                $query->whereRaw("LOWER($replacer) LIKE LOWER(?)", ["%$normalizedFullName%"])
                    ->orWhereRaw("LOWER($replacer) LIKE LOWER(?)", ["%$normalizedFullNameReversed%"])
                    ->orWhereRaw("LOWER($replacer) LIKE LOWER(?)", ["%$normalizedSimpleName%"])
                    ->orWhereRaw("LOWER($replacer) LIKE LOWER(?)", ["%$normalizedReversedInitials%"]);
            });

    }





}
