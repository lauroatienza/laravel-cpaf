<?php

namespace App\Filament\Resources\ChapterInBookResource\Pages;

use App\Filament\Resources\ChapterInBookResource;
use Filament\Actions;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\CreateRecord;

class CreateChapterInBook extends CreateRecord
{
    protected static string $resource = ChapterInBookResource::class;

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
