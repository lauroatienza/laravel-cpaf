<?php

namespace App\Filament\Resources\ResearchResource\Pages;

use App\Filament\Resources\ResearchResource;
use App\Models\Research;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListResearch extends ListRecords
{
    protected static string $resource = ResearchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Create Research Project')
                ->color('secondary')
                ->icon('heroicon-o-pencil-square'),

            Action::make('exportAll')
                ->label('Export')
                ->icon('heroicon-o-arrow-down-tray')
                ->action(fn () => ResearchResource::exportData(Research::all())),
                
        ];
    }
}
