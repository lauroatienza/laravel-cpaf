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
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\DatePicker;
use PhpParser\Node\Stmt\Label;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Text;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ViewAction;
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
        $replacer = 'full_name';
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


    public static function getNavigationBadgeColor(): string
    {
        return 'primary';
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
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
                    ->reactive(),

                TextInput::make('type_of_appointments_other')
                    ->label('Please specify your type of appointment')
                    ->visible(fn($get) => $get('type_of_appointments') === 'Other')
                    ->required(fn($get) => $get('type_of_appointments') === 'Other')
                    ->afterStateUpdated(fn($state, callable $set) => $set('type_of_appointments', $state)),

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
                    ->reactive(),

                TextInput::make('appointment_other')
                    ->label('Please specify your appointment')
                    ->visible(fn($get) => $get('appointment') === 'Other')
                    ->required(fn($get) => $get('appointment') === 'Other')
                    ->afterStateUpdated(fn($state, callable $set) => $set('appointment', $state)),

                DatePicker::make('appointment_effectivity_date')
                    ->label('Appointment Effectivity Date')
                    ->required(),

                TextInput::make('new_appointment_file_path')
                    ->label('Appointment File URL')
                    ->placeholder('https://drive.google.com/...')
                    ->url()
                    ->maxLength(500),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('full_name')
                    ->label('Full Name')
                    ->sortable()
                    ->searchable(),

                BadgeColumn::make('type_of_appointments')
                    ->label('Type of Appointment')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(function ($state, $record) {
                        return $state === 'Other' ? $record->type_of_appointments_other : $state;
                    }),

                BadgeColumn::make('position')
                    ->label('Position')
                    ->sortable()
                    ->searchable(),

                BadgeColumn::make('appointment')
                    ->label('Appointment')
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(function ($state, $record) {
                        return $state === 'Other' ? $record->appointment_other : $state;
                    }),

                TextColumn::make('appointment_effectivity_date')
                    ->label('Appointment Effectivity Date')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('new_appointment_file_path')
                    ->label('File')
                    ->formatStateUsing(fn($state) => $state ? '🔗 View File' : 'None')
                    ->url(fn($record) => $record->new_appointment_file_path ?: null)
                    ->openUrlInNewTab()
                    ->color('primary'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type_of_appointments')
                    ->options([
                        'Affiliate Faculty' => 'Affiliate Faculty',
                        'Adjunct Faculty' => 'Adjunct Faculty',
                        'Lecturer' => 'Lecturer',
                        'Administrator' => 'Administrator',
                    ]),
            ])
            ->actions([
                ViewAction::make(),
                Tables\Actions\EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('delete')
                    ->label('Delete Selected')
                    ->icon('heroicon-o-trash')
                    ->requiresConfirmation()
                    ->color('danger')
                    ->action(fn($records) => $records->each->delete()),

                Tables\Actions\BulkAction::make('exportBulk')
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
                    ->action(fn(array $data, $records) => static::exportData($records, $data['format'])),
            ])
            ->selectable();
    }

    public static function exportData($records, $format)
    {
        $user = Auth::user();
    
        // If not admin, filter the records manually
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
    
            $normalize = function ($name) use ($titles, $user) {
                $name = str_ireplace($titles, '', $name);
                if ($user->middle_name) {
                    $name = str_ireplace($user->middle_name, strtoupper(substr($user->middle_name, 0, 1)) . '.', $name);
                }
                return preg_replace('/\s+/', ' ', trim($name));
            };
    
            $allowedNames = [
                strtolower($normalize($fullName)),
                strtolower($normalize($fullNameReversed)),
                strtolower($normalize($simpleName)),
                strtolower($normalize($reversedInitialsName)),
            ];
    
            // Filter records by normalized full_name
            $records = $records->filter(function ($record) use ($allowedNames, $normalize) {
                return in_array(strtolower($normalize($record->full_name)), $allowedNames);
            });
        }
    
        if ($records->isEmpty()) {
            return back()->with('error', 'No records available for export.');
        }
    
        if ($format === 'csv') {
            return response()->streamDownload(function () use ($records) {
                $handle = fopen('php://output', 'w');
                fputcsv($handle, ['Full Name', 'Type of Appointment', 'Position', 'Appointment', 'Effectivity Date']);
    
                foreach ($records as $appointment) {
                    fputcsv($handle, [
                        $appointment->full_name,
                        $appointment->type_of_appointments,
                        $appointment->position,
                        $appointment->appointment,
                        $appointment->appointment_effectivity_date,
                    ]);
                }
    
                fclose($handle);
            }, 'appointments_export_' . now()->format('Ymd_His') . '.csv');
        }
    
        if ($format === 'pdf') {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('exports.new_appointments', [
                'appointments' => $records,
            ]);
    
            return response()->streamDownload(function () use ($pdf) {
                echo $pdf->output();
            }, 'appointments_export_' . now()->format('Ymd_His') . '.pdf');
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
        $columns = ['full_name'];

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

