<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NewAppointmentResource\Pages;
use App\Filament\Resources\NewAppointmentResource\RelationManagers;
use App\Models\NewAppointment;
use Date;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\DatePicker;
use PhpParser\Node\Stmt\Label;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Text;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Actions\Action;
use Symfony\Contracts\Service\Attribute\Required;
use League\Csv\Writer;
use Illuminate\Support\Facades\Response;
use SplTempFileObject;




class NewAppointmentResource extends Resource
{
    protected static ?string $model = NewAppointment::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-date-range';

    protected static ?string $navigationGroup = 'Other Documents';

    protected static ?string $navigationLabel = 'New Appointment';    
    protected static ?int $navigationSort = 4;

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
        return 'secondary'; 
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                /*DateTimePicker::make('time_stamp')
                ->label('Timestamp')
                ->required(),*/
                TextInput::make('full_name')
                ->label('Name')
                ->required(),
                Select::make('type_of_appointments')
                ->label('Type of Appointment')
                ->required()
                ->options([
                    'Affiliate Faculty' => 'Affiliate Faculty',
                    'Adjunct Faculty' => 'Adjunct Faculty',
                    'Lecturer' => 'Lecturer',
                    'Administrator' => 'Administrator',
                    'Other' => 'Other (Specify)',
                ])
                ->reactive()
                ->afterStateUpdated(fn ($state, callable $set) => $set('appointment_other', $state === 'Other' ? null : '')), // Clears when not "Other"

                TextInput::make('appointment_other')
                    ->label('Please specify your type of appointment')
                    ->visible(fn ($get) => $get('type_of_appointments') === 'Other') // Proper conditional visibility
                    ->required(fn ($get) => $get('type_of_appointments') === 'Other'), 

                TextInput::make('position')
                ->label('Position')
                ->required(),

                Select::make('appointment')
                ->label('Appointment')
                ->required()
                ->options([
                    'Assistant Professor' => 'Assistant Professor',
                    'Associate Professor' => 'Associate Professor',
                    'Professor' => 'Professor',
                    'Lecturer' => 'Lecturer',
                    'Dean' => 'Dean',
                    'Director' => 'Director',
                    'Head' => 'Head',
                    'Other' => 'Other (Specify)',
                ])
                ->reactive()
                ->afterStateUpdated(fn ($state, callable $set) => $set('appointment_other', $state === 'Other' ? null : '')), // Clears when not "Other"

                TextInput::make('appointment_other')
                    ->label('Please specify your appointment')
                    ->visible(fn ($get) => $get('appointment') === 'Other') // Proper conditional visibility
                    ->required(fn ($get) => $get('appointment') === 'Other'), 

                DatePicker::make('appointment_effectivity_date')
                ->label('Appointment Effectivity Date')
                ->required(),

                //TextColumn::make('appointment_effectivity_date')->date(),
                TextInput::make('photo_url')
                    ->url()
                    ->label('Photo File URL')
                    ->helperText('Enter a valid URL') 
                    ->Required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('full_name')
                ->label('Full name')
                ->sortable()
                ->searchable(),
                TextColumn::make('type_of_appointments')
                ->label('Type of Appointment')
                ->sortable()
                ->searchable(),
                TextColumn::make('position')
                ->label('Position')
                ->sortable()
                ->searchable(),
                TextColumn::make('appointment')
                ->label('Appointment')
                ->sortable()
                ->searchable(),
                TextColumn::make('appointment_effectivity_date')
                ->label('Appointment Effectivity Date')
                ->sortable()
                ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->headerActions([
                /*Action::make('Export')
                    ->modalButton('Download')
                    ->color('gray'),*/
                Tables\Actions\CreateAction::make()->label('Create Appointment'),
                Tables\Actions\Action::make('export')
                    ->label('Export')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function () {
                        // Fetch all appointments
                        $appointments = NewAppointment::all([
                            'full_name', 
                            'type_of_appointments', 
                            'position', 
                            'appointment', 
                            'appointment_effectivity_date'
                        ]);
            
                        // Create CSV writer
                        $csv = Writer::createFromFileObject(new SplTempFileObject());
            
                        // Add CSV headers
                        $csv->insertOne(['Full Name', 'Type of Appointment', 'Position', 'Appointment', 'Effectivity Date']);
            
                        // Add data rows
                        foreach ($appointments as $appointment) {
                            $csv->insertOne([
                                $appointment->full_name,
                                $appointment->type_of_appointments,
                                $appointment->position,
                                $appointment->appointment,
                                $appointment->appointment_effectivity_date
                            ]);
                        }
            
                        // Return CSV 
                        return response()->streamDownload(function () use ($csv) {
                            echo $csv->toString();
                        }, 'appointments_export_' . now()->format('Ymd_His') . '.csv');
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
            'index' => Pages\ListNewAppointments::route('/'),
            'create' => Pages\CreateNewAppointment::route('/create'),
            'edit' => Pages\EditNewAppointment::route('/{record}/edit'),
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
    
        return parent::getEloquentQuery()
            ->where(function ($query) use ($normalizedFullName, $normalizedFullNameReversed, $normalizedSimpleName) {
                $query->whereRaw("LOWER(REPLACE(full_name, 'Dr.', '')) LIKE LOWER(?)", ["%$normalizedFullName%"])
                      ->orWhereRaw("LOWER(REPLACE(full_name, 'Dr.', '')) LIKE LOWER(?)", ["%$normalizedFullNameReversed%"])
                      ->orWhereRaw("LOWER(REPLACE(full_name, 'Dr.', '')) LIKE LOWER(?)", ["%$normalizedSimpleName%"]);
            });
    }
    
}
