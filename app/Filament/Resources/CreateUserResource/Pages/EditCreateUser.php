<?php

namespace App\Filament\Resources\CreateUserResource\Pages;

use App\Filament\Resources\CreateUserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCreateUser extends EditRecord
{
    protected static string $resource = CreateUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
