<?php
namespace Database\Seeders;

use App\Models\OrganizedTraining;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;
use Carbon\Carbon;

class OrganizedTrainingSeeder extends Seeder
{
    public function run()
    {
        $filePath = storage_path('app/imports/OrganizedTraining.csv'); // Fixed path separator

        if (!file_exists($filePath)) {
            $this->command->error("File not found: $filePath");
            return;
        }

        $csv = Reader::createFromPath($filePath, 'r');
        $csv->setHeaderOffset(0); // First row as header

        function toIntOrNull($value) {
            return is_numeric($value) ? (int) $value : null;
        }

        function formatDate($date) {
            return !empty($date) ? Carbon::parse($date)->toDateString() : null;
        }

        foreach ($csv as $record) {
            $fullName = $record['Full Name'] ?? null;

            if (empty($fullName)) {
                $this->command->error("Skipping entry: Full Name cannot be null. Record: " . json_encode($record));
                continue;
            }

            OrganizedTraining::create([
                'full_name' => $fullName,
                'contributing_unit' => $record['Contributing Unit'] ?? null,
                'title' => $record['Title of the Event'] ?? null,
                'start_date' => formatDate($record['Start Date']),
                'end_date' => formatDate($record['End Date']),
                'special_notes' => $record['Special Notes about the Schedule'] ?? null,
                'resource_persons' => substr($record['Resource Person(s)'] ?? null, 0, 255),
                'activity_category' => $record['Type of Activity Organized'] ?? null,
                'venue' => $record['Venue'] ?? null,
                'total_trainees' => toIntOrNull($record['Total Number of Trainees']),
                'weighted_trainees' => toIntOrNull($record['No. of persons trained weighted by length of training']),
                'training_hours' => toIntOrNull($record['Number of Hours Required to Complete Training (in Hours)']),
                'funding_source' => $record['Source of Majority Share of Funding for this Training'] ?? null,
                'sample_size' => toIntOrNull($record['Sample Size']),
                'responses_poor' => toIntOrNull($record['Number of Responses - Poor/Below Fair']),
                'responses_fair' => toIntOrNull($record['Number of Responses - Fair']),
                'responses_satisfactory' => toIntOrNull($record['Number of Responses - Satisfactory']),
                'responses_very_satisfactory' => toIntOrNull($record['Number of Responses - Very Satisfactory']),
                'responses_outstanding' => toIntOrNull($record['Number of Responses - Outstanding']),
                'related_extension_program' => $record['Related Extension Program, if applicable'] ?? null,
                'pdf_file_1' => $record['PDF Image File 1 (URL Link)'] ?? null,
                'pdf_file_2' => $record['PDF Image File 2 (URL Link)'] ?? null,
                'relevant_documents' => $record['Link for relevant documents'] ?? null,
                'project_title' => $record['If under Project, include here the title of the Project'] ?? null,
            ]);
        }

        $this->command->info('Training data seeded successfully!');
    }
}