<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'contributing_unit', 'partnership_type', 'extension_title', 'partner_stakeholder', 
        'start_date', 'end_date', 'training_courses', 'technical_advisory_service', 
        'information_dissemination', 'consultancy', 'community_outreach', 
        'technology_transfer', 'organizing_events', 'scope_of_work', 'documents_file_path',
    ];
}
