<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class research extends Model
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
        "category_source_funding",
        "budget",
        "type_funding",
        "pdf_image_1",
        "completed_date",
        "sdg_theme",
        "agora_theme",
        "climate_ccam_initiative",
        "disaster_risk_reduction",
        "flagship_theme",
        "pbms_upload_status",
    ];

     

}
