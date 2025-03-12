<?php

namespace App\Filament\Resources\ChapterInBookResource\Pages;

use App\Filament\Resources\ChapterInBookResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListChapterInBooks extends ListRecords
{
    protected static string $resource = ChapterInBookResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
