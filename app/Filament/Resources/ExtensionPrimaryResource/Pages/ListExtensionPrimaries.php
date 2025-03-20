<?php

namespace App\Filament\Resources\ExtensionPrimaryResource\Pages;

use App\Filament\Resources\ExtensionPrimaryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListExtensionPrimaries extends ListRecords
{
    protected static string $resource = ExtensionPrimaryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
