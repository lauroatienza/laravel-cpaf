<?php

namespace App\Filament\Resources\RepsResource\Pages;

use App\Filament\Resources\RepsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReps extends ListRecords
{
    protected static string $resource = RepsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
