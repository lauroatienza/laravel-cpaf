<?php

namespace App\Filament\Resources\JournalArticleResource\Pages;

use App\Filament\Resources\JournalArticleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListJournalArticles extends ListRecords
{
    protected static string $resource = JournalArticleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
