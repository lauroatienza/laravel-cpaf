<?php

namespace App\Filament\Resources\OrganizedTrainingResource\Pages;

use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\OrganizedTrainingResource;
use App\Models\ExtensionPrime;
use App\Models\Research;

class EditOrganizedTraining extends EditRecord
{
    protected static string $resource = OrganizedTrainingResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
