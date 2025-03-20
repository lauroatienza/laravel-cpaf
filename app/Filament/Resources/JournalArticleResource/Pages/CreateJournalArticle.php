<?php

namespace App\Filament\Resources\JournalArticleResource\Pages;

use App\Filament\Resources\JournalArticleResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateJournalArticle extends CreateRecord
{
    protected static string $resource = JournalArticleResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::id(); // Assign the logged-in user's ID
        return $data;
    }
}
