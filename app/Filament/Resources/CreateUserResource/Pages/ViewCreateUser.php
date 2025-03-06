<?php

namespace App\Filament\Resources\CreateUserResource\Pages;

use App\Filament\Resources\CreateUserResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewCreateUser extends ViewRecord
{
    protected static string $resource = CreateUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
