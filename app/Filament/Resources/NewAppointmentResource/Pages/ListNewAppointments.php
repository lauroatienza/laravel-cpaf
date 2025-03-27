<?php

namespace App\Filament\Resources\NewAppointmentResource\Pages;

use App\Filament\Resources\NewAppointmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNewAppointments extends ListRecords
{
    protected static string $resource = NewAppointmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //Actions\CreateAction::make()->label('Create Appointment'),
        ];
    }
}
