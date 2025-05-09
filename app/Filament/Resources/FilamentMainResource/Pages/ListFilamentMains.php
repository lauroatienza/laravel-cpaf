<?php

namespace App\Filament\Resources\FilamentMainResource\Pages;

use App\Filament\Resources\FilamentMainResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFilamentMains extends ListRecords
{
    protected static string $resource = FilamentMainResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
