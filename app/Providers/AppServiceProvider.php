<?php
namespace App\Providers;

use App\Models\OrganizedTraining;
use Illuminate\Support\ServiceProvider;
use Filament\Support\Facades\FilamentView;
use Illuminate\Support\Facades\Blade;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Navigation\MenuItem;

use Filament\Navigation\Topbar;
use App\Models\Research;
use App\Observers\UserObserver;
use App\Observers\ResearchObserver;
use App\Observers\OrganizeTrainingObserver;
use Illuminate\View\View; // Make sure this is imported
use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        FilamentView::registerRenderHook(
            'panels::auth.login.form.after',
            fn (): View => view('filament.login_extra')
        );
    

        User::observe(UserObserver::class);
        Research::observe(ResearchObserver::class);
        OrganizedTraining::observe(OrganizeTrainingObserver::class);
        

    }
}
