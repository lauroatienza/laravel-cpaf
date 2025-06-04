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
use Filament\Forms\Get;
use Filament\Forms\Set;


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
    
    //Normalise Name
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
                ->default(function () {
                        $name = Auth::user()->name . ' ' . Auth::user()->last_name;
                        $titles = ['Dr.', 'Prof.', 'Engr.', 'Sir', 'Ms.', 'Mr.', 'Mrs.'];
                        $cleaned = str_ireplace($titles, '', $name);
                        return preg_replace('/\s+/', ' ', trim($cleaned));
                    })
                    ->formatStateUsing(fn ($state) => preg_replace('/\s+/', ' ', trim($state)))
                    ->dehydrated()
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
                    'Guest Lecturer' => 'Guest Lecturer',
                    'Other' => 'Other (Specify)',
                ])
                ->required()
                ->live()
                ->afterStateUpdated(function (Set $set, $state) {
                    if ($state !== 'Other') {
                        $set('other_type', null);
                    }
                }),
            TextInput::make('other_type')
                ->label('Specify Other Type of Extension')
                ->maxLength(255)
                ->visible(fn (Get $get) => $get('extension_involvement') === 'Other')
                ->afterStateUpdated(function (Set $set, Get $get, $state) {
                    if ($get('extension_involvement') === 'Other') {
                        $set('extension_involvement', $state);
                    }
                }),


            Select::make('extensiontype')
                ->label('Type of Extension')
                ->options([
                    'Training' => 'Training',
                    'Conference' => 'Conference',
                    'Editorial Team/Board' => 'Editorial Team/Board',
                    'Workshop' => 'Workshop',
                    'Class' => 'Class',
                    'Other' => 'Other (Specify)',
                ])
                ->reactive()
                ->live()
                ->afterStateUpdated(function (Set $set, $state) {
                    if ($state !== 'Other') {
                        $set('other_types', null);
                    }
                }),

            TextInput::make('other_types')
                ->label('Specify Other Type of Extension')
                ->maxLength(255)
                ->visible(fn (Get $get) => $get('extensiontype') === 'Other')
                ->afterStateUpdated(function (Set $set, Get $get, $state) {
                    if ($get('extensiontype') === 'Other') {
                        $set('extensiontype', $state);
                    }
                }),



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
                TextColumn::make('start_date')->label('Start Date')
                    ->sortable()->searchable(),
                TextColumn::make('extensiontype')->label('Type of Extension')
                    ->sortable()->searchable(),
                TextColumn::make('venue')->label('Event Venue')
                    ->sortable()->searchable()
                    ->limit(20)
                    ->tooltip(fn($state) => $state),
                TextColumn::make('end_date')->label('End Date')
                    ->sortable()->searchable(),


            ])
            ->filters([
                Tables\Filters\Filter::make('activity_date_range')
                    ->form([
                        DatePicker::make('start_date')
                            ->label('Start Date From'),
                        DatePicker::make('end_date')
                            ->label('End Date To'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['start_date'], fn ($query, $date) => 
                                $query->whereDate('start_date', '>=', $date)
                            )
                            ->when($data['end_date'], fn ($query, $date) => 
                                $query->whereDate('end_date', '<=', $date)
                            );
                    }),

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
                $recordName = $normalizeName(str_ireplace($titles, '', $record->name ?? ''));
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
                fputcsv($handle, [
                    'Full Name', 'Type of Extension Involvement', 'Event Title', 'Start Date', 'End Date', 'Extension Type', 'Venue'
                ]);
    
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
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('exports.extension_involvements', ['records' => $records]);
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
