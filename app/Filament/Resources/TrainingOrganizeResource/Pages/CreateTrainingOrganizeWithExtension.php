<?php

namespace App\Filament\Resources\OrganizedTrainingResource\Pages;

use App\Filament\Resources\OrganizedTrainingResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\ExtensionPrime;
use App\Models\Research;

class CreateOrganizedTrainingWithExtension extends CreateRecord
{
    protected static string $resource = OrganizedTrainingResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
{
    $fullName = "{$data['first_name']} {$data['last_name']}";
    $fullNameReversed = "{$data['last_name']}, {$data['first_name']}";

    $titles = ['Dr.', 'Prof.', 'Engr.', 'Sir', 'Ms.', 'Mr.', 'Mrs.'];
    $normalize = fn($name) => preg_replace('/\s+/', ' ', trim(str_ireplace($titles, '', $name)));

    $normalizedFullName = $normalize($fullName);
    $normalizedFullNameReversed = $normalize($fullNameReversed);

    // Match Extension Program
    $extensionMatch = \App\Models\ExtensionPrime::where(function ($query) use ($normalizedFullName, $normalizedFullNameReversed) {
        $query->whereRaw("LOWER(REPLACE(researcher_names, 'Dr.', '')) LIKE LOWER(?)", ["%$normalizedFullName%"])
              ->orWhereRaw("LOWER(REPLACE(researcher_names, 'Dr.', '')) LIKE LOWER(?)", ["%$normalizedFullNameReversed%"])
              ->orWhereRaw("LOWER(project_leader) LIKE LOWER(?)", ["%$normalizedFullName%"]);
    })->first();

    if ($extensionMatch) {
        $data['related_extension_program'] = $extensionMatch->id_no;
    }

    // Match Research Program
    $researchMatch = \App\Models\Research::where(function ($query) use ($normalizedFullName, $normalizedFullNameReversed) {
        $query->whereRaw("LOWER(REPLACE(name_of_researchers, 'Dr.', '')) LIKE LOWER(?)", ["%$normalizedFullName%"])
              ->orWhereRaw("LOWER(REPLACE(name_of_researchers, 'Dr.', '')) LIKE LOWER(?)", ["%$normalizedFullNameReversed%"]);
    })->first();

    if ($researchMatch) {
        $data['related_research_program'] = $researchMatch->id;
    }

    return $data;
}
}
