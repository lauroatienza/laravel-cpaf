<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NewAppointmentResource\Pages;
use App\Filament\Resources\NewAppointmentResource\RelationManagers;
use App\Models\NewAppointment;
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
use Symfony\Contracts\Service\Attribute\Required;


class NewAppointmentResource extends Resource
{
    protected static ?string $model = NewAppointment::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-date-range';

    protected static ?string $navigationGroup = 'Programs';

    protected static ?string $navigationLabel = 'New Appointment';    
    protected static ?int $navigationSort = 3;

    public static function getNavigationBadge(): ?string
    {
        return static::$model::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('type_of_appointments')
                ->label('Type of Appointment')
                ->required()
                ->options([
                    'Affiliate Faculty' => 'Affiliate Faculty',
                    'Adjunct Faculty' => 'Adjunct Faculty',
                    'Lecturer' => 'Lecturer',
                    'Administrator' => 'Administrator',
                ])
                ->reactive(),

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
                    'Other' => 'Other (Specify)', // Adds "Other" as an option
                ])
                ->reactive(),

                DatePicker::make('appointment_effectivity_date')
                ->label('Appointment Effectivity Date')
                ->required(),

                //TextColumn::make('appointment_effectivity_date')->date(),
                TextInput::make('photo_url')
                    ->url()
                    ->label('Photo URL')
                    ->helperText('Enter a valid URL') 
                    ->Required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
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
}
