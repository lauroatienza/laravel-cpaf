<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class ViewFullProfile extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static string $view = 'filament.pages.view-full-profile';
    protected static ?string $title = 'Profile';

    public function getUser()
    {
        return Auth::user();
    }
}
