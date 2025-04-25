<?php

namespace App\Filament\Resources\AwardsRecognitionsResource\Pages;

use App\Filament\Resources\AwardsRecognitionsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAwardsRecognitions extends ListRecords
{
    protected static string $resource = AwardsRecognitionsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
