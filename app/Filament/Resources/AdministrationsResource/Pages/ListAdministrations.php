<?php

namespace App\Filament\Resources\AdministrationsResource\Pages;

use App\Filament\Resources\AdministrationsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAdministrations extends ListRecords
{
    protected static string $resource = AdministrationsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
