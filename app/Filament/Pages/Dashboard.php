<?php
 
namespace App\Filament\Pages;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use App\Filament\Widgets\CalendarWidget;
use App\Filament\Widgets\StatsOverview;
 
class Dashboard extends \Filament\Pages\Dashboard
{
 use HasFiltersForm;
 public function filtersForm(Form $form): Form
 {

    return $form->schema([
        Section::make('')->schema([
            DatePicker::make('StartDate')->default(now()->subMonths(12)),
            DatePicker::make('EndDate')->default(now()),
        ])->columns(2)
    ]);

    
 } 
 
 
}