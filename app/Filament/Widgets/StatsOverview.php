<?php

namespace App\Filament\Widgets;

use App\Models\chapterInBook;
use App\Models\Extension;
use App\Models\research;
use App\Models\TrainingOrganize;
use App\Models\OrganizedTraining;
use App\Models\User;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class StatsOverview extends BaseWidget
{
    use InteractsWithPageFilters;

    protected function getStats(): array
    {
        $user = Auth::user();

        // If the user is an admin, count all records
        if ($user->hasRole(['super-admin', 'admin'])) {
            return [
                Stat::make('Total: Faculty and REPS', User::whereIn('staff', ['faculty', 'representatives'])->count())
                    ->chart([1, 3, 5])
                    ->color('success'),

                Stat::make('Total: Research Project', research::count())
                    ->chart([1, 3, 5])
                    ->color('success'),

                Stat::make('Total: Extension', Extension::count())
                    ->chart([1, 3, 5])
                    ->color('success'),

                Stat::make('Total: Training Organized', TrainingOrganize::count())
                    ->chart([1, 3, 5])
                    ->color('success'),

                Stat::make('Total: Chapter in Book', chapterInBook::count())
                    ->chart([1, 3, 5])
                    ->color('success'),
            ];
        }

        // Build possible name formats (same logic as getEloquentQuery)
        $fullName = trim($user->name . ' ' . ($user->middle_name ?? '') . ' ' . $user->last_name);
        $fullNameReversed = trim($user->last_name . ', ' . $user->name . ' ' . ($user->middle_name ?? ''));
        $simpleName = trim($user->name . ' ' . $user->last_name);

        $titles = ['Dr.', 'Prof.', 'Engr.', 'Sir', 'Ms.', 'Mr.', 'Mrs.'];
        
        // Function to normalize names by removing titles and extra spaces
        $normalizeName = function ($name) use ($titles) {
            // Remove titles
            $nameWithoutTitles = str_ireplace($titles, '', $name);
            // Replace multiple spaces with a single space
            return preg_replace('/\s+/', ' ', trim($nameWithoutTitles));
        };
        
        $normalizedFullName = $normalizeName($fullName);
        $normalizedFullNameReversed = $normalizeName($fullNameReversed);
        $normalizedSimpleName = $normalizeName($simpleName);
        return [
            Stat::make('Total: Faculty and REPS', User::whereIn('staff', ['faculty', 'representatives'])
                ->where('id', $user->id) // Only count the logged-in user
                ->count())
                ->chart([1, 3, 5])
                ->color('primary'),

            Stat::make('Total: Research Project', research::where(function ($query) use ($normalizedFullName, $normalizedFullNameReversed, $normalizedSimpleName) {
                 $query->whereRaw("LOWER(REPLACE(name_of_researchers, 'Dr.', '')) LIKE LOWER(?)", ["%$normalizedFullName%"])
              ->orWhereRaw("LOWER(REPLACE(name_of_researchers, 'Dr.', '')) LIKE LOWER(?)", ["%$normalizedFullNameReversed%"])
              ->orWhereRaw("LOWER(REPLACE(name_of_researchers, 'Dr.', '')) LIKE LOWER(?)", ["%$normalizedSimpleName%"]);
            })->count())

                ->chart([1, 3, 5])
                ->color('primary'),

            Stat::make('Total: Extension', Extension::where(function ($query) use ($normalizedFullName, $normalizedFullNameReversed, $normalizedSimpleName) {
                $query->whereRaw("LOWER(REPLACE(name, 'Dr.', '')) LIKE LOWER(?)", ["%$normalizedFullName%"])
                ->orWhereRaw("LOWER(REPLACE(name, 'Dr.', '')) LIKE LOWER(?)", ["%$normalizedFullNameReversed%"])
                ->orWhereRaw("LOWER(REPLACE(name, 'Dr.', '')) LIKE LOWER(?)", ["%$normalizedSimpleName%"]);
            })->count())
                ->chart([1, 3, 5])
                ->color('primary'),

            Stat::make('Total: Training Organized', OrganizedTraining::where(function ($query) use ($normalizedFullName, $normalizedFullNameReversed, $normalizedSimpleName) {
                $query->whereRaw(
                    "LOWER(REPLACE(CONCAT_WS(' ', first_name, middle_name, last_name), 'Dr.', '')) LIKE LOWER(?)",
                    ["%$normalizedFullName%"]
                )
                ->orWhereRaw(
                    "LOWER(REPLACE(CONCAT_WS(' ', last_name, first_name, middle_name), 'Dr.', '')) LIKE LOWER(?)",
                    ["%$normalizedFullNameReversed%"]
                )
                ->orWhereRaw(
                    "LOWER(REPLACE(CONCAT_WS(' ', first_name, last_name), 'Dr.', '')) LIKE LOWER(?)",
                    ["%$normalizedSimpleName%"]
                );
                
            })->count())
                ->chart([1, 3, 5])
                ->color('secondary'),
            Stat::make('Total: Publications', TrainingOrganize::count())
                ->chart([1, 3, 5])
                ->color('secondary'),
            Stat::make('Total: Awards', TrainingOrganize::count())
                ->chart([1, 3, 5])
                ->color('secondary'),
        ];
    }
}
