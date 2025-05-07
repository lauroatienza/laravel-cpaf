<?php

namespace App\Filament\Resources\ExtensionResource\Pages;

use App\Filament\Resources\ExtensionResource;
use Filament\Actions;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\ListRecords;

class ListExtensions extends ListRecords
{
    protected static string $resource = ExtensionResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Create Extension Involvement')
            ->icon('heroicon-o-pencil-square')
            ->color('secondary'),

            Actions\Action::make('export')
                ->label('Export')
                ->icon('heroicon-o-arrow-down-tray')
                ->form([
                    Select::make('format')
                        ->label('Export')
                        ->options([
                            'csv' => 'CSV',
                            'pdf' => 'PDF',
                        ])
                        ->required(),
                ])
                ->action(function (array $data) {
                    $records = \App\Models\Extension::all();
                    return ExtensionResource::exportData($records, $data['format']);
                })
                ->color('primary'),
        ];
    }
}
