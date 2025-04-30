<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExtensionResource\Pages;
use App\Filament\Resources\ExtensionResource\RelationManagers;
use App\Models\Extension;
use App\Models\Users;
use Doctrine\DBAL\Schema\Column;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Radio;
use Illuminate\Support\Facades\Auth;
use League\Csv\Writer;
use SplTempFileObject;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TimePicker;


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
                TextInput::make('name')
                    ->label('Full Name')
                    ->default(Auth::user()->name . ' ' . Auth::user()->last_name)
                    ->hidden()
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
                    ->reactive(),

                Select::make('location')
                    ->label('Type of Extension')
                    ->options([
                        'Training' => 'Training',
                        'Conference' => 'Conference',
                        'Editorial Team/Board' => 'Editorial Team/Board',
                        'Workshop' => 'Workshop',
                        'Other' => 'Other (Specify)',
                    ])
                    ->reactive(),

                TextInput::make('custom_involvement')
                    ->label('Specify Other')
                    ->hidden(fn($get) => $get('type_of_involvement') !== 'Other')
                    ->maxLength(255),

                TextInput::make('event_title')
                    ->label("Event Title"),

                TextInput::make('venue')
                    ->label("Venue and Location"),

                DatePicker::make('activity_date')
                    ->label('Activity Date'),

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
                TextColumn::make('name')->label('Full Names')
                    ->sortable()->searchable()
                    ->limit(20) // Only show first 20 characters
                    ->tooltip(fn($state) => $state),
                TextColumn::make('extension_involvement')->label('Type of Extension Involvement')
                    ->sortable()->searchable(),
                TextColumn::make('event_title')->label('Event Title')
                    ->sortable()->searchable()
                    ->limit(20) // Only show first 20 characters
                    ->tooltip(fn($state) => $state), // Show full name on hover,
                TextColumn::make('created_at')->label('Start Date')
                    ->sortable()->searchable(),
                TextColumn::make('extensiontype')->label('Type of Extension')
                    ->sortable()->searchable(),
                TextColumn::make('venue')->label('Event Venue')
                    ->sortable()->searchable()
                    ->limit(20) // Only show first 20 characters
                    ->tooltip(fn($state) => $state),
                TextColumn::make('date_end')->label('End Date')
                    ->sortable()->searchable(),


            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()->label('Create Extension Involvement')
                    ->color('secondary')->icon('heroicon-o-pencil-square'),
                Tables\Actions\Action::make('export')
                    ->label('Export')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function () {

                        $extensions = Extension::all([
                            'name',
                            'extension_involvement',
                            'event_title',
                            'activity_date',
                            'venue',
                            'date_end',
                        ]);

                        // Create CSV writer
                        $csv = Writer::createFromFileObject(new SplTempFileObject());

                        // Add CSV headers
                        $csv->insertOne(['Full Name', 'Type of Extension Involvement', 'Event Title', 'Activity Date', 'Venue', 'End Date']);

                        // Add data rows
                        foreach ($extensions as $extension) {
                            $csv->insertOne([
                                $extension->name,
                                $extension->extension_involvement,
                                $extension->event_title,
                                $extension->activity_date,
                                $extension->venue,
                                $extension->date_end
                            ]);
                        }

                        // Return CSV
                        return response()->streamDownload(function () use ($csv) {
                            echo $csv->toString();
                        }, 'extensions_export_' . now()->format('Ymd_His') . '.csv');
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
