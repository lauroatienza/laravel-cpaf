<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class NewappointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $filePath = storage_path('app/appointments.csv');

        if (!file_exists($filePath)) {
            echo "CSV file not found!";
            return;
        }

        // Read CSV file
        $csvData = array_map('str_getcsv', file($filePath));
        array_shift($csvData); // Remove header row

        foreach ($csvData as $row) {
            DB::table('appointments')->insert([
                'dtype' => $row[2], // Type of Appointment
                'of_appointmentsposition' => $row[3], // Position
                'appointment' => $row[4], // Appointment
                'appointment_effectivity_date' => Carbon::createFromFormat('m/d/Y', $row[5])->toDateString(),
                'photo_url' => $row[6] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        echo "Data imported successfully!";
    }
}
