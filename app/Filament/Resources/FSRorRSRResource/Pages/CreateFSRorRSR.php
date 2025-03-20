<?php

namespace App\Filament\Resources\FSRorRSRResource\Pages;

use App\Filament\Resources\FSRorRSRResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateFSRorRSR extends CreateRecord
{
    protected static string $resource = FSRorRSRResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::id(); // Assign logged-in user's ID
        return $data;
    }
}
