<?php

namespace App\Observers;

use App\Models\User;
use App\Notifications\UserUpdatedNotification;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
class UserObserver
{
    /**
     * Handle the User "created" event.
     * @param  \App\Models\User  $user
     * @return void
     */
    public function created(User $user): void
    {
      
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        $receipient =Auth::user();
        Notification::make()
            ->title('User Updated')
            ->body("{$user->name} was updated.")
            ->success()
            ->sendToDatabase($receipient);
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
