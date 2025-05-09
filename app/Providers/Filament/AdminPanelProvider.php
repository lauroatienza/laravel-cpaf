<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use app\Filament\Resources\UserResource\Pages\Auth\Register;
use app\Filament\Pages\Auth\Logincustom;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Filament\Navigation\MenuItem;
use Filament\Navigation\UserMenuItem;
use Filament\Support\Facades\FilamentView;

use Illuminate\View\Middleware\ShareErrorsFromSession;
use Joaopaulolndev\FilamentEditProfile\FilamentEditProfilePlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel


            ->brandLogo(asset('brand3 (1).svg'))
            ->brandLogoHeight('100px')
            ->darkModeBrandLogo('/brand4.svg')
            ->default()
            ->id('admin')
            ->sidebarCollapsibleOnDesktop()
            ->path('cpaf')
            ->darkMode(true)
            ->databaseNotifications()
            ->login()

            ->userMenuItems([
                // Custom item for Documentation link
                UserMenuItem::make()
                    ->label('Official Website')
                    ->url('https://cpaf.uplb.edu.ph/')
                    ->icon('heroicon-o-book-open')
                    ->openUrlInNewTab(),
            ])

            //->registration(Register::class) 

            ->plugins([
                FilamentEditProfilePlugin::make()
                    ->setIcon('heroicon-o-user')
                    ->shouldShowAvatarForm(
                        value: true,
                        directory: 'avatars'
                    )
            ])

            ->colors([
                'primary' => '#367ab3   ',
                'secondary' => '#00573e',

            ])
            ->favicon(asset('cpaflogo.png'))
            ->databaseNotifications()
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
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
             
    }
}
