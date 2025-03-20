<?php

namespace App\Filament\Resources\ExtensionResource\Pages;

use App\Filament\Resources\ExtensionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateExtension extends CreateRecord
{
    protected static string $resource = ExtensionResource::class;
<<<<<<< HEAD

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
=======
    public function getTitle(): string
    {
        return 'Create Extension Involvement'; // Change this to your desired title
    }
    
>>>>>>> e9b222846ef45b24ddea0422ada2ef6c81d6f897
}
