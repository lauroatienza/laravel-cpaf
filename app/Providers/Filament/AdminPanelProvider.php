<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use app\Filament\Resources\UserResource\Pages\Auth\Register;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->brandLogo(asset('logo5.svg'))
            ->brandLogoHeight('100%')
            ->default()
            ->id('admin')
            ->sidebarCollapsibleOnDesktop()
            ->path('admin')
            ->login()
            //->registration(Register::class) 
            ->colors([
               'secondary' => [
    50 => '240, 255, 250',  // Lightest green
    100 => '204, 255, 229',
    200 => '153, 255, 204',
    300 => '102, 255, 178',
    400 => '51, 255, 153',
    500 => '26, 230, 128',  // **Main Light Green**
    600 => '20, 200, 110',
    700 => '15, 170, 95',
    800 => '10, 140, 80',
    900 => '5, 110, 65',
    950 => '3, 80, 50',  // Darkest green
],

'primary' => [
    50 => '235, 248, 255',  // Lightest blue
    100 => '200, 235, 255',
    200 => '170, 220, 255',
    300 => '140, 205, 255',
    400 => '110, 190, 255',
    500 => '80, 175, 255',  // **Main Light Blue**
    600 => '60, 160, 240',
    700 => '50, 145, 225',
    800 => '40, 130, 210',
    900 => '30, 115, 195',
    950 => '20, 100, 180',  // Darkest blue
],
            ])
            ->favicon(asset('public\cpaflogo.png'))// add favicon
            ->databaseNotifications()
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                // Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                //Widgets\AccountWidget::class,
                //Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->brandName('CPAf Intranet');  
            //->brandLogo(asset('public\cpaflogo.png'))
            //->favicon(asset('public/favicon.ico'))
        }
    
}
