<?php

namespace App\Filament\Resources\FSRorRSRResource\Pages;

use App\Filament\Resources\FSRorRSRResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFSRorRSR extends EditRecord
{
    protected static string $resource = FSRorRSRResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
