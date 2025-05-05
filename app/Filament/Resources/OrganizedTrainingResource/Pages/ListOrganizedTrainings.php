<?php

namespace App\Filament\Resources\OrganizedTrainingResource\Pages;

use App\Filament\Resources\OrganizedTrainingResource;
use App\Models\OrganizedTraining;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables;
use Filament\Forms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;

class ListOrganizedTrainings extends ListRecords
{
    protected static string $resource = OrganizedTrainingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Create Organized Training')
                ->color('secondary')
                ->icon('heroicon-o-pencil-square'),

            Actions\Action::make('exportAll')
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
                ->action(fn(array $data) => OrganizedTrainingResource::exportData(OrganizedTraining::all(), $data['format'])),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Tables\Actions\ViewAction::make(),
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ];
    }

    protected function getTableBulkActions(): array
    {
        return [
            Tables\Actions\DeleteBulkAction::make(),
            Tables\Actions\BulkAction::make('exportBulk')
                ->label('Export Selected')
                ->icon('heroicon-o-arrow-down-tray')
                ->requiresConfirmation()
                ->form([
                    Forms\Components\Select::make('format')
                        ->options([
                            'csv' => 'CSV',
                            'pdf' => 'PDF',
                        ])
                        ->label('Export Format')
                        ->required(),
                ])
                ->action(fn(array $data, $records) => OrganizedTrainingResource::exportData($records, $data['format'])),
        ];
    }

    public function getTable(): Tables\Table
    {
        return parent::getTable()
            ->selectable();
    }
}