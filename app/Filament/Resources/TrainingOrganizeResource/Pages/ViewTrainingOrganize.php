<?php

namespace App\Filament\Resources\TrainingOrganizeResource\Pages;

use App\Filament\Resources\TrainingOrganizeResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTrainingOrganize extends ViewRecord
{
    protected static string $resource = TrainingOrganizeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
