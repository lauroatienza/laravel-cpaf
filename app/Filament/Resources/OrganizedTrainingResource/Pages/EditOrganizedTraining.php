<?php

namespace App\Filament\Resources\OrganizedTrainingResource\Pages;

use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\OrganizedTrainingResource;
use App\Models\ExtensionPrime;

class EditOrganizedTraining extends EditRecord
{
    protected static string $resource = OrganizedTrainingResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Combine first + last name
        $fullName = "{$data['first_name']} {$data['last_name']}";

        // Search for matching ExtensionPrime record
        $match = ExtensionPrime::where(function ($query) use ($fullName) {
            $query->where('researcher_names', 'LIKE', "%$fullName%")
                  ->orWhere('project_leader', 'LIKE', "%$fullName%");
        })->first();

        // If found, assign it to related_extension_program
        if ($match) {
            $data['related_extension_program'] = $match->id_no;
        }

        return $data;
    }
}
