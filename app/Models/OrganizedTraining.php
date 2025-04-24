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
        'related_extension_program', // ✅ make sure this matches your table name
        'pdf_file_1', 'pdf_file_2', 'relevant_documents',
        'project_title'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date'
    ];

    public function relatedExtension()
    {
        return $this->belongsTo(ExtensionPrime::class, 'related_extension_program', 'id_no');
    }

    // ✅ Auto-populate related_extension_program on create/update
    protected static function booted()
    {
        static::creating(function ($record) {
            $record->related_extension_program = self::matchExtensionProgram($record);
        });

        static::updating(function ($record) {
            $record->related_extension_program = self::matchExtensionProgram($record);
        });
    }

    // ✅ Matching logic
    protected static function matchExtensionProgram($record)
    {
        if (!$record->first_name || !$record->last_name) {
            return null;
        }

        $firstName = $record->first_name;
        $lastName = $record->last_name;
        $fullName = trim("$firstName $lastName");
        $fullNameReversed = trim("$lastName, $firstName");

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
}
