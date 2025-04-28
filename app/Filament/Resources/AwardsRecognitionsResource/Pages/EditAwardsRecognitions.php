<?php

namespace App\Filament\Resources\AwardsRecognitionsResource\Pages;

use App\Filament\Resources\AwardsRecognitionsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAwardsRecognitions extends EditRecord
{
    protected static string $resource = AwardsRecognitionsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
