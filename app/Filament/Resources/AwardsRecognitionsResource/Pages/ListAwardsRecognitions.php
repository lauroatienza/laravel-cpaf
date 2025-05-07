<?php

namespace App\Filament\Resources\AwardsRecognitionsResource\Pages;

use App\Filament\Resources\AwardsRecognitionsResource;
use App\Models\AwardsRecognitions;
use Filament\Actions;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\ListRecords;

class ListAwardsRecognitions extends ListRecords
{
    protected static string $resource = AwardsRecognitionsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('New Awards/Recognitions')
                ->icon('heroicon-o-pencil-square')
                ->color('secondary'),

            Actions\Action::make('export')
                ->label('Export')
                ->icon('heroicon-o-arrow-down-tray')
                ->form([
                    Select::make('format')
                        ->label('Select Export Format')
                        ->options([
                            'csv' => 'CSV',
                            'pdf' => 'PDF',
                        ])
                        ->required(),
                ])
                ->action(function (array $data) {
                    $awardsrecognitions = AwardsRecognitions::all();

                    return AwardsRecognitionsResource::exportData($awardsrecognitions, $data['format']);
                })
                ->color('primary'),
        ];
    }

}
