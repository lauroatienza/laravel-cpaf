<?php

namespace App\Filament\Resources\NewAppointmentResource\Pages;

use App\Filament\Resources\NewAppointmentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateNewAppointment extends CreateRecord
{
    protected static string $resource = NewAppointmentResource::class;
<<<<<<< HEAD
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
=======


>>>>>>> e9b222846ef45b24ddea0422ada2ef6c81d6f897
}
