<?php

namespace App\Filament\Resources\ExtensionPrimaryResource\Pages;

use App\Filament\Resources\ExtensionPrimaryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateExtensionPrimary extends CreateRecord
{
    protected static string $resource = ExtensionPrimaryResource::class;
    public function getTitle(): string
    {
        return 'Add Extension';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

}
