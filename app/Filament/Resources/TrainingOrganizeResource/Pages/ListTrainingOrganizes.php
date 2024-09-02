<?php

namespace App\Filament\Resources\TrainingOrganizeResource\Pages;

use App\Filament\Resources\TrainingOrganizeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTrainingOrganizes extends ListRecords
{
    protected static string $resource = TrainingOrganizeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
