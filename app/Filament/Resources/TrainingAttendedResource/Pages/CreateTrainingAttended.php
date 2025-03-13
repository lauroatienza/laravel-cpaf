<?php

namespace App\Filament\Resources\TrainingAttendedResource\Pages;

use App\Filament\Resources\TrainingAttendedResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTrainingAttended extends CreateRecord
{
    protected static string $resource = TrainingAttendedResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
