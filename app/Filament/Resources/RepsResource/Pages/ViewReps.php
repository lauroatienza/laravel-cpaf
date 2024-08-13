<?php

namespace App\Filament\Resources\RepsResource\Pages;

use App\Filament\Resources\RepsResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewReps extends ViewRecord
{
    protected static string $resource = RepsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
