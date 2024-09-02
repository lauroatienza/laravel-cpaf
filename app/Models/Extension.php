<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Extension extends Model
{
    use HasFactory;

    protected $fillable = [
        "contributing_unit",
        "faculty_id",
        "title",
        "start_date",
        "end_date",
        "extension_date",
        "event_highlight",
        "has_gender_component",
        "status",
        "objectives",
        "expected_output",
        "no_months_orig_timeframe",
        "name_of_researchers",
        "source_funding",
        "source_majority_share_of_funding",
        "budget",
        "type_funding",
        "pdf_image_1",
        "completed_date",
        "training_courses",
        "technical_advisory_service",
        "info_dissemination",
        "consultancy_external_clients",
        "community_outreach",
        "tech_transfer",
        "organizing_conference_eg",
        "delivery_units_academic_degree",
        "target_beneficiary_number",
        "target_beneficiary_group",
        "role_of_unit", //role of the unit and total hours spent.
        "sdg_theme",
        "agora_theme",
        "climate_ccam_initiative",
        "disaster_risk_reduction",
        "flagship_theme",
        "pbms_upload_status",
    ];

    public function faculty(){
        return $this->belongsTo(Faculty::class);
    }
}
