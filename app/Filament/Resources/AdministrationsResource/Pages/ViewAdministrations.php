<?php

namespace App\Filament\Resources\AdministrationsResource\Pages;

use App\Filament\Resources\AdministrationsResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAdministrations extends ViewRecord
{
    protected static string $resource = AdministrationsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
