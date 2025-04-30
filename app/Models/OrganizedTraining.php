<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizedTraining extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name', 'middle_name', 'last_name', 
        'contributing_unit', 'title', 'start_date', 'end_date',
        'special_notes', 'resource_persons', 'activity_category', 'venue',
        'total_trainees', 'weighted_trainees', 'training_hours',
        'funding_source', 'sample_size',
        'responses_poor', 'responses_fair', 'responses_satisfactory',
        'responses_very_satisfactory', 'responses_outstanding',
        'related_extension_program',
        'related_research_program',
        'pdf_file_1', 'pdf_file_2', 'relevant_documents',
        'project_title'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    // Relationships
    public function relatedExtension()
    {
        return $this->belongsTo(ExtensionPrime::class, 'related_extension_program', 'id_no');
    }

    public function relatedResearch()
    {
        return $this->belongsTo(Research::class, 'related_research_program', 'id');
    }

    // Computed accessor for matched research program
    public function getRelatedResearchAttribute()
    {
        $fullName = trim("{$this->first_name} {$this->last_name}");
        $fullNameReversed = trim("{$this->last_name}, {$this->first_name}");
        $simpleName = trim("{$this->first_name} {$this->last_name}");

        $titles = ['Dr.', 'Prof.', 'Engr.', 'Sir', 'Ms.', 'Mr.', 'Mrs.'];
        $normalize = fn($name) => preg_replace('/\s+/', ' ', trim(str_ireplace($titles, '', $name)));

        $normalizedFullName = $normalize($fullName);
        $normalizedFullNameReversed = $normalize($fullNameReversed);
        $normalizedSimpleName = $normalize($simpleName);

        return \App\Models\Research::where(function ($query) use (
            $normalizedFullName,
            $normalizedFullNameReversed,
            $normalizedSimpleName
        ) {
            $query->whereRaw("LOWER(REPLACE(name_of_researchers, 'Dr.', '')) LIKE LOWER(?)", ["%$normalizedFullName%"])
                ->orWhereRaw("LOWER(REPLACE(name_of_researchers, 'Dr.', '')) LIKE LOWER(?)", ["%$normalizedFullNameReversed%"])
                ->orWhereRaw("LOWER(REPLACE(name_of_researchers, 'Dr.', '')) LIKE LOWER(?)", ["%$normalizedSimpleName%"]);
        })->first();
    }

    // Auto-populate related program fields on create/update
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($record) {
            if (!$record->related_extension_program) {
                $record->related_extension_program = self::matchExtensionProgram($record);
            }

            if (!$record->related_research_program) {
                $record->related_research_program = self::matchResearchProgram($record);
            }
        });
    }

    // Matching Extension Program
    protected static function matchExtensionProgram($record)
    {
        if (!$record->first_name || !$record->last_name) {
            return null;
        }

        $fullName = trim("{$record->first_name} {$record->last_name}");
        $fullNameReversed = trim("{$record->last_name}, {$record->first_name}");

        $titles = ['Dr.', 'Prof.', 'Engr.', 'Sir', 'Ms.', 'Mr.', 'Mrs.'];
        $normalize = fn($name) => preg_replace('/\s+/', ' ', trim(str_ireplace($titles, '', $name)));

        $normalized = $normalize($fullName);
        $reversed = $normalize($fullNameReversed);

        $match = \App\Models\ExtensionPrime::where(function ($query) use ($normalized, $reversed) {
            $query->whereRaw("LOWER(REPLACE(researcher_names, 'Dr.', '')) LIKE LOWER(?)", ["%$normalized%"])
                ->orWhereRaw("LOWER(REPLACE(researcher_names, 'Dr.', '')) LIKE LOWER(?)", ["%$reversed%"])
                ->orWhereRaw("LOWER(project_leader) LIKE LOWER(?)", ["%$normalized%"]);
        })->first();

        return $match?->title_of_extension_program;
    }

    // Matching Research Program
    protected static function matchResearchProgram($record)
    {
        $fullName = trim("{$record->first_name} {$record->last_name}");
        $fullNameReversed = trim("{$record->last_name}, {$record->first_name}");
        $simpleName = trim("{$record->first_name} {$record->last_name}");

        $titles = ['Dr.', 'Prof.', 'Engr.', 'Sir', 'Ms.', 'Mr.', 'Mrs.'];
        $normalize = fn($name) => preg_replace('/\s+/', ' ', trim(str_ireplace($titles, '', $name)));

        $normalizedFullName = $normalize($fullName);
        $normalizedFullNameReversed = $normalize($fullNameReversed);
        $normalizedSimpleName = $normalize($simpleName);

        $match = \App\Models\Research::where(function ($query) use (
            $normalizedFullName,
            $normalizedFullNameReversed,
            $normalizedSimpleName
        ) {
            $query->whereRaw("LOWER(REPLACE(name_of_researchers, 'Dr.', '')) LIKE LOWER(?)", ["%$normalizedFullName%"])
                ->orWhereRaw("LOWER(REPLACE(name_of_researchers, 'Dr.', '')) LIKE LOWER(?)", ["%$normalizedFullNameReversed%"])
                ->orWhereRaw("LOWER(REPLACE(name_of_researchers, 'Dr.', '')) LIKE LOWER(?)", ["%$normalizedSimpleName%"]);
        })->first();

        return $match?->id;
    }
}
