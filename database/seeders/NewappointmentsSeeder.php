<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;
use Carbon\Carbon;

class NewappointmentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csv = Reader::createFromPath(storage_path('app/New Appointment.csv'), 'r');
        $csv->setHeaderOffset(0);

        foreach ($csv as $row) {
            DB::table('new_appointments')->insert([
                'time_stamp' => Carbon::parse($row['Timestamp'])->format('Y-m-d H:i:s'),
                'full_name' => $row['Full name (First Name, MI, Last Name)'],
                'type_of_appointments' => $row['Type of Appointment'],
                'position' => $row['Position'],
                'appointment' => $row['Appointment'],
                'appointment_effectivity_date' => Carbon::parse($row['Appointment effectivity date'])->format('Y-m-d'),
                'photo_url' => $row['Photo File or URL Link'] ?? null,
                'new_appointment_file_path' => $row['File URL Link'] ?? null, // Ensure this is populated correctly
                'updated_at' => now(),
            ]);
        }
    }
}
