<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;
use Carbon\Carbon;

class ExtensionnewSeeder extends Seeder
{
    public function run()
    {
        $csv = Reader::createFromPath(storage_path('app\cpaf.csv'), 'r');
        $csv->setHeaderOffset(0); // Assuming first row is the header

        foreach ($csv as $row) {
            DB::table('extensionnew')->insert([
                'user_id' => 1, // Set a default user ID
                'created_at' => Carbon::parse($row['Timestamp'])->format('Y-m-d H:i:s'),
                'name' => $row['Full name (First Name, MI, Last Name)'],
                'extension_involvement' => $row['Type of extension involvement'],
                'event_title' => $row['Event/Activity Title'],
                'activity_date' => Carbon::parse($row['Start Date'])->format('Y-m-d'),
                'extensiontype' => $row['Type of extension'],
                'location' => $row['Venue and Location'],
                'date_end' => Carbon::parse($row['End Date'])->format('Y-m-d'),
                'updated_at' => now(),
            ]);
            
        }
    }
}
