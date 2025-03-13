<?php

namespace Database\Seeders; // Ensure this is included at the top

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DocumentSeeder extends Seeder
{
    public function run()
    {
        // Path to the CSV file
        $csvPath = storage_path('app/MOUMOA.csv');

        // Check if file exists
        if (!file_exists($csvPath)) {
            echo "CSV file not found at: $csvPath\n";
            return;
        }

        // Open and read CSV file
        if (($handle = fopen($csvPath, 'r')) !== FALSE) {
            $header = fgetcsv($handle, 1000, ','); // Get column headers
            
            while (($row = fgetcsv($handle, 1000, ',')) !== FALSE) {
                DB::table('documents')->insert([
                    'contributing_unit' => $row[1] ?? null,
                    'partnership_type' => $row[2] ?? null,
                    'extension_title' => $row[3] ?? null,
                    'partner_stakeholder' => $row[4] ?? null,
                    'start_date' => !empty($row[5]) ? date('Y-m-d', strtotime($row[5])) : null,
                    'end_date' => !empty($row[6]) ? date('Y-m-d', strtotime($row[6])) : null,
                    'training_courses' => $row[7] ?? null,
                    'technical_advisory_service' => $row[8] ?? null,
                    'information_dissemination' => $row[9] ?? null,
                    'consultancy' => $row[10] ?? null,
                    'community_outreach' => $row[11] ?? null,
                    'technology_transfer' => $row[12] ?? null,
                    'organizing_events' => $row[13] ?? null,
                    'scope_of_work' => $row[14] ?? null,
                    'pdf_file_url' => $row[15] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            fclose($handle);
        }
    }
}
