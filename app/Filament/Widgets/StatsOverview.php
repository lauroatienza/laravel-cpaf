<?php

namespace App\Filament\Widgets;

use App\Models\chapterInBook;
use App\Models\Classes;
use App\Models\Extension;
use App\Models\User;
use App\Models\Sections;
use App\Models\Students;
use App\Models\research;
use App\Models\TrainingOrganize;
use Filament\Forms\Components\Section;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    use InteractsWithPageFilters;

    protected function getStats(): array
    {
        $Start = $this->filters['StartDate'];
        $End = $this->filters['EndDate'];

        return [
            Stat::make('Total: Faculty and REPS', 
            User::
             where('staff', ['faculty', 'representatives']) // Count both roles
            ->count()
           
            )
            ->chart([1,3,5])
            ->color('success'),
        

            Stat::make('Total: Research Project', research::count())
                ->chart([1,3,5])
                ->color('success'),

            Stat::make('Total: Extension', Extension::count())  
                ->chart([1,3,5])
                ->color('success'),

            Stat::make('Total: Training Organized', TrainingOrganize::count())
                ->chart([1,3,5])
                ->color('success'),
                
            Stat::make('Total: Chapter in Book', chapterInBook::count())
                ->chart([1,3,5])
                ->color('success'),
        ];
    }
}
