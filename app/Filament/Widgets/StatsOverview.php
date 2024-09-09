<?php

namespace App\Filament\Widgets;

use App\Models\chapterInBook;
use App\Models\Classes;
use App\Models\Extension;
use App\Models\Faculty;
use App\Models\Sections;
use App\Models\Students;
use App\Models\research;
use App\Models\TrainingOrganize;
use Filament\Forms\Components\Section;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total: Faculty and REPS', Faculty::count()),
            Stat::make('Total: Research Project', research::count()),
            Stat::make('Total: Extension', Extension::count()),
            Stat::make('Total: Training Organized', TrainingOrganize::count()),
            Stat::make('Total: Chapter in Book', chapterInBook::count()),
        ];
    }
}
