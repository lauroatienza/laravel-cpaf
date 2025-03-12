<?php

namespace App\Filament\Resources\ChapterInBookResource\Pages;

use App\Filament\Resources\ChapterInBookResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditChapterInBook extends EditRecord
{
    protected static string $resource = ChapterInBookResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
