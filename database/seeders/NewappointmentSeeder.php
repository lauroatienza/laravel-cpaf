<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;
use Carbon\Carbon;

class NewappointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csv = Reader::createFromPath(storage_path('app/newappointment_data.csv'), 'r');
        $csv->setHeaderOffset(0);

        foreach ($csv as $row) {
            DB::table('appointments')->insert([
                'created_at' => Carbon::parse($row['Timestamp'])->format('Y-m-d H:i:s'),
                'name' => $row['Full name (First Name, MI, Last Name)'],
                'type_of_appointments' =>  $row['Type of Appointment'], // Type of Appointment
                'position' => $row['Position'], // Position
                'appointment' => $row['Appointment'], // Appointment
                'appointment_effectivity_date' => Carbon::parse($row['Appointment Effectivity Date'])->format('Y-m-d'),
                'photo_url' => $row['Photo File or URL Link'] ?? null,
                'updated_at' => now(),
            ]);
        }
    }
}
