<?php

namespace App\Observers;

use App\Models\research;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ResearchObserver
{
    /**
     * Handle the research "created" event.
     * @param  \App\Models\User  $user
     * @return void
     */
    public function created(research $research): void
    {
        //
    }

    /**
     * Handle the research "updated" event.
     */
    public function updated(research $research): void
    {
        \Log::info('Research updated observer triggered.');

        // Clean researcher field
        $titles = ['Dr.', 'Prof.', 'Engr.', 'Sir', 'Ms.', 'Mr.', 'Mrs.'];
        $normalizeText = fn($text) => strtolower(preg_replace('/\s+/', ' ', trim(str_ireplace($titles, '', $text))));
        $researchersField = $normalizeText($research->name_of_researchers);

        \Log::info('Researchers Field Normalized:', ['value' => $researchersField]);

        foreach (User::all() as $user) {
            // Create variations of the user's name based on different formats
            $nameVariants = collect([
                // First name, middle name, last name
                "{$user->name} {$user->middle_name} {$user->last_name}",
                // First name + last name
                "{$user->name} {$user->last_name}",
                // Last name, first name
                "{$user->last_name}, {$user->name}",
                // Last name + initials of first and middle names
                "{$user->last_name}, " . strtoupper(substr($user->name, 0, 1)) . ($user->middle_name ? '.' . strtoupper(substr($user->middle_name, 0, 1)) : '') . '.',
                // Initials format
                strtoupper(substr($user->name, 0, 1)) . '.' . ($user->middle_name ? strtoupper(substr($user->middle_name, 0, 1)) . '.' : '') . strtoupper(substr($user->last_name, 0, 1)) . '.',
                // First name only
                "{$user->name}",
                // Last name only
                "{$user->last_name}",
            ])->map(fn($name) => $normalizeText($name));


            \Log::info('Name Variants:', ['user' => $user->name, 'variants' => $nameVariants]);

            // Check if any of the name variants appear in the researchers field
            if ($nameVariants->contains(fn($variant) => str_contains($researchersField, $variant))) {
                Notification::make()
                    ->title('Research Updated')
                    ->body("The research you're involved in has been updated.")
                    ->success()
                    ->sendToDatabase($user);

                \Log::info('Notification sent to:', ['user' => $user->name]);
            }
        }
    }







    /**
     * Handle the research "deleted" event.
     */
    public function deleted(research $research): void
    {
        //
    }

    /**
     * Handle the research "restored" event.
     */
    public function restored(research $research): void
    {
        //
    }

    /**
     * Handle the research "force deleted" event.
     */
    public function forceDeleted(research $research): void
    {
        //
    }
}
