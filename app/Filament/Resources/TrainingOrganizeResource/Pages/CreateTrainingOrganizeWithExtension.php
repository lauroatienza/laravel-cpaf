<?php

namespace App\Filament\Resources\OrganizedTrainingResource\Pages;

use App\Filament\Resources\OrganizedTrainingResource;
use Filament\Resources\Pages\CreateRecord;

class CreateOrganizedTrainingWithExtension extends CreateRecord
{
    protected static string $resource = OrganizedTrainingResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
