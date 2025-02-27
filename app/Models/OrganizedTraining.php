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
        'related_program', 'pdf_file_1', 'pdf_file_2', 'relevant_documents',
        'project_title'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date'
    ];
}
