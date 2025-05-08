<?php

namespace App\Filament\Resources\FSRorRSRResource\Pages;

use App\Filament\Resources\FSRorRSRResource;
use App\Models\FSRorRSR;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms;
use Filament\Resources\Pages\ListRecords;

class ListFSRorRSRS extends ListRecords
{
    protected static string $resource = FSRorRSRResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Create FSR/RSR Attachment')
                ->color('secondary')
                ->icon('heroicon-o-pencil-square'),

            Action::make('exportAll')
                ->label('Export')
                ->icon('heroicon-o-arrow-down-tray')
                ->form([
                    Forms\Components\Select::make('format')
                        ->options([
                            'csv' => 'CSV',
                            'pdf' => 'PDF',
                        ])
                        ->label('Export Format')
                        ->required(),
                ])
                ->action(fn (array $data) => FSRorRSRResource::exportData(FSRorRSR::all(), $data['format'])),
        ];
    }
}
