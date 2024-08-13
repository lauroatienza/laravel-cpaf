<?php

namespace App\Filament\Resources\AdministrationsResource\Pages;

use App\Filament\Resources\AdministrationsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAdministrations extends EditRecord
{
    protected static string $resource = AdministrationsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
