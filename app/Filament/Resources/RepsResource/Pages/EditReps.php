<?php

namespace App\Filament\Resources\RepsResource\Pages;

use App\Filament\Resources\RepsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReps extends EditRecord
{
    protected static string $resource = RepsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
