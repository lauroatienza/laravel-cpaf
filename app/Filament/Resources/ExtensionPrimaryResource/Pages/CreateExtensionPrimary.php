<?php

namespace App\Filament\Resources\ExtensionPrimaryResource\Pages;

use App\Filament\Resources\ExtensionPrimaryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateExtensionPrimary extends CreateRecord
{
    protected static string $resource = ExtensionPrimaryResource::class;
    public function getTitle(): string
    {
        return 'Add Extension'; // Change this to your desired title
    }
}
