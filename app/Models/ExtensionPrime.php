<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ExtensionPrime extends Model
{
    use HasFactory;

    protected $table = 'extension'; 
    

    protected $keyType = 'string'; 

    public $timestamps = false;

    protected $fillable = [
        'name',
        'contributing_unit',
        'start_date',
        'end_date',
        'extension_date',
        'status',
        'title_of_extension_program',
        'objectives',
        'expected_output',
        'original_timeframe_months',
        'researcher_names', // Name of Researcher/s or Extensionist
        'project_leader',
        'source_of_funding',
        'budget',
        'type_of_funding',
        'fund_code',
        'pdf_image_file',
        'training_courses', // Training Courses (non-degree and non-credit)
        'technical_service', // Technical/Advisory Service for external clients
        'info_dissemination', // Information Dissemination/Communication through mass media
        'consultancy_service', // Consultancy for external clients
        'community_outreach', // Community Outreach or Public Service
        'knowledge_transfer', // Technology or Knowledge Transfer
        'organizing_events', // Organizing symposium, forum, exhibit, etc.
        'benefited_academic_programs', // Academic Degree Programs benefited
        'target_beneficiary_count', // Number of Target Beneficiary Groups or Persons Served
        'target_beneficiary_group',
        'funding_source', // Source of Majority Share of Funding for this Training
        'role_of_unit', // Role of Unit and Total Hours Spent
        'unit_theme',
        'sdg_theme',
        'agora_theme',
        'cpaf_re_theme',
        'ccam_initiatives', // Change and Mitigation (CCAM) Initiatives (Y/N)
        'drrms', // Disaster Risk Reduction and Management Service (DRRMS) (Y/N)
        'project_article',
        'pbms_upload_status',
        'created_at',
        'updated_at',
    ];

    public function extension()
    {
        return $this->belongsTo(ExtensionPrime::class, 'extension_id');
    }


}
