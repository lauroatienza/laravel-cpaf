<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TrainingAttendedResource\Pages;
use App\Models\TrainingAttended;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Get;
use Filament\Forms\Set;


class TrainingAttendedResource extends Resource
{
    protected static ?string $model = TrainingAttended::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationLabel = 'Training Attended';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationGroup = 'Accomplishments';

    public static function getPluralLabel(): string
{
    return 'Training Attended';
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
    $normalizeName = function ($name) use ($titles) {
        // Remove titles
        $nameWithoutTitles = str_ireplace($titles, '', $name);
        // Replace multiple spaces with a single space
        return preg_replace('/\s+/', ' ', trim($nameWithoutTitles));
    };

    // Normalize names
    $normalizedFullName = $normalizeName($fullName);
    $normalizedFullNameReversed = $normalizeName($fullNameReversed);
    $normalizedSimpleName = $normalizeName($simpleName);

    return static::$model::where(function ($query) use ($normalizedFullName, $normalizedFullNameReversed, $normalizedSimpleName) {
        $query->whereRaw("LOWER(REPLACE(full_name, 'Dr.', '')) LIKE LOWER(?)", ["%$normalizedFullName%"])
        ->orWhereRaw("LOWER(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(full_name, 'Dr.', ''), 'Prof.', ''), 'Engr.', ''), 'Sir', ''), 'Ms.', ''), 'Mr.', ''), 'Mrs.', '')) LIKE LOWER(?)", ["%$normalizedFullNameReversed%"]); // Modified line
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
                Forms\Components\TextInput::make('full_name')
                    ->label('Full Name')
                    ->required(),

                Forms\Components\Select::make('unit_center')
                    ->label('Unit/Center')
                    ->options([
                        'CSPPS' => 'CSPPS',
                        'CISC' => 'CISC',
                        'IGRD' => 'IGRD',
                        'CPAF' => 'CPAF',
                    ])
                    ->required(),

                Forms\Components\DatePicker::make('start_date')->required(),
                Forms\Components\DatePicker::make('end_date')->required(),

                Forms\Components\Select::make('category')
                    ->label('Category')
                    ->options([
                        'Workshop' => 'Workshop',
                        'Training' => 'Training',
                        'Conference' => 'Conference',
                        'Seminar' => 'Seminar',
                        'Forum' => 'Forum',
                        'Symposium' => 'Symposium',
                        'Other' => 'Other...',
                    ])
                    ->live()
                    ->required()
                    ->afterStateUpdated(function (Set $set, $state) {
                        if ($state !== 'Other') {
                            $set('other_category', null);
                        }
                    }),

                Forms\Components\TextInput::make('other_category')
                    ->label('Please specify')
                    ->maxLength(255)
                    ->visible(fn (Get $get) => $get('category') === 'Other')
                    ->afterStateUpdated(function (Set $set, $state, Get $get) {
                        if ($get('category') === 'Other') {
                            $set('category', $state);
                        }
                    }),



                Forms\Components\TextInput::make('training_title')
                    ->label('Specific Title')
                    ->placeholder('Specify title')
                    ->required(),

                Forms\Components\Textarea::make('highlights')
                    ->label('Highlights of Event')
                    ->rows(4)
                    ->nullable()
                    ->helperText('Do not leave the box blank. Please put "NA" if you have no answers. Thank you')
                    ->required(),

                Forms\Components\Radio::make('has_gender_component')
                    ->label('Has Gender Component?')
                    ->options([
                        true => 'Yes',
                        false => 'No',
                    ])
                    ->required(),

                Forms\Components\TextInput::make('total_hours')
                    ->label('Total Hrs. Spent')
                    ->numeric()
                    ->helperText('Please put "0" if you have no answer. Thank you')
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('full_name')
                    ->label('Full Name')
                    ->searchable()
                    ->sortable()
                    ->limit(20)
                    ->tooltip(fn ($state) => $state),

                Tables\Columns\BadgeColumn::make('unit_center')
                    ->label('Unit/Center')
                    ->sortable()
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('start_date')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('end_date')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('category')
                    ->label('Category')
                    ->sortable(),

                Tables\Columns\TextColumn::make('training_title')
                    ->label('Specific Title')
                    ->sortable()
                    ->searchable()
                    ->limit(20)
                    ->tooltip(fn ($state) => $state),

                Tables\Columns\TextColumn::make('highlights')
                    ->label('Highlights of Event')
                    ->sortable()
                    ->limit(20)
                    ->tooltip(fn ($state) => $state),

                Tables\Columns\BooleanColumn::make('has_gender_component')
                    ->label('Gender Component')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('total_hours')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => rtrim(rtrim(number_format($state, 1, '.', ''), '0'), '.')),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Create Training Attended')
                    ->color('secondary')
                    ->icon('heroicon-o-pencil-square'),

                Action::make('exportAll') // This should work now
                    ->label('Export')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(fn () => static::exportData(TrainingAttended::all())),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('exportBulk')
                        ->label('Export Selected')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->requiresConfirmation()
                        ->action(fn ($records) => static::exportData($records)),
                ]),
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
                'Full Name', 'Unit/Center', 'Start Date', 'End Date', 'Category', 'Training Title', 'Gender Component', 'Total Hours'
            ]);

            foreach ($records as $record) {
                fputcsv($handle, [
                    $record->full_name,
                    $record->unit_center,
                    $record->start_date,
                    $record->end_date,
                    $record->category,
                    $record->training_title,// "Specific title"
                    $record->has_gender_component ? 'Yes' : 'No',
                    $record->total_hours,
                ]);
            }

            fclose($handle);
        }, 'training_attended_data.csv');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTrainingAttendeds::route('/'),
            'create' => Pages\CreateTrainingAttended::route('/create'),
            // 'view' => Pages\ViewTrainingAttended::route('/{record}'),
            'edit' => Pages\EditTrainingAttended::route('/{record}/edit'),
        ];
    }
    public static function getEloquentQuery(): Builder
{
    $user = Auth::user();

    if ($user->hasRole(['super-admin', 'admin'])) {
        return parent::getEloquentQuery();
    }


    $fullName = trim("{$user->name} " . ($user->middle_name ? "{$user->middle_name} " : "") . "{$user->last_name}");
    $fullNameReversed = trim("{$user->last_name}, {$user->name}" . ($user->middle_name ? " {$user->middle_name}" : ""));
    $simpleName = trim("{$user->name} {$user->last_name}");


    $titles = ['Dr.', 'Prof.', 'Engr.', 'Sir', 'Ms.', 'Mr.', 'Mrs.'];


    $normalizeName = function ($name) use ($titles) {

        $nameWithoutTitles = str_ireplace($titles, '', $name);

        return preg_replace('/\s+/', ' ', trim($nameWithoutTitles));
    };


    $normalizedFullName = $normalizeName($fullName);
    $normalizedFullNameReversed = $normalizeName($fullNameReversed);
    $normalizedSimpleName = $normalizeName($simpleName);

    return parent::getEloquentQuery()
        ->where(function ($query) use ($normalizedFullName, $normalizedFullNameReversed, $normalizedSimpleName) {
            $query->whereRaw("LOWER(REPLACE(full_name, 'Dr.', '')) LIKE LOWER(?)", ["%$normalizedFullName%"])
                  ->orWhereRaw("LOWER(REPLACE(full_name, 'Dr.', '')) LIKE LOWER(?)", ["%$normalizedFullNameReversed%"])
                  ->orWhereRaw("LOWER(REPLACE(full_name, 'Dr.', '')) LIKE LOWER(?)", ["%$normalizedSimpleName%"]);
        });
}


}
