<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;
use Carbon\Carbon;

class ExtensionPrimeSeeder extends Seeder
{
    public function run()
    {
        // Path to the CSV file
        $csvFile = storage_path('app/exprim.csv');

        // Read CSV
        $csv = Reader::createFromPath($csvFile, 'r');
        $csv->setHeaderOffset(0); // Use first row as header
        $csv->setDelimiter(","); // Ensure it's a comma-separated file

        // Clean and normalize headers
        $headers = array_map('trim', $csv->getHeader());

        foreach ($csv as $index => $record) {
            try {
                DB::table('extension')->insert([
                    'id_no' => !empty($record[$headers[0]]) ? (int) $record[$headers[0]] : null,
                    'contributing_unit' => $record[$headers[1]] ?? null, // Contributing Unit
                    'start_date' => isset($record[$headers[2]]) ? Carbon::parse(trim($record[$headers[2]])) : null,
                    'end_date' => isset($record[$headers[3]]) ? Carbon::parse(trim($record[$headers[3]])) : null,
                    'extension_date' => $record[$headers[4]] ?? null, 
                    'status' => $record[$headers[5]] ?? null,
                    'title_of_extension_program' => $record[$headers[6]] ?? null,
                    'objectives' => $record[$headers[7]] ?? null,
                    'expected_output' => $record[$headers[8]] ?? null,
                    'original_timeframe_months' => $record[$headers[9]] ?? null,
                    'researcher_names' => $record[$headers[10]] ?? null,
                    'project_leader' => $record[$headers[11]] ?? null,
                    'source_of_funding' => $record[$headers[12]] ?? null,
                    'budget' => $record[$headers[13]] ?? null,
                    'type_of_funding' => $record[$headers[14]] ?? null,
                    'fund_code' => $record[$headers[15]] ?? null,
                    'pdf_image_file' => $record[$headers[16]] ?? null,
                    'training_courses' => $record[$headers[17]] ?? null,
                    'technical_service' => $record[$headers[18]] ?? null,
                    'info_dissemination' => $record[$headers[19]] ?? null,
                    'consultancy_service' => $record[$headers[20]] ?? null,
                    'community_outreach' => $record[$headers[21]] ?? null,
                    'knowledge_transfer' => $record[$headers[22]] ?? null,
                    'organizing_events' => $record[$headers[23]] ?? null,
                    'benefited_academic_programs' => $record[$headers[24]] ?? null,
                    'target_beneficiary_count' => $record[$headers[25]] ?? null,
                    'target_beneficiary_group' => $record[$headers[26]] ?? null,
                    'funding_source' => $record[$headers[27]] ?? null,
                    'role_of_unit' => $record[$headers[28]] ?? null,
                    'unit_theme' => $record[$headers[29]] ?? null,
                    'sdg_theme' => $record[$headers[30]] ?? null,
                    'agora_theme' => $record[$headers[31]] ?? null,
                    'cpaf_re_theme' => $record[$headers[32]] ?? null,
                    'ccam_initiatives' => isset($record[$headers[33]]) && trim($record[$headers[33]]) === 'Y' ? 1 : 0,
                    'drrms' => isset($record[$headers[34]]) && trim($record[$headers[34]]) === 'Y' ? 1 : 0,
                    'project_article' => $record[$headers[35]] ?? null,
                    'pbms_upload_status' => $record[$headers[36]] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } catch (\Exception $e) {
                echo "Error at Row $index: " . $e->getMessage() . "\n";
            }
        }
    }
}
