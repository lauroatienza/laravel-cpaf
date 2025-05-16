<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class OrganizedTraining extends Model
{
    use HasFactory, Notifiable;

    public static function booted()
{
    static::creating(function ($model) {
        $model->full_name = self::normalizeName($model->full_name);
    });

    static::updating(function ($model) {
        $model->full_name = self::normalizeName($model->full_name);
    });
}

protected static function normalizeName($name)
{
    $titles = ['Dr.', 'Prof.', 'Engr.', 'Sir', 'Ms.', 'Mr.', 'Mrs.'];

    // Remove titles and normalize spacing
    $name = str_ireplace($titles, '', $name);
    $name = preg_replace('/\s+/', ' ', trim($name));

    return $name;
}

    protected $fillable = [
        'full_name',
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
}
