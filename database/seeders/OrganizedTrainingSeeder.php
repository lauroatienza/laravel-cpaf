<?php
namespace Database\Seeders;

use App\Models\OrganizedTraining;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;
use Carbon\Carbon; // Added for date formatting

class OrganizedTrainingSeeder extends Seeder
{
    public function run()
    {
        // Ensure the file exists in storage/app
        $filePath = storage_path('app/Training_Seminar Organized 2025.csv');

        if (!file_exists($filePath)) {
            $this->command->error("File not found: $filePath");
            return;
        }

        // Read CSV
        $csv = Reader::createFromPath($filePath, 'r');
        $csv->setHeaderOffset(0); // Assuming first row is headers

        // Function to convert empty strings to null for integer fields
        function toIntOrNull($value) {
            return is_numeric($value) ? (int) $value : null;
        }

        // Function to safely format dates
        function formatDate($date) {
            // If the date is not empty, try to format it using Carbon, otherwise return null
            return !empty($date) ? Carbon::parse($date)->toDateString() : null;
        }

        foreach ($csv as $record) {
            // Extract first, middle, and last names separately
            $firstName = $record['First Name'] ?? null;
            $middleName = $record['Middle Name'] ?? null;
            $lastName = $record['Last Name'] ?? null;

            // Ensure last name is not null (avoid integrity constraint error)
            if (empty($lastName)) {
                $this->command->error("Skipping entry: Last Name cannot be null. Record: " . json_encode($record));
                continue;
            }

            // Insert into the database
            OrganizedTraining::create([
                'first_name' => $firstName,
                'middle_name' => $middleName,
                'last_name' => $lastName,
                'contributing_unit' => $record['Contributing Unit'] ?? null,
                'title' => $record['Title of the Event'] ?? null,
                'start_date' => formatDate($record['Start Date']), // Fixed date format
                'end_date' => formatDate($record['End Date']), // Fixed date format
                'special_notes' => $record['Special Notes about the Schedule'] ?? null,
                'resource_persons' => substr($record['Resource Person(s)'] ?? null, 0, 255), // Corrected substr usage
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