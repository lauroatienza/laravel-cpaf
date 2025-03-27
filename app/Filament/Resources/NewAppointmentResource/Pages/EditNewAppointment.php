<?php

namespace App\Filament\Resources\NewAppointmentResource\Pages;

use App\Filament\Resources\NewAppointmentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditNewAppointment extends EditRecord
{
    protected static string $resource = NewAppointmentResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
