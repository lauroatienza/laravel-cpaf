<?php

namespace App\Observers;

use App\Models\OrganizedTraining;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class OrganizeTrainingObserver
{
    /**
     * Handle the OrganizedTraining "created" event.
     * @param  \App\Models\User  $user
     * @return void
     */
    public function created(OrganizedTraining $organizedTraining): void
    {
        $titles = ['Dr.', 'Prof.', 'Engr.', 'Sir', 'Ms.', 'Mr.', 'Mrs.'];
        $normalizeText = fn($text) => strtolower(preg_replace('/\s+/', ' ', trim(str_ireplace($titles, '', $text))));
        $researchersField = $normalizeText($organizedTraining->full_name);
    
        foreach (User::all() as $user) {
            $nameVariants = collect([
                "{$user->name} {$user->middle_name} {$user->last_name}",
                "{$user->name} {$user->last_name}",
                "{$user->last_name}, {$user->name}",
                "{$user->last_name}, " . strtoupper(substr($user->name, 0, 1)) . ($user->middle_name ? '.' . strtoupper(substr($user->middle_name, 0, 1)) : '') . '.',
                strtoupper(substr($user->name, 0, 1)) . '.' . ($user->middle_name ? strtoupper(substr($user->middle_name, 0, 1)) . '.' : '') . strtoupper(substr($user->last_name, 0, 1)) . '.',
                "{$user->name}",
                "{$user->last_name}",
            ])->map($normalizeText);
    
            if ($nameVariants->contains(fn($variant) => strpos($researchersField, $variant) !== false)) {
                Notification::make()
                    ->title('Training Organized')
                    ->body("There is a Training Organized related with you! Check it out!")
                    ->success()
                    ->sendToDatabase($user);
            }
        }
    }
    

    public function updated(OrganizedTraining $organizedTraining): void
    {
        $titles = ['Dr.', 'Prof.', 'Engr.', 'Sir', 'Ms.', 'Mr.', 'Mrs.'];
        $normalizeText = fn($text) => strtolower(preg_replace('/\s+/', ' ', trim(str_ireplace($titles, '', $text))));
        $researchersField = $normalizeText($organizedTraining->full_name);
        


        foreach (User::all() as $user) {
            $nameVariants = collect([
                "{$user->name} {$user->middle_name} {$user->last_name}",
                "{$user->name} {$user->last_name}",
                "{$user->last_name}, {$user->name}",
                "{$user->last_name}, " . strtoupper(substr($user->name, 0, 1)) . ($user->middle_name ? '.' . strtoupper(substr($user->middle_name, 0, 1)) : '') . '.',
                strtoupper(substr($user->name, 0, 1)) . '.' . ($user->middle_name ? strtoupper(substr($user->middle_name, 0, 1)) . '.' : '') . strtoupper(substr($user->last_name, 0, 1)) . '.',
                "{$user->name}",
                "{$user->last_name}",
            ])->map($normalizeText);
    
            if ($nameVariants->contains(fn($variant) => strpos($researchersField, $variant) !== false)) {
                Notification::make()
                    ->title('Training Organized')
                    ->body("There is a Training Organized related with you! Check it out!")
                    ->success()
                    ->sendToDatabase($user);
            }
        }
    }

    public function deleted(OrganizedTraining $organizedTraining): void
    {
        //
    }

    public function restored(OrganizedTraining $organizedTraining): void
    {
        //
    }

    public function forceDeleted(OrganizedTraining $organizedTraining): void
    {
        //
    }
}
