<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // Path to your CSV file
        $csvFile = storage_path('app/rcrd.csv'); // Make sure the CSV is stored in the right location

        if (!file_exists($csvFile) || !is_readable($csvFile)) {
            return;
        }

        // Open the CSV file
        if (($handle = fopen($csvFile, 'r')) !== false) {
            // Skip the header row
            fgetcsv($handle);

            while (($data = fgetcsv($handle)) !== false) {
                // Extract values from the CSV row
                list(
                    $office,
                    $surname,
                    $firstName,
                    $middleName,
                    $position,
                    $rank,
                    $sg,
                    $s,
                    $classification,
                    $status,
                    $itemNumber,
                    $emailAddress,
                    $birthday,
                    $education,
                    $yearGraduated,
                    $dateHired,
                    $contactNumber
                ) = $data;

                // Generate email and password
                $email = strtolower($firstName) . '@gmail.com';
                $password = Hash::make('12345678');  // Default password

                // Insert data into the users table
                DB::table('users')->insert([
                    'name' => $firstName,
                    'email' => $email,
                    'email_verified_at' => Carbon::now(),
                    'password' => $password,
                    'staff' => 'employee',  // Modify as needed
                    'remember_token' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'last_name' => $surname,
                    'middle_name' => $middleName,
                    'employment_status' => $status,
                    'designation' => $position,
                    'unit' => $office,
                    'ms_phd' => $education,  // You can map this according to your CSV data
                    'systemrole' => 'user',  // Default role for the user
                    'custom_fields' => json_encode([]),  // Example, modify as needed
                    'avatar_url' => null,
                    'fulltime_partime' => $classification,  // Example mapping
                    'rank_' => $rank,
                    'sg' => $sg,
                    's' => $s,
                    'birthday' => $birthday,
                    'item_no' => $itemNumber,
                    'yr_grad' => $yearGraduated,
                    'date_hired' => $dateHired,
                    'contact_no' => $contactNumber,
                ]);
            }

            fclose($handle);
        }
    }
}
