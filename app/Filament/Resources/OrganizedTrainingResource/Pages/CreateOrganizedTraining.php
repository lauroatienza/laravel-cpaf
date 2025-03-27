<?php

namespace App\Filament\Resources\OrganizedTrainingResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\OrganizedTrainingResource;

class CreateOrganizedTraining extends CreateRecord
{
    protected static string $resource = OrganizedTrainingResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}

