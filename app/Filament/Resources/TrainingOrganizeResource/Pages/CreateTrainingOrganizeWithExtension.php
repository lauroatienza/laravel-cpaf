<?php

namespace App\Filament\Resources\OrganizedTrainingResource\Pages;

use App\Filament\Resources\OrganizedTrainingResource;
use Filament\Resources\Pages\CreateRecord;
use App\Models\ExtensionPrime;

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

        $match = ExtensionPrime::where(function ($query) use ($fullName) {
            $query->where('researcher_names', 'LIKE', "%$fullName%")
                  ->orWhere('project_leader', 'LIKE', "%$fullName%");
        })->first();

        if ($match) {
            $data['related_extension_program'] = $match->id_no;
        }

        return $data;
    }
}
