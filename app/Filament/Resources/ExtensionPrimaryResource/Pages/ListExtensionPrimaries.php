<?php

namespace App\Filament\Resources\ExtensionPrimaryResource\Pages;

use App\Filament\Resources\ExtensionPrimaryResource;
use App\Models\ExtensionPrime;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms;
use Filament\Resources\Pages\ListRecords;

class ListExtensionPrimaries extends ListRecords
{
    protected static string $resource = ExtensionPrimaryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Create Extension Program')
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
                ->action(fn (array $data) => ExtensionPrimaryResource::exportData(ExtensionPrime::all(), $data['format'])),
        ];
    }
}
