<?php

namespace App\Filament\Resources\NewAppointmentResource\Pages;

use App\Filament\Resources\NewAppointmentResource;
use App\Models\NewAppointment;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms;
use Filament\Resources\Pages\ListRecords;

class ListNewAppointments extends ListRecords
{
    protected static string $resource = NewAppointmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Create Appointment')
                ->icon('heroicon-o-pencil-square')
                ->color('secondary'),

            Action::make('export')
                ->label('Export')
                ->icon('heroicon-o-arrow-down-tray')
                ->form([
                    Forms\Components\Select::make('format')
                        ->label('Export Format')
                        ->options([
                            'csv' => 'CSV',
                            'pdf' => 'PDF',
                        ])
                        ->required(),
                ])
                ->action(function (array $data) {
                    $appointments = NewAppointment::all([
                        'full_name',
                        'type_of_appointments',
                        'position',
                        'appointment',
                        'appointment_effectivity_date'
                    ]);

                    return NewAppointmentResource::exportData($appointments, $data['format']);
                }),
        ]; 
    }
}
