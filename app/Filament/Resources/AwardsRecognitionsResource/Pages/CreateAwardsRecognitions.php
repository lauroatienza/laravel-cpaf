<?php

namespace App\Filament\Resources\AwardsRecognitionsResource\Pages;

use App\Filament\Resources\AwardsRecognitionsResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAwardsRecognitions extends CreateRecord
{
    protected static string $resource = AwardsRecognitionsResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
