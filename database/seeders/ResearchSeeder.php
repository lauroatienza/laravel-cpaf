<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\research;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ResearchSeeder extends Seeder
{
    public function run()
    {
        $filePath = storage_path('app/research.csv');
        $file = fopen($filePath, 'r');

        // Read the first row (headers)
        $headers = fgetcsv($file);

        while (($row = fgetcsv($file)) !== false) {
            // Ensure ID is not empty; if empty, set to NULL or auto-increment
            $id = !empty($row[0]) ? intval($row[0]) : null;

            research::create([
                'id' => $id, // Ensure ID is handled properly
                'contributing_unit' => $row[1] ?? null,
                'start_date' => !empty($row[2]) ? date('Y-m-d', strtotime($row[2])) : null,
                'end_date' => !empty($row[3]) ? date('Y-m-d', strtotime($row[3])) : null,
                'extension_date' => !empty($row[4]) ? date('Y-m-d', strtotime($row[4])) : null,
                'status' => $row[5] ?? null,
                'title' => $row[6] ?? null,
                'objectives' => $row[7] ?? null,
                'expected_output' => $row[8] ?? null,
                'no_months_orig_timeframe' => !empty($row[9]) ? intval($row[9]) : null,
                'name_of_researchers' => $row[10] ?? null,
                'source_funding' => $row[12] ?? null,
                'category_source_funding' => $row[13] ?? null,
                'budget' => !empty($row[14]) ? floatval($row[14]) : null,
                'type_funding' => $row[15] ?? null,
                'pdf_image_1' => $row[16] ?? null,
                'completed_date' => !empty($row[17]) ? date('Y-m-d', strtotime($row[17])) : null,
                'sdg_theme' => $row[18] ?? null,
                'agora_theme' => $row[19] ?? null,
                'flagship_theme' => $row[20] ?? null,
                'climate_ccam_initiative' => !empty($row[21]) ? ($row[21] == 'Y' ? 1 : 0) : null,
                'disaster_risk_reduction' => !empty($row[22]) ? ($row[22] == 'Y' ? 1 : 0) : null,
                'pbms_upload_status' => $row[23] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        fclose($file);
    }
}
