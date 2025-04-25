<?php

namespace App\Filament\Resources\ExtensionPrimaryResource\Pages;

use App\Filament\Resources\ExtensionPrimaryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditExtensionPrimary extends EditRecord
{
    protected static string $resource = ExtensionPrimaryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
