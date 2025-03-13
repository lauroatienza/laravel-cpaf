<?php

namespace App\Filament\Resources\FSRorRSRResource\Pages;

use App\Filament\Resources\FSRorRSRResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFSRorRSRS extends ListRecords
{
    protected static string $resource = FSRorRSRResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
