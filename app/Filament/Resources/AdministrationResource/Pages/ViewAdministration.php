<?php

namespace App\Filament\Resources\AdministrationResource\Pages;

use App\Filament\Resources\AdministrationResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAdministration extends ViewRecord
{
    protected static string $resource = AdministrationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
