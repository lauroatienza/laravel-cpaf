<?php

namespace App\Filament\Resources\FilamentMainResource\Pages;

use App\Filament\Resources\FilamentMainResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFilamentMain extends EditRecord
{
    protected static string $resource = FilamentMainResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
