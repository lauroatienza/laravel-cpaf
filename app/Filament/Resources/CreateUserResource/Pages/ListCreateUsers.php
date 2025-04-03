<?php

namespace App\Filament\Resources\CreateUserResource\Pages;

use App\Filament\Resources\CreateUserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCreateUsers extends ListRecords
{
    protected static string $resource = CreateUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //Actions\CreateAction::make(),
        ];
    }
}
