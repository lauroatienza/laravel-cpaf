<?php

namespace App\Filament\Resources\DocumentResource\Pages;

use App\Filament\Resources\DocumentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Models\Document;
use Filament\Forms\Components\Select;


class ListDocuments extends ListRecords
{
    protected static string $resource = DocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Create button
            Actions\CreateAction::make()
                ->color('secondary')
                ->icon('heroicon-o-pencil-square')
                ->label('Create New Document'),

            // Export button
            Actions\Action::make('export')
            ->label('Export')
            ->color('primary')
            ->icon('heroicon-o-arrow-down-tray')
            ->form([
                Select::make('type')
                    ->label('Document Type')
                    ->options([
                        'ALL' => 'All Documents',
                        'MOA' => 'MOA',
                        'MOU' => 'MOU',
                    ])
                    ->default('ALL'),
                Select::make('format')
                    ->label('Export Format')
                    ->options([
                        'csv' => 'CSV',
                        'pdf' => 'PDF',
                    ])
                    ->required(),
            ])
            ->action(function (array $data) {
                $query = Document::query();

                // Filter based on the selected type
                if ($data['type'] === 'MOA') {
                    $query->where('partnership_type', 'Memorandum of Agreement (MOA)');
                } elseif ($data['type'] === 'MOU') {
                    $query->where('partnership_type', 'Memorandum of Understanding (MOU)');
                }

                // Get the records to be exported
                $records = $query->get();

                // Call exportData function to generate and download the file
                return DocumentResource::exportData($records, $data['format'], $data['type']);
            }),
        ];
    }
}
