<?php

namespace App\Filament\Resources\OrganizedTrainingResource\Pages;

use App\Filament\Resources\OrganizedTrainingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOrganizedTraining extends EditRecord
{
    protected static string $resource = OrganizedTrainingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
