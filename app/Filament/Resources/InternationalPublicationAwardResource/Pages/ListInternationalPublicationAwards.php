<?php

namespace App\Filament\Resources\InternationalPublicationAwardResource\Pages;

use App\Filament\Resources\InternationalPublicationAwardResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInternationalPublicationAwards extends ListRecords
{
    protected static string $resource = InternationalPublicationAwardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
