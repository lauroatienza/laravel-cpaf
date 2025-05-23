<?php

namespace App\Filament\Resources\TrainingOrganizeResource\Pages;

use App\Filament\Resources\TrainingOrganizeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTrainingOrganize extends EditRecord
{
    protected static string $resource = TrainingOrganizeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
