<?php

namespace App\Filament\Resources\OrganizedTrainingResource\Pages;

use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\OrganizedTrainingResource;
use App\Models\ExtensionPrime;
use App\Models\Research;

class EditOrganizedTraining extends EditRecord
{
    protected static string $resource = OrganizedTrainingResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $fullName = "{$data['first_name']} {$data['last_name']}";

        // Match Extension
        $extensionMatch = ExtensionPrime::where(function ($query) use ($fullName) {
            $query->where('researcher_names', 'LIKE', "%$fullName%")
                  ->orWhere('project_leader', 'LIKE', "%$fullName%");
        })->first();

        if ($extensionMatch) {
            $data['related_extension_program'] = $extensionMatch->id_no;
        }

        // Match Research
        $researchMatch = Research::where(function ($query) use ($fullName) {
            $query->where('name_of_researchers', 'LIKE', "%$fullName%");
        })->first();

        if ($researchMatch) {
            $data['related_research_program'] = $researchMatch->id; 
        }

        return $data;
    }
}
