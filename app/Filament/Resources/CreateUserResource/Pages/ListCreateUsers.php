<?php

namespace App\Filament\Resources\CreateUserResource\Pages;

use App\Filament\Resources\CreateUserResource;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms;
use Filament\Resources\Pages\ListRecords;

class ListCreateUsers extends ListRecords
{
    protected static string $resource = CreateUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Create New User')
                ->color('secondary')
                ->icon('heroicon-o-pencil-square'),
    
            Action::make('Export')
                ->form([
                    Forms\Components\Select::make('role')
                        ->label('Export Type')
                        ->options([
                            'admin' => 'Admin',
                            'faculty' => 'Faculty',
                            'REPS' => 'REPS',
                        ])
                        ->required(),
    
                    Forms\Components\Select::make('format')
                        ->label('Format')
                        ->options([
                            'pdf' => 'PDF',
                            'csv' => 'CSV',
                        ])
                        ->required(),
                ])
                ->modalButton('Download')
                ->color('primary')
                ->icon('heroicon-o-arrow-down-tray')
                ->action(function (array $data) {
                    $users = User::where('staff', $data['role'])->get();
                    $title = ucfirst($data['role']) . ' List';
    
                    if ($data['format'] === 'pdf') {
                        $pdf = Pdf::loadView('exports.faculty', compact('users', 'title'));
    
                        return response()->streamDownload(
                            fn() => print($pdf->output()),
                            "{$data['role']}_list.pdf"
                        );
                    }
    
                    if ($data['format'] === 'csv') {
                        return response()->streamDownload(function () use ($users) {
                            $handle = fopen('php://output', 'w');
                            fputcsv($handle, ['Full Name', 'Role', 'Email']); // customize columns
                            foreach ($users as $user) {
                                fputcsv($handle, [
                                    $user->name,
                                    $user->staff,
                                    $user->email,
                                ]);
                            }
                            fclose($handle);
                        }, "{$data['role']}_list.csv");
                    }
    
                    return null;
                }),
        ];
    }
}
