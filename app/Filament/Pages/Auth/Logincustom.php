<?php

namespace App\Filament\Pages\Auth;


use Filament\Pages\Auth\Login as BaseLogin;
use Illuminate\Contracts\View\View;

class Logincustom extends BaseLogin
{
    protected static string $view = 'filament-panels::pages.auth.login';

}
