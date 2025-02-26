<?php

namespace App\Filament\Resources\InternationalPublicationAwardResource\Pages;

use App\Filament\Resources\InternationalPublicationAwardResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInternationalPublicationAward extends EditRecord
{
    protected static string $resource = InternationalPublicationAwardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
