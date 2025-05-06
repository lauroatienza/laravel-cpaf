<?php

namespace App\Filament\Resources\TrainingAttendedResource\Pages;

use App\Filament\Resources\TrainingAttendedResource;
use App\Models\TrainingAttended;
use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListTrainingAttendeds extends ListRecords
{
    protected static string $resource = TrainingAttendedResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('create')
                ->label('Create Training Attended')
                ->color('secondary')
                ->icon('heroicon-o-pencil-square')
                ->url($this->getResource()::getUrl('create')),

            Action::make('exportAll')
                ->label('Export')
                ->icon('heroicon-o-arrow-down-tray')
                ->action(fn () => \App\Filament\Resources\TrainingAttendedResource::exportData(\App\Models\TrainingAttended::all())),
        ];
    }
}
