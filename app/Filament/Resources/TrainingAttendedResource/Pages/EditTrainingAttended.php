<?php

namespace App\Filament\Resources\TrainingAttendedResource\Pages;

use App\Filament\Resources\TrainingAttendedResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTrainingAttended extends EditRecord
{
    protected static string $resource = TrainingAttendedResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
