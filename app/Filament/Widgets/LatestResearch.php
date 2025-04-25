<?php
namespace App\Filament\Widgets;

use App\Models\Research;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;
use Carbon\Carbon;

class LatestResearch extends BaseWidget
{
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = "full";

    public function table(Table $table): Table
    {
        // Get today's date
        $today = Carbon::today();

        return $table
            ->query(
                // Fetching the latest 5 research entries based on start_date
                Research::query()
                    ->where('start_date', '<=', $today)  // Only include research where start_date is today or earlier
                    ->where('end_date', '>=', $today)    // Only include research where end_date is today or later
                    ->orderByDesc('start_date')  // Sorting by start_date in descending order
                    ->limit(5)  // Limiting to the latest 5
            )
            ->columns([
                // Display the contributing unit
                TextColumn::make('contributing_unit')
                    ->label('Contributing Unit')
                    ->sortable()
                    ->searchable(),

                // Display the research title
                TextColumn::make('title')
                    ->label('Title')
                    ->sortable()
                    ->limit(25)
                    ->tooltip(fn($state) => $state)
                    ->searchable(),

                // Format the start_date as a more readable date
                TextColumn::make('start_date')
                    ->label('Start Date')
                    ->sortable()
                    ->searchable()
                    ->getStateUsing(function ($record) {
                        return $record->start_date ? Carbon::parse($record->start_date)->format('M d, Y') : null;
                    }),

                // Format the end_date as a more readable date
                TextColumn::make('end_date')
                    ->label('End Date')
                    ->sortable()
                    ->searchable()
                    ->getStateUsing(function ($record) {
                        return $record->end_date ? Carbon::parse($record->end_date)->format('M d, Y') : null;
                    }),
            ]);
    }
}
