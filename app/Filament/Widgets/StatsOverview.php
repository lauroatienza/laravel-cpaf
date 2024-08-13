<?php

namespace App\Filament\Widgets;

use App\Models\Classes;
use App\Models\Sections;
use App\Models\Students;
use App\Models\research;
use Filament\Forms\Components\Section;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total: Research Project', research::count()),
            Stat::make('Total: Classes', Classes::count()),
            Stat::make('Total: Sections', Sections::count()),
            Stat::make('Total: Students', Students::count()),
        ];
    }
}
