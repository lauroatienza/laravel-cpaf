<?php

namespace App\Filament\Resources\TrainingAttendedResource\Pages;

use App\Filament\Resources\TrainingAttendedResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTrainingAttendeds extends ListRecords
{
    protected static string $resource = TrainingAttendedResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
}
