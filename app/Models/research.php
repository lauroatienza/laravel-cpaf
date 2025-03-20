<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class research extends Model
{
    use HasFactory;

    protected $fillable = [
        'id', 'contributing_unit', 'start_date', 'end_date', 'extension_date',
        'status', 'title', 'objectives', 'expected_output', 'no_months_orig_timeframe',
        'name_of_researchers', 'source_funding', 'category_source_funding', 'budget',
        'type_funding', 'pdf_image_1', 'completed_date', 'sdg_theme', 'agora_theme',
        'flagship_theme', 'climate_ccam_initiative', 'disaster_risk_reduction',
        'pbms_upload_status'
    ];


}
